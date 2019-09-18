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
    Route::get('/get_user_lists', 'aaa\testController@get_user_lists');
//用户详情
    Route::get('/user_detail/{openid}', 'aaa\testController@user_detail');
//授权登录
    Route::get('/login','aaa\testController@login');
//获取code
    Route::get('/get_code','aaa\testController@get_code');
//文件上传
    Route::get('/upload','aaa\testController@upload');
    Route::post('/do_upload','aaa\testController@do_upload');
//curl post请求测试
    Route::any('post_test','aaa\testController@post_test');
//   素材管理列表
    Route::get('/source','aaa\testController@wechat_source');
//    >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//添加标签视图
    Route::get('/add_tag','aaa\TagController@add_tag');
//添加标签执行
    Route::post('/do_add','aaa\TagController@do_add');
//    标签列表
    Route::get('/tagList','aaa\TagController@tagList');
//    标签删除
    Route::get('/del_tag/{id}','aaa\TagController@del_tag');
//    标签修改视图
    Route::get('/update_tag/{id}','aaa\TagController@update_tag');
//    标签修改执行
    Route::post('/do_update','aaa\TagController@do_update');
//    标签下粉丝列表
    Route::get('/tag_openid_list','aaa\TagController@tag_openid_list');
//    为粉丝打标签
    Route::any('/tag_openid','aaa\TagController@tag_openid');
    //用户下的标签列表
    Route::get('/user_tag_list','aaa\TagController@user_tag_list');
    //推送标签消息
    Route::get('/push_tag_message','aaa\TagController@push_tag_message');
    //执行推送标签消息
    Route::post('/do_push_tag_message','aaa\TagController@do_push_tag_message');
//    模板消息推送
    Route::get('/push_template_message','aaa\testController@push_template_message');
//    >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//    列表
    Route::get('/agent_list','aaa\AgentController@agent_list');
    //创建二维码
    Route::get('/create_qrcode','aaa\AgentController@create_qrcode');
//     >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//    周考 留言
//    留言视图
    Route::get('/liuyan','aaa\zhoukao@liuyan');
//    执行
    Route::post('/do_liuyan','aaa\zhoukao@do_liuyan');
//    次数清零
    Route::get('/clear_api','aaa\testController@clear_api');
});

Route::prefix('/admin')->group(function() {
    Route::any('/jiekou_peizhi_url','aaa\eventController@jiekou_peizhi_url');
    Route::post('/create_menu','aaa\menuController@create_menu'); //创建菜单
    Route::get('/menu_list','aaa\menuController@menu_list');
    Route::get('/load_menu','aaa\menuController@load_menu');
    Route::get('/del_menu','aaa\menuController@del_menu');
});

