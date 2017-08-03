<?php

namespace App\Listeners;

use App\Events\DocumentDeleted;
use App\Repositories\OperationLogs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DocumentDeletedListener
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
     * @param  DocumentDeleted $event
     *
     * @return void
     */
    public function handle(DocumentDeleted $event)
    {
        $doc = $event->getDocument();

        OperationLogs::logf(
            \Auth::user()->id,
            [],
            '用户 [%s](%d) 删除文档 [%s](%d)::[%s](%d)',
            \Auth::user()->name,
            \Auth::user()->id,
            $doc->project->name,
            $doc->project_id,
            $doc->title,
            $doc->id
        );
    }
}
