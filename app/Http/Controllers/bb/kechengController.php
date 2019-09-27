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
        return view('kecheng/add_kecheng');
    }
//    课程执行
    public  function do_add_kecheng(Request $request)
    {
//        echo 111;
        $data = $request->all();
//        dd($data);
        $res = DB::table('kecheng')->insert([
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
        $wechat_info = DB::table('user_wechat')->where(['openid'=>$openid])->first();
//        dd($wechat_info);
        if(!empty($wechat_info)){
//            存在
            $request->session()->put('uid',$wechat_info->uid);
//            echo "ok";
            return redirect('admin/add_kecheng');
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
            $request->session()->put('uid',$wechat_info['uid']);
//            echo "ok";
            return redirect('admin/add_kecheng');
        }
    }
}
