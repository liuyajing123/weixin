<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>文件上传</title>
</head>
<body>
<center>
    <form action="{{url('wechat/do_upload')}}" method="post" enctype="multipart/form-data">
        @csrf
        <select name="type" id="">
            <option value="1">图片</option>
            <option value="2">音频</option>
            <option value="3">视频</option>
            <option value="4">缩略图</option>
        </select><br><br>
        <input type="file" name="file_name" value="">
        <input type="submit" value="提交">
    </form>
</center>
</body>
</html>