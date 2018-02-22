<?php
namespace app\admin\validate;
use think\Validate;

class CategoryWords extends Validate{
    protected $rule=[
        'category_id'=>'require',
        'word'=>'require|unique:categoryWords',
    ];
    protected $message=[
        'category_id.require'=>'栏目不能为空',
        'word.require'=>'关联词不能为空',
        'word.unique'=>'关联词不能重复',
    ];
}