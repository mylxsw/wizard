<?php

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
        if (empty($user)) {
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
        if (empty($user)) {
            return false;
        }

        if (!$template instanceof Template) {
            $template = Template::where('id', $template)->firstOrFail();
        }

        return (int)$user->id === (int)$template->user_id;
    }
}
