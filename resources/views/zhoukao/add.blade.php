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
    <title>添加</title>
</head>
<body>
<center>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">商品名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="inputEmail3"  name="name">
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">商品价格</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="inputPassword3"  name="price">
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">商品图片</label>
            <div class="col-sm-10">
                <input type="file" class="form-control" id="inputPassword3"  name="photo">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default btn">添加</button>
            </div>
        </div>
    </form>
</center>
</body>
</html>
<script src="{{asset('/jquery-3.3.1.js')}} "></script>
<script>
    $(".sub").on("click",function(){
        var name =$("[name='name']").val();
        var price =$("[name='price']").val();
        var url= "http://www.shopdemo.com/api/goods";
        var fd=new FormData();//空表单
        fd.append('photo',$("[name='photo']")[0].files[0]);
        fd.append('name',name);
        fd.append('price',price);
        $.ajax({
            url:url,
            type:"POST",
            data:fd,
            dataType:"json",
            contentType:false,
            processData:false,
            success:function(res){
                alert(res.msg);
                if(res.code == 200){
                    location.href="{{asset("/zhoukao/list")}}";
                }
            }
        })
    })
</script>
@endsection