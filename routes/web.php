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
Route::prefix('/wechat')->group(function() {
//获取access_token
    Route::get('/get_access_token', 'aaa\testController@get_access_token');
//用户列表
    Route::get('/get_user_list', 'aaa\testController@get_user_list');
//用户详情
    Route::get('/user_detail/{openid}', 'aaa\testController@user_detail');
//授权登录
    Route::get('/login','aaa\testController@login');
//获取code
    Route::get('/get_code','aaa\testController@get_code');
//文件上传
    Route::get('/upload','aaa\testController@upload');
    Route::post('/do_upload','aaa\testController@do_upload');
});
