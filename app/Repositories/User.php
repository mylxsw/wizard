<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 *
 * @property integer $id
 * @property string  $name
 * @property string  $password
 * @property integer $role
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 *
 * @package App\Repositories
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * 普通用户
     */
    const ROLE_NORMAL = 1;
    /**
     * 管理员用户
     */
    const ROLE_ADMIN = 2;

    protected $table = 'wz_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable
        = [
            'name',
            'email',
            'password',
            'role',
        ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden
        = [
            'password',
            'remember_token',
        ];

    /**
     * 用户拥有的项目
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * 用户拥有的页面
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pages()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * 用户所属的分组
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'wz_user_group_ref', 'user_id', 'group_id');
    }

    /**
     * 判断当前用户是否为管理员
     *
     * @return bool
     */
    public function isAdmin()
    {
        return (int)$this->role === self::ROLE_ADMIN;
    }
}
