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

该接口实现了xxx功能，这里填写详细描述。

- 请求方式：**PUT**
- 端点地址：**/user/**

## 请求参数

| 参数名 | 类型 | 是否必选 | 说明 |
| ------- | ------ | ------- | ------ |
| username | string | 是 | 用户名 |
| email | string | 是 | 邮箱地址 |

## 返回值

| 参数名 | 类型 | 说明 |
| ------- | ------ | ------- | 
| id | integer | 用户ID |
| email | string | 邮箱地址 |

### 返回值示例

``` 
{
  "id": 5,
  "email": "xxx@xx.com"
}
```

## 错误代码

| 代码 | 说明 |
| ---- | ------ |
| 404 | 资源不存在 |
| 422 | 请求参数不合法 |

## 范例

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/21728c33bdd4b4b703d0)
TTTT;

        $this->createTemplate('默认模板', $defaultTemplate, '默认模板描述');

        $swaggerDefault = <<<SWWW
swagger: "2.0"
info:
  description: 短链接服务实现了将很长的URL链接地址转换为较短的地址，方便用户输入，同时解决在短信中包含url地址时，由于地址过长导致短信分多条发送的问题。
  title: 短链接服务 - API文档
  version: v1
schemes:
- http
host: s.yunsom.cn
basePath: /
paths:
  /:
    get:
      summary: 获取服务统计信息
      description: 获取服务统计信息
      responses:
        200:
          description: 服务器统计信息
          schema:
            properties:
              data:
                properties:
                  url_count:
                    description: 当前短链接数量
                    type: integer
                type: object
              message:
                type: string
              status_code:
                type: string
        422:
          \$ref: '#/responses/Standard422ErrorResponse'
        500:
          \$ref: '#/responses/Standard500ErrorResponse'
    post:
      consumes:
      - application/x-www-form-urlencoded
      description: 创建短链接
      summary: 创建短链接
      parameters:
      - description: URL地址
        in: formData
        name: url
        required: true
        type: string
      - description: 过期时间，单位为秒，默认有效期为15天
        in: formData
        name: expire
        type: integer
      responses:
        200:
          description: 正常响应
          schema:
            properties:
              data:
                properties:
                  expire:
                    description: 过期时间
                    type: integer
                  link:
                    description: 短链接地址
                    type: string
                type: object
              message:
                type: string
              status_code:
                type: string
        422:
          \$ref: '#/responses/Standard422ErrorResponse'
        500:
          \$ref: '#/responses/Standard500ErrorResponse'
  /{hash}:
    get:
      summary: 短链接访问
      responses:
        "302":
          description: 页面跳转，跳转至原始链接地址
        "422":
          \$ref: '#/responses/Standard422ErrorResponse'
        "500":
          \$ref: '#/responses/Standard500ErrorResponse'
    parameters:
    - description: 短链接hash值
      in: path
      name: hash
      required: true
      type: string
  /probe/routes:
    get:
      summary: 探针接口，用于获取服务所有路由
      responses:
        200:
          description: 正常响应
        422:
          \$ref: '#/responses/Standard422ErrorResponse'
        500:
          \$ref: '#/responses/Standard500ErrorResponse'
          
responses:
  Standard422ErrorResponse:
    description: 请求参数不合法，字段缺失或者格式错误
    schema:
      \$ref: '#/definitions/Error'
  Standard500ErrorResponse:
    description: 服务端错误
    schema:
      \$ref: '#/definitions/Error'
      
definitions:
  Error:
    properties:
      errors:
        type: array
        items:
          type: string
      message:
        type: string
      status_code:
        type: string
SWWW;

        $this->createTemplate('默认模板', $swaggerDefault, '默认模板描述', Template::TYPE_SWAGGER);
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
