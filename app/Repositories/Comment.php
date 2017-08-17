<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Repositories;


use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Repository
{
    use SoftDeletes;

    protected $table = 'wz_comments';
    protected $fillable = [
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
}