<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Model\test;
class userController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $where = [];
        $name = request("name");
//        dd($name);
        if(isset($name)){
            $where[] = ["name","like","%$name%"];
        }
        $data =test::where($where)->paginate(2);
        return json_encode(['code'=>'200','data'=>$data,'msg'=>'查询成功']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->input('name');
//        dd($name);
        $age = $request->input('age');
//        dd($age);
        $img_path = $request->input('img_path');
        if(empty($name) || empty($age)){
            return json_encode(['code'=>400,'msg'=>'参数不能为空']);
        }
        //处理文件上传
        $img_path="";
        if($request->hasFile('file')){
            $img_path=$request->file->store('images');
//        var_dump($img_path);die;
        }
        $res = DB::table('test')->insert([
            'name' => $name,
            'age'=>$age,
            'img_path'=>$img_path
        ]);
        if($res){
            return json_encode(['code'=>200,'msg'=>'添加成功']);
        }else{
            return json_encode(['code'=>201,'msg'=>'添加失败']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $id=$request->id;
        $data=DB::table('test')->where(['id'=>$id])->first();
        //对象转数组
//        $data = get_object_vars($data);
        if($data){
            return json_encode(['code'=>'200','msg'=>'查找成功','data'=>$data]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id=$request->id;
        $res=DB::table('test')->where(['id'=>$id])->delete();
        if($res){
            return json_encode(['code'=>200,'msg'=>'删除成功']);
        }
    }
}
