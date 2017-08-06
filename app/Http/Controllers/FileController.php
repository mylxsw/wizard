<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * 上传图片文件
     *
     * @param Request $request
     *
     * @return array
     */
    public function imageUpload(Request $request)
    {
        $file = $request->file('editormd-image-file');
        if (!$file->isValid()) {
            return $this->response(false,
                __('common.upload.failed', ['reason' => $file->getErrorMessage()]));
        }

        if (!in_array($file->extension(), ["jpg", "jpeg", "gif", "png", "bmp"])) {
            return $this->response(false, __('common.upload.invalid_type'));
        }

        $path = $file->storePublicly(sprintf('public/%s', date('Y/m-d')));
        return $this->response(true, __('common.upload.success'), \Storage::url($path));
    }

    private function response(bool $isSuccess, string $message, $url = null)
    {
        return [
            'success' => $isSuccess ? 1 : 0,
            'message' => $message,
            'url'     => $url,
        ];
    }

}