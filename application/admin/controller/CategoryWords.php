<?php
namespace app\admin\controller;
use think\Controller;
use catetree\Catetree;

class CategoryWords extends Controller{

	public function lst(){
		$lst=db('categoryWords a')
		->field('a.*,b.cate_name')
		->join('category b','a.category_id=b.id','left')
		->order('sort desc')->paginate(10);
		$cate=new Catetree();
		$cateObj=db('categoryWords');
		//排序数据
		if(request()->isPost()){
			$data=input('post.');
			$res=$cate->cateSort($data['sort'],$cateObj);
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
			if($data['link_url'] && strpos($data['link_url'],'http://')===false){
				$data['link_url']='http://'.$data['link_url'];
			}
			//验证
			$validate=validate('CategoryWords');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//插入
			$save=db('categoryWords')->insert($data);
			if($save){
				$this->success('添加成功！','lst');
			}else{
				$this->error('添加失败！');
			}
		}
		//获取顶级栏目
		$cates=db('category')->where('pid',0)->select();
		$this->assign([
			'cates'=>$cates,
		]);
		return view();
	}

	public function edit($id){
		//查找
		$res=db('categoryWords')->find($id);
		//更新
		if(request()->isPost()){
			$data=input('post.');
			//自动添加http
			if($data['link_url'] && strpos($data['link_url'],'http://')===false){
				$data['link_url']='http://'.$data['link_url'];
			}
			//验证
			$validate=validate('CategoryWords');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//更新
			$save=db('categoryWords')->update($data);
			if($save!==false){
				$this->success('编辑成功！','lst');
			}else{
				$this->error('编辑失败！');
			}
		}
		//获取顶级栏目
		$cates=db('category')->where('pid',0)->select();
		$this->assign([
			'cates'=>$cates,
			'res'=>$res,
		]);
		return view();
	}
	
	public function del($id){
		$deldb=db('categoryWords');

			$del=$deldb->delete($id);
			if($del){
				$this->success('删除成功！','lst');
			}else{
				$this->error('删除失败！');
			}
	}
}