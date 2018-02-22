<?php
namespace app\index\controller;

class Index extends Base
{
    public function index(){
        $hotGoods=model('Goods')->getRecGoods(2,5);//热卖商品

        $this->assign([
            'homepage'=>1,//控制首页的下拉菜单是否显示
            'hotGoods'=>$hotGoods,//推荐商品
        ]);
        return view();
    } 

}
