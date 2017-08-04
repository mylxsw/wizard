<?php

namespace App\Listeners;

use App\Events\DocumentRecovered;
use App\Repositories\OperationLogs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DocumentRecoveredListener
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
     * @param  DocumentRecovered $event
     *
     * @return void
     */
    public function handle(DocumentRecovered $event)
    {
        $doc = $event->getDocument();


        OperationLogs::log(\Auth::user()->id,
            __(
                'log.user_recover_document',
                [
                    'username'     => \Auth::user()->name,
                    'user_id'      => \Auth::user()->id,
                    'project_name' => $doc->project->name,
                    'project_id'   => $doc->project_id,
                    'doc_title'    => $doc->title,
                    'doc_id'       => $doc->id
                ]
            ),
            [
                'history_id' => $doc->history_id,
            ]
        );
    }
}
