<?php
namespace app\index\controller;
use catetree\Catetree;

class Cate extends Base
{
    public function index($id){
        $cate=db('cate');
        //获取当前栏目以及子栏目的id
        $tree=new Catetree;
        $ids=$tree->childrenids($id,$cate);
        $ids[]=$id;//把当前ID放进去
        $map['cate_id']=array('in',$ids);
        $articles=db('article')->where($map)->select();//根据id获取文章列表
        $cateres=$cate->find($id);//获取当前栏目信息

        $this->assign([
            'articles'=>$articles,
            'cateres'=>$cateres,
        ]);
        return view();
    }
}
