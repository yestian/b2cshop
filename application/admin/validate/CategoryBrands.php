<?php
namespace app\admin\validate;
use think\Validate;

class CategoryBrands extends Validate{
    protected $rule=[
        'category_id'=>'require|unique:CategoryBrands',
    ];
    protected $message=[
        'category_id.require'=>'栏目不能为空',
        'category_id.unique'=>'栏目不能重复',
    ];
}