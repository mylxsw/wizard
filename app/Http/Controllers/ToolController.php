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

    /**
     * 将 Json 转换为 Markdown 表格
     *
     * @param Request $request
     *
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function convertJsonToTable(Request $request)
    {
        $this->validate(
            $request,
            ['content' => 'required|json']
        );

        $jsonContent = $request->input('content');
        return ['markdown' => convertJsonToMarkdownTable($jsonContent)];
    }

    /**
     * 将 SQL 转换为 Markdown 表格
     *
     * @param Request $request
     *
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function convertSQLToTable(Request $request)
    {
        $this->validate(
            $request,
            ['content' => 'required']
        );

        $markdowns = [];
        $sqls      = explode(";\n", $request->input('content'));
        foreach ($sqls as $sql) {
            $markdowns[] = convertSqlToMarkdownTable($sql);
        }

        return ['markdown' => implode("\n", $markdowns)];
    }
}