<?php
namespace app\index\model;
use think\Model;
use catetree\Catetree;

class Goods extends Model{

    /**
     * 获取推荐位的商品
     * rec_id：商品推荐位ID
     * limit:获取的条数
     */
    public function getRecGoods($rec_id,$limit=''){
        $_hotgoodsId=db('rec_item')->where(['value_type'=>1,'rec_id'=>$rec_id])->select();
        $hotgoodsId=array();
        foreach ($_hotgoodsId as $k => $v) {
            $hotgoodsId[]=$v['value_id'];
        }
        $map['id']=array('IN',$hotgoodsId);
        $hotgoods=db('goods')->field('id,goods_name,md_thumb,shop_price')->where($map)->limit($limit)->select();
        return $hotgoods;
    }

    //根据推荐商品类型，以及指定的栏目，获取栏目下的推荐商品，首页各个推荐分类下的推荐商品
    public function getRecIndexGoods($rec_id,$cateid){
        //1.获取顶级栏目下的所有子栏目ID
        $cateTree=new Catetree();
        $sonids=$cateTree->childrenids($cateid,db('category'));//顶级栏目ID，数据库对象
        $sonids[]=$cateid;//把顶级栏目id放入
        //获取推荐位里的商品
        $_rec_goods=db('recItem')->where(['value_type'=>1,'rec_id'=>$rec_id])->select();
        $rec_goods=array();
        foreach ($_rec_goods as $k1 => $v1) {
            $rec_goods[]=$v1['value_id'];
        }
        $map['category_id']=array('IN',$sonids);
        $map['id']=array('IN',$rec_goods);
        $res=db('goods')->where($map)->limit(6)->order('id DESC')->select();
        return $res;
    }
}