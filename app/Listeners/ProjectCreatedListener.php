<?php

namespace App\Listeners;

use App\Events\ProjectCreated;
use App\Repositories\OperationLogs;
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
            ]
        );
    }
}
