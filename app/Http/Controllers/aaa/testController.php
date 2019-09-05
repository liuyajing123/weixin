<?php

namespace App\Http\Controllers\aaa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class testController extends Controller
{
    //获取access_token
    public function get_access_token()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        $access_token_key = 'wechat_access_token';
        if($redis->exists($access_token_key)){
            //去缓存拿
            $access_token = $redis->get($access_token_key);
        }else{
            //去微信接口拿
            $data = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".env('WECHAT_APPID')."&secret=".env('WECHAT_APPSECRET'));
            $data = json_decode($data,1);
            $access_token = $data['access_token'];
            $expire_in = $data['expires_in'];
            //加入缓存
            $redis->set($access_token_key,$access_token,$expire_in);
        }
        return $access_token;
    }

//用户列表
    public function get_user_list()
    {
        $access_token = $this->get_access_token();
        $data = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token."&next_openid=");
        $data = json_decode($data,1);
//        dd($data);
        $last_info = [];
        foreach($data['data']['openid'] as $k=>$v){
            $user_info = file_get_contents('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->get_access_token().'&openid='.$v.'&lang=zh_CN');
            $user = json_decode($user_info,1);
//            dd($user);
            $last_info[$k]['nickname'] = $user['nickname'];
            $last_info[$k]['openid'] = $v;
        }
//        dd($last_info);
//        dd($data);
        return view("wechat/userlist",['data'=> $last_info]);
    }
//用户详情
    public function user_detail(request $request)
    {
        $access_token = $this->get_access_token();
        $open_id=$request->openid;
//        dd($open_id);
       $data = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$open_id."&lang=zh_CN");
       $data = json_decode($data,1);
//       dd($data);

        return view("wechat/user_detail",['data'=>$data]);
    }
//微信第三方登录
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

    public function upload()
    {
        return view('wechat/upload');
    }
    public function do_upload(Request $request)
    {
//        $data = $request->file();
//        dd($data);
        $name = 'image';
        if (!empty($request->hasFile($name)) && request()->file($name)->isValid()) {
            $path = request()->file($name)->store('goods');
            dd('/storage/'.$path);
        }
    }
    public function post_test()
    {
        dd($_POST);
    }
}
