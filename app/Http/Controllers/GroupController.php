<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Http\Controllers;


use App\Repositories\Catalog;
use App\Repositories\Group;
use App\Repositories\Project;
use App\Repositories\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{
    /**
     * 用户组管理
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function groups()
    {
        return view('group.groups', [
            'op'     => 'groups',
            'groups' => Group::withCount('users')->withCount('projects')->get(),
        ]);
    }

    /**
     * 分组信息
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function info(Request $request, $id)
    {
        $group    = Group::where('id', $id)->firstOrFail();
        $subQuery = function ($query) use ($id) {
            $query->where('group_id', $id);
        };

        $usersForSelect = User::whereDoesntHave('groups', $subQuery)
            ->where('status', User::STATUS_ACTIVATED)
            ->get();

        $users = User::whereHas('groups', $subQuery)->get();

        $projects          = $group->projects()->with('catalog')->get();
        $projectsForSelect = Project::whereDoesntHave('groups', $subQuery)->with('catalog')->get();

        // 目录列表，可以按照目录的维度批量选择目录下所有的项目批量为用户组授权
        $catalogs = Catalog::all();

        return view('group.info', [
            'op'                  => 'groups',
            'group'               => $group,
            'users_for_select'    => $usersForSelect,
            'users'               => $users,
            'projects'            => $projects,
            'projects_for_select' => $projectsForSelect,
            'tab'                 => $request->input('tab', 'member'),
            'catalogs'            => $catalogs,
        ]);
    }

    /**
     * 添加用户到分组
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function addUser(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'users' => 'required|array',
            ],
            [
                'users.required' => '您没有选择要添加的用户',
            ]
        );

        $user_ids = $request->input('users');

        $group = Group::where('id', $id)->firstOrFail();
        $group->users()->attach($user_ids);

        $this->alertSuccess(__('common.operation_success'));

        return redirect(wzRoute('admin:groups:view', ['id' => $id, 'tab' => 'member']));
    }

    /**
     * 从分组移除用户
     *
     * @param Request $request
     * @param         $id
     * @param         $user_id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function removeUser(Request $request, $id, $user_id)
    {
        $group = Group::where('id', $id)->firstOrFail();
        $group->users()->detach($user_id);

        $this->alertSuccess(__('common.operation_success'));

        $origin    = $request->input('origin', 'admin:groups:view');
        $originTab = $request->input('origin_tab', 'member');
        $originId  = $request->input('origin_id', null);

        return redirect(wzRoute($origin, ['id' => $originId ?? $id, 'tab' => $originTab]));
    }

    /**
     * 新增分组
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function add(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|unique:wz_groups,name',
            ],
            [
                'name.required' => '分组名称不能为空',
                'name.unique'   => '分组名称已经存在',
            ]
        );

        Group::create([
            'name'    => $request->input('name'),
            'user_id' => \Auth::user()->id,
        ]);

        $this->alertSuccess(__('common.operation_success'));

        return redirect(wzRoute('admin:groups'));
    }

    /**
     * 更新分组信息
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'name' => [
                    'required',
                    Rule::unique('wz_groups', 'name')->ignore($id),
                ]
            ],
            [
                'name.required' => '分组名不能为空',
                'name.unique'   => '分组名已经存在',
            ]
        );

        $group       = Group::where('id', $id)->firstOrFail();
        $group->name = $request->input('name');

        $group->save();

        $this->alertSuccess(__('common.operation_success'));

        return redirect(wzRoute('admin:groups:view', ['id' => $id, 'tab' => 'setting']));
    }

    /**
     * 分组删除
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     * @throws \Throwable
     */
    public function delete(Request $request, $id)
    {
        /** @var Group $group */
        $group = Group::where('id', $id)->firstOrFail();

        \DB::transaction(function () use ($group) {
            $group->users()->detach();
            $group->projects()->detach();

            $group->delete();
        });

        $this->alertSuccess(__('common.delete_success'));

        return redirect(wzRoute('admin:groups'));
    }

    /**
     * 批量为用户组赋予项目权限
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function grantProjects(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'projects'  => 'required|array',
                'privilege' => 'in:wr,r'
            ],
            [
                'projects.required' => '您没有选择要添加的项目',
            ]
        );

        $projects  = $request->input('projects');
        $privilege = $request->input('privilege', 'r');

        $projectIds = [];
        foreach ($projects as $pro) {
            if (starts_with($pro, '#')) {
                /** @var Catalog $catalog */
                $catalog = Catalog::where('id', trim($pro, '#'))->firstOrFail();
                foreach ($catalog->projects()->pluck('id')->toArray() as $projectId) {
                    $projectIds[] = (int)$projectId;
                }
            } else {
                $projectIds[] = (int)$pro;
            }
        }

        $projectIds = array_unique($projectIds, SORT_NUMERIC);

        $group = Group::where('id', $id)->firstOrFail();
        $group->projects()->detach($projectIds);
        $group->projects()->attach($projectIds, ['privilege' => $privilege == 'r' ? 2 : 1]);

        $this->alertSuccess(__('common.operation_success'));

        return redirect(wzRoute('admin:groups:view', ['id' => $id, 'tab' => 'project']));
    }
}