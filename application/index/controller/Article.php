<?php
namespace app\index\controller;

class Article extends Base
{
    public function index($id){

        //当前文章内容
        $cacheName=$id.'_article';//缓存动态数据
        if(cache($cacheName)){
            $res=cache($cacheName);
        }else{
            $res=db('article')->find($id);
            if($this->config['cache']=='是'){
            cache($cacheName,$res,$this->config['cachetime']);
            }
        }
       
        //获取面包屑导航
        $position=model('cate')->getBread($res['cate_id']);
        $this->assign([
            'res'=>$res,
            'position'=>$position,
        ]);
        return view();
    }
}
