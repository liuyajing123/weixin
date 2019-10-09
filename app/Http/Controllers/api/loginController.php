<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tools\Tools;
use App\Model\wechat;
use DB;
use Redis;
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
//        $name=request('name');
//        $password=request('password');
//        //用户名错误 密码错误   用户名或密码错误
//        $data=DB::table('user')->where(['name'=>$name,'password'=>$password])->first();
////        dd($data);
//        if(!data){
//            //报错登录失败
//            die;
//        }
//        $data=$data->toArray();
//        //登录成功 存到session
//        session(['data'=>$data]);
//        return redirect('index/index');
        $username = $request ->input('username');
        $pwd = $request ->input('password');
        $code = $request ->input('code');
        $value = Radis::get('code'.$username);
        if($code != $value){
            return json_encode(['code'=>202, 'msg'=>'验证码错误或已过期']);
        }
        // dd($yan);
        // var_dump($username);
        // var_dump($pwd);die;
        // dd($url);
        $status=DB::table('users')->where(['password'=>$pwd])->first();
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
        echo '账号绑定成功';die;
    }
}
