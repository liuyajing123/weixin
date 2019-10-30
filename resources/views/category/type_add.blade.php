@extends('layouts.admin')
@section('title')@endsection
@section('content')
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>类型添加</title>
</head>
<body>
<form action="{{url('admin/category/do_type_add')}}" method="post">
<div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">类型名称</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="inputEmail3"  name="type_name">
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-default sub">添加</button>
    </div>
</div>
</form>
</body>
</html>
@endsection