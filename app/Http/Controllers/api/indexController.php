<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\category;
use App\Model\Type;
use App\Model\attr;
use App\Model\goods;
use App\Model\goodsAttr;

class indexController extends Controller
{
//    前台首页
    public function index()
    {
        $data = Goods::orderBy("goods_id",'desc')->limit(4)->get();
        return json_encode(['ret'=>200,'data'=>$data]);
    }
//    前台商品详情
    public function detail(Request $request)
    {
        $goods_id=$request->input('goods_id');
        //查询商品表基本信息 goods
        $goodsData=Goods::where(['goods_id'=>$goods_id])->first();
        // dd($goodsData);
        // 查询商品-属性关系表(两表联查)goods_attr
        $goodsAttrData=GoodsAttr::join("attr","goods_attr.attr_id","=","attr.attr_id")->where(['goods_id'=>$goods_id])->get()->toArray();
        // echo '<pre>';
        // var_dump($GoodsAttrData);
        $specData = [];//可先规格数线
        $argsData = [];//普通展示属性
        foreach ($goodsAttrData as $key => $value){
            if($value['attr_type'] == 2){
                //可选规格
                $status = $value ['attr_name'];
                $specData[$status][] = $value;

            }else{
                $argsData[]  = $value;
            }
        }
        return json_encode(['goodsData'=>$goodsData,'specData'=>$specData,'argsData'=>$argsData]);
    }
//分类列表
    public function goods_cate_show(Request $request)
    {
        // $cartData = Cart::join('goodsware',"cart.goods_id","=","goodsware.goods_id")->where(['user_id'=>$user_id])->get()->toArray();
        $cateData=Category::join("goods","category.category_id","=","goods.category_id")->get()->toArray();
        // var_dump($cateData);die;
        // $cateData =GoodsWare::get();
        return json_encode($cateData);
    }
    public function goods_cate()
    {
        $data=Category::get();
        // dd($data);
        return json_encode($data);
    }
}

