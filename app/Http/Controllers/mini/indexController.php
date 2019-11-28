<?php

namespace App\Http\Controllers\mini;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Nav;
use App\Model\KaoGoods;
class indexController extends Controller
{
    public function cha(Request $request){
//        dd(1);
//        echo json_encode(1);
        $name=$request->name;
        $data=[];
        if(!empty($name)){
            $data =KaoGoods::where('g_goods','like',"%$name%")->select('g_goods')->get();
        }
        $data=[
            'code'=>200,
            'masset'=>'success',
            'data'=>$data
        ];
//        dd($data);
        echo  json_encode($data,JSON_UNESCAPED_UNICODE);
    }
}
