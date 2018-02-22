<?php
namespace app\admin\controller;
use think\Controller;
use catetree\Catetree;

class Nav extends Controller{

	public function lst(){
		$db=db('nav');
		$lst=$db->order('sort desc')->paginate(10);

		//排序数据
		if(request()->isPost()){
			$cate=new Catetree();
			$data=input('post.');
			$res=$cate->cateSort($data['sort'],$db);
			$this->success('排序成功！','lst');
		}
		$this->assign([
			'lst'=>$lst
		]);
		return view();
	}

	public function add(){
		if(request()->isPost()){
			$data=input('post.');
			//自动添加http
			if($data['nav_url'] && strpos($data['nav_url'],'http://')===false){
				$data['nav_url']='http://'.$data['nav_url'];
			}
			//插入
			$save=db('nav')->insert($data);
			if($save){
				$this->success('添加成功！','lst');
			}else{
				$this->error('添加失败！');
			}
		}
		return view();
	}

	public function edit($id){
		//查找
		$res=db('nav')->find($id);
		//更新
		if(request()->isPost()){
			$data=input('post.');
			//自动添加http
			if($data['nav_url'] && strpos($data['nav_url'],'http://')===false){
				$data['nav_url']='http://'.$data['nav_url'];
			}
			//更新
			$save=db('nav')->update($data);
			if($save!==false){
				$this->success('编辑成功！','lst');
			}else{
				$this->error('编辑失败！');
			}
		}
		$this->assign([
			'res'=>$res,
		]);
		return view();
	}
	
	public function del($id){
		$del=db('nav')->delete($id);
		if($del){
			$this->success('删除成功！','lst');
		}else{
			$this->error('删除失败！');
		}

	}
}