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
 * @param int   $projectID 当前项目ID
 * @param int   $pageID    选中的文档ID
 * @param array $exclude   排除的文档ID列表
 *
 * @return array
 */
function navigator(
    int $projectID,
    int $pageID = 0,
    $exclude = []
) {
    $pages = \App\Repositories\Document::where('project_id', $projectID)->select(
        'id', 'pid', 'title', 'project_id', 'type', 'status', 'created_at'
    )->orderBy('pid')->get();

    $navigators = [];
    /** @var \App\Repositories\Document $page */
    foreach ($pages as $page) {
        if (in_array((int)$page->id, $exclude)) {
            continue;
        }

        $navigators[$page->id] = [
            'id'         => (int)$page->id,
            'name'       => $page->title,
            'pid'        => (int)$page->pid,
            'url'        => route('project:home', ['id' => $projectID, 'p' => $page->id]),
            'selected'   => $pageID === (int)$page->id,
            'type'       => $page->type == \App\Repositories\Document::TYPE_DOC ? 'markdown' : 'swagger',
            'created_at' => $page->created_at,
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
 * 导航排序，排序后，文件夹靠前，普通文件靠后
 *
 * @param array $navbars
 *
 * @return array
 */
function navigatorSort($navbars)
{
    usort($navbars, function ($a, $b) {
        if (!empty($a['nodes'])) {
            return -1;
        }

        if (!empty($b['nodes'])) {
            return 1;
        }

        try {
            return $a['created_at']->greaterThan($b['created_at']);
        } catch (Exception $e) {
            return 0;
        }
    });

    return $navbars;
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
 * @param int $limit 显示限制数量，如果提供了，则返回string类型的数量展示，最大值为$limit，超过数量显示为"$limit+"
 *
 * @return int|string
 */
function userNotificationCount($limit = 0)
{
    if (Auth::guest()) {
        return 0;
    }

    $unreadCount = count(Auth::user()->unreadNotifications);
    if ($limit > 0) {
        return $unreadCount > $limit ? "{$limit}+" : $unreadCount;
    }

    return $unreadCount;
}

/**
 * 子文档列表
 *
 * @param $pid
 *
 * @return \Illuminate\Database\Eloquent\Collection
 */
function subDocuments($pid)
{
    return \App\Repositories\Document::where('pid', $pid)->select('id', 'title')->get();
}

/**
 * 静态资源版本
 *
 * @return string
 */
function resourceVersion()
{
    static $version = null;
    if (is_null($version)) {
        $version = 'v=' . config('wizard.resource_version');
    }

    return $version;
}


/**
 * 创建一个JWT Token
 *
 * @param array $payloads
 * @param int   $expire
 *
 * @return \Lcobucci\JWT\Token
 */
function jwt_create_token(array $payloads, $expire = 3600 * 2)
{
    $builder = new \Lcobucci\JWT\Builder();
    foreach ($payloads as $key => $payload) {
        $builder->set($key, $payload);
    }

    return $builder->setIssuedAt(time())
        ->setExpiration(time() + $expire)
        ->sign(new \Lcobucci\JWT\Signer\Hmac\Sha256(), config('wizard.jwt_secret'))
        ->getToken();
}

/**
 * 解析Jwt Token
 *
 * @param string $token
 *
 * @return \Lcobucci\JWT\Token
 */
function jwt_parse_token(string $token)
{
    $token = (new \Lcobucci\JWT\Parser())->parse($token);

    if (!$token->verify(new \Lcobucci\JWT\Signer\Hmac\Sha256(), config('wizard.jwt_secret'))) {
        throw new \App\Exceptions\ValidationException('页面Token无效，请刷新后重试');
    }

    $validation = new \Lcobucci\JWT\ValidationData();
    if (!$token->validate($validation)) {
        throw new \App\Exceptions\ValidationException('页面已过期，请刷新页面后重新提交');
    }

    return $token;
}

/**
 * 生成用户头像
 *
 * @param string $id
 *
 * @return string
 */
function user_face($id)
{
    $identicon = new Identicon\Identicon();
    return $identicon->getImageDataUri($id);
}

/**
 * 获取所有用户列表
 *
 * @return \Illuminate\Database\Eloquent\Collection
 */
function users()
{
    static $users = null;
    if (is_null($users)) {
        $users = User::all();
    }

    return $users;
}

/**
 * 用户名列表（js数组）
 *
 * @param \Illuminate\Database\Eloquent\Collection $users
 * @param bool                                     $actived
 *
 * @return string
 */
function ui_usernames(\Illuminate\Database\Eloquent\Collection $users, $actived = true)
{
    return $users->filter(function (User $user) use ($actived) {
        return $actived ? $user->isActivated() : true;
    })->map(function (User $user) {
        return "'{$user->name}'";
    })->implode(',');
}

/**
 * 从内容中解析出用户
 *
 * @param string $content
 *
 * @return \Illuminate\Database\Eloquent\Collection|null
 */
function comment_filter_users($content)
{
    preg_match_all('/@{uid:(\d+)}/', $content, $matches);
    if (!empty($matches[1])) {
        $users = User::whereIn('id', $matches[1])->select('id', 'name', 'email')->get();
        return $users;
    }

    return null;
}


/**
 * 对评论信息预处理
 *
 * @param string $comment
 *
 * @return string
 */
function comment_filter(string $comment): string
{
    $matchRegexp = '/@(.*?)(?:\s|$)/';

    $users = (function ($content) use ($matchRegexp) {
        preg_match_all($matchRegexp, $content, $matches);
        if (!empty($matches[1])) {
            $users = User::whereIn('name', $matches[1])->select('id', 'name', 'email')->get();
            return $users;
        }

        return null;
    })($comment);
    if (is_null($users) || $users->isEmpty()) {
        return $comment;
    }

    return preg_replace_callback($matchRegexp, function ($matches) use ($users) {
        if (count($matches) < 2) {
            return $matches[0];
        }

        $user = $users->firstWhere('name', '=', $matches[1]);
        if (!empty($user)) {
            return "@{uid:{$user->id}} ";
        }

        return $matches[0];
    }, $comment);
}
