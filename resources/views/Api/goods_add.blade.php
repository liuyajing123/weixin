@extends('layouts.admin')
@section('title')商品添加@endsection
@section('content')
<h3>商品添加</h3>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">商品名称</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="goods_name">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">商品价钱</label>
                <div class="col-sm-10">
                    <input type="test" class="form-control" name="goods_price">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">商品LOGO</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control" name="goods_img">
                </div>
            </div>
    <input type="button" id="add" value="提交">
    <script>   
    $("#add").on("click",function(){
        var goods_name =$("[name='goods_name']").val();
        var goods_price =$("[name='goods_price']").val();
        var url= "http://www.shopdemo.com/api/category";
        var fd=new FormData();//空表单
        fd.append('goods_img',$("[name='goods_img']")[0].files[0]);
        fd.append('goods_name',goods_name);
        fd.append('goods_price',goods_price);
        $.ajax({
            url:url,
            type:"POST",
            data:fd,
            dataType:"json",
            contentType:false,
            processData:false,
            success:function(res){
                alert(res.msg);location.href="http://www.shopdemo.com/Api/goods_show";
            }
        })
    })    
</script>
@endsection
