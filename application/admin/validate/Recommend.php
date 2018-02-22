<?php
namespace app\admin\validate;
use think\Validate;

class Recommend extends Validate{
    protected $rule=[
        'rec_name'=>'require',
    ];
    protected $message=[
        'rec_name.require'=>'名称不能为空',
    ];
}