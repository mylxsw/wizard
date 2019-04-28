<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

return [
    /**
     * 新注册账号是否需要邮箱激活
     */
    'need_activate'    => env('WIZARD_NEED_ACTIVATE', false),
    /**
     * JWT 加密密码
     */
    'jwt_secret'       => env('WIZARD_JWT_SECRET'),

    /**
     * 静态资源版本
     */
    'resource_version' => env('WIZARD_RESOURCE_VERSION', '201709071013'),
    /**
     * 版权地址
     */
    'copyright'        => env('WIZARD_COPYRIGHT', 'AICODE.CC'),

    /**
     * LDAP
     */
    'ldap'             => [
        /**
         * 是否启用ldap
         */
        'enabled'        => env('WIZARD_USE_LDAP', false),
        
        /**
         * 允许登录的成员，为空则不限制
         * 比如： 'CN=technology-products,CN=Users,DC=example,DC=com'
         */
        'only_member_of' => env('WIZARD_LDAP_ONLY_MEMBER_OF', ''),
    ],
];