<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>留言</title>
</head>
<body>
<center>
    <form action="/wechat/do_liuyan" method="post">
        @csrf
        <input type="hidden" name="openid" value="{{json_encode($openid)}}">
        留言内容：<input type="text" name="liuyan">
        <br><br>
        <input type="submit" value="留言">
    </form>
</center>
</body>
</html>