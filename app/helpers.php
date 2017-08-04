<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

use App\Repositories\Template;
use App\Repositories\User;

/**
 * 生成路由url
 *
 * @param string $name
 * @param array  $parameters
 * @param bool   $absolute
 *
 * @return string
 */
function wzRoute($name, $parameters = [], $absolute = false)
{
    return route($name, $parameters, $absolute);
}

/**
 * 将页面集合转换为层级结构的菜单
 *
 * @param \Illuminate\Database\Eloquent\Collection $pages     每一页文档
 * @param int                                      $projectID 当前项目ID
 * @param int                                      $pageID    选中的文档ID
 * @param array                                    $exclude   排除的文档ID列表
 *
 * @return array
 */
function navigator(
    \Illuminate\Database\Eloquent\Collection $pages,
    int $projectID,
    int $pageID = 0,
    $exclude = []
) {
    $navigators = [];
    /** @var \App\Repositories\Document $page */
    foreach ($pages as $page) {
        if (in_array((int)$page->id, $exclude)) {
            continue;
        }

        $navigators[$page->id] = [
            'id'       => (int)$page->id,
            'name'     => $page->title,
            'pid'      => (int)$page->pid,
            'url'      => route('project:home', ['id' => $projectID, 'p' => $page->id]),
            'selected' => $pageID === (int)$page->id,
        ];
    }

    foreach ($navigators as &$nav) {
        if ($nav['pid'] === 0 || in_array($nav['id'], $exclude)) {
            continue;
        }

        if (isset($navigators[$nav['pid']])) {
            $navigators[$nav['pid']]['nodes'][] = $nav;
        }
    }

    return array_filter($navigators, function ($nav) {
        return $nav['pid'] === 0;
    });
}

/**
 * 文档模板
 *
 * @param int       $type
 * @param User|null $user
 *
 * @return array
 */
function wzTemplates($type = Template::TYPE_DOC, User $user = null): array
{
    if ($user == null && !Auth::guest()) {
        $user = Auth::user();
    }

    return Template::queryForShow($type, $user);
}