<?php

namespace App\Http\Controllers\bb;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class kechengController extends Controller
{
//    课程添加
    public function add_kecheng()
    {
        $user_name=session('kecheng_user_name');
        $kecheng_info = DB::table('kecheng')->where(['username'=>$user_name])->orderBy('id','desc')->first();
        $kecheng_info=json_decode(json_encode($kecheng_info),1);
//       dd($kecheng_info['kecheng_1']);
//        $nickname_info=$this->wechat->get_user_info('oMbARt6tCM2dJZL6MjdKPmOxrpMY');
////                    dd($nickname_info);
//        $nickname_1=$nickname_info['nickname'];
//        dd($nickname_1);
        if(empty($kecheng_info)){
            //为空跳转去添加页面
//            dump("为空");die();
            return view('kecheng/add_kecheng_1');
        }else{
            //不为空去展示页面 带去数据
//            dump("不为空");die();
            return view('kecheng/add_kecheng',['data'=>$kecheng_info]);
        }
    }
//    课程执行
    public  function do_add_kecheng(Request $request)
    {
//        echo 111;
        $data = $request->all();
//        dd($data);
        $user_name=session('kecheng_user_name');
        $res = DB::table('kecheng')->insert([
            'username'=>$user_name,
            'kecheng1'=>$data['kecheng1'],
            'kecheng2'=>$data['kecheng2'],
            'kecheng3'=>$data['kecheng3'],
            'kecheng4'=>$data['kecheng4'],
        ]);
        if($res){
            echo "添加成功";
        }else{
            echo "添加失败";
        }
    }
//    课程修改
    //微信第三方登录
    public function login()
    {
        $redirect_uri = "http://www.liuyajing.top/admin/get_code";
//        用户同意授权，获取code
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".env('WECHAT_APPID')."&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        header('Location:'.$url);
    }
//获取code
    public function get_code(Request $request)
    {
//        接收所有数据信息
        $data = $request->all();
//        dd($data);
//        通过code换取网页授权access_token
        $code = $data['code'];
        $url = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".env('WECHAT_APPID')."&secret=".env('WECHAT_APPSECRET')."&code={$code}&grant_type=authorization_code");
        $url = json_decode($url, 1);
//        dd($url);

//        拉取用户信息(需scope为 snsapi_userinfo)
        $info = file_get_contents("https://api.weixin.qq.com/sns/userinfo?access_token=".$url['access_token']."&openid=".$url['openid']."&lang=zh_CN");
        $info = json_decode($info, 1);
//        dd($info);
        $openid = $url['openid'];
//        根据openid判断用户表中是否有此用户
        $wechat_info = DB::table('user_wechat')->where(['openid'=>$openid])->first();
//        dd($wechat_info);打印后  id uid openid
        if(!empty($wechat_info)){
//            存在
            $user_wechat_info=DB::table("user")->where(['id'=>$wechat_info->uid])->first();
//            dd($user_wechat_info);
            session(['kecheng_user_name' =>$user_wechat_info->name]);
            return view('admin/success')->with([
                //跳转信息
                'message'=>'登陆成功 正在跳转至课程管理列表！',
                //自己的跳转路径
                'url' =>asset('/admin/add_kecheng'),
                //跳转路径名称
                'urlname' =>'课程管理',
                //跳转等待时间（s）
                'jumpTime'=>3,
            ]);
        }else{
//            不存在
//            插入user表数据一条
            DB::beginTransaction();//打开事务
            $uid = DB::table('user')->insertGetId([
                'name'=>$info['nickname'],
                'password'=>'',
                'reg_time'=>time()
            ]);
            $insert_result = DB::table('user_wechat')->insert([
                'uid'=>$uid,
                'openid'=>$openid
            ]);
//            登录操作
            $user_wechat_info=DB::table("user")->where(['id'=>$wechat_info->uid])->first();
//            dd($user_wechat_info);
            session(['kecheng_user_name' =>$user_wechat_info->name]);
            return view('admin/success')->with([
                //跳转信息
                'message'=>'登陆成功 正在跳转至课程管理列表！',
                //自己的跳转路径
                'url' =>asset('/admin/add_kecheng'),
                //跳转路径名称
                'urlname' =>'课程管理',
                //跳转等待时间（s）
                'jumpTime'=>3,
            ]);
        }
    }
}
