@extends('layouts.admin')
@section('title')列表展示@endsection
@section('content')
<center>
    商品名称:<input type="text" name="goods_name">&nbsp&nbsp<input type="button" value="搜索" id="search">
    <table class="table table-striped table-bordered" border="1">
        <tr>
            <td>#</td>
            <td>商品名称</td>
            <td>商品价钱</td>
            <td>商品LOGO</td>
            <td>操作</td>
        </tr>
        <tbody class="add">


        </tbody>
    </table>
</center>
<nav aria-label="Page navigation">
  <ul class="pagination">
    <!-- <li>
      <a href="#" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <li><a href="#">1</a></li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">4</a></li>
    <li><a href="#">5</a></li>
    <li>
      <a href="#" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li> -->
  </ul>
</nav>
<script>
    var url= "http://www.shopdemo.com/api/goods";
    $.ajax({
        url:url,
        dataType:"json",
        type:"GET",
        success:function(res){
            showData(res);
        }
    })   

    //分页搜索 所有js动态渲染的节点 document绑定
    $(document).on('click',".pagination a",function(){
        //禁止a标签跳转时间 event.preventDefault();
        var name =$("[name='goods_name]").val();
        //获取页码 val html_text <div><p>1</p></div>
        var page =$(this).text();
        // alert(page);return;
        $.ajax({
            url:url,
            dataType:"json",
            type:"GET",
            data:{page:page,name:name},
            success:function(res){
                //渲染
                showData(res);
            }
        })
    })
    
          //搜索
    $("#search").on('click',function(){
        //获取搜索内容
        var goods_name =$("[name='goods_name']").val();
//         alert(goods_name);
        //发送请求
        $.ajax({
            url:url,
            dataType:"json",
            type:"GET",
            data:{goods_name:goods_name},
            success:function(res){
                // alert(res),
                showData(res);
            }
        })
    })
    //根据后台数据 渲染表格数据
    function showData(res)
    {
        $(".add").empty();
                $.each(res.data.data,function(k,v){
                    var tr = $('<tr></tr>');
                    tr.append("<td>"+v.goods_id+"</td>");
                    tr.append("<td>"+v.goods_name+"</td>");
                    tr.append("<td>"+v.goods_price+"</td>");
                    tr.append("<td><img src='"+v.goods_img+"' width='100' heigth='100'></td>");
                    tr.append("<td><a href='http://www.shopdemo.com/user/find?id="+v.id+"'class='btn btn-success'>修改</a>&nbsp<a class='btn btn-danger' id="+v.id+" >删 除</a></td>");
                    $(".add").append(tr);
            })
            var max_page =res.data.last_page;
            $(".pagination").empty();
                for(var i=1;i<=max_page;i++){
                    var li ="<li><a href='javascript:;'>"+i+"</a></li>";
                    $(".pagination").append(li);
            }  
    }
</script>
@endsection
