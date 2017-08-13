<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Request;

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
        Request::session()->flash('alert.message', $message);
    }

    /**
     * Validate the given parameters with the given rules.
     *
     * @param  array  $parameters
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return void
     */
    protected function validateParameters($parameters, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($parameters, $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $this->throwValidationException(app('request'), $validator);
        }
    }
}
