<?php

namespace App\Http\Controllers\aaa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tools\Tools;
class TagController extends Controller
{
    public $tools;
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }
//    标签添加视图
    public function add_tag()
    {
//        echo 1111;
        return view('tag/add_tag');
    }
//    标签添加执行
    public function do_add(Request $request)
    {
//        echo 11;
        $data = $request->all();
//        dd($data);
        $data = [
            'tag'=>[
                'name' => $data['tagname']
            ]
        ];
//        dd($data);
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/create?access_token='.$this->tools->get_access_token();
        $re = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        $res = json_decode($re,1);
//        dd($res);
        return redirect('wechat/tagList');
    }
//    标签管理列表
    public function tagList()
    {
        $data = file_get_contents('https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$this->tools->get_access_token());
        $info = json_decode($data,1);
//        dd($info);
        return view('tag/tagList',['info'=>$info['tags']]);
    }
//   标签删除
    public function del_tag(Request $request)
    {
//        echo 111;
        $url ='https://api.weixin.qq.com/cgi-bin/tags/delete?access_token='.$this->tools->get_access_token();
//        dd($url);
        $data = [
            'tag'=>[ "id"=>intval($request->id)]
        ];
//        dd($data);
        $re = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
//        dd($re);
        $info = json_decode($re,1);
//        dd($info);
        return redirect('wechat/tagList');
    }
//    标签修改视图
    public function update_tag(Request $request)
    {
        $id = $request->id;
//        dd($id);
        $tag_id=[   "tag_id"=>[$id] ];
//        dd($tag_id);
        $data = file_get_contents("https://api.weixin.qq.com/cgi-bin/tags/get?access_token=".$this->tools->get_access_token()."");
        $re = json_decode($data,1);
//        dd($re);
        $re_arr = $re['tags'];
//        dd($re_arr);
        foreach($re_arr as $v){
            foreach($tag_id['tag_id'] as $vo){
                if($vo == $v['id']){
                    return view('tag/update',['id'=>$vo,'name'=>$v['name']]);
                }
            }
        }
    }
//    标签修改执行
    public function do_update(Request $request)
    {
        $name=$request->all(['tagname']);
        $name=implode('',$name);
        $id=$request->all(['id']);
        $id=implode('',$id);
        $url="https://api.weixin.qq.com/cgi-bin/tags/update?access_token=".$this->tools->get_access_token();
        $data=[
            "tag" => [ "id"=>$id ,"name"=>$name]
        ];
//        dd($data);
        $re=$this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
//        dd($re);
        $re=json_decode($re,1);
//        dd($re);
        return redirect('wechat/tagList');
    }
//    标签下粉丝列表
    public function tag_openid_list(Request $request)
    {
//        echo 111;
        $req = $request->all();
//        dd($req);
        $url = 'https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token='.$this->tools->get_access_token();
        $data = [
            'tag'=>$req['tagid'],
            'next_name'=>''
        ];
//        dd($data);
        $re = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        $info = json_decode($re,1);
        dd($info);
    }
//     为粉丝打标签
    public function user_tag_list(Request $request)
    {
        $req = $request->all();
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token='.$this->tools->get_access_token();
        $data = [
            'openid'=>$req['openid']
        ];
        $re = $this->tools->curl_post($url,json_encode($data));
        $result = json_decode($re,1);
        $tag = file_get_contents('https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$this->tools->get_access_token());
        $tag_result = json_decode($tag,1);
        $tag_arr = [];
        foreach($tag_result['tags'] as $v){
            $tag_arr[$v['id']] = $v['name'];
        }
        foreach($result['tagid_list'] as $v){
            echo $tag_arr[$v]."<br/>";
        }
    }
    public function tag_openid(Request $request)
    {
        $req = $request->all();
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token='.$this->tools->get_access_token();
        $data = [
            'openid_list'=>$req['openid_list'],
            'tagid'=>$req['tagid']
        ];
        $re = $this->tools->curl_post($url,json_encode($data));
        $result = json_decode($re,1);
        dd($result);
    }
//    根据标签群发消息
    public function push_tag_message(Request $request)
    {
        return view('tag.pushTagMsg',['tagid'=>$request->all()['tagid']]);
    }
    public function do_push_tag_message(Request $request)
    {
        $req = $request->all();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$this->tools->get_access_token();
        $data = [
            'filter' => [
                'is_to_all'=>false,
                'tag_id'=>$req['tagid']
            ],
            'text'=>[
                'content'=>$req['message']
            ],
            'msgtype'=>'text'
        ];
        $re = $this->tools->curl_post($url,json_encode($data));
        $result = json_decode($re,1);
        dd($result);
    }
}
