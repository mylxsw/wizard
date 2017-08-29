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

Route::group(['middleware' => 'locale'], function () {
    Auth::routes();

    // 公共首页
    Route::get('/', 'HomeController@home')->name('home');
    // 项目公共页面
    Route::get('/project/{id}', 'ProjectController@project')->name('project:home');
    // 设置语言
    Route::get('/locale', 'HomeController@lang')->name('locale');

    // 分享页面
    Route::get('/s/{hash}', 'ShareController@page')->name('share:show');

    // 空白页，用于前端兼容
    Route::get('/blank', function () {
        return '';
    })->name('blank');

    // 小工具
    Route::group(['middleware' => 'auth', 'prefix' => 'tools', 'as' => 'tools:'], function () {
        Route::post('json-to-markdown', 'ToolController@convertJsonToTable')->name('json-to-markdown');
    });

    // 项目分享
    Route::group(['prefix' => 'project', 'middleware' => 'share', 'as' => 'project:'], function () {
        Route::get('/{id}/doc/{page_id}.json', 'DocumentController@getPageJSON')->name('doc:json');
        Route::get('/{id}/doc/{page_id}/histories/{history_id}.json', 'HistoryController@getPageJSON')->name('doc:history:json');
    });

    // 系统管理
    Route::group(['middleware' => ['auth', 'auth.admin'], 'prefix' => 'admin', 'as' => 'admin:'], function () {
        // 用户组管理
        Route::get('/groups', 'GroupController@groups')->name('groups');
        Route::post('/groups', 'GroupController@add')->name('groups:add');
        Route::delete('/groups/{id}', 'GroupController@delete')->name('groups:del');
        Route::get('/groups/{id}', 'GroupController@info')->name('groups:view');
        Route::post('/groups/{id}', 'GroupController@addUser')->name('groups:users:add');
        Route::delete('/groups/{id}/users/{user_id}', 'GroupController@removeUser')->name('groups:users:del');

        // 用户管理
        Route::get('/users', 'UserController@users')->name('users');
    });

    Route::group(['middleware' => 'auth'], function () {
        // 个人首页
        Route::get('/home', 'ProjectController@home')->name('user:home');
        // 文件上传
        Route::post('/upload', 'FileController@imageUpload')->name('upload');

        // 用户信息
        Route::group(['prefix' => 'user', 'as' => 'user:'], function () {
            // 基本信息
            Route::get('/', 'UserController@basic')->name('basic');
            Route::post('/', 'UserController@basicHandle')->name('basic:handle');

            // 修改密码
            Route::get('/password', 'UserController@password')->name('password');
            Route::post('/password', 'UserController@passwordHandle')->name('password:handle');

            // 通知消息
            Route::get('/notifications', 'NotificationController@lists')->name('notifications');
            Route::put('/notifications/all', 'NotificationController@readAll')->name('notifications:read-all');
            Route::put('/notifications/{notification_id}', 'NotificationController@read')->name('notifications:read');
        });

        Route::group(['prefix' => 'project', 'as' => 'project:'], function () {
            // 创建新项目
            Route::post('/', 'ProjectController@newProjectHandle')->name('new:handle');
            Route::delete('/{id}', 'ProjectController@delete')->name('delete');

            // 项目配置
            Route::get('/{id}/setting', 'ProjectController@setting')->name('setting:show');
            Route::post('/{id}/setting', 'ProjectController@settingHandle')->name('setting:handle');
            // 回收项目权限
            Route::delete('/{id}/privilege/{group_id}', 'ProjectController@groupPrivilegeRevoke')->name('privilege:revoke');

            // 创建新的文档
            Route::get('/{id}/doc', 'DocumentController@newPage')->name('doc:new:show');
            Route::post('/{id}/doc', 'DocumentController@newPageHandle')->name('doc:new:handle');

            // 编辑文档
            Route::get('/{id}/doc/{page_id}', 'DocumentController@editPage')->name('doc:edit:show');
            Route::post('/{id}/doc/{page_id}', 'DocumentController@editPageHandle')->name('doc:edit:handle');
            Route::delete('/{id}/doc/{page_id}', 'DocumentController@deletePage')->name('doc:delete');

            // 文档分享
            Route::post('/{id}/doc/{page_id}/share', 'ShareController@create')->name('doc:share');

            // 文档评论
            Route::post('/{id}/doc/{page_id}/comments', 'CommentController@publish')->name('doc:comment');

            // 文档附件
            Route::get('/{id}/doc/{page_id}/attachments', 'AttachmentController@page')->name('doc:attachment');
            Route::delete('/{id}/doc/{page_id}/attachments/{attachment_id}', 'AttachmentController@delete')->name('doc:attachment:delete');
            Route::post('/{id}/doc/{page_id}/attachments', 'AttachmentController@upload')->name('doc:attachment:upload');

            // ajax获取文档是否过期
            Route::get('/{id}/doc/{page_id}/expired', 'DocumentController@checkPageExpired')->name('doc:expired');

            // 文档历史记录
            Route::get('/{id}/doc/{page_id}/histories', 'HistoryController@pages')->name('doc:history');
            Route::get('/{id}/doc/{page_id}/histories/{history_id}', 'HistoryController@page')->name('doc:history:show');
            Route::put('/{id}/doc/{page_id}/histories/{history_id}', 'HistoryController@recover')->name('doc:history:recover');
        });

        // 文档比较
        Route::post('/doc/compare', 'CompareController@compare')->name('doc:compare');

        // 创建模板
        Route::post('/template', 'TemplateController@create')->name('template:create');

    });
});
