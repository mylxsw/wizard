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

        OperationLogs::logf(
            \Auth::user()->id,
            [
                'history_id' => $doc->history_id,
            ],
            '用户 [%s](%d) 还原了文档 [%s](%d)::[%s](%d)',
            \Auth::user()->name,
            \Auth::user()->id,
            $doc->project->name,
            $doc->project_id,
            $doc->title,
            $doc->id
        );
    }
}
