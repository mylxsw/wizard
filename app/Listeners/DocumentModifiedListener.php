<?php

namespace App\Listeners;

use App\Events\DocumentModified;
use App\Notifications\DocumentUpdated;
use App\Repositories\DocumentHistory;
use App\Repositories\OperationLogs;
use App\Repositories\User;
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
     * @param  DocumentModified $event
     *
     * @return void
     */
    public function handle(DocumentModified $event)
    {
        $doc = $event->getDocument();

        // 记录操作日志
        OperationLogs::log(
            \Auth::user()->id,
            'document_updated',
            [
                'username'     => \Auth::user()->name,
                'user_id'      => \Auth::user()->id,
                'project_name' => $doc->project->name,
                'project_id'   => $doc->project_id,
                'doc_title'    => $doc->title,
                'doc_id'       => $doc->id
            ]
        );

        // 发送消息通知相关用户
        $users = User::whereHas('histories', function ($query) use ($doc) {
            $query->where('page_id', $doc->id);
        })->get();

        \Notification::send($users, new DocumentUpdated($doc));
    }
}
