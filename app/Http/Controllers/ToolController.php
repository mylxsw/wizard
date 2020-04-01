<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
    public function convertSQLToMarkdownTable(Request $request)
    {
        $this->validate(
            $request,
            ['content' => 'required']
        );

        $markdowns = [];
        $sqls      = explode(";\n", $request->input('content'));
        foreach ($sqls as $sql) {
            $sql = implode("\n", collect(explode("\n", $sql))->filter(function ($line) {
                $line = trim($line);
                return !Str::startsWith($line, ['--', '//', '#']) && $line !== '';
            })->toArray());

            $markdownTable = convertSqlToMarkdownTable($sql);
            if (!empty($markdownTable)) {
                $markdowns[] = $markdownTable;
            }
        }

        return ['markdown' => implode("\n", $markdowns)];
    }

    /**
     * 将 SQL 转换为 HTML 表格
     *
     * @param Request $request
     *
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function convertSQLToHTMLTable(Request $request)
    {
        $this->validate(
            $request,
            ['content' => 'required']
        );

        $markdowns = [];
        $sqls      = explode(";\n", $request->input('content'));
        foreach ($sqls as $sql) {
            $sql = implode("\n", collect(explode("\n", $sql))->filter(function ($line) {
                $line = trim($line);
                return !Str::startsWith($line, ['--', '//', '#']) && $line !== '';
            })->toArray());

            $htmlTable = convertSqlToHTMLTable($sql);
            if (!empty($htmlTable)) {
                $markdowns[] = $htmlTable;
            }
        }

        return ['html' => implode("<hr>\n", $markdowns)];
    }
}