<?php
namespace App\Tools;
class Tools {
    public $redis;
    public function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1','6379');
    }
    //获取access_token并加入redis缓存
    public function get_access_token()
    {
//        $redis = new \Redis();
//        $redis->connect('127.0.0.1','6379');
        $access_token_key = 'wechat_access_token';
        if($this->redis->exists($access_token_key)){
            //去缓存拿
            $access_token = $this->redis->get($access_token_key);
        }else{
            //去微信接口拿
            $data = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".env('WECHAT_APPID')."&secret=".env('WECHAT_APPSECRET'));
            $data = json_decode($data,1);
            $access_token = $data['access_token'];
            $expire_in = $data['expires_in'];
            //加入缓存
            $this->redis->set($access_token_key,$access_token,$expire_in);
        }
        return $access_token;
//        dd($data);
    }
//
    public function curl_post($url,$data)
    {
        $curl = curl_init($url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_POST,true);  //发送post
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
        $data = curl_exec($curl);
        $errno = curl_errno($curl);  //错误码
        $err_msg = curl_error($curl); //错误信息
        curl_close($curl);
        return $data;
    }

}