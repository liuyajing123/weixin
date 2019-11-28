<?php

namespace App\Http\Controllers\mini;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Nav;
class navController extends Controller
{
//    获取导航列表
    public function lists()
    {
        $list = Nav::get()->toArray();
        $request = [
            'code'=>200,
            'message'=>'success',
            'data'=>$list
        ];
        echo json_encode($request,JSON_UNESCAPED_UNICODE);
    }
}
