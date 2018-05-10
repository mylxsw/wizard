<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Http\Controllers;


use App\Events\CommentCreated;
use App\Notifications\CommentReplied;
use App\Notifications\DocumentCommented;
use App\Policies\ProjectPolicy;
use App\Repositories\Comment;
use App\Repositories\Document;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * 发表评论
     *
     * @param Request $request
     * @param         $id
     * @param         $page_id
     *
     * @return array
     */
    public function publish(Request $request, $id, $page_id)
    {
        $content = $request->input('content');
        $this->validateParameters(
            [
                'project_id' => $id,
                'page_id'    => $page_id,
                'content'    => $content,
            ],
            [
                'project_id' => "required|integer|min:1|in:{$id}|project_exist",
                'page_id'    => "required|integer|min:1|in:{$page_id}|page_exist:{$id}",
                'content'    => 'required|between:1,10000',
            ],
            [
                'content.required' => '评论内容不能为空',
                'content.between'  => '评论内容最大不能超过10000字符',
            ]
        );

        $policy = new ProjectPolicy();
        if (!$policy->view(\Auth::user(), $id)) {
            abort(404);
        }

        $comment = Comment::create([
            'content'     => comment_filter($content),// TODO XSS过滤
            'user_id'     => \Auth::user()->id,
            'reply_to_id' => 0,
            'page_id'     => $page_id,
        ]);

        event(new CommentCreated($comment));

        return [
            'id' => $comment->id
        ];
    }

}