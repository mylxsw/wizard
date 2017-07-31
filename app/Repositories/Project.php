<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Repositories;


class Project extends Repository
{

    protected $table = 'wz_projects';
    protected $fillable
        = [
            'name',
            'description',
            'visibility',
            'user_id',
        ];

    /**
     * 项目下的所有页面
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pages()
    {
        return $this->hasMany(User::class);
    }

    /**
     * 项目所属的用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}