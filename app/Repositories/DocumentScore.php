<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Repositories;

/**
 * 文档评价模型
 *
 * @property integer $id
 * @property integer $page_id
 * @property integer $user_id
 * @property integer $score_type
 *
 * @package App\Repositories
 */
class DocumentScore extends Repository
{
    const SCORE_USEFUL = 1;
    const SCORE_HARD_TO_READ = 2;
    const SCORE_NO_USE = 3;
    const SCORE_GARBAGE = 4;

    protected $table = 'wz_page_score';
    protected $fillable
        = [
            'page_id',
            'user_id',
            'score_type',
        ];

    /**
     * 所属的用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}