<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Http\Controllers;


use App\Repositories\Document;
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
        $keyword = $request->input('keyword');
        $perPage = (int)$request->input('per_page', 20);

        /** @var Document $documentModel */
        $documentModel = Document::query()->with('project', 'user')->has('project');
        if (empty($keyword)) {
            $documentModel->orderBy('updated_at', 'DESC');
        } else {
            $documentModel->where('title', 'like', "%{$keyword}%")->orderBy('updated_at', 'DESC');
        }

        return view('search', [
            'documents' => $documentModel->paginate($perPage)->appends([
                'keyword'  => $keyword,
                'per_page' => $perPage
            ]),
            'keyword'   => $keyword,
        ]);
    }
}