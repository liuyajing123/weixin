<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>标签管理</title>
</head>
<body>
<center>
    <h1>标签管理</h1>
    <h2><a href="{{url('wechat/add_tag')}}">添加标签</a></h2>
    <table border="1">
        <tr>
            <td>id</td>
            <td>标签名称</td>
            <td>标签下粉丝数量</td>
            <td>操作</td>
        </tr>
        @foreach($info as $v)
        <tr>
            <td>{{$v['id']}}</td>
            <td>{{$v['name']}}</td>
            <td>{{$v['count']}}</td>
            <td>
                <a href="{{url('wechat/del_tag',['id'=>$v['id']])}}">删除</a>
                <a href="{{url('wechat/update_tag',['id'=>$v['id']])}}">修改</a>
                <a href="{{url('wechat/tag_openid_list')}}?tagid={{$v['id']}}">标签下粉丝列表</a>
                <a href="{{url('wechat/get_user_lists')}}?tagid={{$v['id']}}">为用户打标签</a>
                <a href="{{url('wechat/push_tag_message')}}?tagid={{$v['id']}}">推送消息</a>
            </td>
        </tr>
        @endforeach
    </table>
</center>

</body>
</html>