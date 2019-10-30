@extends('layouts.admin')
@section('title')修改页面@endsection
@section('content')
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h3>接口修改</h3>
<center>
    <form action="" method="get" enctype="multipart/form-data">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">姓名</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="inputEmail3" placeholder="name" name="name">
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">年龄</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="inputPassword3" placeholder="age" name="age">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default save">修改</button>
            </div>
        </div>
    </form>
</center>
</body>
</html>
<script src="{{asset('/jquery-3.3.1.js')}} "></script>
<script>
    function GetQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]); return null;}
    var id=GetQueryString('id');
    var url="http://www.shopdemo.com/api/user";
    $.ajax({
        url:url+"/"+id,
        type:"GET",
        dataType:'json',
        data:{id:id},
        success:function (res) {
            var name=$('[name="name"]').val(res.data.name);
//            alert(name);
            var age=$('[name="age"]').val(res.data.age);
//            alert(age);
        }
    })
    $('.save').click(function(){
        var name=$('[name="name"]').val();
        var age=$('[name="age"]').val();
        $.ajax({
            url:url+"/"+id,
            type:"POST",
            dataType: "json",
            data:{_method:"PUT",name:name,age:age,id:id},
            success:function(res){
                alert(res.msg);
                if(res.code == 200){
                    location.href="{{asset("/user/user_list")}}";
                }
            }

        })

    })
</script>
@endsection