<?php

namespace App\Http\Controllers\bb;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Tools\Tools;
class biaoqianController extends Controller
{
    public $tools;
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }
//    登录页面
    public function login()
    {
        return view('/biaoqian/login');
    }
//执行
    public function do_login()
    {
//        echo 111;
        $redirect_uri = "http://www.shopdemo.com/admin/get_code";
//        用户同意授权，获取code
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".env('WECHAT_APPID')."&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        header('Location:'.$url);
    }
//    获取code
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
            return redirect('biaoqian/tagList');
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
//    用户列表页
    public function user_list(Request $request)
    {
        $req = $request->all();
//        dd($req);
//        $openid_info = DB::table('wechat_openid')->get();
//        dd($openid_info);
        $access_token = $this->tools->get_access_token();
        $data = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token."&next_openid=");
        $data = json_decode($data,1);
//        dd($data);
        $last_info = [];
        foreach($data['data']['openid'] as $k=>$v){
            $user_info = file_get_contents('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->tools->get_access_token().'&openid='.$v.'&lang=zh_CN');
            $user = json_decode($user_info,1);
//            dd($user);
            $last_info[$k]['nickname'] = $user['nickname'];
            $last_info[$k]['openid'] = $v;
        }
//        dd($last_info);
//        dd($data);
        return view("biaoqian/user_list",['data'=> $last_info,'tagid'=>$req['tagid']]);
    }
//    视图
    public function addtag()
    {
//        echo 1111;
        return view('biaoqian/addtag');
    }
//    标签添加执行
    public function do_add(Request $request)
    {
//        echo 11;
        $data = $request->all();
//        dd($data);
        $data = [
            'tag'=>[
                'name' => $data['tagname']
            ]
        ];
//        dd($data);
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/create?access_token='.$this->tools->get_access_token();
        $re = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        $res = json_decode($re,1);
//        dd($res);
        return redirect('admin/tagList');
    }
    //    标签管理列表
    public function tagList()
    {
        $data = file_get_contents('https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$this->tools->get_access_token());
        $info = json_decode($data,1);
//        dd($info);
        return view('biaoqian/tagList',['info'=>$info['tags']]);
    }
//    打标签
    public function tag_openid(Request $request)
    {
        $req = $request->all();
//        dd($req);
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token='.$this->tools->get_access_token();
        $data = [
            'openid_list'=>$req['openid_list'],
            'tagid'=>$req['tagid']
        ];
        $re = $this->tools->curl_post($url,json_encode($data));
        $result = json_decode($re,1);
        dd($result);
    }

    public function tag(Request $request)
    {
//        echo 11;
        $req = $request->all();
//        dd($req);
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token='.$this->tools->get_access_token();
        $data = [
            'openid'=>$req['openid']
        ];
        $re = $this->tools->curl_post($url,json_encode($data));
        $result = json_decode($re,1);
        $tag = file_get_contents('https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$this->tools->get_access_token());
        $tag_result = json_decode($tag,1);
        $tag_arr = [];
        foreach($tag_result['tags'] as $v){
            $tag_arr[$v['id']] = $v['name'];
        }
        foreach($result['tagid_list'] as $v){
            echo $tag_arr[$v]."<br/>";
        }
    }
}
