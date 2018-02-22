<?php
namespace app\index\model;
use think\Model;

class Category extends Model{
    //获取顶级和二级商品分类
    public function getCates(){
        $cates=$this->where('pid',0)->select();
        foreach ($cates as $k => $v) {
            $cates[$k]['children']=$this->where('pid',$v['id'])->select();
        }
        return $cates;
    }
  //根据顶级分类ID，获取二级和三级分类
  public function getSonCategorys($id){
    $cateres=$this->where('pid',$id)->select();
    //循环二级分类，获取三级分类
    foreach ($cateres as $k => $v) {
        $cateres[$k]['children']=$this->where('pid',$v['id'])->select();//获取三级分类
    }
    return $cateres;
  }

    //通过顶级分类ID，获取关联词，展示在ajax展示的栏目的最上面
    public function getCategoryWords($id){
        $res=db('categoryWords')->where('category_id',$id)->select();
        return $res;
    }

    //通过顶级分类获取品牌列表及推广图
    public function getCategoryBrands($id){
        //将品牌和推广信息分开
        $data=array();
        $res=db('CategoryBrands')->where('category_id',$id)->find();
        $brandarr=explode(',',$res['brand_ids']);//id数组
        $db=db('brand');
        foreach ($brandarr as $k => $v) {
            $data['brand'][]=$db->find($v);//获取品牌表的信息
        }
        //推广信息
        $data['promotion']['img']=$res['pro_img'];
        $data['promotion']['url']=$res['pro_url'];
        return $data;
    }

    //获取推荐位下的分类
    public function getRecCates($rec_id,$pid=0){
        $_res=db('RecItem')->where(['rec_id'=>$rec_id,'value_type'=>2])->select();
        $res=array();
        foreach ($_res as $k => $v) {
            $cateres=db('category')->where(['id'=>$v['value_id'],'pid'=>$pid])->find();//获取对应的栏目信息
            //如果有值，才放入数组
            if($cateres){
                $res[]= $cateres;
            }
            
        }
        return $res;
    }
}