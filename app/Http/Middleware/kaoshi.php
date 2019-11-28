<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
class kaoshi
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
        $ip = $_SERVER['REMOTE_ADDR'];
        $cache_name = "pass_time_".$ip;
        $num = Cache::get($cache_name);
        if(!$num){
            $num=0;
        }
        if($num>3){
            echo "<script>alert('访问接口过于频繁，请稍后！');</script>";
        }
        $num += 1;
        Cache::put($cache_name,$num,60);
        return $next($request);
    }
}
