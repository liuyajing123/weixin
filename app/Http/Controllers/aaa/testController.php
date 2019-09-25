<?php

namespace App\Http\Controllers\aaa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use DB;
use App\Tools\Tools;
class testController extends Controller
{
    public $tools;
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

//用户列表
//liuyan
    public function get_user_list()
    {
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
            $openid=DB::table('wechat_openid')->where('open_id',$v)->value('open_id');
            if(empty($openid))
            {
                $user_info = file_get_contents('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->tools->get_access_token().'&openid='.$v.'&lang=zh_CN');
                $user = json_decode($user_info,1);
                DB::table('wechat_openid')->insert([
                    'open_id' => $v,
                    'add_time' => time(),
                ]);
            }
        }
//        dd($last_info);
//        dd($data);
        return view("wechat/userList",['data'=> $last_info,'openid'=>['data']]);
    }
    //    粉丝
    public function get_user_lists(Request $request)
    {
        $req = $request->all();
//        dd($req);
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
            $openid=DB::table('wechat_openid')->where('open_id',$v)->value('open_id');
            if(empty($openid))
            {
                $user_info = file_get_contents('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->tools->get_access_token().'&openid='.$v.'&lang=zh_CN');
                $user = json_decode($user_info,1);
                DB::table('wechat_openid')->insert([
                    'open_id' => $v,
                    'add_time' => time(),
                ]);
            }
        }
        return view("wechat/userLists",['data'=>$last_info,'tagid'=>$req['tagid']]);
    }
//用户详情
    public function user_detail(request $request)
    {
        $access_token = $this->tools->get_access_token();
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
//下载资源
    public function download_source(Request $request)
    {
        $req = $request->all();
        $source_info = DB::table('wechat_source')->where(['id'=>$req['id']])->first();
        $source_arr = [1=>'image',2=>'voice',3=>'video',4=>'thumb'];
        $source_type = $source_arr[$source_info->type]; //image,voice,video,thumb
        //素材列表
        //$media_id = 'dcgUiQ4LgcdYRovlZqP88RB3GUc9kszTy771IOSadSM'; //音频
        //$media_id = 'dcgUiQ4LgcdYRovlZqP88dUuf1H6G4Z84rdYXuCmj6s'; //视频
        $media_id = $source_info->media_id;
        $url = 'https://api.weixin.qq.com/cgi-bin/material/get_material?access_token='.$this->tools->get_access_token();
        $re = $this->tools->curl_post($url,json_encode(['media_id'=>$media_id]));
        if($source_type != 'video'){
            Storage::put('wechat/'.$source_type.'/'.$source_info->file_name, $re);
            DB::table('wechat_source')->where(['id'=>$req['id']])->update([
                'path'=>'/storage/wechat/'.$source_type.'/'.$source_info->file_name,
            ]);
            dd('ok');
        }
        $result = json_decode($re,1);
        //设置超时参数
        $opts=array(
            "http"=>array(
                "method"=>"GET",
                "timeout"=>3  //单位秒
            ),
        );
        //创建数据流上下文
        $context = stream_context_create($opts);
        //$url请求的地址，例如：
        $read = file_get_contents($result['down_url'],false, $context);
        Storage::put('wechat/video/'.$source_info['file_name'], $read);
        DB::table('wechat_source')->where(['id'=>$req['id']])->update([
            'path'=>'/storage/wechat/'.$source_type.'/'.$source_info->file_name,
        ]);
        dd('ok');
        //Storage::put('file.mp3', $re);
    }
//素材上传
    public function upload()
    {
        return view('wechat/upload',[]);
    }
//    素材上传执行
    public function do_upload(Request $request,Client $client)
    {
        $type = $request->all()['type'];
        $source_type = '';
        switch ($type){
            case 1: $source_type = 'image'; break;
            case 2: $source_type = 'voice'; break;
            case 3: $source_type = 'video'; break;
            case 4: $source_type = 'thumb'; break;
            default;
        }
        $name = 'file_name';
        if(!empty($request->hasFile($name)) && request()->file($name)->isValid()){
            //大小 资源类型限制
            $ext = $request->file($name)->getClientOriginalExtension();  //文件类型
            $size = $request->file($name)->getClientSize() / 1024 / 1024;
            if($source_type == 'image'){
                if(!in_array($ext,['jpg','png','jpeg','gif'])){
                    dd('图片类型不支持');
                }
                if($size > 2){
                    dd('太大');
                }
            }elseif($source_type == 'voice'){}
            $file_name = time().rand(1000,9999).'.'.$ext;
            $path = request()->file($name)->storeAs('wechat/'.$source_type,$file_name);
            $storage_path = '/storage/'.$path;
            $path = realpath('./storage/'.$path);
            $url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$this->tools->get_access_token().'&type='.$source_type;
            //$result = $this->curl_upload($url,$path);
            if($source_type == 'video'){
                $title = '标题'; //视频标题
                $desc = '描述'; //视频描述
                $result = $this->guzzle_upload($url,$path,$client,1,$title,$desc);
//                dd($request);
            }else{
                $result = $this->guzzle_upload($url,$path,$client);
//                dd($result);
            }
            $re = json_decode($result,1);
//            dd($re);
            //插入数据库
            DB::table('wechat_source')->insert([
                'media_id'=>$re['media_id'],
                'type' => $type,
                'path' => $storage_path,
                'add_time'=>time()
            ]);
//            echo 'ok';
            return redirect('/wechat/source');
        }
    }
// 素材管理页面
    public function wechat_source(Request $request,Client $client)
    {
        $req = $request->all();
        empty($req['source_type'])?$source_type = 'image':$source_type=$req['source_type'];
        if(!in_array($source_type,['image','voice','video','thumb'])){
            dd('类型错误');
        }
        if($req['page'] <= 0 ){
            dd('页码错误');
        }
        empty($req['page'])?$page = 1:$page=$req['page'];
        if($page <= 0 ){
            dd('页码错误');
        }
        $pre_page = $page - 1;
        $pre_page <= 0 && $pre_page = 1;
        $next_page = $page + 1;

        $url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.$this->tools->get_access_token();
        $data = [
            'type' =>$source_type,
            'offset' => 1,
            'count' => 20
        ];
//        dd($data);
        //guzzle使用方法
//        $r = $client->request('POST', $url, [
//            'body' => json_encode($data)
//        ]);
//        $re = $r->getBody();
//        echo $re;
//        die();
        $re = $this->tools->redis->get('source_info');
        $re = $this->tools->curl_post($url,json_encode($data));
        $info = json_decode($re,1);
        dd($info);
        $media_id_list = [];
        $source_arr = ['image'=>1,'voice'=>2,'video'=>3,'thumb'=>4];
        foreach($info['item'] as $v){
            //同步数据库
            $media_info = DB::table('wechat_source')->where(['media_id'=>$v['media_id']])->select(['id'])->first();
            if(empty($media_info)){
                DB::table('wechat_source')->insert([
                    'media_id'=>$v['media_id'],
                    'type' => $source_arr[$source_type],
                    'add_time'=>$v['update_time'],
                    'file_name'=>$v['name'],
                ]);
            }
            $media_id_list[] = $v['media_id'];
        }
        $source_info = DB::table('wechat_source')->whereIn('media_id',$media_id_list)->where(['type'=>$source_arr[$source_type]])->get();
        foreach($source_info as $k=>$v){
            $is_download = 0;  //是否需要下载文件 0 否 1 是
            if(empty($v->path)){
                $is_download = 1;
            }elseif (!empty($v->path) && !file_exists('.'.$v->path)){
                $is_download = 1;
            }
            $source_info[$k]->is_download = $is_download;
        }
        return view('wechat/uploadList',['info'=>$source_info,'pre_page'=>$pre_page,'next_page'=>$next_page,'source_type'=>$source_type]);
    }
//素材guzzle上传
    public function guzzle_upload($url,$path,$client,$is_video=0,$title='',$desc='')
    {
        $multipart =  [
            [
                'name'     => 'media',
                'contents' => fopen($path, 'r')
            ]
        ];
        if($is_video == 1){
            $multipart[] = [
                'name'=>'description',
                'contents' => json_encode(['title'=>$title,'introduction'=>$desc],JSON_UNESCAPED_UNICODE)
            ];
        }
        $result = $client->request('POST',$url,[
            'multipart' => $multipart
        ]);
        return $result->getBody(); $result->getBody();
    }
//素材curl上传
    public function curl_upload($url,$path)
    {
        $curl = curl_init($url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_POST,true);
        $form_data = [
            'meida' => new \CURLFile($path)
        ];
        curl_setopt($curl,CURLOPT_POSTFIELDS,$form_data);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }
//    curl post请求测试
    public function post_test()
    {
        //ini_set('display_errors',1);            //错误信息
        //ini_set('display_startup_errors',1);    //php启动错误信息
        //error_reporting(-1);                    //打印出所有的 错误信息
        //GET方式
        /*$curl = curl_init('http://www.baidu.com');
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        $data = curl_exec($curl);
        $errno = curl_errno($curl);  //错误码
        $err_msg = curl_error($curl); //错误信息
        var_dump($data);
        curl_close($curl);*/

        //POST方式
        $curl = curl_init('http://www.shopdemo.com/api/post_test');
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_MAIL_RCPTLOPT_POST,true);  //发送post
        $form_data = [
            'name' => 'liuyajing',
            'sex' => 18
        ];
        curl_setopt($curl,CURLOPT_POSTFIELDS,$form_data);
        $data = curl_exec($curl);
        $errno = curl_errno($curl);  //错误码
        $err_msg = curl_error($curl); //错误信息
        var_dump($data);
        curl_close($curl);
    }
//次数清零
    public function  clear_api(){
        $url = 'https://api.weixin.qq.com/cgi-bin/clear_quota?access_token='.$this->tools->get_access_token();
        $data = ['appid'=>env('WECHAT_APPID')];
        $this->tools->curl_post($url,json_encode($data));
    }
//    模板消息推送
    public function push_template_message()
    {
        $openid = 'oPi8KuHGfvHjhL4u9BnIL4upIaJE';
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->tools->get_access_token();
        $data = [
            'touser'=>$openid,
            'template_id'=>'O0fFvgd-spPBGqQ_FrMt2zUgqxgZMz0fgZDquvswt14',
            'url'=>'http://www.shopdemo.com',
            'data'=>[
                'first'=>[
                    'value'=>'first',
                    'color'=>''
                ],
                'keyword1'=>[
                    'value'=>'keyword1',
                    'color'=>''
                ],
                'keyword2'=>[
                    'value'=>'keyword2',
                    'color'=>''
                ]
            ]
        ];
        $re = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        $result = json_decode($re,1);
        dd($result);
    }
}
