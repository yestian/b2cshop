<?php
namespace app\index\controller;
use catetree\Catetree;
class Index extends Base
{
    public function index(){
        //调用公告及促销文章
        $ans=model('article')->getArts(33,3);
        //促销
        $promote=model('article')->getArts(35,3);
        //获取指定推荐位的商品
        $recgoods=model('goods')->getRecGoods(10,10);
        //首页推荐栏目，才展示在首页的各个大的分类下面
        $caterec=model('category')->getRecCates(5,0);//推荐位ID，栏目pid
        //循环出每一个大分类
        foreach ($caterec as $k => $v) {
            //根据大分类ID，以及设为了首页推荐的栏目
            $caterec[$k]['children']=model('Category')->getRecCates(5,$v['id']);
            //对推荐的栏目循环，得到推荐的栏目下面的推荐商品（推荐商品指定的推荐ID为9）
            foreach ($caterec[$k]['children'] as $k1 => $v1) {
                $caterec[$k]['children'][$k1]['bestgoods']=model('Goods')->getRecIndexGoods(9,$v1['id']);
            }
            //获取首页大模块下的新品推荐
            $caterec[$k]['newRecGoods']=model('Goods')->getRecIndexGoods(8,$v['id']);
            //获取每个顶级分类下面的品牌列表，放在商品列表的最下面
            $caterec[$k]['brands']=model('Category')->getCategoryBrands($v['id']);
            //获取关联广告图片
            $caterec[$k]['leftImgs']=model('CategoryAd')->getCategoryAds($v['id']);
        }
        // dump($caterec);die;
        $this->assign([
            'homepage'=>1,//控制首页的下拉菜单是否显示
            'cateRec'=>$caterec,//推荐栏目
            'recgoods'=>$recgoods,//首页底部推荐商品
            'ans'=>$ans,//公告
            'promote'=>$promote,//促销
        ]);
        return view();
    } 

}
