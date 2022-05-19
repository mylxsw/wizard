<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Listeners;

use App\Components\Search\Search;
use App\Events\DocumentCreated;
use App\Repositories\OperationLogs;
use Illuminate\Support\Facades\Log;

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
     * @param DocumentCreated $event
     *
     * @return void
     */
    public function handle(DocumentCreated $event)
    {
        $doc = $event->getDocument();

        OperationLogs::log(
            \Auth::user()->id,
            'document_created',
            [
                'username'     => $doc->user->name,
                'user_id'      => $doc->user_id,
                'project_name' => $doc->project->name,
                'project_id'   => $doc->project_id,
                'doc_title'    => $doc->title,
                'doc_id'       => $doc->id,
            ],
            impersonateUser()
        );

        try {
            Search::get()->syncIndex($doc);
        } catch (\Exception $ex) {
            Log::error('create document index failed', ['message' => $ex->getMessage()]);
        }
    }
}
