<?php

namespace App\Policies;

use App\Repositories\Group;
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
    use HandlesAuthorization, GroupHasProjectPrivilege;

    /**
     * 项目配置修改权限
     *
     * @param User $user
     * @param      $project
     *
     * @return bool
     */
    public function setting(User $user = null, $project)
    {
        if (empty($user)) {
            return false;
        }

        return $user->isAdmin() || $this->isOwner($user, $project);
    }

    /**
     * 项目查看权限
     *
     * @param User $user
     * @param      $project
     *
     * @return bool
     */
    public function view(User $user = null, $project)
    {
        $project = $this->getProject($project);
        if ($project->visibility == Project::VISIBILITY_PUBLIC) {
            return true;
        }

        if (empty($user)) {
            return false;
        }

        return $user->isAdmin()
            || $this->isOwner($user, $project)
            || $this->groupHasProjectPrivilege($project, $user);
    }

    /**
     * 新增页面权限
     *
     * @param User $user
     * @param      $project
     *
     * @return bool
     */
    public function addPage(User $user = null, $project)
    {
        if (empty($user)) {
            return false;
        }

        // 管理员
        if ($user->isAdmin()) {
            return true;
        }

        // 项目创建者
        if ($this->isOwner($user, $project)) {
            return true;
        }

        $project = $this->getProject($project);
        return $this->groupHasProjectPrivilege($project, $user);
    }


    /**
     * 编辑项目信息权限
     *
     * @param User $user
     * @param      $project
     *
     * @return bool
     */
    public function edit(User $user = null, $project)
    {
        if (empty($user)) {
            return false;
        }

        return $user->isAdmin() || $this->isOwner($user, $project);
    }

    /**
     * 项目删除权限检查
     *
     * @param User $user
     * @param      $project
     *
     * @return bool
     */
    public function delete(User $user = null, $project)
    {
        if (empty($user)) {
            return false;
        }

        return $user->isAdmin() || $this->isOwner($user, $project);
    }

    /**
     * 是否是项目的创建者
     *
     * @param User $user
     * @param      $project
     *
     * @return bool
     */
    private function isOwner(User $user = null, $project)
    {
        if (empty($user)) {
            return false;
        }

        $project = $this->getProject($project);

        return (int)$user->id === (int)$project->user_id;
    }

    private function getProject($project): Project
    {
        if (!$project instanceof Project) {
            $project = Project::where('id', $project)->firstOrFail();
        }

        return $project;
    }
}
