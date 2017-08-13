<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
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
     *
     * @return bool
     */
    protected function groupHasProjectPrivilege(Project $project, User $user = null): bool
    {
        $groupHasPrivilege = false;
        $userGroups        = $user->groups->pluck('id')->toArray();
        if (!empty($userGroups)) {
            $groupHasPrivilege = $project->groups()
                ->wherePivotIn('group_id', $userGroups)
                ->wherePivot('privilege', '=', 1)->exists();
        }

        return $groupHasPrivilege;
    }
}