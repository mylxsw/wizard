<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

use App\Repositories\Document;
use App\Repositories\Template;
use App\Repositories\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * 生成路由url
 *
 * @param string $name
 * @param array $parameters
 * @param bool $absolute
 *
 * @return string
 */
function wzRoute($name, $parameters = [], $absolute = false)
{
    return route($name, $parameters, $absolute);
}

/**
 * 文档类型标识转换
 *
 * @param $type
 *
 * @return string
 */
function documentType($type): string
{
    $types = [
        Document::TYPE_DOC => 'markdown',
        Document::TYPE_SWAGGER => 'swagger',
        Document::TYPE_TABLE => 'table',
    ];

    return $types[$type] ?? '';
}

/**
 * 将页面集合转换为层级结构的菜单
 *
 * 必须保证pages是按照pid进行asc排序的，否则可能会出现菜单丢失
 *
 * @param int $projectID 当前项目ID
 * @param int $pageID 选中的文档ID
 * @param array $exclude 排除的文档ID列表
 *
 * @return array
 */
function navigator(
    int $projectID,
    int $pageID = 0,
    $exclude = []
) {
    static $cached = [];

    $key = "{$projectID}:{$pageID}:" . implode(':', $exclude);
    if (isset($cached[$key])) {
        return $cached[$key];
    }

    $pages = Document::where('project_id', $projectID)->select(
        'id', 'pid', 'title', 'project_id', 'type', 'status', 'created_at', 'sort_level'
    )->orderBy('pid')->get();

    $navigators = [];
    /** @var Document $page */
    foreach ($pages as $page) {
        if (in_array((int)$page->id, $exclude)) {
            continue;
        }

        $navigators[$page->id] = [
            'id' => (int)$page->id,
            'name' => $page->title,
            'pid' => (int)$page->pid,
            'url' => route('project:home', ['id' => $projectID, 'p' => $page->id]),
            'selected' => $pageID === (int)$page->id,
            'type' => documentType($page->type),
            'created_at' => $page->created_at,
            'sort_level' => $page->sort_level ?? 1000,
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

    $res = array_filter($navigators, function ($nav) {
        return $nav['pid'] === 0;
    });

    $cached[$key] = $res;

    return $res;
}

/**
 * 导航排序，排序后，文件夹靠前，普通文件靠后
 *
 * @param array $navItems
 *
 * @return array
 */
function navigatorSort($navItems)
{
    $sortItem = function ($a, $b) {
        try {
            if ($a['sort_level'] > $b['sort_level']) {
                return 1;
            } else {
                if ($a['sort_level'] < $b['sort_level']) {
                    return -1;
                } else {
                    return $a['created_at']->greaterThan($b['created_at']);
                }
            }
        } catch (Exception $e) {
            return 0;
        }
    };

    usort($navItems, function ($a, $b) use ($sortItem) {

        $aIsFolder = !empty($a['nodes']);
        $bIsFolder = !empty($b['nodes']);

        $bothIsFolder = $aIsFolder && $bIsFolder;
        $bothNotFolder = !$aIsFolder && !$bIsFolder;

        if ($bothIsFolder || $bothNotFolder) {
            return $sortItem($a, $b);
        } else {
            if ($aIsFolder) {
                return -1;
            }

            return 1;
        }
    });

    return $navItems;
}

/**
 * 文档模板
 *
 * @param int $type
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
        } else {
            if (is_object($object)) {
                foreach (get_object_vars($object) as $key => $obj) {
                    $flatten($obj, "{$prefix}.{$key}");
                }
            } else {
                $setCurrent($prefix, gettype($object));
            }
        }
    };

    if (is_array($object)) {
        $flatten($object, '');
    } else {
        if (is_object($object)) {
            foreach (get_object_vars($object) as $key => $obj) {
                $flatten($obj, $key);
            }
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
    return Document::where('pid', $pid)->select('id', 'title')->get();
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
 * @param int $expire
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
 * @param bool $actived
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


/**
 * 是否启用了LDAP支持
 *
 * @return bool
 */
function ldap_enabled(): bool
{
    static $enabled = null;
    if (is_null($enabled)) {
        $enabled = (bool)config('wizard.ldap.enabled');
    }

    return $enabled;
}

/**
 * 站长统计代码区域
 *
 * @return string
 */
function statistics(): string
{
    $customFile = base_path('custom');
    if (file_exists("{$customFile}/statistics.html")) {
        return file_get_contents("{$customFile}/statistics.html");
    }

    return '';
}

/**
 * 判断内容是否为json格式
 *
 * @param string $content
 *
 * @return bool
 */
function isJson($content): bool
{
    // 尝试解析为json
    json_decode($content);
    return json_last_error() === JSON_ERROR_NONE;
}

/**
 * 转换 SQL 为 Markdown 表格
 *
 * @param string $sql
 *
 * @return string
 */
function convertSqlToMarkdownTable(string $sql)
{
    return convertSqlTo($sql, function ($markdowns, $tableName, $tableComment) {
        if (empty($markdowns)) {
            return '';
        }

        $headers = [
            ['字段', '类型', '空', '说明'],
            ['---', '---', '---', '---',],
        ];

        array_unshift($markdowns, ...$headers);

        $html = '';
        foreach ($markdowns as $line) {
            $html .= '| ' . implode(' | ', $line) . ' | ' . "\n";
        }

        return "\n表名：**{$tableName}**   \n备注：*{$tableComment}*\n\n{$html}\n";
    });
}

/**
 * 转换 SQL 为 HTML 表格
 *
 * @param string $sql
 *
 * @return string
 */
function convertSqlToHTMLTable(string $sql)
{
    return convertSqlTo($sql, function ($markdowns, $tableName, $tableComment) {
        if (empty($markdowns)) {
            return '';
        }

        $html = '';
        foreach ($markdowns as $line) {
            $html .= '<tr><td>' . implode('</td><td>', $line) . "</td></tr>";
        }

        return <<<HEADER
<p class="wz-table-name">❖ 表名： <b>{$tableName}</b></p>
<p class="wz-table-desc">❖ 备注：<i>{$tableComment}</i></p>
<table class="table table-hover">
    <thead>
        <tr>
           <th>字段</th> 
           <th>类型</th> 
           <th>空</th> 
           <th>说明</th> 
        </tr>
    </thead>
    <tbody>{$html}</tbody>
</thead>
</table>
HEADER;

    });
}

/**
 * SQL 格式转换
 *
 * @param string $sql
 * @param        $callback
 *
 * @return string
 */
function convertSqlTo(string $sql, $callback)
{
    try {
        $parser = new PHPSQLParser\PHPSQLParser();
        $parsed = $parser->parse($sql);

        $fields = $parsed['TABLE']['create-def']['sub_tree'];
        $tableName = $parsed['TABLE']['base_expr'];

        $markdowns = [];

        foreach ($fields as $field) {
            if ($field['sub_tree'][0]['expr_type'] == 'constraint') {
                continue;
            }

            // 如果当前行不是列定义，则没有 sub_tree，比如 PRIMARY KEY(id)
            if (!isset($field['sub_tree'][1]['sub_tree'])) {
                continue;
            }

            $type = $length = '';
            foreach ($field['sub_tree'][1]['sub_tree'] as $item) {
                if ($item['expr_type'] == 'data-type') {
                    $type = $item['base_expr'] ?? '';
                    $length = $item['length'] ?? '';
                }
            }

            $name = $field['sub_tree'][0]['base_expr'];
            $comment = trim($field['sub_tree'][1]['comment'] ?? '', "'");
            $nullable = $field['sub_tree'][1]['nullable'] ?? false;

//        $autoInc      = $field['sub_tree'][1]['auto_inc'] ?? false;
//        $primary      = $field['sub_tree'][1]['primary'] ?? false;
//        $defaultValue = $field['sub_tree'][1]['default'] ?? '-';

            $type = empty($length) ? $type : "{$type} ($length)";
            $markdowns[] = [trim($name, '`'), $type, $nullable ? 'Y' : 'N', $comment];
        }


        $tableComment = '-';
        $options = $parsed['TABLE']['options'] ?? [];
        if (!$options || empty($options)) {
            $options = [];
        }

        foreach ($options as $option) {
            $type = strtoupper($option['sub_tree'][0]['base_expr'] ?? '');
            if ($type === 'COMMENT') {
                $tableComment = trim($option['sub_tree'][1]['base_expr'] ?? '', "'");
                break;
            }
        }

        return $callback($markdowns, trim($tableName, '`'), $tableComment);
    } catch (Exception $ex) {
        return "{$ex->getMessage()} @{$ex->getFile()}:{$ex->getLine()}";
    }
}

/**
 * 预处理 X-spreadsheet 表格内容
 *
 * @param string $content
 *
 * @return string
 */
function processSpreedSheet(string $content): string
{
    $contentArray = json_decode($content, true);

    $maxRowNum = collect(array_keys($contentArray['rows']))
        ->filter(function ($item) {
            return is_numeric($item);
        })->map(function ($item) {
            return (int)$item;
        })->max();

    $maxColNum = collect($contentArray['rows'])
        ->filter(function ($item, $key) {
            return is_numeric($key);
        })
        ->map(function ($item) {
            return collect(array_keys($item['cells'] ?? []))
                ->filter(function ($item) {
                    return is_numeric($item);
                })
                ->map(function ($item) {
                    return (int)$item;
                })
                ->max();
        })->max();

    $contentArray['cols']['len'] = $maxColNum + 1;
    $contentArray['rows']['len'] = $maxRowNum + 1;

    $content = json_encode($contentArray, JSON_UNESCAPED_UNICODE);
    return $content;
}

/**
 * 是否使用较为严格的 markdown 解释器
 *
 * 2019-12-16T21:54:00+08:00 之后创建的所有文档人采用该模式
 *
 * @param Document $pageItem
 *
 * @return bool
 */
function markdownCompatibilityStrict($pageItem = null)
{
    if (empty($pageItem) || empty($pageItem->created_at)) {
        return true;
    }

    return $pageItem->created_at->greaterThan(Carbon::createFromFormat(
        Carbon::RFC3339,
        '2019-12-16T21:54:00+08:00'
    ));
}

/**
 * 遍历导航项
 *
 * @param array $navigators
 * @param Closure $callback
 * @param array $parents
 */
function traverseNavigators(array $navigators, \Closure $callback, array $parents = [])
{
    foreach ($navigators as $nav) {
        $callback($nav['id'], $parents);

        if (!empty($nav['nodes'])) {
            array_push($parents, ['id' => $nav['id'], 'name' => $nav['name']]);
            traverseNavigators($nav['nodes'], $callback, $parents);
            array_pop($parents);
        }
    }
}