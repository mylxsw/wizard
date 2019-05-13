# Wizard 开源文档管理系统

[TOC]

## 概述

Wizard是一款开源文档管理系统，目前支持三种类型的文档管理

- **Markdown**：也是Wizard最主要的文档类型，研发团队日常工作中交流所采用的最常用文档类型，在 Wizard 中，对 [Editor.md](https://pandao.github.io/editor.md/) 项目进行了功能扩展，增加了文档模板，Json 转表格，图片粘贴上传等功能
- **Swagger**：支持 [OpenAPI 3.0](https://swagger.io/specification/) 规范，嵌入了 Swagger 官方的编辑器，通过定制开发，使其融入到 Wizard 项目当中，支持文档模板，全屏编辑，文档自动同步功能
- **Table**：这种文档类型是类似于 Excel 电子表格，采用了 [x-spreadsheet](https://github.com/myliang/x-spreadsheet) 项目，将该项目嵌入到了 Wizard 中，目前还不是很完善

> 在Wizard中，正在编辑的文档会定时自动保存到本地的 Local Storage 中，避免错误关闭页面而造成编辑内容丢失。

目前主要包含以下功能

- Swagger，Markdown，Table 类型的文档管理
- 文档修改历史管理
- 文档修改差异对比
- 用户权限管理
- 项目分组管理
- LDAP 统一身份认证
- 文档搜索，标签搜索
- 阅读模式
- 文档评论
- 消息通知
- 文档分享
- 统计功能

如果想快速体验一下Wizard的功能，有两种方式

- 在线体验请访问 [http://wizard.aicode.cc/](http://wizard.aicode.cc/) ，目前只提供部分功能的体验，功能预览和使用说明请参考 [Wiki](https://github.com/mylxsw/wizard/wiki)。
- 使用Docker来创建一个完整的Wizard服务
    
    进入项目的根目录，执行 `docker-compose up`，就可以快速创建一个Wizard服务了，访问地址 http://localhost:8080 。

## 起源

为了鼓励大家在开发过程中写开发文档，最开始我们选择了 [ShowDoc](https://www.showdoc.cc/) 项目来作为文档管理工具，当时团队规模也非常的小，大家都是直接用 Markdown 写一些简单的开发文档。后来随着团队的壮大，前后端分离，团队分工的细化，仅仅采用 Markdown 开始变得捉襟见肘，这时候，我们首先想到了使用开源界比较流行的 [Swagger](https://swagger.io/) 来创建开发文档。但是 Swagger 文档多了，总得有个地方维护起来吧？

项目中的文档仅仅用Swagger也是不够的，它只适应于API文档的管理，还有很多其它文档，比如设计构想，流程图，架构文档，技术方案，数据库变更等各种文档需要一起维护起来。因此，我决定利用业余时间开发一款 **集成 Markdown 和 Swagger 文档的管理工具**，也就是 **Wizard** 项目了。

起初打算用 Go 语言来开发，但是没过几天发现使用 Golang 来做 Web 项目开发效率太低（快速开发效率，并非指性能），很多常用的功能都需要自己去实现，遂放弃使用 Golang，转而使用 PHP 的 Laravel 框架来开发。所以虽然项目创建的时间为 2017年7月27日，但是实际上真正开始的时间应该算是 2017年7月31日。

![-w986](https://ssl.aicode.cc/2019-05-04-15568614311881.jpg)

起初Wizard项目的想法比较简单，只是用来将 Markdown 文档和 Swagger 文档放在一起，提供一个简单的管理界面就足够了，但是随着在团队中展开使用后，发现在企业中作为一款文档管理工具来说，只提供简单的文档管理功能是不够的，比如说权限控制，文档修改历史，文档搜索，文档分类等功能需求不断的被提出来，因此也促成了 Wizard 项目的功能越来越完善。

- **用户权限管理** 参考了 Gitlab 的权限管理方式，在用户的身份上只区分了 **管理员** 和 **普通用户**，通过创建**用户组**来对用户的权限进行细致的管理，同时每个项目都支持单独的为用户赋予读写权限。
- **项目分组** 在 Wizard 中，文档是以项目为单位进行组织的，刚开始的时候发现这样是OK的，后来项目越来越多，项目分组功能应运而生，以目录的形式来组织项目结构。
- **文档修改历史** 每次对文档的修改，Wizard 都会记录一个快照，避免错误的修改了文档而造成损失，可以通过文档历史快速的恢复文档，对文档的修改，新增，删除等关键操作都会记录审计日志，以最近活动的形式展示出来。
- **文档差异对比** 在团队协助中，经常会出现很多人修改同一份文档，为了避免冲突，文档修改后，其它人在提交旧的历史版本时，系统会提示用户文档内容发生了变更，用户可以通过文档比对功能找出文档中有哪些内容发生了修改。
- **阅读模式** 当使用投影仪展示文档来过技术方案的时候，为了减少不必要的干扰，使用阅读模式，只展示文档内容部分，提供更好的展示体验。
- **文档搜索** 通过搜索功能快速查找需要的文档，目前支持通过文档标题来搜素文档，后续会增加全文检索功能。
- **LDAP支持** 很多公司都会使用 LDAP 来统一的管理公司员工的账号，员工的在公司内部的所有系统中都是用同一套帐号来登录各种系统比如 Jira，Wiki，Gitlab 等，Wizard 也提供了对 LDAP 的支持，只需要简单的几个配置，就可以快速的接入公司的统一帐号体系。
- **文档附件**，**文档分享**，**统计**，**文档排序**，**模板管理**，**文档评论** ...

## 关于代码

项目采用了 Laravel 开发框架开发，目前框架的版本已经升级到最新的 5.8（最开始为5.4，一路升级过来）。为了提高开发效率，保持架构的简洁，在开发过程中，一直避免引入过多的外部组件，尽可能的利用 Laravel 提供的各种组件，比如 **Authentication**，**Authorization**，**Events**，**Mail**，**Notifications** 等，非常适合Laravel新手利用该项目来学习Laravel开发框架。

## 安装

目前支持两种安装方式，如果你熟悉Docker，可以直接使用Docker容器的方式来运行该项目，这也是最简单的方式了。如果你没有使用Docker或者不知道什么是Docker，那么请直接参考手动安装部分。

### 通过 Docker 安装 

详细安装方法参考 Docker Hub [mylxsw/wizard](https://hub.docker.com/r/mylxsw/wizard)。

我们需要创建一个Dockerfile，在Dockerfile中添加环境配置，比如我采用了宿主机上安装的MySQL服务器，就有了下面的这段Dockerfile配置

    FROM mylxsw/wizard:latest

    # 数据库连接配置
    # 这里可以根据需要添加其它的Env配置，可用选项参考项目的.env.example文件
    ENV DB_CONNECTION=mysql
    ENV DB_HOST=host.docker.internal
    ENV DB_PORT=3306
    ENV DB_DATABASE=wizard_2
    ENV DB_USERNAME=wizard
    ENV DB_PASSWORD=wizard
    ENV WIZARD_NEED_ACTIVATE=false
    
    # 文件上传存储目录
    VOLUME /webroot/storage/app/public

    RUN php artisan config:cache

执行构建

    docker build -t my-wizard .

数据库初始化

    docker run -it --rm --name my-wizard my-wizard php artisan migrate:install
    docker run -it --rm --name my-wizard my-wizard php artisan migrate

运行

    docker run -d --name my-wizard -p 8080:80  my-wizard

然后就可以通过 http://localhost:8080 访问 Wizard 了。    

### 手动安装

手动安装方式需要先安装配置好PHP环境，建议采用 PHP-FPM/Nginx 的方式来运行，具体配置参考 环境依赖 部分。

#### 环境依赖

以下组件的安装配置这里就不做详细展开，可以自行 百度/Google 安装方法。

- PHP 7.2 + (需要启用以下扩展)
    - OpenSSL PHP Extension
    - PDO PHP Extension
    - Mbstring PHP Extension
    - Tokenizer PHP Extension
    - XML PHP Extension
    - Ctype PHP Extension
    - JSON PHP Extension
    - BCMath PHP Extension
    - LDAP PHP Extension
- composer.phar
- MySQL 5.7 + / MariaDB （需要支持ARCHIVE存储引擎，MariaDB 10.0+ 默认没有启用参考 **FAQ 3**）
- Nginx
- Git

#### 下载代码

推荐使用 git 来下载项目代码到服务器，我们假定将该项目放在服务器的 `/data/webroot` 目录

    cd /data/webroot
    git clone https://github.com/mylxsw/wizard.git
    cd wizard

下载代码之后，使用 **composer** 安装项目依赖

    composer install --prefer-dist --ignore-platform-reqs

composer 会在在项目目录中创建 **vender** 目录，其中包含了项目所依赖的所有第三方代码库。

> 你也可以直接到项目的 [release](https://github.com/mylxsw/wizard/releases) 页面直接下载包含依赖的软件包。

#### 配置

复制一份配置文件 

    cp .env.example .env

修改 `.env` 中的配置信息，比如 MySQL 连接信息，文件存储目录，项目网址等。

接下来创建数据库，提前在MySQL中创建好项目的数据库，然后在项目目录执行下面的命令

    php artisan migrate:install
    php artisan migrate

接下来配置文件上传目录

    php artisan storage:link

执行该命令后会在 public 目录下创建 `storage/app/public` 目录的符号链接。

在Nginx中配置项目的访问地址

    server {
        listen       80;
        server_name  wizard.example.com;
        root         /data/webroot/wizard/public;
        index        index.php;
    
        location / {
            index index.php index.html index.htm;
            try_files $uri $uri/ /index.php?$query_string;
        }
        
        location ~ .*\.(gif|jpg|png|bmp|swf|js|css)$ {
            try_files $uri  =302;
        }
        
        location ~ .*\.php$ {
            # php-fpm 监听地址，这里用了socket方式
            fastcgi_pass  unix:/usr/local/php/var/run/php-cgi.sock;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            fastcgi_index index.php;
            include fastcgi_params;
        }
    }

#### 升级

项目升级过程非常简单，只需要使用git拉取最新代码（git pull），然后执行下面的命令完成数据库迁移和依赖更新就OK了。

    composer install --prefer-dist --ignore-platform-reqs
    php artisan migrate

### 初始化

安装完成后，Wizard项目就可以通过浏览器访问了，接下来需要访问注册页面创建初始用户 

    http://项目地址/register

在系统中注册的第一个用户为默认管理员角色。

## FAQ

1. 如果在执行数据库迁移（`php artisan migrate`）的时候，报错 `SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long; max key length is 767 bytes`

    该错误是因为 MySQL 版本低于 5.7，在低版本的 MySQL 中会出现该问题。解决方案如下，二选一即可

    - 在 `.env` 文件中添加配置项 `DB_CHARSET=utf8` 和 `DB_COLLATION=utf8_unicode_ci`，添加之后再执行 `php artisan migrate` 命令（缺点是这样就不支持Emoji了）
    - 升级MySQL到 5.7

2. 报错 `SQLSTATE[HY000] [2054] The server requested authentication method unknown to the client` 和 `The server requested authentication method unknown to the client [caching_sha2_password]`

    因为Mariadb版本比较新，对应的MySQL版本在8.0之后也可能会有问题（默认认证方式修改为了`caching_sha2_password`），解决办法连接到数据库，修改一下密码的认证方式为 `mysql_native_password`：

        ALTER USER 'USERNAME'@'HOSTNAME' IDENTIFIED WITH mysql_native_password BY 'PASSWORD';

    > 参考 [Caching SHA-2 Pluggable Authentication](https://dev.mysql.com/doc/refman/8.0/en/caching-sha2-pluggable-authentication.html)

3. 数据库使用 Mariadb 10.0+ 版本时，执行数据库迁移报错 `Unknown storage engine 'ARCHIVE'` 

    操作日志存储用到了 **ARCHIVE** 存储引擎，Mariadb 10.0 版本之后默认是没有安装这个存储引擎的

    > The ARCHIVE storage engine was installed by default until MariaDB 10.0. In MariaDB 10.1 and later, the storage engine's plugin will have to be installed.

    所以解决方案有下面这两种（**推荐第一种**）

    1. 最简单的方式时在Mariadb中安装这个插件，只需要连接到Mariadb之后执行 `INSTALL SONAME 'ha_archive';` 命令就可以了，**不需要** 重启数据库

    2. 第二种办法时不安装 **ARCHIVE** 存储引擎，修改 `$WIZARD_HOME/database/migrations/2017_08_03_232417_create_operation_logs_table.php` 文件的第 17 行，将`$table->engine = 'ARCHIVE';` 注释掉（完成迁移之后记得改回去，避免以后使用 `git pull` 来升级系统产生冲突）
        
        ```diff
         Schema::create('wz_operation_logs', function (Blueprint $table) {
        -$table->engine = 'ARCHIVE';
        +// $table->engine = 'ARCHIVE';
         
         $table->increments('id');
        ```


## Stargazers over time

[![Stargazers over time](https://starchart.cc/mylxsw/wizard.svg)](https://starchart.cc/mylxsw/wizard)
    
## 开发计划

* [x] __开发框架升级Laravel 5.8__
* [ ] 新版本更新
    * [ ] 自动检测是否有新版本
    * [ ] 无损更新版本
* [x] markdown编辑器增加json转换为markdown表格的功能
* [ ] 增加空间管理，类似于命名空间，不同部门在自己的空间中管理项目，文档等
* [x] 项目功能
    * [x] 项目新增
    * [x] 项目配置
    * [x] 项目权限分配
    * [x] 项目删除
    * [x] 支持对项目进行分组，首页分组展示
    * [x] 关注项目优先展示
* [x] 文档历史管理，文档恢复
* [x] 操作日志记录
* [ ] 国际化支持
* [x] markdown编辑器增加图片上传支持
* [x] 文档差异比较，文档历史版本差异比较
* [ ] 基于git的文档差异分析
* [x] 文档多人编辑避免内容冲突覆盖
* [ ] 个人文档置顶（个人关注的文档，在首页增加展示区域，方便快速进入）
* [ ] 全局文档置顶（重要的常用文档，由管理员设置在首页特定区域展示，方便快速进入）
* [ ] 全文检索（文档，项目，评论，标签） 
* [x] 搜索功能优化，大部分用户都认为首页项目栏目顶部的搜索是用来搜索文档的，因此搜索不到
* [ ] 图片拖拽上传
* [x] 复制自动上传
* [ ] 文档管理
    * [x] 文档编辑
    * [x] 新增文档
    * [x] 支持Swagger格式文档
    * [x] 支持markdown文档
    * [x] 支持表格类型的文档
    * [x] 文档删除
    * [ ] **跨项目移动文档（移动时级联选择项目-目录）**
    * [ ] 文档点赞
* [x] 文档菜单支持折叠
* [x] 权限组，分组权限，管理员权限
    * [x] 项目按照分组分配读写权限
    * [x] 项目按照用户分配读写权限
* [ ] 文档模板管理
    * [x] 另存为模板
    * [x] 编辑器选择模板
    * [x] 模板列表
    * [x] 模板更新
    * [x] 模板删除
* [x] 文档排序，支持在文档配置中国年修改文档排序
* [ ] 文档排序优化：以拖动的方式进行排序
* [ ] 文档快速在项目内部变更目录
* [x] 项目排序
* [x] 文档标签
* [ ] 文档评论
    * [x] 实现最基本的评论功能
    * [x] 实现评论回复，带层级的评论
    * [x] 实现评论支持@某人
    * [ ] 评论点赞，类似Github issue评论后的emoji
    * [ ] 实现评论支持@用户组下所有用户
* [ ] 消息通知功能
    * [x] 支持@某人后收到消息
    * [x] 支持消息列表
    * [x] 新的消息提示
    * [x] 消息全部已读，部分已读
    * [ ] 新消息邮件提醒
    * [ ] 消息接收配置（站内信，邮件，接收类型）
* [x] 关注项目
    * [x] 关注项目，取消关注
    * [x] 已关注项目列表
    * [ ] 关注项目变更后接收消息通知
* [ ] **支持导出文件**
    * [x] **导出pdf**
    * [x] **导出markdown、swagger**
    * [ ] 导出word
    * [x] **文档批量导出**
* [x] 实现API接口管理，~~自动根据接口数据判断接口是否需要修改~~，手动触发文档同步
* [ ] 对接postman，实现自动生成接口文档，接口测试
* [ ] 实现页面中之间互相引用
* [x] 项目列表分页展示，增加按照项目标题搜索
* [x] 文档增加标题搜索
* [x] 文档保存后弹框提示选择：继续编辑还是创建新文档
* [ ] 文档分享
    * [x] 分享链接
    * [x] 分享后的文档页面，单页面模式
    * [ ] 分享链接管理
    * [ ] 分享链接有效期设置
    * [ ] 分享链接删除
* [x] 文档附件
    * [x] 附件上传
    * [x] 附件展示
    * [x] 附件删除
    * [x] 附件重传（历史附件）
    * [ ] 文档，评论内引用附件 
* [x] 用户管理
    * [ ] **用户姓名自动转拼音，用于引用该用户时快速提示和搜素**
    * [ ] 用户部门管理，用于不同部门使用不同空间
    * [x] 用户登录，注册，找回密码
    * [x] 基本信息修改
    * [x] 修改密码
    * [x] 管理员分配
    * [x] 用户分组管理
        * [x] 管理员管理分组
        * [x] 分组基础数据结构支持
    * [x] 用户管理页面，增加为用户分配用户组的功能，简化新员工入职时，权限分配的工作量
    * [x] **LDAP支持**
* [ ] 统计信息查看
    * [x] 用户数量统计
    * [x] 文档数量统计
    * [x] 评论数量统计
    * [ ] 用户活跃度统计
    * [ ] 文档更新统计

