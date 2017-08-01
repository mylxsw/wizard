<?php

use Illuminate\Database\Seeder;

class PageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $content1 = <<<EEE
![](media/14588322296871/14789340428960.jpg)

[TOC]

## 如何在页面中输出所有的表单错误
    
    @if (count(\$errors) > 0)
        @foreach (\$errors->toArray() as \$err)
            {{ current(\$err) }}
        @endforeach
    @endif


## 使用Lumen操作MySQL出现时间比本地时间多了8小时

在Lumen中设置时区需要两个设置，一个是应用的设置，还有一个是数据库的设置.

    DB_TIMEZONE=+08:00
    APP_TIMEZONE=PRC

## 如何修改storage目录

线上环境肯定是不希望storage目录在项目目录下的，修改storage目录需要新建一个配置文件`path.php`，增加以下配置

    <?php
    return [
        'storage' => '/home/data/storage',
    ]; 

## 如何获取当前路由的名称（命名路由）

    \Route::getCurrentRoute()->getName()

EEE;


        \App\Repositories\Document::create([
            'pid'         => 0,
            'title'       => '跟我一起学Laravel-常见问题',
            'description' => '',
            'content'     => $content1,
            'project_id'  => 1,
            'user_id'     => 1,
            'type'        => 1,
            'status'      => 1,
        ]);

        $content2 = <<<EEE
## Lumen整合DingoAPI后Validator无法返回错误详情

使用`\$this->validate`方法对请求参数进行校验后无法显示错误详情，解决方案是注册对`Illuminate\Validation\ValidationException`异常的处理器

    app('Dingo\Api\Exception\Handler')->register(function (
        ValidationException \$exception
    ) {
        return [
            'message' => \$exception->getMessage(),
            'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'errors' => \$exception->validator->errors(),
        ];
    });

之后在使用`\$this->validate`就可以了

    \$this->validate(\$request, [
        'page'  => 'integer|min:1',
        'limit' => 'integer|between:1,100'
    ], [
        'page.integer'  => '页码参数 :attribute 必须为整数',
        'page.min'      => '页码参数 :attribute 必须为大于0的整数',
        'limit.integer' => '每页显示数量参数 :attribute 必须为整数',
        'limit.between' => '每页显示数量参数 :attribute 必须为 :min ~ :max 之间的整数'
    ]);

    
第二个参数可选，如果不提供则使用默认的提示信息。可用的校验规则参考文档 [Validation](https://laravel.com/docs/5.2/validation#available-validation-rules)。
EEE;


        \App\Repositories\Document::create([
            'pid'         => 0,
            'title'       => 'Lumen整合DingoAPI的问题',
            'description' => '',
            'content'     => $content2,
            'project_id'  => 1,
            'user_id'     => 1,
            'type'        => 1,
            'status'      => 1,
        ]);
    }
}
