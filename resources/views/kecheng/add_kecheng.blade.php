<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>课程添加</title>
</head>
<body>
<form action="{{url('admin/do_add_kecheng')}}" method="post">
    @csrf
    <center>
        <table border="1">
            <tr>
                <td>第一节课：</td>
                <td>
                    <select name="kecheng1" id="">
                        <option value="语文">语文</option>
                        <option value="数学">数学</option>
                        <option value="英语">英语</option>
                        <option value="php">php</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>第二节课：</td>
                <td>
                    <select name="kecheng2" id="">
                        <option value="语文">语文</option>
                        <option value="数学">数学</option>
                        <option value="英语">英语</option>
                        <option value="php">php</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>第三节课：</td>
                <td>
                    <select name="kecheng3" id="">
                        <option value="语文">语文</option>
                        <option value="数学">数学</option>
                        <option value="英语">英语</option>
                        <option value="php">php</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td> 第四节课：</td>
                <td>
                    <select name="kecheng4" id="">
                        <option value="语文">语文</option>
                        <option value="数学">数学</option>
                        <option value="英语">英语</option>
                        <option value="php">php</option>
                    </select>
                </td>
            </tr>
           <tr>
               <td></td>
               <td><input type="submit" value="提交"></td>
           </tr>
        </table>
    </center>
</form>
</body>
</html>