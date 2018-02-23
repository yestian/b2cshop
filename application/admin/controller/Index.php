<?php
namespace app\admin\controller;
use think\Controller;

class Index extends Controller{

	public function index(){
		
		return view();
	}


	/**
	 * 清空后台缓存
	 */
	public function clearCache(){
		if(cache(null)){
			$this->success('清除缓存成功！');
		}
	}
}