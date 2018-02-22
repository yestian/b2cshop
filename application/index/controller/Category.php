<?php
namespace app\index\controller;
use catetree\Catetree;

class Category extends Base
{
    public function index($id){
        return view();
    }
    /**
     * 根据栏目ID获取子栏目及内容
     */
    public function ajax_cate_info($id){
        $cateres=model('Category')->getSonCategorys($id);//获取模型中的方法，二三级栏目
        $channelres=model('category')->getCategoryWords($id);//获取顶级分类的推荐词
        $brandsres=model('category')->getCategoryBrands($id);//获取品牌及推广信息
        $data=array();
         //栏目数据
       $cates='';
       //循环获取二级
       foreach ($cateres as $k => $v) {
           $cates.='<dl class="dl_fore'.$k.'"><dt><a href="'.url('category/index',['id'=>$v['id']]).'" target="_blank">'.$v['cate_name'].'</a> </dt><dd>';
          //循环获取三级
           foreach ($v['children'] as $k1 => $v1) {
              $cates.='<a href="'.url('category/index',['id'=>$v1['id']]).'" target="_blank">'.$v1['cate_name'].'</a>';
          }
           $cates.='</dd></dl>';
       }
        $cates.='<div class="item-brands"><ul></ul></div><div class="item-promotions"></div>';


     //channel数据
     $channel='';
     foreach ($channelres as $k => $v) {
        $channel.='<a href="'.$v['link_url'].'" target="_blank">'.$v['word'].'</a>';
     }
    //品牌图片数据
    $brands='';
    $brands.='<div class="cate-layer-rihgt" ectype="brands_858"><div class="cate-brand">';
    foreach ($brandsres['brand'] as $k => $v) {
        if($v['brand_img']){
            $brands.='<div class="img"><a href="#" target="_blank" title="'.$v['brand_name'].'">
            <img src="'.config('view_replace_str.__uploads__').'/'.$v['brand_img'].'"></a>
            </div>';
        }
    }
    $brands.='</div>';
    if($brandsres['promotion']['img']){
        $brands.='<div class="cate-promotion">
        <a href="'.$brandsres['promotion']['url'].'"  target="_blank">
        <img width="199" height="97" src="'.config('view_replace_str.__uploads__').'/'.$brandsres['promotion']['img'].'">
        </a></div></div>';
    }

        $data['topic_content']=$channel;
        $data['cat_content']=$cates;
        $data['brands_ad_content']=$brands;
        $data['cat_id']=$id;
        return json($data);
    }
}
