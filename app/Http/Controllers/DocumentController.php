<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Http\Controllers;


use App\Repositories\Document;
use App\Repositories\DocumentHistory;
use App\Repositories\Project;
use Carbon\Carbon;
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
     */
    public function newPage(Request $request, $id)
    {
        $this->validate(
            $request,
            ['type' => 'in:swagger,doc']
        );

        /** @var Project $project */
        $project = Project::where('id', $id)->firstOrFail();

        $this->authorize('page-add', $project);

        $type = $request->input('type', 'doc');
        return view("doc.{$type}", [
            'newPage'   => true,
            'project'   => $project,
            'navigator' => navigator($project->pages, (int)$id),
        ]);
    }

    /**
     * 编辑文档页面
     *
     * @param $id
     * @param $page_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPage($id, $page_id)
    {
        /** @var Document $pageItem */
        $pageItem = Document::where('project_id', $id)->where('id', $page_id)->firstOrFail();

        $this->authorize('page-edit', $pageItem);

        $viewName = 'doc.' . ((int)$pageItem->type === Document::TYPE_DOC ? 'doc' : 'swagger');
        return view($viewName, [
            'pageItem'  => $pageItem,
            'project'   => $pageItem->project,
            'newPage'   => false,
            'navigator' => navigator(Document::where('project_id', $id)->get(), (int)$id,
                (int)$pageItem->pid, [$pageItem->id]),
        ]);
    }


    /**
     * 创建一个新文档
     *
     * @param Request $request
     * @param         $id
     *
     * @return array
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
                'title.required' => '文档标题不能为空',
                'title.between'  => '文档标题格式不合法',
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

        return [
            'redirect' => wzRoute(
                'project:doc:edit:show',
                ['id' => $projectID, 'page_id' => $pageItem->id]
            ),
            'message'  => '新增成功',
        ];
    }

    /**
     * 更新文档内容
     *
     * @param Request $request
     * @param         $id
     * @param         $page_id
     *
     * @return array|\Symfony\Component\HttpFoundation\Response
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
            ],
            [
                'title.required' => '文档标题不能为空',
                'title.between'  => '文档标题格式不合法',
            ]
        );

        $pid            = $request->input('pid', 0);
        $projectID      = $request->input('project_id');
        $title          = $request->input('title');
        $content        = $request->input('content');
        $lastModifiedAt = Carbon::parse($request->input('last_modified_at'));
        $forceSave      = $request->input('force', false);

        /** @var Document $pageItem */
        $pageItem = Document::where('id', $page_id)->firstOrFail();

        $this->authorize('page-edit', $pageItem);

        // 检查文档是否已经被别人修改过了，避免修改覆盖
        if (!$forceSave && !$pageItem->updated_at->equalTo($lastModifiedAt)) {
            return $this->buildFailedValidationResponse($request, [
                'last_modified_at' => [
                    "该页面已经被 {$pageItem->lastModifiedUser->name} 于 {$pageItem->updated_at} 修改过了",
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
        }

        return [
            'message'  => '保存成功',
            'redirect' => wzRoute(
                'project:doc:edit:show',
                ['id' => $projectID, 'page_id' => $page_id]
            )
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
                'message' => "该页面已经被 {$pageItem->lastModifiedUser->name} 于 {$pageItem->updated_at} 修改过了",
                'expired' => true,
            ];
        }

        return [
            'message' => '页面正常',
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

        $request->session()->flash('alert.message', '文档删除成功');
        return redirect(wzRoute('project:home', ['id' => $id]));
    }

    /**
     * 以JSON形式返回文档
     *
     * @param $id
     * @param $page_id
     *
     * @return array
     */
    public function getPageJSON($id, $page_id)
    {
        $pageItem = Document::where('id', $page_id)->where('project_id', $id)->firstOrFail();

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
}