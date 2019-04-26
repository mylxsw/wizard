<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Http\Controllers;


use App\Events\DocumentRecovered;
use App\Policies\ProjectPolicy;
use App\Repositories\Document;
use App\Repositories\DocumentHistory;
use App\Repositories\Project;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    protected $types = [
        Document::TYPE_DOC     => 'markdown',
        Document::TYPE_SWAGGER => 'swagger',
        Document::TYPE_TABLE   => 'table',
    ];

    /**
     * 文档编辑历史
     *
     * @param Request $request
     * @param         $id
     * @param         $page_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pages(Request $request, $id, $page_id)
    {
        $page = Document::where('project_id', $id)->where('id', $page_id)->firstOrFail();
        /** @var Project $project */
        $project = Project::findOrFail($id);

        $histories = DocumentHistory::with('operator')
            ->where('page_id', $page_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('doc.history', [
            'histories'   => $histories,
            'project'     => $project,
            'pageID'      => $page_id,
            'pageItem'    => $page,
            'navigators'  => navigator($id, $page_id),
            'isFavorited' => $project->isFavoriteByUser(\Auth::user()),
        ]);
    }

    /**
     * 历史文档查看
     *
     * @param Request $request
     * @param         $id
     * @param         $page_id
     * @param         $history_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function page(Request $request, $id, $page_id, $history_id)
    {
        $page = Document::where('project_id', $id)->where('id', $page_id)->firstOrFail();
        /** @var Project $project */
        $project = Project::findOrFail($id);

        $history = DocumentHistory::where('page_id', $page_id)
            ->where('id', $history_id)->firstOrFail();
        $type    =  $this->types[$page->type];

        return view('doc.history-doc', [
            'history'     => $history,
            'project'     => $project,
            'pageID'      => $page_id,
            'pageItem'    => $page,
            'type'        => $type,
            'navigators'  => navigator($id, $page_id),
            'isFavorited' => $project->isFavoriteByUser(\Auth::user()),
        ]);
    }

    /**
     * 从历史页面恢复
     *
     * @param Request $request
     * @param         $id
     * @param         $page_id
     * @param         $history_id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function recover(Request $request, $id, $page_id, $history_id)
    {
        $pageItem = Document::where('project_id', $id)->where('id', $page_id)->firstOrFail();
        $this->authorize('page-recover', $pageItem);

        $historyItem = DocumentHistory::where('project_id', $id)->where('id', $history_id)
            ->where('page_id', $page_id)->firstOrFail();

        event(new DocumentRecovered(Document::recover($pageItem, $historyItem)));
        $this->alertSuccess(__('document.document_recover_success'));

        return redirect(wzRoute('project:home', ['id' => $id, 'p' => $page_id]));
    }

    /**
     * 以JSON返回历史文档
     *
     * @param Request $request
     * @param         $id
     * @param         $page_id
     * @param         $history_id
     *
     * @return array|mixed|string
     */
    public function getPageJSON(Request $request, $id, $page_id, $history_id)
    {
        if ($history_id == 0) {
            $document = Document::findOrFail($page_id);
            $history  = DocumentHistory::where('page_id', $page_id)
                ->where('id', '!=', $document->history_id)
                ->orderBy('id', 'desc')
                ->firstOrFail();
        } else {
            $history = DocumentHistory::where('page_id', $page_id)->where('id', $history_id)
                ->firstOrFail();
        }


        $onlyBody = $request->input('only_body', 0);
        if ($onlyBody) {
            return $history->content;
        }

        return [
            'id'                     => $history->id,
            'page_id'                => $history->page_id,
            'pid'                    => $history->pid,
            'title'                  => $history->title,
            'description'            => $history->description,
            'content'                => $history->content,
            'type'                   => $history->type,
            'user_id'                => $history->user_id,
            'username'               => $history->user->name,
            'last_modified_user_id'  => $history->operator->id,
            'last_modified_username' => $history->operator->name,
            'created_at'             => $history->created_at->format('Y-m-d H:i:s'),
        ];
    }
}