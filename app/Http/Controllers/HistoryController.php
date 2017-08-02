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
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
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
        $project = Project::with([
            'pages' => function (Relation $query) {
                $query->select('id', 'pid', 'title', 'description', 'project_id', 'type', 'status');
            }
        ])->findOrFail($id);

        $histories = DocumentHistory::with('operator')
            ->where('page_id', $page_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('doc.history', [
            'histories'  => $histories,
            'project'    => $project,
            'pageID'     => $page_id,
            'pageItem'   => $page,
            'navigators' => navigator($project->pages, $id, $page_id)
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
        $project = Project::with([
            'pages' => function (Relation $query) {
                $query->select('id', 'pid', 'title', 'description', 'project_id', 'type', 'status');
            }
        ])->findOrFail($id);

        $history = DocumentHistory::where('page_id', $page_id)
            ->where('id', $history_id)->firstOrFail();

        return view('doc.history-doc', [
            'history'    => $history,
            'project'    => $project,
            'pageID'     => $page_id,
            'pageItem'   => $page,
            'navigators' => navigator($project->pages, $id, $page_id)
        ]);
    }

    public function recover(Request $request, $id, $page_id, $history_id)
    {

    }
}