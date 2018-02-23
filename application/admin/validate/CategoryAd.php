<?php
namespace app\admin\validate;
use think\Validate;

class CategoryAd extends Validate{
    protected $rule=[
        'category_id'=>'require',
    ];
    protected $message=[
        'category_id.require'=>'栏目不能为空',
    ];
}