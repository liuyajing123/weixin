<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\News;
use App\Model\Login;
use App\Tools\Curl;
use Illuminate\Support\Facades\Redis;

class kaoshi extends Controller
{
//    注册视图
    public function register()
    {
        return view('kaoshi/register');
    }
//    注册执行
    public  function do_register(Request $request)
    {
        $name = $request->input('name');
        $password = $request->input('password');
        $data = Login::create([
           'name'=> $name,
            'password' =>$password
        ]);
        if($data){
            echo "<script>alert('注册账号成功');location.href='/kaoshi/login';</script>";
        }else{
            echo "<script>alert('注册账号失败');location.href='/kaoshi/register';</script>";
        }
    }
// 登录页面
    public function login()
    {
        return view('kaoshi/login');
    }
//    登录执行
    public function do_login(Request $request)
    {
        $name = $request->input('name');
        $password = $request->input('password');
        $data = Login::where(['name'=>$name,'password'=>$password])->first();
        if(!$data){
            echo "用户名或密码错误！";die;
        }
        $token = md5('Login_id'.time());
        $data->token=$token;
        $data->expire_time=time()+7200;
        $data->save();
        //返回给客户端
        echo "<script>alert('登录账号成功');location.href='/kaoshi/news_list';</script>";
    }

//    调用接口获取数据
    public function add()
    {
        set_time_limit(100);
//        获取热点新闻
        $url = "http://api.avatardata.cn/ActNews/LookUp?key=35b6f55658e44bedb23e29addcaa1e32";
        $hotData = Curl::get($url);
        $hotData = json_decode($hotData,true);
//        dd($hotData);
        $keywordArr = [];
        for ($i=0;$i<=9; $i++){
            $keywordArr[] = $hotData['result'][$i];
        }
        foreach ($keywordArr as $k =>$v){
            $url = "http://api.avatardata.cn/ActNews/Query?key=35b6f55658e44bedb23e29addcaa1e32&keyword=$v";
            $data = Curl::get($url);
//        dd($data);
            $data = json_decode($data,true);
//        dd($data);
            foreach ($data['result'] as $key => $value){
                News::create([
                    'title'=>$value['title'],
                    'content'=>$value['content'],
                    'img_width'=>$value['img_width'],
                    'src'=>$value['src'],
                    'img'=>$value['img'],
                ]);
            }
        }
    }
//    新闻列表
    public function news_list(Request $request)
    {
        $data = $request->all();
//        dd($data);
        $data = News::paginate(5);
        return view('kaoshi/news_list',['data'=>$data]);
    }
}
