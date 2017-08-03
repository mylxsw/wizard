<?php

namespace App\Listeners;

use App\Events\DocumentCreated;
use App\Repositories\OperationLogs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DocumentCreatedListener
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
     * @param  DocumentCreated $event
     *
     * @return void
     */
    public function handle(DocumentCreated $event)
    {
        $doc = $event->getDocument();

        OperationLogs::log($doc->user_id,
            sprintf(
                '用户 [%s](%d) 创建了文档 [%s](%d)::[%s](%d)',
                $doc->user->name,
                $doc->user_id,
                $doc->project->name,
                $doc->project_id,
                $doc->title,
                $doc->id
            )
        );
    }
}
