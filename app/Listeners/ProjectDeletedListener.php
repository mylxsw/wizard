<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Listeners;

use App\Events\ProjectDeleted;
use App\Repositories\OperationLogs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProjectDeletedListener
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
     * @param  ProjectDeleted $event
     *
     * @return void
     */
    public function handle(ProjectDeleted $event)
    {
        $project = $event->getProject();

        OperationLogs::log(
            \Auth::user()->id,
            'project_deleted',
            [
                'username'     => \Auth::user()->name,
                'user_id'      => \Auth::user()->id,
                'project_name' => $project->name,
                'project_id'   => $project->id
            ],
            impersonateUser()
        );

    }
}
