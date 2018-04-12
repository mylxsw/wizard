<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Repositories;


use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Repository
{

    use SoftDeletes;

    /**
     * 公开项目
     */
    const VISIBILITY_PUBLIC = '1';
    /**
     * 私有项目
     */
    const VISIBILITY_PRIVATE = '2';

    /**
     * 读写
     */
    const PRIVILEGE_WR = 1;
    /**
     * 只读
     */
    const PRIVILEGE_RO = 2;

    protected $table = 'wz_projects';
    protected $fillable
        = [
            'name',
            'description',
            'visibility',
            'user_id',
            'sort_level',
            'catalog_id',
        ];

    public $dates = ['deleted_at'];

    /**
     * 项目下的所有页面
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pages()
    {
        return $this->hasMany(Document::class);
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

    /**
     * 项目所属的分组
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'wz_project_group_ref', 'project_id', 'group_id')
            ->withPivot('created_at', 'updated_at', 'privilege');
    }

    /**
     * 关注该项目的用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoriteUsers()
    {
        return $this->belongsToMany(User::class, 'wz_project_stars', 'project_id', 'user_id');
    }

    /**
     * 项目下所有的附件
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'project_id', 'id');
    }

    /**
     * 项目所属的目录
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function catalog()
    {
        return $this->belongsTo(Catalog::class, 'catalog_id', 'id');
    }

    /**
     * 判断是否用户关注了该项目
     *
     * @param User $user
     *
     * @return bool
     */
    public function isFavoriteByUser(User $user = null)
    {
        if (empty ($user)) {
            return false;
        }

        return $this->favoriteUsers()->wherePivot('user_id', $user->id)->exists();
    }
}