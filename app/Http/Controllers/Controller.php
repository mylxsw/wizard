<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 创建页面提示消息
     *
     * 一次性有效
     *
     * @param string $message
     */
    protected function alert(string $message)
    {
        Request::session()->flash('alert.message.info', $message);
    }

    /**
     * 创建操作成功的页面提示消息
     *
     * @param string $message
     */
    protected function alertSuccess(string $message)
    {
        Request::session()->flash('alert.message.success', $message);
    }

    /**
     * 创建操作失败的页面提示消息
     *
     * @param string $message
     */
    protected function alertError(string $message)
    {
        Request::session()->flash('alert.message.error', $message);
    }

    /**
     * Validate the given parameters with the given rules.
     *
     * @param       $parameters
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     *
     * @return static
     */
    protected function validateParameters(
        $parameters,
        array $rules,
        array $messages = [],
        array $customAttributes = []
    ) {
        $this->getValidationFactory()
            ->make($parameters, $rules, $messages, $customAttributes)
            ->validate();

        return collect($parameters)->only(collect($rules)->keys()->map(function ($rule) {
            return Str::contains($rule, '.') ? explode('.', $rule)[0] : $rule;
        })->unique()->toArray());
    }

    /**
     * Create the response for when a request fails validation.
     *
     * @param \Illuminate\Http\Request $request
     * @param array                    $errors
     *
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function buildFailedValidationResponse(
        \Illuminate\Http\Request $request,
        array $errors
    ) {
        if ($request->ajax() || $request->wantsJson()) {
            return new JsonResponse($errors, 422);
        }
        return redirect()->back()
            ->withInput($request->input())
            ->withErrors($errors);
    }
}
