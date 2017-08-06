<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Http\Controllers;


use App\Events\ProjectCreated;
use App\Events\ProjectModified;
use App\Repositories\Document;
use App\Repositories\Group;
use App\Repositories\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * 用户个人首页（个人项目列表）
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        /** @var Project $projects */
        $projects = Project::where('user_id', \Auth::user()->id)->get();

        return view('user-home', ['projects' => $projects]);
    }

    /**
     * 创建新项目
     *
     * @param Request $request
     *
     * @return array
     */
    public function newProjectHandle(Request $request)
    {
        $this->validate(
            $request,
            [
                'name'        => 'required|between:1,100',
                'description' => 'max:255',
                'visibility'  => 'required|in:1,2'
            ],
            [
                'name.required'   => __('project.validation.project_name_required'),
                'name.between'    => __('project.validation.project_name_between'),
                'description.max' => __('project.validation.project_description_max'),
            ]
        );

        $name        = $request->input('name');
        $description = $request->input('description');
        $visibility  = $request->input('visibility');

        $project = Project::create([
            'name'        => $name,
            'description' => $description,
            'user_id'     => \Auth::user()->id,
            'visibility'  => $visibility,
        ]);

        event(new ProjectCreated($project));

        $request->session()->flash('alert.message', __('common.operation_success'));
        return [
            'id'          => $project->id,
            'name'        => $project->name,
            'description' => $project->description,
            'visibility'  => $project->visibility,
        ];
    }

    /**
     * 项目页面
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function project(Request $request, $id)
    {
        $this->validate(
            $request,
            ['p' => 'integer|min:1']
        );

        $pageID = (int)$request->input('p', 0);

        /** @var Project $project */
        $project = Project::with([
            'pages' => function (Relation $query) {
                $query->select('id', 'pid', 'title', 'description', 'project_id', 'type', 'status');
            }
        ])->findOrFail($id);

        $page = null;
        if ($pageID !== 0) {
            $page = Document::where('project_id', $id)->where('id', $pageID)->firstOrFail();
        }

        return view('project.project', [
            'project'    => $project,
            'pageID'     => $pageID,
            'pageItem'   => $page,
            'navigators' => navigator($project->pages, $id, $pageID)
        ]);
    }

    /**
     * 项目配置页面
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function setting(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'op' => 'in:basic,privilege'
            ]
        );

        $project = Project::findOrFail($id);
        $this->authorize('project-edit', $project);

        $op       = $request->input('op', 'basic');
        $viewData = ['project' => $project, 'op' => $op];

        switch ($op) {
            case 'basic':
                break;
            case 'privilege':
                $groups = Group::with([
                    'projects' => function ($query) use ($id) {
                        $query->where('project_id', $id);
                    }
                ])->get();

                // 对用户组进行分组，分为已经分配的组合剩余组，方面页面分开展示
                $viewData['addedGroups'] = $viewData['restGroups'] = [];
                /** @var Group $group */
                foreach ($groups as $group) {
                    if ($group->projects->count() === 0) {
                        $viewData['restGroups'][] = $group;
                    } else {
                        $viewData['addedGroups'][] = $group;
                    }
                }
                break;
        }


        return view("project.setting-{$op}", $viewData);
    }

    /**
     * 更新项目配置信息
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function settingHandle(Request $request, $id)
    {
        $this->validate($request, ['op' => 'required|in:basic,privilege']);

        $op = $request->input('op');

        $project = Project::where('id', $id)->firstOrFail();
        $this->authorize('project-edit', $project);

        switch ($op) {
            case 'basic':
                $updated = $this->basicSettingHandle($request, $project);
                break;
            case 'privilege':
                $updated = $this->privilegeSettingHandle($request, $project);
                break;
            default:
                $updated = false;
        }

        if ($updated) {
            event(new ProjectModified($project, $op));
        }

        $this->alert(__('project.project_update_success'));
        return redirect(wzRoute(
            'project:setting:show',
            ['id' => $id, 'op' => $op]
        ));
    }


    /**
     * 项目基本信息更新
     *
     * @param Request $request
     * @param Project $project
     *
     * @return bool 如果返回true，说明执行了更新操作，false说明没有更新
     */
    private function basicSettingHandle(Request $request, Project $project): bool
    {
        $this->validate(
            $request,
            [
                'name'        => 'required|between:1,100',
                'description' => 'max:255',
                'project_id'  => "required|in:{$project->id}|project_exist",
                'visibility'  => 'required|in:1,2',
            ],
            [
                'name.required'   => __('project.validation.project_name_required'),
                'name.between'    => __('project.validation.project_name_between'),
                'description.max' => __('project.validation.project_description_max'),
            ]
        );

        $name        = $request->input('name');
        $description = $request->input('description');
        $visibility  = $request->input('visibility');

        $project->name        = $name;
        $project->description = $description;
        $project->visibility  = $visibility;

        if ($project->isDirty()) {
            $project->save();
            return true;
        }

        return false;
    }

    /**
     * 项目权限信息更新
     *
     * @param Request $request
     * @param Project $project
     *
     * @return bool 如果返回true，说明执行了更新操作，false说明没有更新
     */
    private function privilegeSettingHandle(Request $request, Project $project): bool
    {
        $this->validate(
            $request,
            [
                'group_id'  => 'required|integer|min:1|group_exist',
                'privilege' => 'in:wr,r'
            ]
        );

        $groupID   = $request->input('group_id');
        $privilege = $request->input('privilege', 'r');

        $project->groups()->detach($groupID);
        $project->groups()->attach($groupID, ['privilege' => $privilege == 'r' ? 2 : 1]);

        return true;
    }

    /**
     * 项目权限回收
     *
     * @param Request $request
     * @param         $id
     * @param         $group_id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function groupPrivilegeRevoke(Request $request, $id, $group_id)
    {
        $project = Project::where('id', $id)->firstOrFail();
        $this->authorize('project-edit', $project);

        $project->groups()->detach($group_id);

        $this->alert(__('project.revoke_privilege_success'));
        return redirect(route('project:setting:handle', ['id' => $id, 'op' => 'privilege']));
    }
}