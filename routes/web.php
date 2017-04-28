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


Route::group(['middleware' => ['web', 'wechat.oauth', 'user.service']], function () {
    Route::get('/teacher/index', [
        'as' => 'teacherIndex',
        'uses' => 'TeacherController@index'
    ]);
    Route::get('/course/list', [
        'as' => 'courseList',
        'uses' => 'CourseController@courseList'
    ]);

    Route::get('/course/{id}', [
        'as' => 'courseItem',
        'uses' => 'CourseController@item'
    ])->where('id', '[0-9]+');

    Route::any('/wechat/oauth_callback', [
        'as' => 'wechatCallback',
        'uses' => 'WechatController@oAuthCallback'
    ]);

    Route::any('/wxPay/getPayConfig', [
        'as' => 'wxPayConfig',
        'uses' => 'WxPayController@getPayConfig'
    ]);

    Route::any('/wxPay/getPaySign', [
        'as' => 'wxPaySign',
        'uses' => 'WxPayController@getPaySign'
    ]);

    Route::any('/user/homepage', [
        'as' => 'userHome',
        'uses' => 'UserController@homepage'
    ]);

    Route::any('/user/orders', [
        'as' => 'userOrderList',
        'uses' => 'UserController@orderList'
    ]);

    Route::get('/user/order/{order_no}', [
        'as' => 'userOrderDetail',
        'uses' => 'UserController@orderDetail'
    ])->where('order_no', '[0-9]+');

    Route::any('/order/updateOrder', [
        'as' => 'updateOrder',
        'uses' => 'OrderController@updateOrder'
    ]);

    Route::any('/wxPay/paySuccess', [
        'as' => 'paySuccess',
        'uses' => 'WxPayController@showPaySuccess'
    ]);

    Route::any('/wxPay/payFail', [
        'as' => 'payFail',
        'uses' => 'WxPayController@showPayFail'
    ]);

    Route::any('/wxPay/payCancel', [
        'as' => 'payCancel',
        'uses' => 'WxPayController@showPayCancel'
    ]);

    Route::any('/teacher/apply', [
        'as' => 'teacherApply',
        'uses' => 'TeacherController@apply'
    ]);

    Route::any('/teacher/submitApply', [
        'as' => 'submitApply',
        'uses' => 'TeacherController@submitApply'
    ]);
});

Route::any('/course/payCallback', [
    'as' => 'coursePayCallback',
    'uses' => 'CourseController@payCallback'
]);

Route::any('/wechat/serve', [
    'as' => 'wechatServe',
    'uses' => 'WechatController@serve'
]);

