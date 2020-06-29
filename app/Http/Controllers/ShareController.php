<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Http\Controllers;


use App\Repositories\Document;
use App\Repositories\PageShare;
use App\Repositories\Project;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    /**
     * 分享链接访问
     *
     * @param Request $request
     * @param         $hash
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function page(Request $request, $hash)
    {
        /** @var PageShare $share */
        $share = PageShare::where('code', $hash)->firstOrFail();

        $projectId = $share->project_id;
        $pageId = $share->page_id;

        /** @var Project $project */
        $project = Project::with([
            'pages' => function (Relation $query) {
                $query->select('id', 'pid', 'title', 'description', 'project_id', 'type', 'status');
            }
        ])->findOrFail($projectId);

        $page = Document::where('project_id', $projectId)->where('id', $pageId)->firstOrFail();
        $type = $page->type == Document::TYPE_DOC ? 'markdown' : 'swagger';

        return view('share-show', [
            'project'  => $project,
            'pageItem' => $page,
            'type'     => $type,
            'code'     => $hash,
            'noheader' => true,
        ]);
    }

    /**
     * 删除分享链接
     *
     * @param Request $request
     * @param $project_id
     * @param $page_id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Request $request, $project_id, $page_id)
    {
        $this->validateParameters(
            ['page_id' => $page_id,],
            ['page_id' => "required|page_exist:{$project_id}",]
        );

        $this->authorize('page-share', $page_id);

        PageShare::where('project_id', $project_id)
                 ->where('page_id', $page_id)
                 ->delete();

        $this->alertSuccess('取消分享成功');
        return [];
    }

    /**
     * 创建分享链接
     *
     * @param Request $request
     * @param         $project_id
     * @param         $page_id
     *
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Request $request, $project_id, $page_id)
    {
        $this->validateParameters(
            ['page_id' => $page_id,],
            ['page_id' => "required|page_exist:{$project_id}",]
        );

        $this->authorize('page-share', $page_id);

        $share = PageShare::where('project_id', $project_id)
                          ->where('page_id', $page_id)
                          ->where('user_id', \Auth::user()->id)
                          ->first();
        if (empty($share)) {
            $code = sha1("{$project_id}-{$page_id}-" . microtime() . rand(0, 9999999999));
            $share = PageShare::create([
                'code'       => $code,
                'project_id' => $project_id,
                'page_id'    => $page_id,
                'user_id'    => \Auth::user()->id,
            ]);
        }

        return [
            'code' => $share->code,
            'link' => wzRoute('share:show', ['hash' => $share->code]),
        ];
    }

}