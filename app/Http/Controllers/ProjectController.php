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
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function home(Request $request, $id)
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
            $page = Page::where('project_id', $id)->where('id', $pageID)->firstOrFail();
        }

        return view('project', [
            'project'  => $project,
            'pageID'   => $pageID,
            'pageItem' => $page,
        ]);
    }

}