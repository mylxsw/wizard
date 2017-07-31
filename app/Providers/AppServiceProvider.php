<?php

namespace App\Providers;

use App\Repositories\Page;
use App\Repositories\Project;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->addProjectExistRules('project_exist');
        $this->addPageExistRules('page_exist');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * 检查页面是否存在
     *
     * 参数：项目ID
     *
     * @param string $ruleName
     */
    private function addPageExistRules(string $ruleName)
    {
        $this->registerValidationRule(
            $ruleName,
            '对应的页面不存在',
            function ($attribute, $value, $parameters, $validator) {
                $projectID = $parameters[0] ?? 0;

                $conditions   = [];
                $conditions[] = ['id', $value];

                if (!empty($projectID)) {
                    $conditions[] = ['project_id', $projectID];
                }

                return Page::where($conditions)->exists();
            }
        );
    }

    /**
     * 添加检查项目是否存在的规则
     *
     * @param string $ruleName
     */
    private function addProjectExistRules(string $ruleName)
    {
        $this->registerValidationRule(
            $ruleName,
            '对应的项目不存在',
            function ($attribute, $value, $parameters, $validator) {
                return Project::where('id', $value)->exists();
            }
        );
    }

    /**
     * 注册校验规则
     *
     * @param string   $ruleName
     * @param string   $validationMessage
     * @param \Closure $callback
     */
    private function registerValidationRule(
        string $ruleName,
        string $validationMessage,
        \Closure $callback
    ) {
        \Validator::extend($ruleName, $callback);
        \Validator::replacer($ruleName,
            function ($message, $attribute, $rule, $parameters) use (
                $ruleName,
                $validationMessage
            ) {
                if ($message == "validation.{$ruleName}") {
                    return $validationMessage;
                }

                return $message;
            });
    }
}
