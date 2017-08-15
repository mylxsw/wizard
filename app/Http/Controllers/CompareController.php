<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use SebastianBergmann\Diff\Differ;

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
                'noheader'  => 'in:0,1',
                'act'       => 'in:compare,diff'
            ]
        );

        $doc1title = $request->input('doc1title');
        $doc1      = $request->input('doc1');

        $doc2title = $request->input('doc2title');
        $doc2      = $request->input('doc2');

        $viewData = [
            'doc1'           => $doc1,
            'doc2'           => $doc2,
            'doc1title'      => $doc1title,
            'doc2title'      => $doc2title,
            'noheader'       => !!$request->input('noheader', 0)
        ];

        $act = $request->input('act', 'diff');
        if ($act === 'diff') {
            $differ         = new Differ();
            $viewData['differContents'] = $differ->diffToArray($doc2, $doc1);
        }


        return view("doc.{$act}", $viewData);
    }
}