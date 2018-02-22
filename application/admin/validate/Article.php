<?php
namespace app\admin\validate;
use think\Validate;

class Article extends Validate{
    protected $rule=[
        'title'=>'require|max:120|min:3',
        'cate_id'=>'require',
        'email'=>'email',
        'link_url'=>'url',
    ];
    protected $message=[
        'title.require'=>'文章名称必须填写',
        'title.max'=>'标题最大长度120个字符',
        'title.min'=>'标题最小长度3个字符',
        'cate_id.require'=>'必须选择栏目',
        'email.email'=>'邮件格式不正确',
        'link_url'=>'url格式不正确',
    ];
}