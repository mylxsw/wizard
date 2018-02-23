<?php

namespace App\Notifications;

use App\Repositories\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProjectCreated extends Notification
{
    use Queueable;

    private $project;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
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
            'message' => sprintf(
                '用户 %s 创建了新的项目 <a target="_blank" href="%s">%s</a>',
                $this->project->user->name,
                wzRoute(
                    'project:home',
                    [
                        'id' => $this->project->id,
                    ]
                ),
                $this->project->name
            ),
            'project' => [
                'id'         => $this->project->id,
                'title'      => $this->project->name,
                'created_at' => $this->project->created_at->format('Y-m-d H:i:s'),
                'username'   => $this->project->user->name,
                'user_id'    => $this->project->user_id,
            ]
        ];
    }
}
