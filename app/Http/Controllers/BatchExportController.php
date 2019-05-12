<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Http\Controllers;


use App\Policies\ProjectPolicy;
use App\Repositories\Document;
use App\Repositories\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Log;
use SoapBox\Formatter\Formatter;
use ZipStream\Option\Archive;
use ZipStream\ZipStream;

class BatchExportController extends Controller
{
    /**
     * 最大处理超时时间
     */
    const TIMEOUT = 320;

    /**
     * 批量导出文档
     *
     * @param Request $request
     * @param         $project_id
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Mpdf\MpdfException
     * @throws \ZipStream\Exception\OverflowException
     */
    public function batchExport(Request $request, $project_id)
    {
        $this->canExport($project_id);

        $this->validate(
            $request,
            [
                'pid'  => 'integer',
                'type' => 'required|in:pdf,raw'
            ]
        );

        $pid  = (int)$request->input('pid', 0);
        $type = $request->input('type');

        /** @var Project $project */
        $project = Project::where('id', $project_id)->firstOrFail();

        /** @var Collection $documents */
        $documents  = $project->pages;
        $navigators = navigatorSort(navigator($project_id, 0));

        if ($pid !== 0) {
            $navigators = $this->filterNavigators($navigators, function (array $nav) use ($pid) {
                return (int)$nav['id'] === $pid;
            });
        }

        switch ($type) {
            case 'pdf':
                $this->exportPDF($navigators, $project, $documents);
                break;
            case 'raw':
                $this->exportRaw($navigators, $project, $documents);
                break;
        }
    }

    /**
     * 过滤要导出的文档
     *
     * @param array    $navigators
     * @param \Closure $filter
     *
     * @return array|mixed
     */
    private function filterNavigators(array $navigators, \Closure $filter)
    {
        foreach ($navigators as $nav) {
            if ($filter($nav)) {
                return $nav['nodes'] ?? [];
            }
        }

        return [];
    }

    /**
     * Export to zip archive
     *
     * @param array      $navigators
     * @param Project    $project
     * @param Collection $documents
     *
     * @throws \ZipStream\Exception\OverflowException
     */
    private function exportRaw(array $navigators, Project $project, Collection $documents)
    {
        set_time_limit(self::TIMEOUT);

        $options = new Archive();
        $options->setSendHttpHeaders(true);

        $zip = new ZipStream("{$project->name}.zip", $options);
        $this->traverseNavigators(
            $navigators,
            function ($id, array $parents) use ($documents, $zip) {
                /** @var Document $doc */
                $doc = $documents->where('id', $id)->first();

                switch ($doc->type) {
                    case Document::TYPE_DOC:
                        $ext     = 'md';
                        $content = $doc->content;
                        break;
                    case Document::TYPE_SWAGGER:
                        $ext = 'yml';
                        if (isJson($doc->content)) {
                            $formatter = Formatter::make($doc->content, Formatter::JSON);
                            $content   = $formatter->toYaml();
                        } else {
                            $content = $doc->content;
                        }
                        break;
                    default:
                        $ext     = 'txt';
                        $content = $doc->content;
                }

                $path     = collect($parents)->implode('name', '/');
                $filename = "{$path}/{$doc->title}.{$ext}";

                $fp = fopen('php://memory', 'r+');
                fwrite($fp, $content);
                rewind($fp);
                $zip->addFileFromStream($filename, $fp);
            },
            []
        );

        $zip->finish();
    }

    /**
     * Export to pdf document
     *
     * @param array      $navigators
     * @param Project    $project
     * @param Collection $documents
     *
     * @throws \Mpdf\MpdfException
     */
    private function exportPDF(array $navigators, Project $project, Collection $documents)
    {
        set_time_limit(self::TIMEOUT);

        $mpdf = new Mpdf([
            'mode'              => 'utf-8',
            'tempDir'           => sys_get_temp_dir(),
            'defaultfooterline' => false,
        ]);

        $mpdf->allow_charset_conversion = true;
        $mpdf->useAdobeCJK              = true;
        $mpdf->autoLangToFont           = true;
        $mpdf->autoScriptToLang         = true;
        $mpdf->author                   = $author ?? \Auth::user()->name ?? 'wizard';

        $mpdf->SetFooter('{PAGENO} / {nbpg}');
        $mpdf->SetTitle($project->name);

        $header = '<link href="/assets/css/normalize.css" rel="stylesheet">';
        $header .= '<link href="/assets/vendor/editor-md/css/editormd.preview.css" rel="stylesheet"/>';
        $header .= '<link href="/assets/vendor/markdown-body.css" rel="stylesheet">';
        $header .= '<link href="/assets/css/style.css" rel="stylesheet">';
        $header .= '<link href="/assets/css/pdf.css" rel="stylesheet">';
        $mpdf->WriteHTML($header);

        $pageNo = 1;
        $this->traverseNavigators($navigators,
            function ($id, array $parents) use ($documents, $mpdf, &$pageNo) {
                if ($pageNo > 1) {
                    $mpdf->AddPage();
                }

                /** @var Document $doc */
                $doc = $documents->where('id', $id)->first();

                $title = "* {$doc->title}";

                if ($doc->type != Document::TYPE_DOC) {
                    $raw = "# {$title}\n\n暂不支持该类型的文档。";
                } else {
                    $raw = "# {$title}\n\n" . $doc->content;
                }

                $html = (new \Parsedown())->text($raw);
                $html =
                    "<div class='markdown-body wz-markdown-style-fix wz-pdf-content'>{$html}</div>";

                $mpdf->Bookmark($doc->title, count($parents));
                try {
                    $mpdf->WriteHTML($html);
                } catch (\Exception $ex) {
                    Log::error('html_to_pdf_failed', [
                        'error' => $ex->getMessage(),
                        'code'  => $ex->getCode(),
                        'doc'   => [
                            'id'      => $doc->id,
                            'title'   => $doc->title,
                            'content' => $html,
                        ]
                    ]);

                    $mpdf->WriteHTML('<p class="pdf-error">部分文档生成失败</p>');
                }

                $pageNo++;
            }, []);

        $mpdf->Output();
    }

    /**
     * 遍历所有目录
     *
     * @param array    $navigators
     * @param \Closure $callback
     * @param array    $parents
     */
    private function traverseNavigators(array $navigators, \Closure $callback, array $parents = [])
    {
        foreach ($navigators as $nav) {
            $callback($nav['id'], $parents);

            if (!empty($nav['nodes'])) {
                array_push($parents, ['id' => $nav['id'], 'name' => $nav['name']]);
                $this->traverseNavigators($nav['nodes'], $callback, $parents);
                array_pop($parents);
            }
        }
    }


    /**
     * 检查是否用户有导出权限
     *
     * @param int $projectId
     *
     * @return Project
     */
    private function canExport($projectId)
    {
        /** @var Project $project */
        $project = Project::findOrFail($projectId);

        $policy = new ProjectPolicy();
        if (!$policy->view(\Auth::user(), $project)) {
            abort(404);
        }

        return $project;
    }
}