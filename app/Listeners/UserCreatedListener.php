<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Listeners;


use App\Events\UserCreated;
use App\Repositories\User;
use Illuminate\Database\Eloquent\Collection;

class UserCreatedListener
{
    public function handle(UserCreated $event)
    {
        // 通知管理员有新用户创建了
        /** @var Collection $users */
        $users = User::where('role', User::ROLE_ADMIN)->get();
        $users->map(function (User $user) use ($event) {
            $user->notify(new \App\Notifications\UserCreated($event->getUser()));
        });
    }
}