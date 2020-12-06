<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */


namespace App\Http\Controllers;


use App\Repositories\Attachment;
use App\Repositories\Document;
use App\Repositories\Project;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    /**
     * 上传文件
     *
     * @param Request $request
     * @param         $id
     * @param         $page_id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function upload(Request $request, $id, $page_id)
    {
        $this->validate(
            $request,
            [
                'attachment' => 'required|file',
            ],
            [
                'attachment.required' => '附件不能为空',
                'attachment.file'     => '附件不能为空',
            ]
        );

        $file = $request->file('attachment');
        $extension = $this->getFileExtension($file);

        $this->validateParameters(
            [
                'extension'  => strtolower($extension),
                'project_id' => $id,
                'page_id'    => $page_id,
            ],
            [
                'extension'  => 'in:' . implode(',', $this->getSupportExtensions()),
                'project_id' => "required|integer|min:1|project_exist",
                'page_id'    => "required|integer|min:1|page_exist:{$id}",
            ],
            [
                'extension.in' => '上传文件类型不支持'
            ]
        );

        $this->authorize('page-edit', $page_id);

        $path = $file->storePubliclyAs(
            sprintf('public/%s', date('Y/m-d')),
            $this->getSaveAsName($file, $extension)
        );
        $name = $request->input('name');

        Attachment::create([
            'name'       => $name ?? $file->getClientOriginalName(),
            'path'       => \Storage::url($path),
            'user_id'    => \Auth::user()->id,
            'page_id'    => $page_id,
            'project_id' => $id
        ]);
        $this->alertSuccess(__('common.operation_success'));
        return redirect(wzRoute('project:doc:attachment', ['id' => $id, 'page_id' => $page_id]));
    }


    /**
     * 获取文件扩展名
     *
     * 直接读取文件扩展名
     *
     * @param $file
     *
     * @return string
     */
    private function getFileExtension($file)
    {
        return $file->getClientOriginalExtension();
    }

    /**
     * 获取上传后保存的文件名
     *
     * 解决上传文件无法获取mime类型而存储为默认的zip格式的问题
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $ext
     *
     * @return string
     */
    private function getSaveAsName($file, $ext): string
    {
        if (ends_with($file, $ext)) {
            return $file->hashName();
        }

        $guessedName = $file->hashName();
        return substr($guessedName, 0, strrpos($guessedName, '.')) . '.' . $ext;
    }

    /**
     * 文档的附件列表
     *
     * @param Request $request
     * @param         $id
     * @param         $page_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function page(Request $request, $id, $page_id)
    {
        $page = Document::where('project_id', $id)->where('id', $page_id)->firstOrFail();
        /** @var Project $project */
        $project = Project::findOrFail($id);

        $attachments = Attachment::with('user')
                                 ->where('page_id', $page_id)
                                 ->where('project_id', $id)
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(20);

        return view('doc.attachments', [
            'attachments' => $attachments,
            'project'     => $project,
            'pageID'      => $page_id,
            'pageItem'    => $page,
            'extensions'  => $this->getSupportExtensions(),
            'navigators'  => navigator($id, $page_id),
            'isFavorited' => $project->isFavoriteByUser(\Auth::user()),
        ]);
    }

    /**
     * 返回支持的文件扩展名
     *
     * @return array
     */
    private function getSupportExtensions()
    {
        return collect(explode(',', config('wizard.attachments.support_extensions')))
            ->map(function ($ext) {
                return trim($ext);
            })->filter(function ($ext) {
                return !empty($ext);
            })->toArray();
    }

    /**
     * 删除附件
     *
     * @param Request $request
     * @param         $id
     * @param         $page_id
     * @param         $attachment_id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Request $request, $id, $page_id, $attachment_id)
    {
        $this->validateParameters(
            [
                'project_id'    => $id,
                'page_id'       => $page_id,
                'attachment_id' => $attachment_id,
            ],
            [
                'project_id'    => "required|integer|min:1|project_exist",
                'page_id'       => "required|integer|min:1|page_exist:{$id}",
                'attachment_id' => 'required|integer|min:1',
            ]
        );

        $pageItem = Document::where('project_id', $id)->where('id', $page_id)->firstOrFail();
        $this->authorize('page-edit', $pageItem);

        $pageItem->attachments()->where('id', $attachment_id)->delete();

        $this->alertSuccess(__('common.delete_success'));
        return redirect(wzRoute('project:doc:attachment', [
            'id'      => $id,
            'page_id' => $page_id,
        ]));
    }
}