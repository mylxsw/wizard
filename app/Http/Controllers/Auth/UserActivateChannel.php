<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Http\Controllers\Auth;


use App\Mail\UserActivate;
use App\Repositories\User;

trait UserActivateChannel
{
    /**
     * 发送用户激活邮件
     *
     * @param User $user
     */
    protected function sendUserActivateEmail(User $user)
    {
        \Mail::to($user)->queue(new UserActivate($user));
    }
}