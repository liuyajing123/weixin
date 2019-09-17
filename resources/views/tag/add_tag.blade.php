<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>添加标签</title>
</head>
<body>
<center>
    <form action="{{url('wechat/do_add')}}" method="post">
        @csrf
        <table border="1">
            <tr>
                <td>
                    标签名称：
                </td>
                <td>
                    <input type="text" name="tagname" id="">
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button>添加</button>
                </td>
            </tr>
        </table>
    </form>
</center>
</body>
</html>