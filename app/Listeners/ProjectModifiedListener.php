<?php

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
     * @param  ProjectModified  $event
     * @return void
     */
    public function handle(ProjectModified $event)
    {
        $project = $event->getProject();

        OperationLogs::logf(
            \Auth::user()->id,
            [],
            '用户 [%s](%d) 修改了项目 [%s](%d)',
            \Auth::user()->name,
            \Auth::user()->id,
            $project->name,
            $project->id
        );
    }
}
