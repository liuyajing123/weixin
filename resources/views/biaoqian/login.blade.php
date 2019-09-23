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
<center>
    <form action="{{url('/admin/do_login')}}">
        用户名：<input type="text" name="" id=""><br>
        密码：<input type="password" name="" id=""><br>
        第三方登录：<input type="submit" value="微信授权登录">
    </form>
</center>
</body>
</html>