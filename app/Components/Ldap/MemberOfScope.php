<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Components\Ldap;


use Adldap\Laravel\Scopes\ScopeInterface;
use Adldap\Query\Builder;

class MemberOfScope implements ScopeInterface
{

    /**
     * Apply the scope to a given Adldap query builder.
     *
     * @param Builder $builder
     *
     * @return void
     */
    public function apply(Builder $builder)
    {
        // 限制可以登录的成员组，如果不设置，则默认所有用户都可以登录
        $memberOf = config('wizard.ldap.only_member_of');
        if (!empty($memberOf)) {
            $builder->whereMemberOf($memberOf);
        }
    }
}