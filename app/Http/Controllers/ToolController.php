<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
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