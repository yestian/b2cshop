<?php
namespace app\index\controller;
use think\Controller;

class Brand extends Controller{

	public function ajax_brand_lst(){
		$lst=db('brand')->where('brand_img','neq','')->paginate(17);
		$data['totalpage']=$lst->lastPage();//总页数
		$brands=$lst->items();
		$html='';
		$html.='<ul>';
		foreach ($brands as $k => $v) {
			$html.='<li><div class="brand-img"><a href="'.$v['brand_url'].'" target="_blank"><img src="'.config('view_replace_str.__uploads__').'/'.$v['brand_img'].'"></a></div>
			<div class="brand-mash"></div></li>';
		}
		$html.='</ul><input type="hidden" name="user_id" value="0"><a href="javascript:void(0);" ectype="changeBrand" class="refresh-btn"><i class="iconfont icon-rotate-alt"></i><span>换一批</span></a>';
		$data['brands']=$html;
		return json($data);
	}
}