<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'locale'], function() {
    // 如果启用 LDAP ，则不允许用户注册和重置密码
    $ldapDisabled = !ldap_enabled();
    Auth::routes([
        'reset' => $ldapDisabled,
        'verify' => $ldapDisabled,
        'register' => $ldapDisabled,
    ]);

    Route::group(['middleware' => 'global-auth'], function() {
        // 公共首页
        Route::get('/{catalog?}', 'HomeController@home')->name('home');
        // 项目公共页面
        Route::get('/project/{id}', 'ProjectController@project')->name('project:home');
        // 设置语言
        Route::get('/locale', 'HomeController@lang')->name('locale');

        // 分享页面
        Route::get('/s/{hash}', 'ShareController@page')->name('share:show');
        // 用户账号激活
        Route::get('/user/activate', 'UserController@activate')->name('user:activate');

        // 空白页，用于前端兼容
        Route::get('/blank', 'HomeController@blank')->name('blank');
        // 文档比较
        Route::post('/doc/compare', 'CompareController@compare')->name('doc:compare');
        // 阅读模式
        Route::get('/project/{id}/doc/{page_id}/read', 'DocumentController@readMode')->name('project:doc:read');

        // 小工具
        Route::group(['prefix' => 'tools', 'as' => 'tools:'], function () {
            Route::post('json-to-markdown', 'ToolController@convertJsonToTable')->name('json-to-markdown');
            Route::post('sql-to-markdown', 'ToolController@convertSQLToMarkdownTable')->name('sql-to-markdown');
            Route::post('sql-to-html', 'ToolController@convertSQLToHTMLTable')->name('sql-to-html');
        });

        // 文件导出
        Route::post('/export/{type}.pdf', 'ExportController@pdf')->name('export:pdf');
        Route::post('/export-file/{filename}', 'ExportController@download')->name('export:download');

        // 批量导出（项目）
        Route::post('/project/{project_id}/export', 'BatchExportController@batchExport')->name('export:batch');

        Route::group(['prefix' => 'project', 'middleware' => 'share', 'as' => 'project:'], function () {
            // 项目分享
            Route::get('/{id}/doc/{page_id}.json', 'DocumentController@getPageJSON')->name('doc:json');
            Route::get('/{id}/doc/{page_id}/histories/{history_id}.json',
                       'HistoryController@getPageJSON')->name('doc:history:json');
        });

        Route::group(['prefix' => 'swagger', 'as' => 'swagger:'], function () {
            // 获取swagger文档内容
            Route::get('/{id}/doc/{page_id}.yml', 'DocumentController@getSwagger')->name('doc:yml');
            Route::get('/{id}/doc/{page_id}.json', 'DocumentController@getJson')->name('doc:json');
        });

        // 用户扮演
        Route::group(['prefix' => 'impersonate', 'as' => 'impersonate:'], function() {
            Route::post('/{id}', 'ImpersonateController@impersonate')->name('start');
            Route::delete('/', 'ImpersonateController@stopImpersonate')->name('stop');
        });

        // 系统管理
        Route::group(['middleware' => ['auth', 'auth.admin'], 'prefix' => 'admin', 'as' => 'admin:'],
            function () {
                // 仪表盘
                Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

                // 用户组管理
                Route::get('/groups', 'GroupController@groups')->name('groups');
                Route::post('/groups', 'GroupController@add')->name('groups:add');
                Route::delete('/groups/{id}', 'GroupController@delete')->name('groups:del');
                Route::get('/groups/{id}', 'GroupController@info')->name('groups:view');
                Route::post('/groups/{id}/user', 'GroupController@addUser')->name('groups:users:add');
                Route::post('/groups/{id}', 'GroupController@update')->name('groups:update');
                Route::delete('/groups/{id}/users/{user_id}', 'GroupController@removeUser')
                     ->name('groups:users:del');
                Route::post('/groups/{id}/projects', 'GroupController@grantProjects')
                     ->name('groups:projects:add');

                // 用户管理
                Route::get('/users', 'UserController@users')->name('users');
                Route::get('/users/{id}', 'UserController@user')->name('user');
                Route::post('/users/{id}', 'UserController@updateUser')->name('user:update');
                Route::post('/users/{id}/groups', 'UserController@joinGroup')->name('user:join-group');

                // 项目目录管理
                Route::get('/catalogs', 'CatalogController@catalogs')->name('catalogs');
                Route::post('/catalogs', 'CatalogController@add')->name('catalogs:add');
                Route::get('/catalogs/{id}', 'CatalogController@info')->name('catalogs:view');
                Route::post('/catalogs/{id}', 'CatalogController@edit')->name('catalogs:edit');
                Route::delete('/catalogs/{id}', 'CatalogController@delete')->name('catalogs:delete');
                Route::delete('/catalogs/{id}/project/{project_id}', 'CatalogController@removeProject')
                     ->name('catalogs:project:del');
            });

        // 文档搜索
        Route::group(['prefix' => 'search', 'as' => 'search:'], function () {
            Route::get('/', 'SearchController@search')->name('search');
        });

        Route::group(['middleware' => 'auth'], function () {
            // 个人首页
            Route::get('/home', 'ProjectController@home')->name('user:home');
            // 文件上传
            Route::post('/upload', 'FileController@imageUpload')->name('upload');

            // 思维导图
            Route::group(['prefix' => 'mind-mapping', 'as' => 'mind-mapping:'], function () {
                Route::get('/editor', 'MindMappingController@editor')->name('editor');
                Route::post('/', 'MindMappingController@save')->name('save');
            });

            // 用户信息
            Route::group(['prefix' => 'user', 'as' => 'user:'], function () {
                // 重新发送账号激活邮件
                Route::post('/activate/email', 'UserController@sendActivateEmail')
                     ->name('activate:send');
                // 基本信息
                Route::get('/', 'UserController@basic')->name('basic');
                Route::post('/', 'UserController@basicHandle')->name('basic:handle');

                // 修改密码
                Route::get('/password', 'UserController@password')->name('password');
                Route::post('/password', 'UserController@passwordHandle')->name('password:handle');

                // 通知消息
                Route::get('/notifications', 'NotificationController@lists')->name('notifications');
                Route::put('/notifications/all', 'NotificationController@readAll')
                     ->name('notifications:read-all');
                Route::put('/notifications/{notification_id}', 'NotificationController@read')
                     ->name('notifications:read');

                // 个人模板管理
                Route::get('/templates', 'TemplateController@all')->name('templates');
                Route::delete('/templates/{id}', 'TemplateController@deleteTemplate')
                     ->name('templates:delete');
                Route::get('/templates/{id}', 'TemplateController@edit')->name('templates:edit');
                Route::put('/templates/{id}', 'TemplateController@editHandle')
                     ->name('templates:edit:handle');

                // 用户可写的项目列表
                Route::get('/writable-projects', 'UserController@projectsCanWrite');
            });

            Route::group(['prefix' => 'project', 'as' => 'project:'], function () {
                // 创建新项目
                Route::post('/', 'ProjectController@newProjectHandle')->name('new:handle');
                Route::delete('/{id}', 'ProjectController@delete')->name('delete');

                // 项目配置
                Route::get('/{id}/setting', 'ProjectController@setting')->name('setting:show');
                Route::post('/{id}/setting', 'ProjectController@settingHandle')->name('setting:handle');
                // 回收项目权限
                Route::delete('/{id}/privilege/{group_id}', 'ProjectController@groupPrivilegeRevoke')
                     ->name('privilege:revoke');

                // 创建新的文档
                Route::get('/{id}/doc', 'DocumentController@newPage')->name('doc:new:show');
                Route::post('/{id}/doc', 'DocumentController@newPageHandle')->name('doc:new:handle');
                Route::post('/{id}/doc-import', 'ImportController@documents')->name('doc:import');

                // 编辑文档
                Route::get('/{id}/doc/{page_id}', 'DocumentController@editPage')->name('doc:edit:show');
                Route::post('/{id}/doc/{page_id}', 'DocumentController@editPageHandle')
                     ->name('doc:edit:handle');
                Route::delete('/{id}/doc/{page_id}', 'DocumentController@deletePage')
                     ->name('doc:delete');

                // 文档同步
                Route::post('/{id}/doc/{page_id}/sync-from', 'DocumentController@syncFromRemote')
                     ->name('doc:sync-from');

                // 文档标记
                Route::put('/{id}/doc/{page_id}/mark-status', 'DocumentController@markStatus')->name('doc:mark-status');

                // 文档分享
                Route::post('/{id}/doc/{page_id}/share', 'ShareController@create')->name('doc:share');
                Route::delete('/{id}/doc/{page_id}/share', 'ShareController@delete')->name('doc:share:delete');

                // 文档评论
                Route::post('/{id}/doc/{page_id}/comments', 'CommentController@publish')
                     ->name('doc:comment');

                // 文档附件
                Route::get('/{id}/doc/{page_id}/attachments', 'AttachmentController@page')
                     ->name('doc:attachment');
                Route::delete('/{id}/doc/{page_id}/attachments/{attachment_id}',
                              'AttachmentController@delete')->name('doc:attachment:delete');
                Route::post('/{id}/doc/{page_id}/attachments', 'AttachmentController@upload')
                     ->name('doc:attachment:upload');

                // ajax获取文档是否过期
                Route::get('/{id}/doc/{page_id}/expired', 'DocumentController@checkPageExpired')->name('doc:expired');
                // 文档评价
                Route::post('/{id}/doc/{page_id}/score', 'DocumentController@updateDocumentScore')->name('doc:score');

                // 文档历史记录
                Route::get('/{id}/doc/{page_id}/histories', 'HistoryController@pages')
                     ->name('doc:history');
                Route::get('/{id}/doc/{page_id}/histories/{history_id}', 'HistoryController@page')
                     ->name('doc:history:show');
                Route::put('/{id}/doc/{page_id}/histories/{history_id}', 'HistoryController@recover')
                     ->name('doc:history:recover');

                // 关注项目
                Route::post('/{id}/favorite', 'ProjectController@favorite')->name('favorite');

                // 跨项目移动文档
                Route::post('/{project_id}/doc/{page_id}/move-to', 'DocumentController@move')->name('move');
                Route::get('/{project_id}/doc-selector', 'ProjectController@documentSelector')->name('doc-selector');
            });

            // 创建模板
            Route::post('/template', 'TemplateController@create')->name('template:create');

            // ajax获取操作历史
            Route::get('/operations/recently', 'OperationLogController@recently')
                 ->name('operation-log:recently');
            Route::group(['prefix' => 'tag', 'as' => 'tag:'], function () {
                // 创建标签
                Route::post('/', 'TagController@store')->name('tag:store');
            });
        });
    });
});
