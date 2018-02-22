<?php
namespace app\index\controller;
use think\Controller;

class Base extends Controller{
    public $config;//在控制器中也可以使用config配置内容

    public function _initialize(){
        $this->_getFooter();//底部栏目和文章
        $this->_bottomArts();//底部文字
        $this->_getComCates();//普通栏目
        $this->_getNav();//导航
        $this->_getConf();//配置信息
        $this->_getCategorys();//商品分类
    }

    //获取商品栏目一二级分类
    private function _getCategorys(){
        $res=model('Category')->getCates();
        $this->assign([
            'categorys'=>$res,
        ]);
    }

    //普通栏目分类, //网店帮助分类
    private function _getComCates(){
        $comcates=model('Cate')->getComCates();//普通栏目分类
        $helpCates=model('Cate')->helpCates();//网店帮助分类
        $this->assign([
            'comcates'=>$comcates,
            'helpCates'=>$helpCates,
        ]);
    }

    //使用article模型中的方法
    private function _getFooter(){
        $res=model('article')->getFootArts();
        $this->assign([
            'footerList'=>$res,
        ]);
    }
    //footer底部的文章链接
    private function _bottomArts(){
        $res=db('article')->where('cate_id',3)->select();
        $this->assign([
            'botArticle'=>$res,
        ]);
    }
    //获取导航
    private function _getNav(){
        //nav没有model，就直接使用db方法
        $_res=db('nav')->select();
        $res=array();
        foreach ($_res as $k => $v) {
            $res[$v['pos']][]=$v;
        }
        $this->assign([
            'navs'=>$res,
        ]);
    }
    //获取配置项
    private function _getConf(){
        $conf=model('Conf')->getConfs();
        $this->config=$conf;//传递给config参数，控制器只要继承了Base也可以使用（$this->config);
        $this->assign([
            'config'=>$conf,
        ]);
    }
}