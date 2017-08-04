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

        OperationLogs::log(\Auth::user()->id,
            __(
                'log.user_delete_document',
                [
                    'username'     => \Auth::user()->name,
                    'user_id'      => \Auth::user()->id,
                    'project_name' => $doc->project->name,
                    'project_id'   => $doc->project_id,
                    'doc_title'    => $doc->title,
                    'doc_id'       => $doc->id
                ]
            )
        );
    }
}
