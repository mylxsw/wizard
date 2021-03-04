<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Providers;

use Adldap\Laravel\Commands\Import;
use App\Components\LdapUserImportScope;
use App\Repositories\Document;
use App\Repositories\Group;
use App\Repositories\InvitationCode;
use App\Repositories\Project;
use App\Repositories\Template;
use App\Repositories\User;
use Carbon\Carbon;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Schema;
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
        // 用于解决某些版本的mysql下，由于默认编码为utf8mb4而导致出现错误
        // Syntax error or access violation: 1071 Specified key was too long; max key length is 767 bytes
        Schema::defaultStringLength(191);

        $this->addProjectExistRules('project_exist');
        $this->addPageExistRules('page_exist');
        $this->addTemplateUniqueRules('template_unique');
        $this->addGroupExistRule('group_exist');
        $this->addCheckUserPasswordRules('user_password');
        $this->addUsernameUniqueRules('username_unique');
        $this->addInvitationCodeRules('invitation_code');

        // 在日志中输出sql历史
//        \DB::listen(function (QueryExecuted $query) {
//            \Log::debug('sql_execute', [
//                'sql'   => $query->sql,
//                'binds' => $query->bindings,
//            ]);
//        });
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
     * 参数：项目ID,是否验证0值
     *
     * @param string $ruleName
     */
    private function addPageExistRules(string $ruleName)
    {
        $this->registerValidationRule(
            $ruleName,
            '参数 %s 对应的页面不存在',
            function ($attribute, $value, $parameters, $validator) {
                $projectID = $parameters[0] ?? 0;
                $validateZeroValue = isset($parameters[1]) ? ($parameters[1] == 'true') : true;

                if (empty($value)) {
                    return !$validateZeroValue;
                }

                $conditions = [];
                $conditions[] = ['id', $value];

                if (!empty($projectID)) {
                    $conditions[] = ['project_id', $projectID];
                }

                return Document::where($conditions)->exists();
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
            '参数 %s 对应的项目不存在',
            function ($attribute, $value, $parameters, $validator) {
                return Project::where('id', $value)->exists();
            }
        );
    }

    /**
     * 新增用户名唯一校验规则
     *
     * @param string $ruleName
     */
    private function addUsernameUniqueRules(string $ruleName)
    {
        $this->registerValidationRule(
            $ruleName,
            '用户名已经存在',
            function ($attribute, $value, $parameters, $validator) {
                $excludeId = $parameters[0] ?? 0;

                $user = User::where('name', $value);
                if (!empty($excludeId)) {
                    $user = $user->where('id', '!=', $excludeId);
                }

                return !$user->exists();
            }
        );
    }

    /**
     * 添加检查用户密码是否合法的规则
     *
     * @param string $ruleName
     */
    private function addCheckUserPasswordRules(string $ruleName)
    {
        $this->registerValidationRule(
            $ruleName,
            '密码 % 不合法',
            function ($attribute, $value, $parameters, $validator) {
                $user = \Auth::user()->makeVisible('password');
                return \Hash::check($value, $user->password);
            }
        );
    }

    /**
     * 添加邀请码校验规则
     *
     * @param string $ruleName
     */
    private function addInvitationCodeRules(string $ruleName)
    {
        $this->registerValidationRule(
            $ruleName,
            '邀请码无效',
            function ($attribute, $value, $parameters, $validator) {
                $staticCode = config('wizard.register_invitation_static');
                if (empty($staticCode)) {
                    return false;
                }

                if ($value === $staticCode) {
                    return true;
                }

                /** @var InvitationCode $invitationCode */
                $invitationCode = InvitationCode::where('code', $value)->first();
                if (empty($invitationCode)) {
                    return false;
                }

                return $invitationCode->expired_at === null
                       || Carbon::now()->isBefore(Carbon::createFromTimeString($invitationCode->expired_at));
            }
        );
    }

    /**
     * 添加检查分组名称是否存在的规则
     *
     * @param string $ruleName
     */
    private function addGroupExistRule(string $ruleName)
    {
        $this->registerValidationRule(
            $ruleName,
            '参数 %s 对应的用户组不存在',
            function ($attribute, $value, $parameters, $validator) {
                return Group::where('id', $value)->exists();
            }
        );
    }

    /**
     * 模板名称是否唯一
     *
     * @param string $ruleName
     */
    private function addTemplateUniqueRules(string $ruleName)
    {
        $this->registerValidationRule(
            $ruleName,
            '模板名称已经存在',
            function ($attribute, $value, $parameters, $validator) {
                $excludeId = $parameters[0] ?? 0;

                $template = Template::where('name', $value);
                if (!empty($excludeId)) {
                    $template = $template->where('id', '!=', $excludeId);
                }

                return !$template->exists();
            }
        );
    }

    /**
     * 注册校验规则
     *
     * @param string $ruleName
     * @param string $validationMessage
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
                    return sprintf($validationMessage, $attribute);
                }

                return $message;
            });
    }
}
