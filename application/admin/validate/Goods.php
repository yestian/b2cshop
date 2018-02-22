<?php
namespace app\admin\validate;
use think\Validate;

class Goods extends Validate{
    protected $rule=[
        'goods_name'=>'require',
        'category_id'=>'require',
        'market_price'=>'require',
        'shop_price'=>'require',
    ];
    protected $message=[
        'goods_name.require'=>'名称不能为空',
        'category_id.require'=>'分类不能为空',
        'market_price.require'=>'市场价格不能为空',
        'shop_price.require'=>'商品价格不能为空',
    ];
}