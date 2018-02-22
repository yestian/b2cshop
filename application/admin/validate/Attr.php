<?php
namespace app\admin\validate;
use think\Validate;

class Attr extends Validate{
    protected $rule=[
        'type_id'=>'require',
        'attr_name'=>'require',
    ];
    protected $message=[
        'type_id.require'=>'请选择属性类型',
        'attr_name.require'=>'属性名不能为空',
    ];
}