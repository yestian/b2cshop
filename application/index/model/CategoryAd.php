<?php
namespace app\index\model;
use think\Model;

class CategoryAd extends Model{

//通过顶级分类ID获取关联广告
    public function getCategoryAds($id){
        $data=array();
        $res=db('CategoryAd')->where('category_id',$id)->select();
        foreach ($res as $k => $v) {
            //获取slider图片
            $data[$v['position']][]=$v;
        }
        return $data;
    }

}