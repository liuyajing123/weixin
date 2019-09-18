<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>用户详情</title>
</head>
<body>
<table border="1" align="center">
    <tr align="center">
        <td>用户昵称</td>
        <td>用户头像</td>
        <td>性别</td>
        <td>城市</td>
        <td>openid</td>
    </tr>

    <tr align="center">
        <td>{{$data['nickname']}}</td>
        <td><img src="{{asset($data['headimgurl'])}}" width="100" alt=""></td>
        <td>@if($data['sex'] === 0)未设置@elseif($data['sex']==1)男@elseif($data['sex']==2)女@endif</td>
        <td>{{$data['country']}}{{$data['province']}}{{$data['city']}}</td>
        <td>{{$data['openid']}}</td>
    </tr>

</table>
</body>
</html>