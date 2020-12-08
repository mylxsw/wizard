<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Http\Controllers;


use App\Events\ProjectCreated;
use App\Events\ProjectDeleted;
use App\Events\ProjectModified;
use App\Policies\ProjectPolicy;
use App\Repositories\Catalog;
use App\Repositories\Document;
use App\Repositories\DocumentScore;
use App\Repositories\Group;
use App\Repositories\OperationLogs;
use App\Repositories\PageShare;
use App\Repositories\Project;
use App\Repositories\DocumentHistory;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ProjectController extends Controller
{
    protected $types = [
        Document::TYPE_DOC     => 'markdown',
        Document::TYPE_SWAGGER => 'swagger',
        Document::TYPE_TABLE   => 'table',
    ];

    /**
     * 用户个人首页（个人项目列表）
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home(Request $request)
    {
        $perPage = $request->input('per_page', 19);
        $name = $request->input('name');

        /** @var Project $projectModel */
        $projectModel = Project::query();
        $projectModel->withCount('pages');
        if (!empty($name)) {
            $projectModel->where('name', 'like', "%{$name}%");
        }

        /** @var LengthAwarePaginator $projects */
        $projects = $projectModel->where('user_id', \Auth::user()->id)
                                 ->orderBy('sort_level', 'ASC')
                                 ->paginate($perPage)
                                 ->appends([
                                     'per_page' => $perPage,
                                     'name'     => $name,
                                 ]);

        return view('user-home',
            ['projects' => $projects, 'name' => $name,]);
    }

    /**
     * 创建新项目
     *
     * @param Request $request
     *
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function newProjectHandle(Request $request)
    {
        $this->authorize('project-create');

        $this->validate(
            $request,
            [
                'name'        => 'required|between:1,100',
                'description' => 'max:255',
                'visibility'  => 'required|in:1,2',
                'sort_level'  => 'integer|between:-9999999999,999999999',
                'catalog'     => 'required|integer',
            ],
            [
                'name.required'   => __('project.validation.project_name_required'),
                'name.between'    => __('project.validation.project_name_between'),
                'description.max' => __('project.validation.project_description_max'),
            ]
        );

        $name = $request->input('name');
        $description = $request->input('description');
        $visibility = $request->input('visibility');
        $catalog = $request->input('catalog');

        if (\Auth::user()->can('project-sort')) {
            $sortLevel = $request->input('sort_level', 1000);
        } else {
            $sortLevel = 1000;
        }

        $project = Project::create([
            'name'        => $name,
            'description' => $description,
            'user_id'     => \Auth::user()->id,
            'visibility'  => $visibility,
            'sort_level'  => (int)$sortLevel,
            'catalog_id'  => $catalog,
        ]);

        event(new ProjectCreated($project));

        $this->alertSuccess(__('common.operation_success'));
        return [
            'id'          => $project->id,
            'name'        => $project->name,
            'description' => $project->description,
            'visibility'  => $project->visibility,
        ];
    }

    /**
     * 删除项目
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Request $request, $id)
    {
        $project = Project::where('id', $id)->firstOrFail();
        $this->authorize('project-delete', $project);

        $project->delete();
        event(new ProjectDeleted($project));

        return redirect(wzRoute('user:home'));
    }

    /**
     * 项目页面
     *
     * p - 文档ID
     * cm - 高亮显示的评论ID
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function project(Request $request, $id)
    {
        $pageID = (int)$request->input('p', 0);

        /** @var Project $project */
        $project = Project::with('catalog')->findOrFail($id);

        $policy = new ProjectPolicy();
        if (!$policy->view(\Auth::user(), $project)) {
            abort(403, '您没有访问该项目的权限');
        }

        $page = $type = null;
        if ($pageID !== 0) {
            $queryBuilder = Document::query();

            if (config('wizard.reply_support')) {
                $queryBuilder->with([
                    'comments' => function ($query) {
                        $query->orderBy('created_at', 'desc');
                    }
                ]);
            }

            $page = $queryBuilder->where('project_id', $id)
                                 ->where('id', $pageID)
                                 ->firstOrFail();
            $type = $this->types[$page->type];

            $history = DocumentHistory::where('page_id', $page->id)
                                      ->where('id', '!=', $page->history_id)
                                      ->orderBy('id', 'desc')
                                      ->first();
            $share = PageShare::where('page_id', $page->id)
                              ->where('project_id', $page->project_id)
                              ->first();
            // 文档评分
            $scores = DocumentScore::select(DB::raw('score_type, count(*) as count'))
                                   ->where('page_id', $page->id)
                                   ->groupBy('score_type')
                                   ->get()
                                   ->mapWithKeys(function ($item) {
                                       return [$item['score_type'] => $item['count']];
                                   });
            $usefulScoreUsers = DocumentScore::where('page_id', $page->id)
                                             ->where('score_type', DocumentScore::SCORE_USEFUL)
                                             ->with([
                                                 'user' => function ($query) {
                                                     $query->select('id', 'name');
                                                 }
                                             ])
                                             ->select('id', 'user_id', 'page_id')
                                             ->orderBy('updated_at', 'desc')
                                             ->limit(10)->get();
            if (!Auth::guest()) {
                $userScoreType = DocumentScore::where('page_id', $page->id)->where('user_id',
                    Auth::user()->id)->value('score_type');
            }
        } else {
            // 查询操作历史
            $operationLogs = OperationLogs::where('project_id', $id)
                                          ->whereNotNull('page_id')
                                          ->orderBy('created_at', 'desc')
                                          ->limit(10)->get();
        }

        return view('project.project', [
            'project'            => $project,
            'pageID'             => $pageID,
            'pageItem'           => $page,
            'scores'             => $scores ?? [],
            'useful_score_users' => $usefulScoreUsers ?? [],
            'user_score_type'    => $userScoreType ?? 0,
            'type'               => $type,
            'code'               => '',
            'operationLogs'      => isset($operationLogs) ? $operationLogs : [],
            'comment_highlight'  => $request->input('cm', ''),
            'navigators'         => navigator($id, $pageID),
            'history'            => $history ?? false,
            'share'              => $share ?? false,
            'isFavorited'        => $project->isFavoriteByUser(\Auth::user()),
        ]);
    }

    /**
     * 项目配置页面
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setting(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'op' => 'in:basic,privilege,advanced,sort'
            ]
        );

        $project = Project::findOrFail($id);
        $this->authorize('project-edit', $project);

        $op = $request->input('op', 'basic');
        $viewData = ['project' => $project, 'op' => $op];

        switch ($op) {
            case 'basic':
                $viewData['catalogs'] = Catalog::all();
                break;
            case 'privilege':
                $groups = Group::with([
                    'projects' => function ($query) use ($id) {
                        $query->wherePivot('project_id', $id);
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
            case 'advanced':
                break;
            case 'sort':
                $viewData['navigators'] = navigator($id);
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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function settingHandle(Request $request, $id)
    {
        $this->validate($request, ['op' => 'required|in:basic,privilege,advanced,sort']);

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
            case 'advanced':
                $updated = false;
                break;
            case 'sort':
                $updated = $this->sortSettingHandle($request, $project);
                break;
            default:
                $updated = false;
        }

        if ($updated) {
            event(new ProjectModified($project, $op));
        }

        $this->alertSuccess(__('project.project_update_success'));
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
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
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
                'sort_level'  => 'integer|between:-999999999,999999999',
                'catalog'     => 'required|integer',
            ],
            [
                'name.required'   => __('project.validation.project_name_required'),
                'name.between'    => __('project.validation.project_name_between'),
                'description.max' => __('project.validation.project_description_max'),
            ]
        );

        $name = $request->input('name');
        $description = $request->input('description');
        $visibility = $request->input('visibility');
        $sortLevel = $request->input('sort_level');
        $catalog = $request->input('catalog');

        $project->name = $name;
        $project->description = $description;
        $project->visibility = $visibility;
        $project->catalog_id = empty($catalog) ? null : $catalog;
        if (\Auth::user()->can('project-sort') && $sortLevel != null) {
            $project->sort_level = (int)$sortLevel;
        }

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
     * @throws \Illuminate\Validation\ValidationException
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

        $groupID = $request->input('group_id');
        $privilege = $request->input('privilege', 'r');

        $project->groups()->detach($groupID);
        $project->groups()->attach($groupID, ['privilege' => $privilege == 'r' ? 2 : 1]);

        return true;
    }

    /**
     * 更新项目中文档的排序
     *
     * @param Request $request
     * @param Project $project
     *
     * @return bool
     * @throws \Throwable
     */
    private function sortSettingHandle(Request $request, Project $project): bool
    {
        $sortLevelsJson = $request->input('sort_levels');
        if (empty($sortLevelsJson)) {
            return false;
        }

        $sortLevels = new Collection(json_decode($sortLevelsJson, true));

        // 检查ID是否存在
        $ids = $sortLevels->map(function ($s) {
            return $s['id'];
        });

        /** @var \Illuminate\Database\Eloquent\Collection $documents */
        $documents =
            Document::whereIn('id', $ids->toArray())->where('project_id', $project->id)->get();
        $retrivedIds = $documents->map(function ($s) {
            return $s->id;
        });

        if (!$ids->diff($retrivedIds)->isEmpty()) {
            throw new NotFoundResourceException('部分文档不存在');
        }

        // 更新文档
        $sortLevelsById = $sortLevels->keyBy('id');
        \DB::transaction(function () use ($documents, $sortLevelsById) {
            $documents->each(function (Document $doc) use ($sortLevelsById) {
                $doc->sort_level = (int)$sortLevelsById->get($doc->id)['sort_level'];
                $doc->save();
            });
        });

        return false;
    }

    /**
     * 项目权限回收
     *
     * @param Request $request
     * @param         $id
     * @param         $group_id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function groupPrivilegeRevoke(Request $request, $id, $group_id)
    {
        $project = Project::where('id', $id)->firstOrFail();
        $this->authorize('project-edit', $project);

        $project->groups()->detach($group_id);

        $this->alertSuccess(__('project.revoke_privilege_success'));

        $redirectURL = $request->input('redirect');
        if (!empty($redirectURL)) {
            return redirect($redirectURL);
        }

        return redirect(wzRoute('project:setting:handle', ['id' => $id, 'op' => 'privilege']));
    }

    /**
     * 关注、取消关注项目
     *
     * @param Request $request
     * @param         $id
     *
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function favorite(Request $request, $id)
    {
        $this->validate($request, ['action' => 'required|in:fav,unfav']);

        $action = $request->input('action');
        $project = Project::where('id', $id)->firstOrFail();
        $user = \Auth::user();

        if ($action == 'fav') {
            $user->favoriteProjects()->attach($project->id);
        } else {
            $user->favoriteProjects()->detach($project->id);
        }

        $this->alertSuccess($action == 'fav' ? '您已成功关注该项目' : '您已取消对该项目的关注');

        return [
            'message' => '操作成功',
            'reload'  => true,
        ];
    }

    /**
     * 文档目录选择，用于select的option
     *
     * @param Request $request
     * @param         $project_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function documentSelector(Request $request, $project_id)
    {
        /** @var Project $project */
        $project = Project::with('catalog')->findOrFail($project_id);

        $policy = new ProjectPolicy();
        if (!$policy->view(\Auth::user(), $project)) {
            abort(403, '您没有访问该项目的权限');
        }

        $exclude = [];
        $excludePageID = $request->input('exclude_page_id');
        if (!empty($excludePageID)) {
            $exclude[] = $excludePageID;
        }

        return view('project.document-selector',
            ['navigator' => navigator($project_id, 0, $exclude)]);
    }
}
