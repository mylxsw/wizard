<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Notifications;

use App\Repositories\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DocumentDeleted extends Notification
{
    use Queueable;

    private $document;

    /**
     * Create a new notification instance.
     * DocumentDeleted constructor.
     *
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'document' => [
                'id'                 => $this->document->id,
                'title'              => $this->document->title,
                'last_modified_user' => $this->document->lastModifiedUser->name,
                'deleted_at'         => $this->document->deleted_at,
            ],
            'message'  => sprintf(
                '%s 删除了文档 %s',
                $this->document->lastModifiedUser->name,
                $this->document->title
            )
        ];
    }
}
