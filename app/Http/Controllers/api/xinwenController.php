<?php
namespace App\Http\Controllers\api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tools\Curl;
use App\Model\News;
use App\Model\Login;
class xinwenController extends Controller
{

//    添加
    public function add()
    {
        set_time_limit(100);
//        搜索关键字从新闻热电里
        $url  = "http://api.avatardata.cn/ActNews/LookUp?key=35b6f55658e44bedb23e29addcaa1e32";
        $hotData = Curl::get($url);
        $hotData = json_decode($hotData,true);
        $keywordArr = [];
//        循环取10个热点
        for ($i = 0; $i<=9;$i++){
            $keywordArr[] = $hotData['result'][$i];
        }
        foreach($keywordArr as $k => $v){
            $url = "http://api.avatardata.cn/ActNews/Query?key=35b6f55658e44bedb23e29addcaa1e32&keyword=".$v;
            $data = Curl::get($url);
            $data = json_decode($data,true);
//        dd($data);
            if(!empty($data['result'])){
                foreach ($data['result'] as $key => $value){
                    $newsData = News::where(['title'=>$value['title']])->first();
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
    }
//注册视图
    public function register()
    {
        return view('News/register');
    }
//    注册执行
    public function register_do(request $request)
    {
        $re=$request->all();
        $data=Login::create([
            'name'=>$re['name'],
            'password'=>$re['password']
        ]);
//        dd($data);
        if($data){
            echo "<script>alert('注册账号成功');location.href='/news/login';</script>";
        }else{
            echo "<script>alert('注册账号失败');location.href='/news/register';</script>";
        }
//        return redirect('zk_login');
    }
//    登录视图
    public function login(){
        return view('News/login');
    }
//    登录执行
    public function login_do(request $request)
    {
        //用户名和密码
        $name=$request->input('name');
        $password=$request->input('password');
        //查询数据库
        $userData =Login::where(['name'=>$name,'password'=>$password])->first();
        if(!$userData){
            echo '用户名密码错误';die;
        }
        // 生成token令牌
        $token=md5('news_id'.time());//生成一个不重复的token令牌
        // dd($token);
        //修改数据库
        $userData->token=$token;
        $userData->expire_time=time()+7200;
        $userData->save();
        //返回给客户端
        echo "<script>alert('登录账号成功');location.href='/news/news_list';</script>";
    }
//    新闻列表
    public function news_list(request $request)
    {
        $data=$request->all();
        $res=News::paginate(5);
        return view('News/news_list',['res'=>$res]);
    }
}
