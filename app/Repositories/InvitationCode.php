<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Repositories;

/**
 * 注册邀请码
 *
 * @package App\Repositories
 * @property int $id
 * @property string $code         邀请码
 * @property \Carbon\Carbon|null $expired_at   过期时间
 * @property int $user_id      创建用户ID
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class InvitationCode extends Repository
{
    protected $table = 'wz_invitation_code';
    protected $fillable = [
        'code',
        'expired_at',
        'user_id',
    ];
}