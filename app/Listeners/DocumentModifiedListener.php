<?php

namespace App\Listeners;

use App\Events\DocumentModified;
use App\Repositories\OperationLogs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DocumentModifiedListener
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
     * @param  DocumentModified  $event
     * @return void
     */
    public function handle(DocumentModified $event)
    {
        $doc = $event->getDocument();

        OperationLogs::logf(
            \Auth::user()->id,
            [],
            '用户 [%s](%d) 修改了文档 [%s](%d)::[%s](%d)',
            \Auth::user()->name,
            \Auth::user()->id,
            $doc->project->name,
            $doc->project_id,
            $doc->title,
            $doc->id
        );
    }
}
