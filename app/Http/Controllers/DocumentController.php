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
use App\Events\DocumentMarkModified;
use App\Events\DocumentModified;
use App\Policies\ProjectPolicy;
use App\Repositories\Document;
use App\Repositories\DocumentHistory;
use App\Repositories\DocumentScore;
use App\Repositories\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SoapBox\Formatter\Formatter;

class DocumentController extends Controller
{

    protected $types = [
        Document::TYPE_DOC     => 'markdown',
        Document::TYPE_SWAGGER => 'swagger',
        Document::TYPE_TABLE   => 'table',
    ];

    /**
     * 创建一个新文档页面
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function newPage(Request $request, $id)
    {
        $this->validate(
            $request,
            ['type' => 'in:swagger,markdown,table', 'pid' => 'integer|min:0']
        );

        /** @var Project $project */
        $project = Project::where('id', $id)->firstOrFail();

        $this->authorize('page-add', $project);

        $type = $request->input('type', 'markdown');
        $pid = $request->input('pid', 0);
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

        $type = $this->types[$pageItem->type];
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
     * @return array|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function newPageHandle(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'project_id' => "required|integer|min:1|in:{$id}|project_exist",
                'title'      => 'required|between:1,255',
                'type'       => 'required|in:markdown,swagger,table',
                'pid'        => 'integer|min:0',
                'sort_level' => 'integer',
                'sync_url'   => 'nullable|url',
            ],
            [
                'title.required' => __('document.validation.title_required'),
                'title.between'  => __('document.validation.title_between'),
                'sync_url.url'   => '文档同步地址必须为合法的URL地址',
            ]
        );

        $this->authorize('page-add', $id);

        $pid = $request->input('pid', 0);
        $projectID = $request->input('project_id');
        $title = $request->input('title');
        $content = $request->input('content');
        $type = $request->input('type', 'markdown');
        $sortLevel = $request->input('sort_level', 1000);
        $syncUrl = $request->input('sync_url');

        // 类型如果是表格，则需要检验表格内容是否合法
        if ($type === 'table') {
            json_decode($content);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->buildFailedValidationResponse($request, [
                    'content' => ['页面内容不合法，表格页面必须为合法的json格式'],
                ]);
            }

            $content = $this->processTableRequest($content);
        }

        $pageItem = Document::create([
            'pid'               => $pid,
            'title'             => $title,
            'description'       => '',
            'content'           => $content,
            'project_id'        => $projectID,
            'user_id'           => \Auth::user()->id,
            'last_modified_uid' => \Auth::user()->id,
            'type'              => array_flip($this->types)[$type],
            'status'            => Document::STATUS_NORMAL,
            'sort_level'        => $sortLevel,
            'sync_url'          => $syncUrl,
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
                'show' => wzRoute(
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
     * @return array|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
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
                'sort_level'       => 'integer',
                'sync_url'         => 'nullable|url',
            ],
            [
                'title.required' => __('document.validation.title_required'),
                'title.between'  => __('document.validation.title_between'),
                'sync_url.url'   => '文档同步地址必须为合法的URL地址',
            ]
        );

        $pid = $request->input('pid', 0);
        $projectID = $request->input('project_id');
        $title = $request->input('title');
        $content = $request->input('content');
        $lastModifiedAt = Carbon::parse($request->input('last_modified_at'));
        $history_id = $request->input('history_id');
        $forceSave = $request->input('force', false);
        $sortLevel = $request->input('sort_level', 1000);
        $syncUrl = $request->input('sync_url');

        /** @var Document $pageItem */
        $pageItem = Document::where('id', $page_id)->firstOrFail();

        // 类型如果是表格，则需要检验表格内容是否合法
        if ($pageItem->isTable()) {
            json_decode($content);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->buildFailedValidationResponse($request, [
                    'content' => ['页面内容不合法，表格页面必须为合法的json格式'],
                ]);
            }

            $content = $this->processTableRequest($content);
        }

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

        $pageItem->pid = $pid;
        $pageItem->project_id = $projectID;
        $pageItem->title = $title;
        $pageItem->content = $content;
        $pageItem->sort_level = $sortLevel;
        $pageItem->sync_url = $syncUrl;

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
                'show' => wzRoute(
                    'project:home',
                    ['id' => $projectID, 'p' => $pageItem->id]
                )
            ]
        ];
    }

    /**
     * 标记文档为过时装填
     *
     * @param Request $request
     * @param $id
     * @param $page_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function markStatus(Request $request, $id, $page_id)
    {
        $this->validate(
            $request,
            [
                'status' => 'required|in:1,2',
            ]
        );

        /** @var Document $pageItem */
        $pageItem = Document::where('project_id', $id)->where('id', $page_id)->firstOrFail();
        $this->authorize('page-edit', $pageItem);

        $pageItem->status = $request->input('status');
        // 只有文档内容发生修改才进行保存
        if ($pageItem->isDirty()) {
            $pageItem->last_modified_uid = \Auth::user()->id;
            $pageItem->save();

            event(new DocumentMarkModified($pageItem));
        }

        $this->alertSuccess('操作成功');
        return redirect(wzRoute('project:home', ['id' => $id, 'p' => $page_id]));
    }

    /**
     * 检查页面是否已经过期
     *
     * @param Request $request
     * @param         $id
     * @param         $page_id
     *
     * @return array
     * @throws \Illuminate\Validation\ValidationException
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
            'sort_level'             => $pageItem->sort_level,
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
        $yaml = $this->getSwaggerContent($id, $page_id);
        if (isJson($yaml)) {
            $formatter = Formatter::make($yaml, Formatter::JSON);
            return response($formatter->toYaml());
        }

        return response($yaml);
    }

    /**
     * 获取json格式的文档
     *
     * @param $id
     * @param $page_id
     *
     * @return mixed
     */
    public function getJson($id, $page_id)
    {
        $yaml = $this->getSwaggerContent($id, $page_id);
        if (isJson($yaml)) {
            $jsonContent = $yaml;
        } else {
            $formatter = Formatter::make($yaml, Formatter::YAML);
            $jsonContent = $formatter->toJson();
        }

        return response($jsonContent, 200, ['Content-Type' => 'application/json']);
    }


    /**
     * 获取Swagger文档内容
     *
     * @param $id
     * @param $page_id
     *
     * @return string
     */
    private function getSwaggerContent($id, $page_id): string
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

        return $page->content;
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
        $policy = new ProjectPolicy();
        if (!$policy->view(\Auth::user(), $project)) {
            abort(403, '您没有访问该项目的权限');
        }

        $page = Document::where('project_id', $id)->where('id', $page_id)->firstOrFail();
        $type = $this->types[$page->type];

        return view('share-show', [
            'project'  => $project,
            'pageItem' => $page,
            'type'     => $type,
            'noheader' => true,
        ]);
    }

    /**
     * 文档同步
     *
     * 用于从sync_url同步swagger文档
     *
     * @param $id
     * @param $page_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function syncFromRemote($id, $page_id)
    {
        /** @var Document $pageItem */
        $pageItem = Document::where('id', $page_id)
                            ->where('project_id', $id)
                            ->firstOrFail();

        $this->authorize('page-edit', $pageItem);

        $synced = false;
        if (!empty($pageItem->sync_url)) {
            $client = new \GuzzleHttp\Client();
            $resp = $client->get($pageItem->sync_url);
            $respCode = $resp->getStatusCode();
            $respBody = $resp->getBody()->getContents();

            if ($respCode !== 200) {
                \Log::error('document_sync_failed', [
                    'status_code' => $respCode,
                    'resp_body'   => $respBody,
                    'project_id'  => $id,
                    'page_id'     => $page_id,
                    'operator_id' => \Auth::user()->id,
                ]);
                throw new \Exception('文档同步失败');
            }

            $pageItem->content = $respBody;

            // 只有文档内容发生修改才进行保存
            if ($pageItem->isDirty()) {
                $pageItem->last_modified_uid = \Auth::user()->id;
                $pageItem->last_sync_at = Carbon::now();

                $pageItem->save();

                // 记录文档变更历史
                DocumentHistory::write($pageItem);

                event(new DocumentModified($pageItem));

                $synced = true;
            }
        }

        if ($synced) {
            $this->alertSuccess('文档同步成功');
        } else {
            $this->alertSuccess('文档同步完成，没有新的内容');
        }

        return redirect(wzRoute('project:home', ['id' => $id, 'p' => $page_id]));
    }

    /**
     * 文档移动
     *
     * @param Request $request
     * @param $project_id
     * @param $page_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function move(Request $request, $project_id, $page_id)
    {
        $this->validate(
            $request,
            [
                'target_project_id' => 'required|integer',
                'target_page_id'    => 'integer',
            ]
        );


        /** @var Document $pageItem */
        $pageItem = Document::where('id', $page_id)->firstOrFail();
        $this->authorize('page-edit', $pageItem);

        // 检查目标项目权限
        $targetProjectId = $request->input('target_project_id', 0);
        $targetPageId = $request->input('target_page_id', 0);

        /** @var Project $targetProject */
        $targetProject = Project::where('id', $targetProjectId)->firstOrFail();
        $this->authorize('page-add', $targetProject);

        // 检查目标页面是否存在
        $targetPage = null;
        if (!empty($targetPageId)) {
            /** @var Document $targetPage */
            $targetPage = $targetProject->pages()->where('id', $targetPageId)->firstOrFail();
        }

        $navigators = navigatorSort(navigator($project_id, 0));
        $navigators = $this->filterNavigators($navigators, function (array $nav) use ($pageItem) {
            return (int)$nav['id'] === (int)$pageItem->id;
        });

        DB::transaction(function () use ($pageItem, $targetProject, $targetPage, $navigators) {
            // 修改当前页面的pid和project_id
            $pageItem->project_id = $targetProject->id;
            $pageItem->pid = $targetPage->id ?? 0;

            $pageItem->save();

            // 历史
            DocumentHistory::where('page_id', $pageItem->id)->update([
                'project_id' => $targetProject->id,
                'pid'        => $targetPage->id ?? 0,
            ]);

            // 修改子页面的project_id
            $this->traverseNavigators(
                $navigators,
                function ($id, array $parents) use ($targetProject) {
                    Document::where('id', $id)->update(['project_id' => $targetProject->id]);
                    DocumentHistory::where('page_id', $id)->update([
                        'project_id' => $targetProject->id,
                    ]);
                }
            );
        });

        return redirect(wzRoute(
            'project:home',
            ['id' => $targetProject->id, 'p' => $targetPage->id ?? null]
        ));
    }

    /**
     * 更新文档评价
     *
     * @param Request $request
     * @param $id
     * @param $page_id
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateDocumentScore(Request $request, $id, $page_id)
    {
        $this->validate($request, [
            'score_type' => 'required|in:1,2,3',
        ]);

        $policy = new ProjectPolicy();
        if (!$policy->view(\Auth::user(), Project::where('id', $id)->firstOrFail())) {
            abort(403, '您没有访问该项目的权限');
        }

        /** @var Document $pageItem */
        $pageItem = Document::where('id', $page_id)->where('project_id', $id)->firstOrFail();
        $scoreType = (int)$request->input('score_type');

        /** @var DocumentScore $existedScore */
        $existedScore = DocumentScore::where('page_id', $pageItem->id)->where('user_id', Auth::user()->id)->first();
        if ($existedScore) {
            if ($existedScore->score_type == $scoreType) {
                $existedScore->delete();
            } else {
                $existedScore->score_type = $scoreType;
                $existedScore->save();
            }
        } else {
            DocumentScore::create([
                'user_id'    => Auth::user()->id,
                'page_id'    => $pageItem->id,
                'score_type' => $scoreType,
            ]);
        }

        $this->alertSuccess('操作成功');
        return [];
    }

    /**
     * 过滤要导出的文档
     *
     * @param array $navigators
     * @param \Closure $filter
     *
     * @return array|mixed
     */
    private function filterNavigators(array $navigators, \Closure $filter)
    {
        foreach ($navigators as $nav) {
            if ($filter($nav)) {
                return $nav['nodes'] ?? [];
            }
            if (!empty($nav['nodes'])) {
                $sub = $this->filterNavigators($nav['nodes'], $filter);
                if (!empty($sub)) {
                    return $sub;
                }
            }
        }

        return [];
    }

    /**
     * 遍历所有目录
     *
     * @param array $navigators
     * @param \Closure $callback
     * @param array $parents
     */
    private function traverseNavigators(array $navigators, \Closure $callback, array $parents = [])
    {
        traverseNavigators($navigators, $callback, $parents);
    }

    /**
     * 预处理表格存储内容
     *
     * @param string $content 原始内容
     *
     * @return string 重新编码后的内容
     */
    protected function processTableRequest($content)
    {
        return processSpreedSheet($content);
    }
}