<?php

namespace App\Http\Controllers\aaa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Tools\Tools;
class zhoukao extends Controller
{
    public $tools;
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }
//    登录
    public function login()
    {
        $redirect_uri = "http://www.shopdemo.com/wechat/get_code";
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
            return redirect('/wechat/get_user_list');
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
            return redirect('/wechat/get_user_list');
        }
    }
//    留言视图
    public function liuyan(Request $request)
    {
        $req=$request->all();
//        dd($req);
        $openid = [];
        return view('zhoukao/liuyan',['openid'=>$request['openid']]);
    }
//    执行
    public function do_liuyan(Request $request)
    {
//        echo 1111;
        $req = $request->all();
//        dd($req);
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$this->tools->get_access_token();
//        dd($url);
        $data = [
            "touser"=>[
                'openid'=>explode(",",$req['openid']),
             ],
            "text"=>[
            "content"=>$req['liuyan'],
            ],
            "msgtype"=>"text"
        ];
//        dd($data);
        $res =$this->tools->curl_post($url,json_encode($data));
//        dd($res);
        $re = json_decode($res,1);
        dd($re);
    }
}
