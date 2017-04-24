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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/teacher/index', [
    'as' => 'teacherIndex',
    'uses' => 'TeacherController@index'
]);

Route::any('/wechat/serve', [
    'as' => 'wechatServe',
    'uses' => 'WechatController@serve'
]);

Route::any('/wechat/oauth_callback', [
    'as' => 'wechatCallback',
    'uses' => 'WechatController@oAuthCallback'
]);
