<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Http\Middleware;

/**
 * 语言设置中间件
 *
 * @package App\Http\Middleware
 */
class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, \Closure $next, $guard = null)
    {
        $locale = $request->session()->get('locale');
        if (!empty($locale)) {
            \App::setLocale($locale);
        }

        return $next($request);
    }
}