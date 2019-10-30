@extends('layouts.admin')
@section('title')商品列表展示@endsection
@section('content')
    <table class="table table-striped table-bordered" border="1">
        <tr>
            <td>id</td>
            <td>商品类型名称</td>
            <td>属性数</td>
            <td>操作</td>
        </tr>
        @foreach($res as $v)
            <tr>
                <td>{{$v->type_id}}</td>
                <td>{{$v->type_name}}</td>
                <td>{{$v->attr_count}}</td>
                <td>
                    <a href="{{url('')}}" class='btn btn-danger'>属性列表</a>
                </td>

            </tr>
        @endforeach
    </table>
@endsection