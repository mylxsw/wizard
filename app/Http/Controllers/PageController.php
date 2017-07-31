<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Http\Controllers;


use App\Repositories\Page;
use App\Repositories\Project;
use Illuminate\Http\Request;

class PageController extends Controller
{

    public function newPage($id)
    {
        /** @var Project $project */
        $project = Project::where('id', $id)->firstOrFail();

        return view('edit', [
            'newPage' => true,
            'project' => $project,
        ]);
    }

    public function editPage($id, $page_id)
    {
        /** @var Page $pageItem */
        $pageItem = Page::where('project_id', $id)->where('id', $page_id)->firstOrFail();

        return view('edit', [
            'pageItem' => $pageItem,
            'project'  => $pageItem->project,
            'newPage'  => false,
        ]);
    }

    public function newPageHandle(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'project_id' => "required|integer|min:1|in:{$id}|project_exist",
                'title'      => 'required|between:1,255',
            ]
        );

        $pid       = $request->input('pid', 0);
        $projectID = $request->input('project_id');
        $title     = $request->input('title');
        $content   = $request->input('content');

        $pageItem = Page::create([
            'pid'         => $pid,
            'title'       => $title,
            'description' => '',
            'content'     => $content,
            'project_id'  => $projectID,
            'user_id'     => 1,
            'type'        => 1,
            'status'      => 1,
        ]);

        return redirect(wzRoute(
            'page-edit-show',
            ['id' => $projectID, 'page_id' => $pageItem->id]
        ));

    }

    public function editPageHandle(Request $request, $id, $page_id)
    {
        $this->validate(
            $request,
            [
                'project_id' => "required|integer|min:1|in:{$id}|project_exist",
                'page_id'    => "required|integer|min:1|in:{$page_id}|page_exist:{$id}",
                'title'      => 'required|between:1,255',
            ]
        );

        $pid       = $request->input('pid', 0);
        $projectID = $request->input('project_id');
        $title     = $request->input('title');
        $content   = $request->input('content');

        /** @var Page $pageItem */
        $pageItem             = Page::where('id', $page_id)->firstOrFail();
        $pageItem->pid        = $pid;
        $pageItem->project_id = $projectID;
        $pageItem->title      = $title;
        $pageItem->content    = $content;

        $pageItem->save();

        return redirect(wzRoute(
            'page-edit-show',
            ['id' => $projectID, 'page_id' => $page_id]
        ));
    }
}