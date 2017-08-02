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

Auth::routes();

// 公共首页
Route::get('/', 'HomeController@home')->name('home');
// 项目公共页面
Route::get('/project/{id}', 'ProjectController@project')->name('project:home');

Route::group(['middleware' => 'auth'], function () {
    // 个人首页
    Route::get('/home', 'ProjectController@home')->name('user:home');

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

        // 文档历史记录
        Route::get('/{id}/doc/{page_id}/histories', 'HistoryController@pages')->name('doc:history');
        Route::get('/{id}/doc/{page_id}/histories/{history_id}', 'HistoryController@page')->name('doc:history:show');
        Route::put('/{id}/doc/{page_id}/histories/{history_id}', 'HistoryController@recover')->name('doc:history:recover');
    });

});

