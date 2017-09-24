<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Policies;

use App\Repositories\Project;
use App\Repositories\Template;
use App\Repositories\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * 模板策略
 *
 * @package App\Policies
 */
class TemplatePolicy
{
    use HandlesAuthorization;

    /**
     * 是否用户可以创建全局可用的模板
     *
     * @param User $user
     *
     * @return bool
     */
    public function globalCreate(User $user = null)
    {
        if (empty($user) || !$user->isActivated()) {
            return false;
        }

        return $user->isAdmin();
    }

    /**
     * 是否是项目的创建者
     *
     * @param User         $user
     * @param Template|int $template
     *
     * @return bool
     */
    private function isOwner(User $user = null, $template)
    {
        if (empty($user) || !$user->isActivated()) {
            return false;
        }

        if (!$template instanceof Template) {
            $template = Template::where('id', $template)->firstOrFail();
        }

        return (int)$user->id === (int)$template->user_id;
    }
}
