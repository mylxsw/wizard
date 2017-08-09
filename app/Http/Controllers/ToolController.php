<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class ToolController extends Controller
{

    public function convertJsonToTable(Request $request)
    {
        $this->validate(
            $request,
            ['content' => 'required|json']
        );

        $jsonContent = $request->input('content');
        return ['markdown' => convertJsonToMarkdownTable($jsonContent)];
    }
}