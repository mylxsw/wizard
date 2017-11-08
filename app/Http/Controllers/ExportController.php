<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Http\Controllers;


use App\Policies\ProjectPolicy;
use App\Repositories\Document;
use App\Repositories\Project;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class ExportController extends Controller
{

    public function pdf(Request $request, $id, $page_id)
    {
        $this->canExport($id);

        /** @var Document $doc */
        $doc = Document::where('id', $page_id)
            ->where('project_id', $id)
            ->where('type', Document::TYPE_DOC)
            ->firstOrFail();

        $html = (new \Parsedown())->text($doc->content);

        $mpdf = new Mpdf([
            'mode'         => 'utf-8',
            'default_font' => 'dejavusans'
        ]);
        $mpdf->useAdobeCJK = true;
        $mpdf->autoLangToFont = true;


        $mpdf->WriteHTML($html);
        $mpdf->Output();
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