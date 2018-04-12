<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Http\Controllers;


use App\Repositories\Group;
use App\Repositories\User;
use Illuminate\Http\Request;

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
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function info($id)
    {
        $group    = Group::where('id', $id)->firstOrFail();
        $subQuery = function ($query) use ($id) {
            $query->where('group_id', $id);
        };

        $usersForSelect = User::whereDoesntHave('groups', $subQuery)->get();
        $users          = User::whereHas('groups', $subQuery)->get();

        $projects = $group->projects()->with('catalog')->get();

        return view('group.info', [
            'op'               => 'groups',
            'group'            => $group,
            'users_for_select' => $usersForSelect,
            'users'            => $users,
            'projects'         => $projects,
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

        return redirect(wzRoute('admin:groups:view', ['id' => $id]));
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

        return redirect()->back();
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
}