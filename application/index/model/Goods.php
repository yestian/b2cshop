<?php
namespace app\index\model;
use think\Model;

class Goods extends Model{

    /**
     * 获取推荐位的商品
     * rec_id：推荐位ID
     * limit:获取的条数
     */
    public function getRecGoods($rec_id,$limit=''){
        $_hotgoodsId=db('rec_item')->where(['value_type'=>1,'rec_id'=>$rec_id])->select();
        $hotgoodsId=array();
        foreach ($_hotgoodsId as $k => $v) {
            $hotgoodsId[]=$v['value_id'];
        }
        $map['id']=array('IN',$hotgoodsId);
        $hotgoods=db('goods')->field('id,goods_name,md_thumb')->where($map)->limit($limit)->select();
        return $hotgoods;
    }
}