<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

return [
    /**
     * 当前版本
     */
    'version'                    => '1.2.8',
    /**
     * 版本检查，暂时不可用
     */
    'version-check'              => env('WIZARD_VERSION_CHECK', false),
    /**
     * 新注册账号是否需要邮箱激活
     */
    'need_activate'              => env('WIZARD_NEED_ACTIVATE', false),
    /**
     * 是否启用用户注册支持
     */
    'register_enabled'           => env('WIZARD_REGISTER_ENABLED', true),
    /**
     * 新账号注册是否需要邀请码
     */
    'register_invitation'        => env('WIZARD_REGISTER_INVITATION', false),
    /**
     * 静态邀请码
     *
     * 不设置则不启用静态邀请码，设置后，邀请码必须与该邀请码一致才能注册
     */
    'register_invitation_static' => env('WIZARD_REGISTER_INVITATION_STATIC', ''),
    /**
     * JWT 加密密码
     */
    'jwt_secret'                 => env('WIZARD_JWT_SECRET'),

    /**
     * 默认主题
     */
    'theme'                      => env('WIZARD_DEFAULT_THEME', 'default'),

    /**
     * 静态资源版本
     */
    'resource_version'           => env('WIZARD_RESOURCE_VERSION', '201709071013'),
    /**
     * 版权地址
     */
    'copyright'                  => env('WIZARD_COPYRIGHT', 'AICODE.CC'),

    /**
     * 管理员在公共页面可以查看所有项目
     */
    'admin_see_all'              => env('WIZARD_ADMIN_SEE_ALL', true),

    /**
     * 登录页面背景图片
     */
    'login_background_img'       => env('WIZARD_LOGIN_BACKGROUND_IMG', '/assets/background-image.jpeg'),

    /**
     * 是否启用文档评论功能
     */
    'reply_support'              => env('WIZARD_REPLY_SUPPORT', true),
    /**
     * 是否必须登录才能查看文档
     */
    'must_login'                 => env('WIZARD_MUST_LOGIN', false),

    /**
     * CDN 加速
     */
    'cdn'                        => [
        /**
         * 是否启用 CDN 加速
         */
        'enabled' => env('CDN_ENABLED', false),
        /**
         * CDN 服务器地址，比如七牛云，就用七牛云配置的融合CDN域名
         */
        'url'     => env('CDN_URL', 'http://cdn.example.com'),
    ],

    /**
     * LDAP
     */
    'ldap'                       => [
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

    /**
     * Markdown 配置
     */
    'markdown'                   => [
        /**
         * 是否启用严格的 Markdown 解释器，如果你的 markdown 格式并不标准，可以将该选项设置为 false
         */
        'strict'      => env('WIZARD_MARKDOWN_STRICT', true),
        /**
         * 是否自动添加 TOC（文档目录，当页面内容中不包含 TOC/TOCM 标签时，自动添加）
         *
         * 设置为空则不启用该功能，如果启用，则设置为 TOC 或者 TOCM
         */
        'default_toc' => env('WIZARD_MARKDOWN_TOC_DEFAULT', ''),
    ],

    /**
     * 表格类型文档配置
     */
    'spreedsheet'                => [
        /**
         * 最大支持的行数
         */
        'max_rows' => env('WIZARD_SPREEDSHEET_MAX_ROWS', 100),
        /**
         * 最大支持的列数
         */
        'max_cols' => env('WIZARD_SPREEDSHEET_MAX_COLS', 26),
        /**
         * 最小展示行数
         */
        'min_rows' => env('WIZARD_SPREEDSHEET_MIN_ROWS', 10),
        /**
         * 最小展示的列数
         */
        'min_cols' => env('WIZARD_SPREEDSHEET_MIN_COLS', 6),
    ],

    /**
     * 文件附件
     */
    'attachments'                => [
        /**
         * 支持的文件扩展名列表，使用,分割
         */
        'support_extensions' => env('WIZARD_ATTACHMENTS_SUPPORT_EXTENSIONS',
            'jpg,jpeg,gif,png,bmp,zip,rar,war,mwb,xmind,itmz,mindnode,svg,md,vsd,vsdx,txt,doc,docx,xls,xlsx,ppt,pptx,pdf,sql'),
    ],
    /**
     * 全文搜索
     */
    'search'                     => [
        /**
         * 全文搜索驱动 Class
         *
         * GoFound: App\Components\Search\GoFoundDriver
         * Null: App\Components\Search\NullDriver
         * ZincSearch: App\Components\Search\ZincSearchDriver
         */
        'driver'  => env('WIZARD_SEARCH_DRIVER', 'App\Components\Search\NullDriver'),

        /**
         * 驱动配置
         */
        'drivers' => [
            /**
             * GoFound 搜索引擎驱动
             *
             * https://github.com/newpanjing/gofound
             */
            'gofound' => [
                'server'   => env('WIZARD_GOFOUND_SERVER', 'http://localhost:5678'),
                'database' => env('WIZARD_GOFOUND_DATABASE', 'default'),
                'username' => env('WIZARD_GOFOUND_USERNAME', ''),
                'password' => env('WIZARD_GOFOUND_PASSWORD', ''),
            ],
            /**
             *  ZincSearchDriver
             *
             * https://docs.zincsearch.com/
             */
            'zinc'    => [
                'server'      => env('WIZARD_ZINC_SERVER', 'http://localhost:4080'),
                'index'       => env('WIZARD_ZINC_INDEX', 'wizard'),
                'username'    => env('WIZARD_ZINC_USERNAME', ''),
                'password'    => env('WIZARD_ZINC_PASSWORD', ''),
                // 支持： alldocuments,wildcard,fuzzy,term,daterange,matchall,match,matchphrase,multiphrase,prefix,querystring
                // 参考文档： https://docs.zincsearch.com/API%20Reference/search/1_search/
                'search_type' => env('WIZARD_ZINC_SEARCH_TYPE', 'matchphrase'),
            ],
        ],
    ],
];
