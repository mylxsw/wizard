<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
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
 * 必须保证pages是按照pid进行asc排序的，否则可能会出现菜单丢失
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
            'type'     => $page->type == \App\Repositories\Document::TYPE_DOC ? 'doc' : 'sw',
        ];
    }

    foreach ($navigators as &$nav) {
        if ($nav['pid'] === 0 || in_array($nav['id'], $exclude)) {
            continue;
        }

        if (isset($navigators[$nav['pid']])) {
            $navigators[$nav['pid']]['nodes'][] = &$nav;
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

/**
 * 转换json为markdown table
 *
 * @param string $json
 *
 * @return string
 */
function convertJsonToMarkdownTable(string $json): string
{
    $markdowns = [
        ['参数名', '类型', '是否必须', '说明'],
        ['---', '---', '---', '---']
    ];

    foreach (jsonFlatten($json) as $key => $type) {
        $markdowns[] = [$key, $type, '', ''];
    }

    $html = '';
    foreach ($markdowns as $line) {
        $html .= '| ' . implode(' | ', $line) . ' | ' . "\n";
    }

    return $html;
}

/**
 * Json扁平化为一维数组
 *
 * @param string $json
 *
 * @return array
 */
function jsonFlatten(string $json): array
{
    $object = json_decode(trim($json));
    $result = [];

    $setCurrent = function ($prefix, $type) use (&$result) {
        if (ends_with($prefix, '.[]')) {
            $result[substr($prefix, 0, -3)] = "array({$type})";
        } else {
            $result[$prefix] = $type;
        }
    };

    $flatten = function ($object, $prefix = '') use (&$flatten, &$result, $setCurrent) {
        $setCurrent($prefix, gettype($object));
        if (is_array($object)) {
            foreach ($object as $o) {
                $flatten($o, "{$prefix}.[]");
            }
        } else if (is_object($object)) {
            foreach (get_object_vars($object) as $key => $obj) {
                $flatten($obj, "{$prefix}.{$key}");
            }
        } else {
            $setCurrent($prefix, gettype($object));
        }
    };

    if (is_array($object)) {
        $flatten($object, '');
    } else if (is_object($object)) {
        foreach (get_object_vars($object) as $key => $obj) {
            $flatten($obj, $key);
        }
    }

    return $result;
}

/**
 * 判断用户是否有通知
 *
 * @return bool
 */
function userHasNotifications()
{
    if (Auth::guest()) {
        return false;
    }

    return userNotificationCount() > 0;
}

/**
 * 用户通知消息数
 *
 * @return int
 */
function userNotificationCount()
{
    if (Auth::guest()) {
        return 0;
    }

    return count(Auth::user()->unreadNotifications);
}

function subDocuments($pid)
{
    return \App\Repositories\Document::where('pid', $pid)->select('id', 'title')->get();
}