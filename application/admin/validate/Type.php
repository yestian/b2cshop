<?php
namespace app\admin\validate;
use think\Validate;

class Type extends Validate{
    protected $rule=[
        'type_name'=>'require|unique:type',
    ];
    protected $message=[
        'type_name.require'=>'类型名不能为空',
    ];
}