<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Http\Controllers;


use App\Repositories\Document;
use App\Repositories\Project;
use Illuminate\Http\Request;

/**
 * 文档搜索
 *
 * @package App\Http\Controllers
 */
class SearchController extends Controller
{
    /**
     * 文档搜索
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $keyword   = $request->input('keyword');
        $tagName   = urldecode($request->input('tag'));
        $projectId = (int)$request->input('project_id');
        $perPage   = (int)$request->input('per_page', 20);
        $range     = $request->input('range', '');

        /** @var Document $documentModel */
        $documentModel = Document::query()->with('project', 'user');
        if (!empty($projectId)) {
            $documentModel->where('project_id', $projectId);
        }

        if (!empty($tagName)) {
            $documentModel->whereHas('tags', function ($query) use ($tagName) {
                $query->where('name', $tagName);
            });
        }

        if (empty($keyword)) {
            $documentModel->orderBy('updated_at', 'DESC');
        } else {
            $documentModel->where('title', 'like', "%{$keyword}%")->orderBy('updated_at', 'DESC');
        }

        // 用户已登录，则全量搜索
        // 用户未登录，则只搜索公开文档
        if (\Auth::guest()) {
            $documentModel->whereHas('project', function ($query) {
                $query->where('visibility', Project::VISIBILITY_PUBLIC);
            });
        }

        // 只搜索我的文档
        if ($range === 'my' && !\Auth::guest()) {
            $documentModel->where('user_id', \Auth::user()->id);
        }

        return view('search', [
            'documents'  => $documentModel->paginate($perPage)->appends([
                'keyword'    => $keyword,
                'per_page'   => $perPage,
                'project_id' => $projectId,
                'tag'        => $tagName,
                'range'      => $range,
            ]),
            'keyword'    => $keyword,
            'project_id' => $projectId,
            'tag'        => $tagName,
            'range'      => $range,
        ]);
    }
}