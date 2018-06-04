<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Repositories;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Comment
 *
 * @property integer $id
 * @property integer $page_id
 * @property integer $user_id
 * @property string  $content
 * @property integer $reply_to_id
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @property Carbon  $deleted_at
 * @package App\Repositories
 * @property-read \App\Repositories\Document $document
 * @property-read \App\Repositories\Comment $replyComment
 * @property-read \App\Repositories\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Comment onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Comment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Comment withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Comment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Comment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Comment wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Comment whereReplyToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Comment whereUserId($value)
 */
class Comment extends Repository
{
    use SoftDeletes;

    protected $table = 'wz_comments';
    protected $fillable
        = [
            'page_id',
            'user_id',
            'content',
            'reply_to_id',
        ];

    public $dates = ['deleted_at'];

    /**
     * 发表评论的用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 所属文档ID
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function document()
    {
        return $this->belongsTo(Document::class, 'page_id', 'id');
    }

    /**
     * 当前评论回复的评论
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function replyComment()
    {
        return $this->belongsTo(Comment::class, 'id', 'reply_to_id');
    }

}