<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>账号绑定</title>
</head>
<body>
<form action="do_bind" method="post">
    @csrf
    用户名：<input type="text" name="name" id=""><br>
    密码：<input type="password" name="password" id=""><br>
    <button>绑定</button>
</form>
</body>
</html>