<?php
namespace app\index\controller;

class Article extends Base
{
    public function index($id){

        //当前文章内容
        $res=db('article')->find($id);
        //获取面包屑导航
        $position=model('cate')->getBread($res['cate_id']);
        $this->assign([
            'res'=>$res,
            'position'=>$position,
        ]);
        return view();
    }
}
