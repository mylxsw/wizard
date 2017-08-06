# Wizard 

Wizard是基于Laravel开发框架开发的一款开源项目（API）文档管理工具。


## 安装

安装依赖

    composer install --prefer-dist

安装配置数据库

    cp .env.example .env
    
    php artisan migrate:install
    php artisan migrate
    
文件上传支持需要执行以下命令

    php artisan storage:link
    
执行该命令后会在public目录下创建`storage/app/public`目录的符号链接。