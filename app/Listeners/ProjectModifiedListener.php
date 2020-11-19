<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Listeners;

use App\Events\ProjectModified;
use App\Repositories\OperationLogs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProjectModifiedListener
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
     * @param  ProjectModified $event
     *
     * @return void
     */
    public function handle(ProjectModified $event)
    {
        $project = $event->getProject();

        OperationLogs::log(
            \Auth::user()->id,
            'project_updated',
            [
                'username'     => \Auth::user()->name,
                'user_id'      => \Auth::user()->id,
                'project_name' => $project->name,
                'project_id'   => $project->id,
                'type'         => $event->getOp(),
            ],
            impersonateUser()
        );
    }
}
