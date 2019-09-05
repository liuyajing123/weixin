<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>用户列表</title>
</head>
<body>
<center>
    <table border="1">
        <tr>
            <td>用户昵称</td>
            <td>用户openid</td>
            <td>操作</td>
        </tr>
        @foreach($data as $v)
            <tr>
                <td>{{$v['nickname']}}</td>
                <td>{{$v['openid']}}</td>
                <td><a href="{{url('wechat/user_detail',['openid'=>$v['openid']])}}">查看详情</a></td>
            </tr>
        @endforeach
    </table>
</center>
</body>
</html>