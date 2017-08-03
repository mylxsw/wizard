<?php

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
     * @param  ProjectDeleted  $event
     * @return void
     */
    public function handle(ProjectDeleted $event)
    {
        $project = $event->getProject();

        OperationLogs::logf(
            \Auth::user()->id,
            [],
            '用户 [%s](%d) 删除了项目 [%s](%d)',
            \Auth::user()->name,
            \Auth::user()->id,
            $project->name,
            $project->id
        );
    }
}
