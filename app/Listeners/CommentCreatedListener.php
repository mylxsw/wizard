<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Listeners;

use App\Events\CommentCreated;
use App\Notifications\CommentMentioned;
use App\Notifications\CommentReplied;
use App\Notifications\DocumentCommented;
use App\Repositories\Comment;
use App\Repositories\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentCreatedListener
{
    /**
     * Handle the event.
     *
     * @param  CommentCreated $event
     *
     * @return void
     */
    public function handle(CommentCreated $event)
    {
        // 通知相关用户
        $comment = $event->getComment();
        if ($comment->user_id != $comment->document->user_id) {
            $comment->document->user->notify(new DocumentCommented($comment->document, $comment));
        }

        // 如果当前评论回复了其它评论，则需要通知被回复的评论所属用户
        if ($comment->reply_to_id != 0) {
            /** @var Comment $replyComment */
            $replyComment = $comment->replyComment;
            if ($replyComment->user_id != $comment->document->user_id) {
                $replyComment->user->notify(new CommentReplied($comment->document, $comment));
            }
        }

        // 解析文本内容，如果包含@user，则通知该用户
        $users = parseUsersFromContent($comment->content);
        if (!empty($users)) {
            $users->map(function (User $user) use ($comment) {
                $user->notify(new CommentMentioned($comment->document, $comment));
            });
        }
    }
}
