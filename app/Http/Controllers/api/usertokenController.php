<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\Product;
use App\Model\Cart;
use App\Model\GoodsAttr;
//use DB;
class usertokenController extends Controller
{
//    登录
    public function login(Request $request)
    {
        //用户名和密码
        $username =$request->input("username");
        $password =$request->input("password");
        //查询数据库
        $userData =User::where(['username'=>$username,'password'=>$password])->first();
//        dd($userData);
        if(!$userData){
            echo "用户名密码错误";die;
        }
        //用户登录成功
        //生成token令牌
        $token=md5($userData[0]['user_id'].time());
//        dd($token);
        //修改数据库 把token
        $user_info=User::where(['username'=>$username,'password'=>$password])->update([
            'token'=>$token,
            'expire_time'=>time()+7200,
        ]);
        //返回客户端
        return json_encode(['code'=>1,'msg'=>"登陆成功",'token'=>$token],JSON_UNESCAPED_UNICODE);
    }
//      校验
    public function getUser(Request $request)
    {
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
    }
//    加入购物车
    public function cart_add(Request $request)
    {
       $userData = $request->get('userData');//中间件产生的参数
//        接值
        $goods_id = $request->input('goods_id');
//        dd($goods_id);
        $goods_attr_list = implode(",",$request->input('goods_attr_list'));
//        dd($goods_attr_list);
        $user_id = $userData['user_id'];
        $buy_number = 1;
        $productData = Product::where(['goods_id'=>$goods_id,'value_list'=>$goods_attr_list])->first();
//        dd($productData);
        $product_num = $productData['product_number'];
        if($buy_number>=$product_num){
//            没货
            $is_have_num = 0;
        }else{
//            有货
            $is_have_num = 1;
        }
        $cartData = Cart::where(['goods_id'=>$goods_id,'user_id'=>$user_id,'goods_attr_list'=>$goods_attr_list])->first();
//        dd($cartData);
        if(!empty($cartData)){
            $num_info=Cart::where(['goods_id'=>$goods_id])->update([
               'buy_number'=> $cartData->buy_number+$buy_number,
            ]);
        }else{
            cart::create([
                'goods_id'=>$goods_id,
                'goods_attr_list'=>$goods_attr_list,
                'user_id'=>$user_id,
                'buy_number'=>$buy_number,
                'product_id'=>$productData['product_id'],
                'is_have_num'=>$is_have_num
            ]);
        }
    }
//    购物车列表
    public function cart_list(Request $request)
    {
//        接收中间件传递的参数
        $userData = $request->get('userData');
        $user_id = $userData['user_id'];
//         查询购物车表数据
        $cartData = Cart::join('goods',"cart.goods_id","=","goods.goods_id")->where(['user_id'=>$user_id])->get()->toArray();
//        属性值的组合
        foreach ($cartData as $key => $value) {
            $goods_attr_list = explode(",",$value['goods_attr_list']);
//            查属性值表
            $goodsAttrData = GoodsAttr::join('attr','goods_attr.attr_id','=','attr.attr_id')->whereIn('goods_attr_id',$goods_attr_list)->get()->toArray();
//            组装字符串
            $attr_show_list = '';
            $count_price = $value['goods_price'];
            foreach ($goodsAttrData as $k => $v){
                $attr_show_list .= $v['attr_name'].":".$v['attr_value'].",";
//                价钱的计算 加上每个属性的价钱
                 $count_price += $v['attr_price'];
            }
//            重新对数组元素赋值
            $cartData[$key]['attr_show_list'] = rtrim($attr_show_list,",");
            $cartData[$key]['goods_price'] = $count_price;
        }
        return json_encode($cartData);
    }
}
