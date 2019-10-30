<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\category;
use App\Model\Type;
use App\Model\attr;
use App\Model\goods;
use App\Model\goodsAttr;
use App\Model\Product;
class categoryController extends Controller
{
//    分类视图
    public function add()
    {
        return view('category/add');
    }
//    分类添加
    public function do_add(Request $request)
    {
//        echo 11;
        $name = $request->input('category_name');
//        dd($name);
        $validatedData = $request->validate([
            'category_name' => 'unique:category',
        ],[
            'category_name.unique'=>'商品名称已存在',
        ]);
        $res = Category::insert([
            'category_name'=>$name,
        ]);
        if($res){
            echo "<script>alert('添加成功');location.href='/admin/category/list';</script>";
        }else{
            echo "<script>alert('添加失败');location.href='/admin/category/add';</script>";
        }
    }
//    分类列表
    public function list()
    {
        $res = Category::get();
//        dd($res);
        return view('category/list',['res'=>$res]);
    }
//    类型视图
    public function type_add()
    {
        return view('category/type_add');
    }
//    类型添加
    public function do_type_add(Request $request)
    {
//        echo 11;
        $name = $request->input('type_name');
//        dd($name);
        $res = Type::insert([
            'type_name'=>$name,
        ]);
        if($res){
            echo "<script>alert('添加成功');location.href='/admin/category/type_list';</script>";
        }else{
            echo "<script>alert('添加失败');location.href='/admin/category/type_add';</script>";
        }
    }
//    类型展示
    public function type_list()
    {
        $res=Type::get();
//        dd($res);
        foreach($res as $key=>$val){
            $info = Type::where('type_id',$val['type_id'])->count();
            $res[$key]['attr_count']=$info;
        }
        // $res = json_encode($info);
        // dd($data);
        return view('category/type_list',['res'=>$res]);
    }
//    属性视图
        public function attr_add()
        {
            $data =Type::get();
//             dd($data);
            return view('category/attr_add',['data'=>$data]);
        }
//        属性添加
    public function do_attr_add(Request $request)
    {
        $info =$request->all();
//         dd($info);
        $res =Attr::insert([
            'attr_name'=>$info['attr_name'],
            'type_id'=>$info['type_id'],
            'attr_type'=>$info['attr_type'],
        ]);
        if($res){
            echo "<script>alert('添加成功');location.href='/admin/category/attr_list';</script>";
        }else{
            echo "<script>alert('添加失败');location.href='/admin/category/attr_add';</script>";
        }
    }
    public function attr_list()
    {
        $res = Attr::get();
//        dd($res);
        foreach($res as $key=>$val){
            $info = Type::where('type_id',$val['type_id'])->value('type_name');
            $res[$key]['type_name'] = $info;
        }
        return view('category/attr_list',['res'=>$res]);
    }
//    删除
    public function del()
    {
        $attr_id = request()->input('attr_id');
//         dd($attr_id);
        $res = Attr::where(['attr_id' => $attr_id])->delete();
        if($res){
            return json_encode(['code'=>200,'msg'=>'删除成功']);
        }else{
            return json_encode(['code'=>201,'msg'=>'删除失败']);
        }
    }
//    商品添加
    public function goods_add()
    {
//       查分类
        $categoryData = Category::get()->toArray();
//        查类型
        $typeData = Type::get()->toArray();
        return view('category/goods_add',['categoryData'=>$categoryData,'typeData'=>$typeData]);
    }
    public function do_goods_add(Request $request)
    {
//        根据类型id 查找该类型下的属性
        $type_id = $request->input('type_id');
//        查询属性表
        $attrData = Attr::where(['type_id'=>$type_id])->get()->toArray();
//        var_dump($attrData);die;
        return json_encode($attrData);
    }
    public function add_do(Request $request)
    {
        $postData =$request->input();
//         dd($postData);
        $path = $request->file('goods_img')->store('');//图片路径
//         dd($path);
        $goods_img=asset('storage'.'/'.$path);
//        dd($goods_img);
        //根据基本信息入库
        $goodsModel = Goods::create([
            'goods_name'=>$postData['goods_name'],
            'category_id'=>$postData['category_id'],
            'goods_price'=>$postData['goods_price'],
            'goods_desc'=>$postData['goods_desc'],
            'goods_img'=>$goods_img,
        ]);
//        dd($goodsModel);
        //获取商品主键id
        $goods_id =$goodsModel->goods_id;
//         dd($goods_id);
        //2.商品属性信息入库=》商品-属性关系表
        $insertData = [];//定义要添加入库的数据
        // var_dump($insertData);die;
        foreach($postData['attr_value_list'] as $key=>$value){
           $insertData[] =[
                'goods_id'=>$goods_id,
                'attr_id'=>$postData['attr_id_list'][$key],
                'attr_value'=>$value,
                'attr_price'=>$postData['attr_price_list'][$key]
            ];
//            dd($insertData);
        }
        //批量入库
        $res =GoodsAttr::insert($insertData);
//        var_dump($res);die;
        return redirect('admin/category/product_add/'.$goods_id);
    }

//    商品列表
    public function goods_list()
    {
        $res = Goods::paginate(2);
//        dd($res);
        return view('category/goods_list',['res'=>$res]);
    }

//    货品添加
    public function product_add($goods_id)
    {
        //根据商品id查询商品基本信息
//        $goods_id=$request->input('goods_id');
////        dd($goods_id);
        $goodsData=Goods::where(['goods_id'=>$goods_id])->first();
//        dd($goodsData);
//        根据商品id 查商品属性关系表(属性值)
        $goodsAttrData=GoodsAttr::join("attr","goods_attr.attr_id","=","attr.attr_id")->where(['goods_id'=>$goods_id])->get()->toArray();
//        dd($goodsAttrData);
        //处理数据
        $newArr=[];
        foreach ($goodsAttrData as $key=>$value){
            $status=$value['attr_name'];
            $newArr[$status][]=$value;
        }
        //        echo "<pre>";
//        var_dump($goodsAttrData);
        return view('category/product_add',[
            'attrData'=>$newArr,
            'goods_id'=>$goods_id
        ]);
    }
    public function do_product_add(request $request)
    {
        $postData = $request->input();
//        dd($postData);
//        echo "<pre>";
//        var_dump(count($postData['attr']));die;
        //属性值组合处理数据
//        dd(count($postData['product_number']));
        $size = count($postData['goods_attr']) / count($postData['product_number']);
//        dd($size);
        $goodsAttr = array_chunk($postData['goods_attr'], $size);
//        dd($goodsAttr);
        //echo "<pre>";
//        var_dump($postData);
//        var_dump($goodsAttr[0][0]);die;
        foreach ($goodsAttr as $key => $value) {
            Product::create([
                'goods_id' => $postData['goods_id'],
                'value_list' => implode(",", $value),
                'product_number' => $postData['product_number'][$key]
            ]);
        }
    }
}
