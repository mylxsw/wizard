<?php


namespace App\Listeners;


use App\Events\DocumentMarkModified;
use App\Events\DocumentModified;
use App\Notifications\DocumentUpdated;
use App\Repositories\OperationLogs;
use App\Repositories\User;

class DocumentMarkModifiedListener
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
     * @param DocumentMarkModified $event
     *
     * @return void
     */
    public function handle(DocumentMarkModified $event)
    {
        $doc = $event->getDocument();

        // 记录操作日志
        OperationLogs::log(
            \Auth::user()->id,
            'document_mark_updated',
            [
                'username'     => \Auth::user()->name,
                'user_id'      => \Auth::user()->id,
                'project_name' => $doc->project->name,
                'project_id'   => $doc->project_id,
                'doc_title'    => $doc->title,
                'doc_id'       => $doc->id,
                'status'       => $doc->status,
            ],
            impersonateUser()
        );

        // 发送消息通知相关用户
        $users = User::whereHas('histories', function ($query) use ($doc) {
            $query->where('page_id', $doc->id);
        })->get()->filter(function ($user) use ($doc) {
            // 不通知当前操作用户
            return $user->id != $doc->last_modified_uid;
        });

        if (count($users) > 0) {
            \Notification::send($users, new DocumentUpdated($doc));
        }
    }

}