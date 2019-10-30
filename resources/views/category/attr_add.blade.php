@extends('layouts.admin')
@section('title')分类属性@endsection
@section('content')
    <div class="ibox-content">
        <form class="form-horizontal" action="{{url('admin/category/do_attr_add')}}" method="post">
            @csrf
            <div class="form-group">
                <label class="col-sm-3 control-label">属性名称：</label>
                <div class="col-sm-8">
                    <input type="text" placeholder="请输入属性名称" class="form-control" name="attr_name">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">所有商品类型：</label>
                <div class="col-sm-8">
                    <select name="type_id" id="">
                        <option value="">---请选择---</option>
                        @foreach($data as $v)
                            <option value="{{$v->type_id}}">{{$v->type_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">所有商品类型：</label>
                <div class="col-sm-8">
                    <input type="radio" name="attr_type" value="2">参数&nbsp&nbsp<input type="radio" name="attr_type" value="1">规格
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-8">
                    <button class="btn btn-sm btn-info" type="submit">提 交</button>
                </div>
            </div>
        </form>
    </div>
@endsection