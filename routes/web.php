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
//八月微信 （aaa文件夹demo bb文件夹月考卷）
//用户  授权登录  素材  标签  模板消息推送
Route::prefix('/wechat')->group(function() {
    Route::get('/text','aaa\testController@text');
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
    Route::post('/tag_openid','aaa\TagController@tag_openid');
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
//自定义菜单  月份周考题
Route::prefix('/admin')->group(function() {
    Route::any('/jiekou_peizhi_url','aaa\eventController@jiekou_peizhi_url');
    Route::post('/create_menu','aaa\menuController@create_menu'); //创建菜单
    Route::get('/menu_list','aaa\menuController@menu_list');//菜单列表
    Route::get('/load_menu','aaa\menuController@load_menu');//
    Route::get('/del_menu','aaa\menuController@del_menu');//删除菜单
//    2019四月份技能题B卷
//   登录
    Route::get('/login','bb\biaoqianController@login');
//    登录执行
    Route::get('/do_login','bb\biaoqianController@do_login');
//    获取code
    Route::get('/get_code','bb\biaoqianController@get_code');
//    用户列表
    Route::get('user_list','bb\biaoqianController@user_list');
    //添加标签视图
    Route::get('/addtag','bb\biaoqianController@addtag');
//添加标签执行
    Route::post('/do_add','bb\biaoqianController@do_add');
//    标签列表
    Route::get('/tagList','bb\biaoqianController@tagList');
//用户标签
    Route::get('/tag','bb\biaoqianController@tag');
    //    打标签
    Route::post('/tag_openid','bb\biaoqianController@tag_openid');
//2019-8A卷课程
    //授权登录
    Route::get('/login','bb\kechengController@login');
    //获取code
    Route::get('/get_code','bb\kechengController@get_code');
//    添加课程
    Route::get('/add_kecheng','bb\kechengController@add_kecheng');
    Route::post('/do_add_kecheng','bb\kechengController@do_add_kecheng');
});
//--------------------------------------------------------------------------------------------------------------------------------------------------//
//九月API接口*
// 首页 登录 绑定账号
Route::prefix('/index')->group(function (){
    Route::get('/login','api\loginController@login');//登录
    Route::post('/do_login','api\loginController@do_login');//登录执行
    Route::get('/index','api\loginController@index');//后台首页
    Route::get('/send','api\loginController@send');//发送验证码
    Route::get('/bind','api\loginController@bind');//绑定账号
    Route::post('/do_bind','api\loginController@do_bind');//绑定账号执行
});
//接口添加视图
Route::get('user/add',function(){
    return view('curd/add');
});
//接口展示视图
Route::get('/user/user_list',function(){
    return view('curd/list');
});
//修改查询视图
Route::get('/user/find',function(){
    return view('curd/find');
});
//接口增删改查
Route::prefix('api')->group(function() {
    //用户接口列表
    Route::any('/list','api\testController@list');
    //用户接口添加
    Route::any('/add','api\testController@add');
    //用户接口删除
    Route::any('/del','api\testController@del');;
    //修改查询用户信息
    Route::any('/find','api\testController@find');
    //执行修改
    Route::any('/save','api\testController@save');
});
//接口增删改查restful
Route::resource('/api/user','api\userController');

//周考1 商品添加  展示  天气
Route::resource('/api/category','api\GoodsController');
Route::get('Api/goods_add', function () {
    return view('Api.goods_add');
});
Route::get('Api/goods_show', function () {
    return view('Api.goods_show');
});
Route::any('/weather','api\GoodsController@weather');
Route::get('Api/weather', function () {
    return view('Api.weather');
});

//后台管理
Route::prefix('/admin')->group(function(){
    Route::any('/category/add','api\categoryController@add');//分类视图
    Route::any('/category/do_add','api\categoryController@do_add');//执行
    Route::any('/category/list','api\categoryController@list');//列表
    Route::any('/category/type_add','api\categoryController@type_add');//类型视图
    Route::any('/category/do_type_add','api\categoryController@do_type_add');//执行
    Route::any('/category/type_list','api\categoryController@type_list');//列表
    Route::any('/category/attr_add','api\categoryController@attr_add');//属性视图
    Route::any('/category/del','api\categoryController@del');//属性视图
    Route::any('/category/do_attr_add','api\categoryController@do_attr_add');//执行
    Route::any('/category/attr_list','api\categoryController@attr_list');//列表
    Route::any('/category/goods_add','api\categoryController@goods_add');//商品视图
    Route::any('/category/do_goods_add','api\categoryController@do_goods_add');//执行
    Route::any('/category/add_do','api\categoryController@add_do');//执行
    Route::any('/category/goods_list','api\categoryController@goods_list');//列表
    Route::any('/category/product_add/{goods_id}','api\categoryController@product_add');//货品视图
    Route::any('/category/do_product_add','api\categoryController@do_product_add');//货品执行
});
//前台管理
Route::prefix('/api/index')->middleware('apihearder')->group(function (){ //apiheader 中间件 跨域header头 防刷
    Route::any('/index','api\indexController@index');
    Route::any('/detail','api\indexController@detail');
    Route::any('/goods_cate_show','api\indexController@goods_cate_show');
    Route::any('/goods_cate','api\indexController@goods_cate');
    Route::get('/login','api\usertokenController@login');
    Route::get('/getUser','api\usertokenController@getUser');

    Route::middleware('apitoken')->group(function(){ //apitoken 校验token
        Route::get('/cart_add','api\usertokenController@cart_add');
        Route::any('/cart_list','api\usertokenController@cart_list');
    });
});
//   八月份技能题 AB卷
Route::prefix('/news')->middleware('login')->group(function(){  //中间件防刷  每天5次
   Route::any('add','api\xinwenController@add');//新闻添加 接口获取数据 循环加入四十条数据
   Route::any('register','api\xinwenController@register');//普通注册
   Route::any('register_do','api\xinwenController@register_do');
   Route::any('login','api\xinwenController@login');//普通登录 加token
   Route::any('login_do','api\xinwenController@login_do');
   Route::any('news_list','api\xinwenController@news_list');//新闻列表
});

//考试
Route::prefix('/kaoshi')->middleware('kaoshi')->group(function (){
   Route::any('register','api\kaoshi@register');
   Route::any('do_register','api\kaoshi@do_register');
   Route::any('login','api\kaoshi@login');
   Route::any('do_login','api\kaoshi@do_login');
   Route::any('add','api\kaoshi@add');
   Route::any('news_list','api\kaoshi@news_list');
});
//腾讯视频小程序
Route::prefix('/mini')->group(function(){
    Route::any('/nav/lists','mini\navController@lists');
    Route::any('/nav/cha','mini\indexController@cha');
});

Route::prefix('/admin')->group(function() {
    Route::any('/get_access_token','adminController@access_token');
    Route::get('/admin','adminController@admin');//后台首页
    Route::get('/login','adminController@login');//后台登录
    Route::post('/do_login','adminController@do_login');//登录执行
    Route::get('/upload_video','adminController@upload_video');//视频上传
    Route::any('/do_upload','adminController@do_upload');//上传执行
    Route::get('/cate_add','adminController@cate_add');//分类添加
    Route::post('/do_cate_add','adminController@do_cate_add');//分类添加执行
    Route::get('/cate_list','adminController@cate_list');//分类列表
    Route::get('/delete_cate/{id}','adminController@delete_cate');//分类删除
    Route::get('/update_cate/{id}','adminController@update_cate');//分类修改
    Route::post('/update','adminController@update');//分类修改
//    Route::get('/upload_thumb','adminController@upload_video');//轮播图上传
//    Route::post('/do_upload_thumb','adminController@do_upload_thumb');//轮播图上传执行
    Route::get('/add_menu','adminController@add_menu');//添加菜单
    Route::post('/create_menu','adminController@create_menu');//添加菜单执行
    Route::get('/list_menu','adminController@list_menu');//菜单列表
    Route::get('/load_menu','adminController@load_menu');//刷新
});
