<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>修改标签</title>
</head>
<body>
<center>
    <form action="{{url('wechat/do_update')}}" method="post">
        @csrf
        <table border="1">
            <input type="hidden" name="id" value="{{$id}}">
                    标签名称：<input type="text" name="tagname" value="{{$name}}">
                    <button>修改</button>
        </table>
    </form>
</center>
</body>
</html>