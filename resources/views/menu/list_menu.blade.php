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
<h2>菜单列表</h2>
<table border="1">
    <tr>
        <td>name1</td>
        <td>name2</td>
        <td>操作</td>
    </tr>
    @foreach($info as $v)
        <tr>
            <td>{{$v->name1}}</td>
            <td>{{$v->name2}}</td>
            <td><a href="{{url('admin/del_menu')}}?id={{$v->id}}">删除</a></td>
        </tr>
    @endforeach
</table>
</body>
</html>