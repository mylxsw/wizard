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

        return view('project', [
            'project'    => $project,
            'pageID'     => $pageID,
            'pageItem'   => $page,
            'navigators' => navigator($project->pages, $id, $pageID)
        ]);
    }

    /**
     * 项目配置页面
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function setting($id)
    {
        $project = Project::findOrFail($id);
        $this->authorize('project-edit', $project);

        return view('setting', ['project' => $project]);
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
        $this->validate(
            $request,
            [
                'name'        => 'required|between:1,100',
                'description' => 'max:255',
                'project_id'  => "required|in:{$id}|project_exist",
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

        $project = Project::where('id', $id)->firstOrFail();
        $this->authorize('project-edit', $project);

        $project->name        = $name;
        $project->description = $description;
        $project->visibility  = $visibility;

        $project->save();

        event(new ProjectModified($project));

        $request->session()->flash('alert.message', __('project.project_update_success'));
        return redirect(wzRoute(
            'project:setting:show',
            ['id' => $id]
        ));
    }

}