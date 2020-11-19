<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Listeners;

use App\Events\ProjectCreated;
use App\Repositories\OperationLogs;
use App\Repositories\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProjectCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProjectCreated $event
     *
     * @return void
     */
    public function handle(ProjectCreated $event)
    {
        $project = $event->getProject();

        OperationLogs::log(
            \Auth::user()->id,
            'project_created',
            [
                'username'     => \Auth::user()->name,
                'user_id'      => \Auth::user()->id,
                'project_name' => $project->name,
                'project_id'   => $project->id
            ],
            impersonateUser()
        );

        // 通知管理员有新项目创建
        /** @var Collection $users */
        $users = User::where('role', User::ROLE_ADMIN)->get();
        $users->map(function (User $user) use ($event) {
            $user->notify(new \App\Notifications\ProjectCreated($event->getProject()));
        });
    }
}
