<?php

namespace App\Mail;

use App\Repositories\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserActivate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    private $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $token = jwt_create_token([
            'uid'   => $this->user->id,
            'email' => $this->user->email,
        ], 3600 * 24);

        // 这里要用route函数，不能用wzRoute!!!
        $link = route('user:activate', [
            'token' => (string)$token
        ]);

        return $this->subject('用户账号激活')
            ->markdown('auth.emails.activate')
            ->with([
                'link' => $link,
                'user' => $this->user,
            ]);
    }
}
