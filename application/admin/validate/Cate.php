<?php
namespace app\admin\validate;
use think\Validate;

class Cate extends Validate{
    protected $rule=[
        'cate_name'=>'require|unique:cate|max:30',
    ];
    protected $message=[
        'cate_name.require'=>'品牌名称必须填写',
        'cate_name.max'=>'最大长度30个字符',
        'cate_name.unique'=>'分类名称不能重复',
    ];
}