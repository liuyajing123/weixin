<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
class login
    {
        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @return mixed
         */
        public function handle($request, Closure $next)
        {
            //        防刷
            //        通过ip获取客户端信息
            $ip = $_SERVER['REMOTE_ADDR'];//实际上获取id
            //        记录当前IP  一分钟访问了接口多少次 缓存里 键名
            $cache_name = "pass_time_".$ip;
            //        上一次访问了多少次
            $num = Cache::get($cache_name);
            if(!$num){
                $num = 0;
            }
            if($num>5){
    //            ip记录到文件 服务器端配置屏蔽某个ip
                echo json_encode(['code'=>201,'msg'=>"访问接口过于频繁，请稍后"],JSON_UNESCAPED_UNICODE);die;
            }
            $num += 1;
            Cache::put($cache_name,$num,86400);
            return $next($request);
    }
}
