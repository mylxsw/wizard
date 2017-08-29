<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Policies;


use App\Repositories\Project;
use App\Repositories\User;

trait GroupHasProjectPrivilege
{
    /**
     * 判断用户分组是否有对项目的写权限
     *
     * @param Project $project
     * @param User    $user
     * @param integer $privilege 1-读写 2-只读
     *
     * @return bool
     */
    protected function groupHasProjectPrivilege(
        Project $project,
        User $user = null,
        $privilege = Project::PRIVILEGE_WR
    ): bool {
        $groupHasPrivilege = false;
        $userGroups        = $user->groups->pluck('id')->toArray();
        if (!empty($userGroups)) {

            $projectModel = $project->groups()->wherePivotIn('group_id', $userGroups);
            // 如果不是读写权限，则不需要判断权限类别，默认为只读
            if ($privilege == Project::PRIVILEGE_WR) {
                $projectModel = $projectModel->wherePivot('privilege', '=', $privilege);
            }

            $groupHasPrivilege = $projectModel->exists();
        }

        return $groupHasPrivilege;
    }
}