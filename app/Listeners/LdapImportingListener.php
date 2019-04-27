<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Listeners;


use Adldap\Laravel\Events\Importing;
use App\Repositories\User;

/**
 * LDAP用户导入事件监听（本地数据库没有该用户）
 *
 * @package App\Listeners
 */
class LdapImportingListener
{
    public function handle(Importing $event)
    {
        // 用户是新同步来的，状态为未激活
        // LDAP 同步时，自动激活用户
        $event->model->status = User::STATUS_ACTIVATED;
        $event->model->role   = User::ROLE_NORMAL;
    }
}