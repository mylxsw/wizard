# Wizard 开源文档管理系统

![GitHub All Releases](https://img.shields.io/github/downloads/mylxsw/wizard/total)
![Docker Pulls](https://img.shields.io/docker/pulls/mylxsw/wizard)

## 概述

Wizard是一款开源文档管理系统，目前支持三种类型的文档管理

- **Markdown**：也是Wizard最主要的文档类型，研发团队日常工作中交流所采用的最常用文档类型，在 Wizard 中，对 [Editor.md](https://pandao.github.io/editor.md/) 项目进行了功能扩展，增加了文档模板，Json 转表格，图片粘贴上传等功能
- **Swagger**：支持 [OpenAPI 3.0](https://swagger.io/specification/) 规范，集成了 Swagger 官方的编辑器，支持文档模板，全屏编辑，文档自动同步功能
- **Table**：这种文档类型是类似于 Excel 电子表格，集成了 [x-spreadsheet](https://github.com/myliang/x-spreadsheet) 项目

> 在Wizard中，正在编辑的文档会定时自动保存到本地的 Local Storage 中，避免错误关闭页面而造成编辑内容丢失。

目前主要包含以下功能

- Swagger，Markdown，[Table](https://github.com/mylxsw/wizard/wiki/%E8%A1%A8%E6%A0%BC%E7%B1%BB%E5%9E%8B%E6%96%87%E6%A1%A3%E6%94%AF%E6%8C%81) 类型的文档管理
- [文档修改历史管理](https://github.com/mylxsw/wizard/wiki/%E6%96%87%E6%A1%A3%E5%B7%AE%E5%BC%82%E5%AF%B9%E6%AF%94%E4%BB%A5%E5%8F%8A%E5%8E%86%E5%8F%B2%E6%96%87%E6%A1%A3)
- [文档修改差异对比](https://github.com/mylxsw/wizard/wiki/%E6%96%87%E6%A1%A3%E5%B7%AE%E5%BC%82%E5%AF%B9%E6%AF%94%E4%BB%A5%E5%8F%8A%E5%8E%86%E5%8F%B2%E6%96%87%E6%A1%A3)
- 用户权限管理
- 项目分组管理
- LDAP 统一身份认证
- 文档搜索，标签搜索
- 阅读模式
- 文档评论
- 消息通知
- 文档分享
- 统计功能
- [流程图，序列图，饼图，Tex LaTex 科学公式支持](https://github.com/mylxsw/wizard/wiki/%E6%B5%81%E7%A8%8B%E5%9B%BE%EF%BC%8C%E5%BA%8F%E5%88%97%E5%9B%BE%EF%BC%8C%E9%A5%BC%E5%9B%BE%EF%BC%8CTex-LaTex-%E7%A7%91%E5%AD%A6%E5%85%AC%E5%BC%8F%E6%94%AF%E6%8C%81)
- [多主题切换](https://github.com/mylxsw/wizard/wiki/%E9%BB%91%E6%9A%97%E4%B8%BB%E9%A2%98%E5%88%87%E6%8D%A2)

如果想快速体验一下Wizard的功能，可以使用Docker来创建一个完整的Wizard服务
    
> 进入项目的根目录，执行 `docker-compose up`，就可以快速创建一个Wizard服务了，访问地址 http://localhost:8080 。

## 起源

为了鼓励大家在开发过程中写开发文档，最开始我们选择了 [ShowDoc](https://www.showdoc.cc/) 项目来作为文档管理工具，当时团队规模也非常的小，大家都是直接用 Markdown 写一些简单的开发文档。后来随着团队的壮大，前后端分离，团队分工的细化，仅仅采用 Markdown 开始变得捉襟见肘，这时候，我们首先想到了使用开源界比较流行的 [Swagger](https://swagger.io/) 来创建开发文档。但是 Swagger 文档多了，总得有个地方维护起来吧？

项目中的文档仅仅用Swagger也是不够的，它只适应于API文档的管理，还有很多其它文档，比如设计构想，流程图，架构文档，技术方案，数据库变更等各种文档需要一起维护起来。因此，我决定利用业余时间开发一款 **集成 Markdown 和 Swagger 文档的管理工具**，也就是 **Wizard** 项目了。

起初Wizard项目的想法比较简单，只是用来将 Markdown 文档和 Swagger 文档放在一起，提供一个简单的管理界面就足够了，但是随着在团队中展开使用后，发现在企业中作为一款文档管理工具来说，只提供简单的文档管理功能是不够的，比如说权限控制，文档修改历史，文档搜索，文档分类等功能需求不断的被提出来，因此也促成了 Wizard 项目的功能越来越完善。

- **用户权限管理** 参考了 Gitlab 的权限管理方式，在用户的身份上只区分了 **管理员** 和 **普通用户**，通过创建**用户组**来对用户的权限进行细致的管理，同时每个项目都支持单独的为用户赋予读写权限。
- **项目分组** 在 Wizard 中，文档是以项目为单位进行组织的，刚开始的时候发现这样是OK的，后来项目越来越多，项目分组功能应运而生，以目录的形式来组织项目结构。
- **文档修改历史** 每次对文档的修改，Wizard 都会记录一个快照，避免错误的修改了文档而造成损失，可以通过文档历史快速的恢复文档，对文档的修改，新增，删除等关键操作都会记录审计日志，以最近活动的形式展示出来。
- **文档差异对比** 在团队协助中，经常会出现很多人修改同一份文档，为了避免冲突，文档修改后，其它人在提交旧的历史版本时，系统会提示用户文档内容发生了变更，用户可以通过文档比对功能找出文档中有哪些内容发生了修改。
- **阅读模式** 当使用投影仪展示文档来过技术方案的时候，为了减少不必要的干扰，使用阅读模式，只展示文档内容部分，提供更好的展示体验。
- **文档搜索** 通过搜索功能快速查找需要的文档，目前支持通过文档标题来搜素文档，后续会增加全文检索功能。
- **LDAP支持** 很多公司都会使用 LDAP 来统一的管理公司员工的账号，员工的在公司内部的所有系统中都是用同一套帐号来登录各种系统比如 Jira，Wiki，Gitlab 等，Wizard 也提供了对 LDAP 的支持，只需要简单的几个配置，就可以快速的接入公司的统一帐号体系。
- **文档附件**，**文档分享**，**统计**，**文档排序**，**模板管理**，**文档评论** ...


## 功能演示

请查看项目的 [Wiki](https://github.com/mylxsw/wizard/wiki) 文档。

![Wizard-功能预览图](https://ssl.aicode.cc/mweb/Wizard-%E5%8A%9F%E8%83%BD%E9%A2%84%E8%A7%88%E5%9B%BE.gif)

## 关于代码

项目采用了 Laravel 开发框架开发，目前框架的版本已经升级到最新的 5.8（最开始为5.4，一路升级过来）。为了提高开发效率，保持架构的简洁，在开发过程中，一直避免引入过多的外部组件，尽可能的利用 Laravel 提供的各种组件，比如 **Authentication**，**Authorization**，**Events**，**Mail**，**Notifications** 等，非常适合Laravel新手利用该项目来学习Laravel开发框架。

## 安装

目前支持两种安装方式，如果你熟悉Docker，可以直接使用Docker容器的方式来运行该项目，这也是最简单的方式了。如果你没有使用Docker或者不知道什么是Docker，那么请直接参考手动安装部分。

### 通过 Docker 安装 

详细安装方法参考 Docker Hub [mylxsw/wizard](https://hub.docker.com/r/mylxsw/wizard)。

#### 方法一

首先对于新安装用户，需要执行数据库的初始化

    docker run -it --rm --name wizard \
        -e DB_HOST=host.docker.internal \
        -e DB_PORT=3306  \
        -e DB_DATABASE=wizard  \
        -e DB_USERNAME=wizard  \
        -e DB_PASSWORD=wizard  \
        mylxsw/wizard 初始化命令
        
        
这里的 **初始化命令** 包含两个，依次执行即可
  
  - php artisan migrate:install
  - php artisan migrate

最后，直接运行下面的 Docker 命令即可

    docker run -d --name wizard \
        -e DB_HOST=host.docker.internal \
        -e DB_PORT=3306  \
        -e DB_DATABASE=wizard  \
        -e DB_USERNAME=wizard  \
        -e DB_PASSWORD=wizard  \
        -p 8080:80 \
        -v /Users/mylxsw/Downloads:/webroot/storage/app/public   \
        mylxsw/wizard
        
简要说明：

- `-e` 指定配置，用环境变量的形式覆盖 `.env` 中的配置
- `-d` 后台模式运行
- `-p` 指定映射容器内的80端口为宿主机的 8080 端口，这样就可以在宿主机上以 http://localhost:8080 的形式访问了
- `-v` 映射数据目录位置，将本地目录映射到文件上传存储目录，避免重启服务时图片等数据丢失

> 在使用 Docker 模式启动后，如果启动后访问页面报错 500，可以在启动命令中添加 `-e APP_DEBUG=true` 来启用 DEBUG 模式，在访问的时候就可以看到详细的报错信息了。具体参考 [这里](https://github.com/mylxsw/wizard/wiki/Docker-%E6%A8%A1%E5%BC%8F%E5%90%AF%E5%8A%A8%E6%8A%A5%E9%94%99-500-%E9%97%AE%E9%A2%98%E6%8E%92%E6%9F%A5)

#### 方法二

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
    # 访问地址，只有正确配置后，导出的 markdown 文档图片才能正常展示
    ENV APP_URL=http://localhost:8080
    
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
    - Zlib PHP Extension （PDF 导出功能需要用到）
- composer.phar
- MySQL 5.7 + / MariaDB （需要支持ARCHIVE存储引擎，MariaDB 10.0+ 默认没有启用参考 **FAQ 3**）
- Nginx
- Git

> PHP 运行环境的创建，可以参考这里 https://gist.github.com/mylxsw/4b7bbe81fb7f59714423f3284c867149

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
    
如果是用 Docker 部署的话，在重新拉取最新镜像之后，执行下面的命令就可以了

    docker run -it --rm my-wizard php artisan migrate


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

4. 默认上传文件大小限制为 2M，这个限制并不是 Wizard 自身的限制，而是运行环境的限制，如何提高上传文件大小限制呢？

   首先需要修改 PHP 的配置文件 `php.ini`，修改以下两行
   
       ; 上传文件大小限制
       upload_max_filesize = 100M
       ; 表单提交大小限制，必须大于 upload_max_filesize，或者可以设置为 0，不做任何限制
       post_max_size = 0
   
   然后，根据 web 服务器的不同进行修改
   
   - **nginx**： 在 nginx 配置中添加 `client_max_body_size 120M;` 来指定最大 body 大小，可以参考 `docker-compose/nginx.conf` 的配置
   - **apache**：修改 Wizard 目录 `public/.htaccess` 文件中 `LimitRequestBody 0` 选项的值即可，默认为0表示不限制（默认已经修改过）

5. 导出 Markdown 文档后，图片地址错误，无法显示图片

   需要配置 `APP_URL` 环境变量参数，在 `.env` 文件中，修改 `APP_URL` 地址为当前访问 URL 地址即可。

6. 服务启动后，访问页面报错 500，没有具体错误信息，无法顺利排查问题

   最简单的办法是可以通过查看错误日志来排查问题，日志文件在 `storage/logs/` 目录。如果不够直观，可以在 `.env` 配置文件中修改 `APP_DEBUG=true` 来启用调试模式，在访问页面就会展示具体报错信息了。在 Docker 环境中，可以在启动命令中添加 `-e APP_DEBUG=true` 来启用 DEBUG 模式。


## Stargazers over time

[![Stargazers over time](https://starchart.cc/mylxsw/wizard.svg)](https://starchart.cc/mylxsw/wizard)
