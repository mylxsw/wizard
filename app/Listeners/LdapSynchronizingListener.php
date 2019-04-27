<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Listeners;


use Adldap\Laravel\Events\Synchronizing;

/**
 * LDAP 用户同步事件监听
 *
 * @package App\Listeners
 */
class LdapSynchronizingListener
{
    public function handle(Synchronizing $event)
    {

    }
}