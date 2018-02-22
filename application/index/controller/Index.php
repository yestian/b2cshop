<?php
namespace app\index\controller;
use catetree\Catetree;
class Index extends Base
{
    public function index(){
        $hotGoods=model('Goods')->getRecGoods(2,5);//热卖商品
        //首页推荐栏目
        $caterec=model('category')->getRecCates(5,0);//推荐位ID，栏目pid
        foreach ($caterec as $k => $v) {
            $caterec[$k]['children']=model('category')->getRecCates(5,$v['id']);
            //获取首页指定顶级栏目下的新品推荐
            //1.获取顶级栏目下的所有子栏目ID
            $cateTree=new Catetree();
            $sonids=$cateTree->childrenids($v['id'],db('category'));//顶级栏目ID，数据库对象
            $sonids[]=$v['id'];//把顶级栏目id放入
            //获取推荐位里的商品
            $_rec_new_goods=db('recItem')->where(['value_type'=>1,'rec_id'=>8])->select();
            $rec_new_goods=array();
            foreach ($_rec_new_goods as $k1 => $v1) {
                $rec_new_goods[]=$v1['value_id'];
            }
            $map['category_id']=array('IN',$sonids);
            $map['id']=array('IN',$rec_new_goods);
            $caterec[$k]['newRecGoods']=db('goods')->where($map)->limit(6)->order('id DESC')->select();
        }

        $this->assign([
            'homepage'=>1,//控制首页的下拉菜单是否显示
            'hotGoods'=>$hotGoods,//推荐商品
            'cateRec'=>$caterec,//推荐栏目
        ]);
        return view();
    } 

}
