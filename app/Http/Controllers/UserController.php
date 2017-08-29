<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Http\Controllers;


use App\Repositories\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * 用户列表
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function users(Request $request)
    {
        return view('user.users', [
            'users' => User::orderBy('created_at', 'desc')->paginate(),
            'op'    => 'users',
        ]);
    }

    /**
     * 用户基本信息配置页面
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function basic(Request $request)
    {
        return view('user.basic', [
            'op'   => 'basic',
            'user' => \Auth::user(),
        ]);
    }

    /**
     * 修改用户基本信息
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function basicHandle(Request $request)
    {
        $uid = \Auth::user()->id;
        $this->validate(
            $request,
            [
                'username' => "required|string|max:255|username_unique:{$uid}",
            ]
        );

        $username = $request->input('username');

        \Auth::user()->update([
            'name' => $username,
        ]);

        $this->alertSuccess(__('common.operation_success'));

        return redirect(wzRoute('user:basic'));
    }

    /**
     * 修改密码页面
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function password(Request $request)
    {
        return view('user.password', [
            'op' => 'password',
        ]);
    }

    /**
     * 修改密码
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function passwordHandle(Request $request)
    {
        $this->validate(
            $request,
            [
                'original_password' => 'required|user_password',
                'password'          => 'required|string|min:6|confirmed'
            ],
            [
                'original_password.required'      => __('passwords.validation.original_password_required'),
                'original_password.user_password' => __('passwords.validation.original_password_unmatch'),
                'password.required'               => __('passwords.validation.new_password_required'),
                'password.string'                 => __('passwords.validation.new_password_invalidate'),
                'password.min'                    => __('passwords.validation.new_password_at_least'),
                'password.confirmed'              => __('passwords.validation.new_password_confirm_failed'),
            ]
        );

        User::where('id', \Auth::user()->id)->update([
            'password' => \Hash::make($request->input('password'))
        ]);

        $this->alertSuccess(__('passwords.change_password_success'));
        return redirect(wzRoute('user:password'));
    }
}