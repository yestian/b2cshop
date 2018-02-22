<?php
namespace app\admin\validate;
use think\Validate;

class Conf extends Validate{
    protected $rule=[
        'enname'=>'require|unique:conf',
        'cnname'=>'require|unique:conf',
        'form_type'=>'require'
    ];
    protected $message=[
        'enname.require'=>'英文名不能为空',
        'enname.unique'=>'英文名不能重复',
        'cnname.require'=>'中文名不能为空',
        'cnname.require'=>'中文名不能重复',
        'form_type.require'=>'表单类型不能为空',
    ];
}