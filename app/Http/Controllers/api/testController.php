<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class testController extends Controller
{
//    添加接口
    public function add(Request $request)
    {
        $name = $request->input('name');
//        dd($name);
        $age = $request->input('age');
//        dd($age);
        $sign = $request->input('sign');

        if(empty($name) || empty($age)){
            return json_encode(['code'=>0,'msg'=>"参数不能为空"]);
        }
        if(empty($sign)){
            return  json_encode(['code'=>201,'msg'=>"签名没传"]);
        }
        $mySign = md5('shopdemo'.$name.$age);
        if($mySign != $sign){
            return json_encode(['code'=>201,'msg'=>"签名不对，别逗了"]);
        }
        $res = DB::table('test')->insert([
            'name' => $name,
            'age'=>$age,
            'ip'=>$_SERVER['REMOTE_ADDR']
        ]);
        if($res){
            return json_encode(['code'=>200,'msg'=>'添加成功']);
        }else{
            return json_encode(['code'=>201,'msg'=>'添加失败']);
        }
    }
//    接口查询列表
    public function list()
    {
        $data =DB::table('test')->get()->toArray();
        return json_encode(['code'=>'200','data'=>$data]);
    }
//    修改
    public function find(Request $request)
    {
        $id=$request->id;
        $data=DB::table('test')->where(['id'=>$id])->first();
        //对象转数组
//        $data = get_object_vars($data);
        if($data){
            return json_encode(['code'=>'200','msg'=>'查找成功','data'=>$data]);
        }
    }
//    修改执行
    public function save(Request $request)
    {

        $data=$request->all();
        $res=DB::table('test')->where(['id'=>$data['id']])->update([
            'name'=>$data['name'],
            'age'=>$data['age']
        ]);
        if($res){
            return json_encode(['code'=>200,'msg'=>'修改成功']);
        }
    }
//    接口删除
    public function del(Request $request)
    {
        $id=$request->id;
        $res=DB::table('test')->where(['id'=>$id])->delete();
        if($res){
            return json_encode(['code'=>200,'msg'=>'删除成功']);
        }
    }
}
