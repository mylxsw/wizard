<?php

use Illuminate\Database\Seeder;
use App\Repositories\Template;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultTemplate
            = <<<TTTT
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
TTTT;

        $this->createTemplate('默认模板', $defaultTemplate, '默认模板描述');

    }

    /**
     * 创建模板
     *
     * @param string $name
     * @param string $content
     * @param string $description
     * @param int    $type
     */
    private function createTemplate(
        string $name,
        string $content,
        string $description = '',
        $type = Template::TYPE_DOC
    ) {
        $template              = new Template();
        $template->name        = $name;
        $template->description = $description;
        $template->content     = $content;
        $template->type        = $type;
        $template->status      = Template::STATUS_NORMAL;
        $template->scope       = Template::SCOPE_GLOBAL;

        $template->save();
    }
}
