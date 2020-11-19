<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Http\Controllers;


use App\Repositories\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 用户扮演
 *
 * 管理员可以临时扮演成为其它用户
 *
 * @package App\Http\Controllers
 */
class ImpersonateController extends Controller
{
    /**
     * 扮演某个用户
     *
     * @param Request $request
     * @param int $id 用户ID
     * @return RedirectResponse
     */
    public function impersonate(Request $request, $id)
    {
        if (Auth::user()->id == $id || !Auth::user()->canImpersonate()) {
            $this->alertError('无法扮演当前用户');
            return redirect()->back();
        }

        /** @var User $user */
        $user = User::where('id', $id)->firstOrFail();
        Auth::user()->impersonate($user);

        $this->alertSuccess("您现在扮演的是 {$user->name}");
        return redirect()->home();
    }

    /**
     * 停止扮演
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function stopImpersonate(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->isImpersonated()) {
            $user->leaveImpersonation();
        }

        $this->alertSuccess('成功停止扮演');
        return redirect()->back();
    }
}