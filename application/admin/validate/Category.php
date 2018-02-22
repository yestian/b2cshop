<?php
namespace app\admin\validate;
use think\Validate;

class Category extends Validate{
    protected $rule=[
        'cate_name'=>'require|unique:category|max:30',
    ];
    protected $message=[
        'cate_name.require'=>'分类名不能为空',
        'cate_name.max'=>'最大长度30个字符',
        'cate_name.unique'=>'分类名称不能重复',
    ];
}