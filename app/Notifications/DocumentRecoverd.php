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
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DocumentRecoverd extends Notification
{
    use Queueable;

    private $document;

    /**
     * Create a new notification instance.
     *
     * @return void
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
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
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
                'id'    => $this->document->id,
                'title' => $this->document->title,
            ],
            'message'  => sprintf(
                '%s 恢复了文档 <a target="_blank" href="%s">%s</a>，历史版本为 %d',
                $this->document->lastModifiedUser->name,
                wzRoute('project:home', [
                    'id' => $this->document->project_id,
                    'p'  => $this->document->id
                ]),
                $this->document->title,
                $this->document->history_id
            )
        ];
    }
}
