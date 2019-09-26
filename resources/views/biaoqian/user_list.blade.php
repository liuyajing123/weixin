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
        <form action="{{url('admin/tag_openid')}}" method="post">
            @csrf
            <input type="submit" value="提交">
            <input type="hidden" value="{{$tagid}}" name="tagid">
            <table border="1">
                <tr>
                    <td>选择</td>
                    <td>用户昵称</td>
                    <td>用户openid</td>
                    <td>操作</td>
                </tr>
                @foreach($data as $v)
                    <tr>
                        <td>
                            <input type="checkbox" name="openid_list[]" value="{{$v['openid']}}">
                        </td>
                        <td>{{$v['nickname']}}</td>
                        <td>{{$v['openid']}}</td>
                        <td>
                            <a href="{{url('/admin/tag')}}?openid={{$v['openid']}}">用户标签</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </form>

    </center>
</body>
</html>