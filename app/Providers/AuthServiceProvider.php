<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Providers;

use App\Policies\DocumentPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\TemplatePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // 检查用户是否具有创建项目权限
        Gate::define('project-create', ProjectPolicy::class . '@create');
        // 检查用户是否有对项目的编辑权限
        Gate::define('project-edit', ProjectPolicy::class . '@edit');
        // 检查用户是否有删除项目的权限
        Gate::define('project-delete', ProjectPolicy::class . '@delete');
        // 检查是否可以评论
        Gate::define('project-comment', ProjectPolicy::class . '@comment');
        // 检查是否具有项目排序权限
        Gate::define('project-sort', ProjectPolicy::class . '@sortLevel');

        // 检查是否有新增页面的权限
        Gate::define('page-add', ProjectPolicy::class . '@addPage');
        // 检查是否有编辑页面的权限
        Gate::define('page-edit', DocumentPolicy::class . '@edit');
        // 检查是否有还原页面的权限
        Gate::define('page-recover', DocumentPolicy::class . '@recover');
        // 检查是否有文档的分享权限
        Gate::define('page-share', DocumentPolicy::class . '@share');

        // 是否可以创建全局可用的模板
        Gate::define('template-global-create', TemplatePolicy::class . '@globalCreate');
    }
}
