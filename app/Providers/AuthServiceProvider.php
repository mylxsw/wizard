<?php

namespace App\Providers;

use App\Repositories\Document;
use App\Repositories\Project;
use App\Repositories\User;
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

        // 检查用户是否有对项目的编辑权限
        Gate::define('project-setting', function (User $user, $project) {
            if (empty($user)) {
                return false;
            }

            if (!$project instanceof Project) {
                $project = Project::where('id', $project)->firstOrFail();
            }

            return (int)$user->id === (int)$project->user_id;
        });

        // 检查是否有新增页面的权限
        Gate::define('page-add', function (User $user, $project) {
            if (empty($user)) {
                return false;
            }

            if (!$project instanceof Project) {
                $project = Project::where('id', $project)->firstOrFail();
            }

            return (int)$user->id === (int)$project->user_id;
        });

        // 检查是否有编辑页面的权限
        Gate::define('page-edit', function (User $user, $page) {
            if (empty($user)) {
                return false;
            }

            if (!$page instanceof Document) {
                $page = Document::where('id', $page)->firstOrFail();
            }

            return (int)$user->id === (int)$page->user_id;
        });
    }
}
