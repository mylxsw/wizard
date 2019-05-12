<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Mpdf\Mpdf;

class ExportController extends Controller
{

    /**
     * 直接将内容导出为下载文件
     *
     * @param Request $request
     * @param         $filename
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(Request $request, $filename)
    {
        return response()->streamDownload(function () use ($request) {
            echo $request->input('content');
        }, $filename);
    }

    /**
     * 将HTML转换为PDF文档
     *
     * @param Request $request
     * @param         $type
     *
     * @throws \Mpdf\MpdfException
     */
    public function pdf(Request $request, $type)
    {
        $content = $request->input('html');
        $title   = $request->input('title');
        $author  = $request->input('author');

        $mpdf = new Mpdf([
            'mode'    => 'utf-8',
            'tempDir' => sys_get_temp_dir()
        ]);

        $mpdf->allow_charset_conversion = true;
        $mpdf->useAdobeCJK              = true;
        $mpdf->autoLangToFont           = true;
        $mpdf->autoScriptToLang         = true;
        $mpdf->title                    = $title;
        $mpdf->author                   = $author ?? \Auth::user()->name ?? 'wizard';

        $header = '<link href="/assets/css/normalize.css" rel="stylesheet">';

        switch ($type) {
            case 'markdown':
                $header .= '<link href="/assets/vendor/editor-md/css/editormd.preview.css" rel="stylesheet"/>';
                $header .= '<link href="/assets/vendor/markdown-body.css" rel="stylesheet">';
                break;
            case 'swagger':
                $header .= '<link href="/assets/vendor/swagger-ui/swagger-ui.css" rel="stylesheet">';
                break;
        }


        $header .= '<link href="/assets/css/style.css" rel="stylesheet">';
        $header .= '<link href="/assets/css/pdf.css" rel="stylesheet">';
        $mpdf->WriteHTML($header);

        $html = "<div class='markdown-body wz-markdown-style-fix wz-pdf-content'>{$content}</div>";
        $mpdf->Bookmark($title, 0);
        try {
            $mpdf->WriteHTML($html);
        } catch (\Exception $ex) {
            Log::error('html_to_pdf_failed', [
                'error' => $ex->getMessage(),
                'code'  => $ex->getCode(),
                'doc'   => [
                    'content' => $html,
                ]
            ]);

            $mpdf->WriteHTML('<p class="pdf-error">部分文档生成失败</p>');
        }

        $mpdf->Output();
    }

}