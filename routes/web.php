<?php

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

    // 空白页，用于前端兼容
    Route::get('/blank', function () {
        return '';
    })->name('blank');

    Route::group(['middleware' => 'auth'], function () {
        // 个人首页
        Route::get('/home', 'ProjectController@home')->name('user:home');
        // 文件上传
        Route::post('/upload', 'FileController@imageUpload')->name('upload');

        Route::group(['prefix' => 'project', 'as' => 'project:'], function () {
            // 创建新项目
            Route::post('/', 'ProjectController@newProjectHandle')->name('new:handle');

            // 项目配置
            Route::get('/{id}/setting', 'ProjectController@setting')->name('setting:show');
            Route::post('/{id}/setting', 'ProjectController@settingHandle')->name('setting:handle');

            // 创建新的文档
            Route::get('/{id}/doc', 'DocumentController@newPage')->name('doc:new:show');
            Route::post('/{id}/doc', 'DocumentController@newPageHandle')->name('doc:new:handle');

            // 编辑文档
            Route::get('/{id}/doc/{page_id}', 'DocumentController@editPage')->name('doc:edit:show');
            Route::post('/{id}/doc/{page_id}', 'DocumentController@editPageHandle')->name('doc:edit:handle');
            Route::delete('/{id}/doc/{page_id}', 'DocumentController@deletePage')->name('doc:delete');

            // ajax获取文档是否过期
            Route::get('/{id}/doc/{page_id}/expired', 'DocumentController@checkPageExpired')->name('doc:expired');
            Route::get('/{id}/doc/{page_id}.json', 'DocumentController@getPageJSON')->name('doc:json');
            Route::get('/{id}/doc/{page_id}/histories/{history_id}.json', 'HistoryController@getPageJSON')->name('doc:history:json');

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
