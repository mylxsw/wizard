<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class CompareController extends Controller
{

    /**
     * 文档比较
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function compare(Request $request)
    {
        $this->validate(
            $request,
            [
                'doc1'      => 'required',
                'doc2'      => 'required',
                'doc1title' => 'required',
                'doc2title' => 'required',
            ]
        );

        return view('doc.compare', [
            'doc1'      => $request->input('doc1'),
            'doc2'      => $request->input('doc2'),
            'doc1title' => $request->input('doc1title'),
            'doc2title' => $request->input('doc2title'),
        ]);
    }
}