<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

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
 * @return array
 */
function wzTemplates(): array
{
    $template
        = <<<TTT

[TOC]

## 接口描述

- 请求方式：**PUT**
- 端点地址：**/user/**

### 请求参数

| 参数名 | 类型 | 是否必选 | 说明 |
| ------- | ------ | ------- | ------ |
| username | string | 是 | 用户名 |
| email | string | 是 | 邮箱地址 |

### 返回值

| 参数名 | 类型 | 说明 |
| ------- | ------ | ------- | 
| id | integer | 用户ID |
| email | string | 邮箱地址 |

返回值示例

``` 
{
  "id": 5,
  "email": "xxx@xx.com"
}
```

### 错误代码

| 代码 | 说明 |
| ---- | ------ |
| 404 | 资源不存在 |
| 422 | 请求参数不合法 |

### 范例

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/21728c33bdd4b4b703d0)

TTT;

    $template2
        = <<<XXXXX
#### Using FlowChart

setting:

    {
        flowChart : true
    }

#### Example

```flow
st=>start: User login
op=>operation: Operation
cond=>condition: Successful Yes or No?
e=>end: Into admin

st->op->cond
cond(yes)->e
cond(no)->op
```
XXXXX;


    return [
        [
            'id'      => 1,
            'name'    => '默认模板',
            'content' => $template,
            'default' => true
        ],
        [
            'id'      => 2,
            'name'    => '我的模板',
            'content' => $template2,
            'default' => false,
        ]
    ];
}