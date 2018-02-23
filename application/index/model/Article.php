<?php
namespace app\index\model;
use think\Model;

class Article extends Model{
    public function getFootArts(){
        $res=model('cate')->where('cate_type',3)->order('sort DESC')->select();
        foreach ($res as $k => $v) {
            $res[$k]['arts']=$this->where('cate_id',$v['id'])->select();
        }
        return $res;
    }

    //获取文章
    public function getArts($cateid,$limit=''){
        $res=$this->where('cate_id',$cateid)->order('id desc')->limit($limit)->select();
        return $res;
    }
}