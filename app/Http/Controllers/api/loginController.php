<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tools\Tools;
use App\Model\wechat;
use DB;
class loginController extends Controller
{

    public $tools;
    public function __construct(Tools $tools)
    {
        $this->tools=$tools;
    }

    //后台登录
    public function login()
    {
        return view('admin\login');
    }
    public function do_login()
    {
        $name=request('name');
        $password=request('password');
        //用户名错误 密码错误   用户名或密码错误
        $data=DB::table('user')->where(['name'=>$name,'password'=>$password])->first();
//        dd($data);
        if(!data){
            //报错登录失败
            die;
        }
        $data=$data->toArray();
        //登录成功 存到session
        session(['data'=>$data]);
        return redirect('admin/index');
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
        $data=$request->except(['_token']);
//         dd($data);
        $res=DB::table('users')->insert([
            'name'=>$data['name'],
            'password'=>$data['password'],
        ]);
        if($res){
            echo '账号绑定成功';
            redirect('index/bind');
        }else{
            echo "账号绑定失败";
        }
    }
}
