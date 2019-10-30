<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\User;
class token
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
//        echo 11;die;
        //        校验token令牌 校验用户信息
        $token = $request->input("token");
        if(empty($token)){
            return json_encode(['code'=>201,'msg'=>"请先登录"],JSON_UNESCAPED_UNICODE);
        }
//        校验token是否正确
        $userData = User::where(['token'=>$token])->first();
        if(empty($token)){
            return json_encode(['code'=>201,'msg'=>"请先登录"],JSON_UNESCAPED_UNICODE);
        }
//        校验token有效期
        if(time()>$userData['expire_time']){
            return json_encode(['code'=>201,'msg'=>"请先登录"],JSON_UNESCAPED_UNICODE);
        }
//        延长token的有效时间
        $user_info=User::where(['token'=>$token])->update([
            'expire_time'=>time()+7200,
        ]);

        $userData = ['userData'=>$userData];
        $request->attributes->add($userData);//添加参数

        return $next($request);//进行下一步(即传递给控制器)

    }
}
