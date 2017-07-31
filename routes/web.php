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

Route::get('/', 'HomeController@home')->name('home');
Route::get('/project/{id}', 'ProjectController@project')->name('project:home');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'ProjectController@home')->name('user:home');

    Route::group(['prefix' => 'project', 'as' => 'project:'], function () {
        Route::post('/', 'ProjectController@newProjectHandle')->name('new:handle');

        Route::get('/{id}/setting', 'ProjectController@setting')->name('setting:show');
        Route::post('/{id}/setting', 'ProjectController@settingHandle')->name('setting:handle');

        Route::get('/{id}/page', 'PageController@newPage')->name('page:new:show');
        Route::post('/{id}/page', 'PageController@newPageHandle')->name('page:new:handle');

        Route::get('/{id}/page/{page_id}', 'PageController@editPage')->name('page:edit:show');
        Route::post('/{id}/page/{page_id}', 'PageController@editPageHandle')->name('page:edit:handle');
    });

});

