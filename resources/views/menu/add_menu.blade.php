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
<form action="{{url('/admin/create_menu')}}" method="post"><br/><br/>
    @csrf
    一级菜单名称 <input type="text" name="name1"><br/><br/>
    二级菜单名称 <input type="text" name="name2"><br/><br/>
    菜单类型[click/view]
    <select name="type" id="">
        <option value="1">click</option>
        <option value="2">view</option>
    </select><br/><br/>
    事件值
    <input type="text" name="event_value">
    <br/><br/>
    <input type="submit" value="提交">
</form>
</body>
</html>