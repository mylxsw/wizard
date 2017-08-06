<?php

namespace App\Policies;

use App\Repositories\Document;
use App\Repositories\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * 文档权限策略
 *
 * @package App\Policies
 */
class DocumentPolicy
{
    use HandlesAuthorization;

    /**
     * 文档编辑权限
     *
     * @param User $user
     * @param      $page
     *
     * @return bool
     */
    public function edit(User $user, $page)
    {
        return $this->isOwner($user, $page);
    }

    /**
     * 文档还原权限
     *
     * @param User $user
     * @param      $page
     *
     * @return bool
     */
    public function recover(User $user, $page)
    {
        return $this->isOwner($user, $page);
    }

    /**
     * 是否是文档创建者
     *
     * @param User $user
     * @param      $page
     *
     * @return bool
     */
    private function isOwner(User $user, $page)
    {
        if (empty($user)) {
            return false;
        }

        if (!$page instanceof Document) {
            $page = Document::where('id', $page)->firstOrFail();
        }

        return (int)$user->id === (int)$page->user_id;
    }
}
