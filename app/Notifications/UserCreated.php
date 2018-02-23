<?php

namespace App\Notifications;

use App\Repositories\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserCreated extends Notification
{
    use Queueable;

    private $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
                '有新用户注册，用户名 %s，账号 <a target="_blank" href="%s">%s</a>，注册时间 %s',
                $this->user->name,
                wzRoute(
                    'admin:user',
                    [
                        'id' => $this->user->id,
                    ]
                ),
                $this->user->email,
                $this->user->created_at->format('Y-m-d H:i:s')
            ),
            'user'    => [
                'name'       => $this->user->name,
                'email'      => $this->user->email,
                'id'         => $this->user->id,
                'created_at' => $this->user->created_at->format('Y-m-d H:i:s'),
            ]
        ];
    }
}
