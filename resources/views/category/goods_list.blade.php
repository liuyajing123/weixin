@extends('layouts.admin')
@section('title')列表展示@endsection
@section('content')
    <h3>列表展示</h3>
    <center>
        <table class="table table-striped table-bordered" border="1">
            <tr>
                <td>Id</td>
                <td>商品名称</td>
                <td>商品价钱</td>
                <td>商品图片</td>
                <td>商品描述</td>
                <td>操作</td>
            </tr>
        </table>
        <tbody class="add">

        </tbody>
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
            <script src="{{asset('/jquery-3.3.1.js')}} "></script>
            <script>
                        {{--列表--}}
                var url="http://www.shopdemo.com/api/user";
                $.ajax({
                    url:url,
                    type:"GET",
                    dataType:"json",
                    success:function(res){
                        $.each(res.data.data,function(k,v){
                            var tr = $('<tr></tr>');
                            tr.append("<td>"+v.id+"</td>");
                            tr.append("<td>"+v.name+"</td>");
                            tr.append("<td>"+v.age+"</td>");
                            tr.append("<td>"+v.img_path+"</td>");
                            tr.append("<td><a href='http://www.shopdemo.com/user/find?id="+v.id+"'class='btn btn-success'>修改</a>|<a class='btn btn-danger' id="+v.id+" >删 除</a></td>");
                            $(".add").append(tr);
                        })
                        var max_page = res.data.last_page;
                        for(var i = 1; i <= max_page; i++){
                            var li = "<li><a href='#'>"+i+"</a></li>";
                            $(".pagination").append(li);
                        }
//            删除
                        $('.btn-danger').click(function() {
                            var id = $(this).attr('id');
                            // alert(id);
                            var url="http://www.shopdemo.com/api/user";
                            $.ajax({
                                url:url+"/"+id,
                                type:"delete",
                                dataType: 'json',
                                data: {id: id},
                                success:function(res){
                                    alert(res.msg);
                                    if(res.code == 200){
                                        location.href="{{asset("/user/user_list")}}";
                                    }
                                }
                            })
                        })
                        showData(res);
                    }
                })

@endsection