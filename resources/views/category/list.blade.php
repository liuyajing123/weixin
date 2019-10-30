@extends('layouts.admin')
@section('title')列表展示@endsection
@section('content')
    <h3>列表展示</h3>
    <center>
        <table class="table table-striped table-bordered" border="1">
            <tr>
                <td>Id</td>
                <td>分类名称</td>
                <td>操作</td>
            </tr>
            @foreach( $res as $v)
            <tr>
                <td>{{$v['category_id']}}</td>
                <td>{{$v['category_name']}}</td>
                <td><a href="" class='btn btn-success'>修改</a>||<a href="" class='btn btn-danger'>删除</a></td>
            </tr>
            @endforeach
        </table>
    </center>
@endsection