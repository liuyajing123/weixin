<html>
<head>
    <title>素材管理</title>
</head>
<body>
<center>
    <h1>素材管理</h1>
    <a href="{{url('/wechat/upload')}}">上传永久素材</a><br/><br/>
    <table border="1">
        <tr>
            <td>id</td>
            <td>media_id</td>
            <td>type</td>
            <td>path</td>
            <td>add_time</td>
            <td>操作</td>
        </tr>
        @foreach($info as $v)
            <tr>
                <td>{{$v->id}}</td>
                <td>{{$v->media_id}}</td>
                <td>@if($v->type == 1)image @elseif($v->type == 2)voice @elseif($v->type == 3)video @elseif($v->type)thumb @endif</td>
                <td>{{$v->path}}</td>
                <td>{{date('Y-m-d H:i',$v->add_time)}}</td>
                <td>
                    <a href="{{url('/wechat/del_source')}}?id={{$v->id}}">删除</a> |
                    <a href="{{url('')}}">下载资源</a>
                </td>
            </tr>
        @endforeach
    </table>
 </center>
</body>
</html>