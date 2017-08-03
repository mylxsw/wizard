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

        OperationLogs::logf(
            \Auth::user()->id,
            [],
            '用户 [%s](%d) 创建了项目 [%s](%d)',
            \Auth::user()->name,
            \Auth::user()->id,
            $project->name,
            $project->id
        );
    }
}
