<?php

namespace App\Policies;

use App\Repositories\Project;
use App\Repositories\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * 项目策略
 *
 * @package App\Policies
 */
class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * 项目配置修改权限
     *
     * @param User $user
     * @param      $project
     *
     * @return bool
     */
    public function setting(User $user, $project)
    {
        return $this->isOwner($user, $project);
    }

    /**
     * 新增页面权限
     *
     * @param User $user
     * @param      $project
     *
     * @return bool
     */
    public function addPage(User $user, $project)
    {
        return $this->isOwner($user, $project);
    }

    /**
     * 编辑项目信息权限
     *
     * @param User $user
     * @param      $project
     *
     * @return bool
     */
    public function edit(User $user, $project)
    {
        return $this->isOwner($user, $project);
    }

    /**
     * 是否是项目的创建者
     *
     * @param User $user
     * @param      $project
     *
     * @return bool
     */
    private function isOwner(User $user, $project)
    {
        if (empty($user)) {
            return false;
        }

        if (!$project instanceof Project) {
            $project = Project::where('id', $project)->firstOrFail();
        }

        return (int)$user->id === (int)$project->user_id;
    }
}
