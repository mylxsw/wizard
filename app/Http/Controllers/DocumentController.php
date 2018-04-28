<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Http\Controllers;


use App\Events\DocumentCreated;
use App\Events\DocumentDeleted;
use App\Events\DocumentModified;
use App\Policies\ProjectPolicy;
use App\Repositories\Document;
use App\Repositories\DocumentHistory;
use App\Repositories\OperationLogs;
use App\Repositories\PageShare;
use App\Repositories\Project;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class DocumentController extends Controller
{

    /**
     * 创建一个新文档页面
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function newPage(Request $request, $id)
    {
        $this->validate(
            $request,
            ['type' => 'in:swagger,doc', 'pid' => 'integer|min:0']
        );

        /** @var Project $project */
        $project = Project::where('id', $id)->firstOrFail();

        $this->authorize('page-add', $project);

        $type = $request->input('type', 'doc');
        $pid  = $request->input('pid', 0);
        return view("doc.{$type}", [
            'newPage'   => true,
            'project'   => $project,
            'type'      => $type,
            'pid'       => $pid,
            'navigator' => navigator((int)$id, $pid),
        ]);
    }

    /**
     * 编辑文档页面
     *
     * @param $id
     * @param $page_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function editPage($id, $page_id)
    {
        /** @var Document $pageItem */
        $pageItem = Document::where('project_id', $id)->where('id', $page_id)->firstOrFail();

        $this->authorize('page-edit', $pageItem);

        $type = ((int)$pageItem->type === Document::TYPE_DOC ? 'doc' : 'swagger');
        return view("doc.{$type}", [
            'pageItem'  => $pageItem,
            'project'   => $pageItem->project,
            'newPage'   => false,
            'type'      => $type,
            'pid'       => $pageItem->pid,
            'navigator' => navigator((int)$id, (int)$pageItem->pid, [$pageItem->id]),
        ]);
    }


    /**
     * 创建一个新文档
     *
     * @param Request $request
     * @param         $id
     *
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function newPageHandle(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'project_id' => "required|integer|min:1|in:{$id}|project_exist",
                'title'      => 'required|between:1,255',
                'type'       => 'required|in:doc,swagger',
                'pid'        => 'integer|min:0',
            ],
            [
                'title.required' => __('document.validation.title_required'),
                'title.between'  => __('document.validation.title_between'),
            ]
        );

        $this->authorize('page-add', $id);

        $pid       = $request->input('pid', 0);
        $projectID = $request->input('project_id');
        $title     = $request->input('title');
        $content   = $request->input('content');
        $type      = $request->input('type', 'doc');

        $pageItem = Document::create([
            'pid'               => $pid,
            'title'             => $title,
            'description'       => '',
            'content'           => $content,
            'project_id'        => $projectID,
            'user_id'           => \Auth::user()->id,
            'last_modified_uid' => \Auth::user()->id,
            'type'              => $type == 'doc' ? Document::TYPE_DOC : Document::TYPE_SWAGGER,
            'status'            => 1,
        ]);

        // 记录文档变更历史
        DocumentHistory::write($pageItem);

        event(new DocumentCreated($pageItem));

        return [
            'redirect' => [
                'edit' => wzRoute(
                    'project:doc:edit:show',
                    ['id' => $projectID, 'page_id' => $pageItem->id]
                ),
                'show' => route(
                    'project:home',
                    ['id' => $projectID, 'p' => $pageItem->id]
                )
            ],
            'message'  => __('common.operation_success'),
        ];
    }

    /**
     * 更新文档内容
     *
     * @param Request $request
     * @param         $id
     * @param         $page_id
     *
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function editPageHandle(Request $request, $id, $page_id)
    {
        $this->validate(
            $request,
            [
                'project_id'       => "required|integer|min:1|in:{$id}|project_exist",
                'page_id'          => "required|integer|min:1|in:{$page_id}|page_exist:{$id}",
                'pid'              => "required|integer|min:0|page_exist:{$id},false",
                'title'            => 'required|between:1,255',
                'last_modified_at' => 'required|date',
                'force'            => 'bool',
                'history_id'       => 'required|integer',
            ],
            [
                'title.required' => __('document.validation.title_required'),
                'title.between'  => __('document.validation.title_between'),
            ]
        );

        $pid            = $request->input('pid', 0);
        $projectID      = $request->input('project_id');
        $title          = $request->input('title');
        $content        = $request->input('content');
        $lastModifiedAt = Carbon::parse($request->input('last_modified_at'));
        $history_id     = $request->input('history_id');
        $forceSave      = $request->input('force', false);

        /** @var Document $pageItem */
        $pageItem = Document::where('id', $page_id)->firstOrFail();

        $this->authorize('page-edit', $pageItem);

        // 检查文档是否已经被别人修改过了，避免修改覆盖
        if (!$forceSave
            && (!$pageItem->updated_at->equalTo($lastModifiedAt)
                || $history_id != $pageItem->history_id)
        ) {
            return $this->buildFailedValidationResponse($request, [
                'last_modified_at' => [
                    __('document.validation.doc_modified_by_user', [
                        'username' => $pageItem->lastModifiedUser->name,
                        'time'     => $pageItem->updated_at
                    ])
                ]
            ]);
        }

        $pageItem->pid        = $pid;
        $pageItem->project_id = $projectID;
        $pageItem->title      = $title;
        $pageItem->content    = $content;

        // 只有文档内容发生修改才进行保存
        if ($pageItem->isDirty()) {
            $pageItem->last_modified_uid = \Auth::user()->id;
            $pageItem->save();

            // 记录文档变更历史
            DocumentHistory::write($pageItem);

            event(new DocumentModified($pageItem));
        }

        return [
            'message'  => __('common.operation_success'),
            'redirect' => [
                'edit' => wzRoute(
                    'project:doc:edit:show',
                    ['id' => $projectID, 'page_id' => $pageItem->id]
                ),
                'show' => route(
                    'project:home',
                    ['id' => $projectID, 'p' => $pageItem->id]
                )
            ]
        ];
    }

    /**
     * 检查页面是否已经过期
     *
     * @param Request $request
     * @param         $id
     * @param         $page_id
     *
     * @return array
     */
    public function checkPageExpired(Request $request, $id, $page_id)
    {
        $this->validate(
            $request,
            [
                'l' => 'required|date',
            ]
        );

        $lastModifiedAt = Carbon::parse($request->input('l'));

        /** @var Document $pageItem */
        $pageItem = Document::where('id', $page_id)->firstOrFail();

        // 检查文档是否已经被别人修改过了，避免修改覆盖
        if (!$pageItem->updated_at->equalTo($lastModifiedAt)) {
            return [
                'message' => __('document.validation.doc_modified_by_user', [
                    'username' => $pageItem->lastModifiedUser->name,
                    'time'     => $pageItem->updated_at
                ]),
                'expired' => true,
            ];
        }

        return [
            'message' => 'ok',
            'expired' => false,
        ];
    }

    /**
     * 删除文档
     *
     * @param Request $request
     * @param         $id
     * @param         $page_id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function deletePage(Request $request, $id, $page_id)
    {
        $pageItem = Document::where('id', $page_id)->where('project_id', $id)->firstOrFail();
        $this->authorize('page-edit', $pageItem);

        // 页面删除后，所有下级页面全部移动到该页面的上级
        $pageItem->subPages()->update(['pid' => $pageItem->pid]);

        // 更新删除文档的用户
        $pageItem->last_modified_uid = \Auth::user()->id;
        $pageItem->save();

        // 删除文档
        $pageItem->delete();
        $this->alertSuccess(__('document.document_delete_success'));

        event(new DocumentDeleted($pageItem));

        return redirect(wzRoute('project:home', ['id' => $id]));
    }

    /**
     * 以JSON形式返回文档
     *
     * @param Request $request
     * @param         $id
     * @param         $page_id
     *
     * @return array|mixed|string
     */
    public function getPageJSON(Request $request, $id, $page_id)
    {
        $pageItem = Document::where('id', $page_id)->where('project_id', $id)->firstOrFail();

        $onlyBody = $request->input('only_body', 0);
        if ($onlyBody) {
            return $pageItem->content;
        }

        return [
            'id'                     => $pageItem->id,
            'pid'                    => $pageItem->pid,
            'title'                  => $pageItem->title,
            'description'            => $pageItem->description,
            'content'                => $pageItem->content,
            'type'                   => $pageItem->type,
            'user_id'                => $pageItem->user_id,
            'username'               => $pageItem->user->name,
            'last_modified_user_id'  => $pageItem->lastModifiedUser->id,
            'last_modified_username' => $pageItem->lastModifiedUser->name,
            'created_at'             => $pageItem->created_at->format('Y-m-d H:i:s'),
            'updated_at'             => $pageItem->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * 获取原生swagger文档
     *
     * @param $id
     * @param $page_id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getSwagger($id, $page_id)
    {
        /** @var Project $project */
        $project = Project::findOrFail($id);

        $policy = new ProjectPolicy();
        if (!$policy->view(\Auth::user(), $project)) {
            abort(403, '您没有访问该项目的权限');
        }

        $page = Document::where('project_id', $id)
            ->where('id', $page_id)
            ->firstOrFail();
        if ($page->type != Document::TYPE_SWAGGER) {
            abort(422, '该文档不是Swagger文档');
        }

        return response($page->content);
    }

    /**
     * 阅读模式
     *
     * @param $id
     * @param $page_id
     *
     * @return mixed|string
     */
    public function readMode($id, $page_id)
    {
        /** @var Project $project */
        $project = Project::query()->findOrFail($id);
        $policy  = new ProjectPolicy();
        if (!$policy->view(\Auth::user(), $project)) {
            abort(403, '您没有访问该项目的权限');
        }

        $page = Document::where('project_id', $id)->where('id', $page_id)->firstOrFail();
        $type = $page->type == Document::TYPE_DOC ? 'markdown' : 'swagger';

        return view('share-show', [
            'project'  => $project,
            'pageItem' => $page,
            'type'     => $type,
            'noheader' => true,
        ]);
    }
}