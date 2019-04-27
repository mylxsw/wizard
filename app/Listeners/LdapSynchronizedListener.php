<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Listeners;


use Adldap\Laravel\Events\Synchronized;

/**
 * LDAP 信息已经同步到用户Model对象
 *
 * @package App\Listeners
 */
class LdapSynchronizedListener
{
    public function handle(Synchronized $event)
    {
        // 如果用户名为空（默认根据配置环境变量 LDAP_SYNC_NAME_ATTR 读取），则使用cn作为名称
        if (empty($event->model->name)) {
            $event->model->name = $event->user->getCommonName();
        }
    }
}