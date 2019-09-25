<?php

namespace App\Http\Controllers\aaa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Tools\Tools;
class eventController extends Controller
{
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    /**
         * 接收微信发送的消息【用户互动】
         */
        public function event()
    {
//        echo $_GET['echostr'];
//        die();
//        echo "您已经进入接口配置的url";
//        echo 1;dd();
        $xml_string = file_get_contents('php://input');  //获取
        $wechat_log_psth = storage_path('logs/wechat/'.date('Y-m-d').'.log');
        file_put_contents($wechat_log_psth,"<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n",FILE_APPEND);
        file_put_contents($wechat_log_psth,$xml_string,FILE_APPEND);
        file_put_contents($wechat_log_psth,"\n<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n\n",FILE_APPEND);
        //dd($xml_string);
        $xml_obj = simplexml_load_string($xml_string,'SimpleXMLElement',LIBXML_NOCDATA);
        $xml_arr = (array)$xml_obj;
        \Log::Info(json_encode($xml_arr,JSON_UNESCAPED_UNICODE));
        //echo $_GET['echostr'];
        //业务逻辑
        if($xml_arr['MsgType'] == 'event') {
            if ($xml_arr['Event'] == 'subscribe') {
//                $share_code = explode('_', $xml_arr['EventKey'])[1];
//                $user_openid = $xml_arr['FromUserName']; //粉丝openid
//                //判断openid是否已经在日志表
//                $wechat_openid = DB::table('wechat_openid')->where(['openid' => $user_openid])->first();
//                if (empty($wechat_openid)) {
//                    DB::table('user')->where(['id' => $share_code])->increment('share_num', 1);
//                    DB::table('wechat_openid')->insert([
//                        'openid' => $user_openid,
//                        'add_time' => time()
//                    ]);
//                }
//                关注 通过openid拿到用户基本信息
                $user = file_get_contents('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->tools->get_access_token().'&openid='.$xml_arr['FormUserName'].'&lang=zh_CN');
                $user_info = json_decode($user,1);
                $message = '欢迎'.$user_info['nickname'].'同学，感谢您的关注';
                $xml_str = '<xml><ToU0serName><![CDATA['.$xml_arr['FromUserName'].']]></ToU0serName><FromUserName><![CDATA['.$xml_arr['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                echo $xml_str;
            }
        }
//        $message = '欢迎关注';
//        $xml_str = '<xml><ToU0serName><![CDATA['.$xml_arr['FromUserName'].']]></ToU0serName><FromUserName><![CDATA['.$xml_arr['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
//        echo $xml_str;
    }
}
