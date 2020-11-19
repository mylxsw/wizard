<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lab404\Impersonate\Models\Impersonate;
use Lab404\Impersonate\Services\ImpersonateManager;

/**
 * Class User
 *
 * @property integer $id
 * @property string $name
 * @property string $password
 * @property integer $role
 * @property integer $status
 * @property string $objectguid
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @package App\Repositories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Project[] $favoriteProjects
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Group[] $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\DocumentHistory[] $histories
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Document[] $pages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Project[] $projects
 * @mixin \Eloquent
 * @property string $email
 * @property string|null $remember_token
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\User whereUpdatedAt($value)
 */
class User extends Authenticatable
{
    use Notifiable, Impersonate;

    /**
     * 普通用户
     */
    const ROLE_NORMAL = 1;
    /**
     * 管理员用户
     */
    const ROLE_ADMIN = 2;

    const STATUS_NONE = 0;
    const STATUS_ACTIVATED = 1;
    const STATUS_DISABLED = 2;

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
            'status',
            'objectguid',
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
     * 用户关注的项目
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoriteProjects()
    {
        return $this->belongsToMany(Project::class, 'wz_project_stars', 'user_id', 'project_id');
    }

    /**
     * 用户编辑过的历史页面
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function histories()
    {
        return $this->hasMany(DocumentHistory::class, 'operator_id', 'id');
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

    /**
     * 是否用户已激活
     *
     * @return bool
     */
    public function isActivated()
    {
        return (int)$this->status === self::STATUS_ACTIVATED;
    }

    /**
     * 是否用户已经禁用
     *
     * @return bool
     */
    public function isDisabled()
    {
        return (int)$this->status === self::STATUS_DISABLED;
    }

    /**
     * 用户是否可以扮演其它用户
     *
     * @return bool
     */
    public function canImpersonate()
    {
        return $this->isAdmin();
    }

    /**
     * 用户是否可以被扮演
     *
     * @return bool
     */
    public function canBeImpersonated()
    {
        return !$this->isAdmin();
    }

    /**
     * 查询扮演者信息
     *
     * @return User
     */
    public function impersonator()
    {
        return app(ImpersonateManager::class)->getImpersonator();
    }
}
