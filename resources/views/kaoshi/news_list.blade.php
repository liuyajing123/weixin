@extends('layouts.admin')
@section('title','类型展示页面')
@section('content')
    <h3>新闻展示页面</h3>
    <table class='table table-striped table-bordered'>
        <tr>
            <td>id</td>
            <td>标题</td>
            <td>内容</td>
            <td>img_width</td>
            <td>src</td>
            <td>图片</td>

        </tr>
        @foreach($data as $v)
            <tr>
                <td>{{$v['news_id']}}</td>
                <td>{{$v['title']}}</td>
                <td>{{$v['content']}}</td>
                <td>{{$v['img_width']}}</td>
                <td>{{$v['src']}}</td>
                <td><img src="{{$v['img']}}" width="100" height="100"></td>
            </tr>
        @endforeach
    </table>
    {{ $data->links() }}
@endsection