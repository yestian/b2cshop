<?php
namespace app\admin\validate;
use think\Validate;

class MemberLevel extends Validate{
    protected $rule=[
        'level_name'=>'require|unique:memberLevel',
        'bot_point'=>'require',
        'top_point'=>'require',
    ];
    protected $message=[
        'level_name.require'=>'分类名不能为空',
        'level_name.unique'=>'分类名不能重复',
        'bot_point'=>'积分下限不能为空',
        'top_point'=>'积分上限不能为空',
    ];
}