<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tools\Tools;
use App\Model\wechat;
use DB;
use Illuminate\Support\Facades\Cache;
class loginController extends Controller
{

    public $tools;
    public function __construct(Tools $tools)
    {
        $this->tools=$tools;
    }
    //后台首页
    public function index()
    {
        return view('admin/index');
    }
    //后台登录
    public function login()
    {
        return view('admin/login');
    }
    public function do_login(Request $request)
    {
        $username = $request ->input('name');
//        dd($username);
        $pwd = $request ->input('password');
//        dd($pwd);
        $code = $request ->input('code');
//        dd($code);
        $value = Cache::get('code'.$username);
//        dd($value);
        if(empty($code)){
            echo "<script>alert('验证码不为空');location.href='/index/login';</script>";die;
        }
        if($value != $code){
            echo "<script>alert('验证码不正确');location.href='/index/login';</script>";die;
        }
        if($code != $value){
            echo json_encode(['code'=>202, 'msg'=>'验证码错误或已过期']);
        }
        $status=DB::table('users')->where(['password'=>$pwd])->first();
        echo "<script>alert('登陆成功');location.href='/index/index';</script>";
    }
//发送验证码
    public function send(request $request)
    {
        $req=$request->all();
//        dd($req);
        //接收用户名 密码
        $name=$request->input('name');
//       dd($name);
        $password=$request->input('password');
//        dd($password);
        //发送验证码 4位 6位
        $code=rand(1000,9999);
        $rd = "code".$name;
        // 存入缓存 Cache::put('key', 'value', $seconds);
        $data = Cache::put($rd,$code,60);
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->tools->get_access_token();
        //参数
        $data=[
            'touser'=>'oPi8KuHGfvHjhL4u9BnIL4upIaJE',
            'template_id'=>'cT-DKrPjOjI7ieya635iHM38D-H5d7CYsypyU4q2SrE',
            'data'=>[
                'code'=>[
                    'value'=>$code,
                    'color'=>''
                ],
                'name'=>[
                    'value'=>$name,
                    'color'=>''
                ],
                'time'=>[
                    'value'=>time(),
                    'color'=>''
                ],
                'remark' => [
                    'value' => '',
                    'color' => ''
                ]
            ]
        ];
//        dd($data);
        $re=$this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        $result=json_decode($re,1);
        dd($result);
    }
//    账号绑定
    public function bind()
    {
        return view('admin/bind');
    }
    public function do_bind(Request $request)
    {
        $name = request('name');
        $password = request('password');
        $adminInfo = DB::table('users')->where(['name'=>$name,'password'=>$password])->first();
        if(!$adminInfo){
            echo json_encode(['ret'=>0,'msg'=>'用户名或密码错误']);die;
        }
        $openid = wechat::getOpenid();
        DB::table('users')->where(['name'=>$name,'password'=>$password])->update([
            'openid'=>$openid
        ]);
        $adminInfo->openid = $openid;
//        $adminInfo->save();
        echo "<script>alert('绑定账号成功');location.href='/index/login';</script>";
    }
}
