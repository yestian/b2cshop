<?php
namespace app\admin\validate;
use think\Validate;

class Link extends Validate{
    protected $rule=[
        'title'=>'require',
        'url'=>'url',
    ];
    protected $message=[
        'title.require'=>'标题必须填写',
        'url.url'=>'url格式不正确',
    ];
}