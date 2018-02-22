<?php
namespace app\admin\validate;
use think\Validate;

class Brand extends Validate{
    protected $rule=[
        'brand_name'=>'require|unique:brand',
        'brand_url'=>'url',
        'brand_desc'=>'min:6'
    ];
    protected $message=[
        'brand_name.require'=>'品牌名称必须填写',
        'brand_name.require'=>'品牌名称唯一',
        'brand_url.url'=>'url格式不正确',
        'brand_desc.min'=>'最小6个字符',
    ];
}