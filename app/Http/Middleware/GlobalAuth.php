<?php

namespace App\Http\Middleware;


use Illuminate\Support\Facades\Auth;

class GlobalAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     *
     * @return mixed
     */
    public function handle($request, \Closure $next, $guard = null)
    {
        // 启用 must_login 选项之后，必须登录后才能查看文档
        if (config('wizard.must_login')) {
            if (Auth::guest()) {
                return redirect(wzRoute('login'));
            }
        }

        return $next($request);
    }
}