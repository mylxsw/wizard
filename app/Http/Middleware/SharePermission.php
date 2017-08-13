<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Http\Middleware;


use App\Policies\ProjectPolicy;
use App\Repositories\PageShare;

class SharePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $code       = $request->input('code');
        $project_id = $request->route('id');

        if (empty($code) && !(new ProjectPolicy())->view(\Auth::user(), $project_id)) {
            abort(404);
        } else if (!empty($code) && !PageShare::where('code', $code)->exists()) {
            abort(404);
        }

        return $next($request);
    }
}