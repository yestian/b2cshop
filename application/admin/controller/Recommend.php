<?php
namespace app\admin\controller;
use think\Controller;

class Recommend extends Controller{

	public function lst(){
		$lst=db('recommend')->paginate(10);
		$this->assign([
			'lst'=>$lst,
		]);
		return view();
	}

	public function add(){
		if(request()->isPost()){
			$data=input('post.');
			//验证
			$validate=validate('Recommend');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//插入
			$save=db('recommend')->insert($data);
			if($save){
				//添加成功的时候，跳转到对应的列表
				$this->success('添加成功！','lst');
			}else{
				$this->error('添加失败！');
			}
		}
		return view();
	}

	public function edit($id){
		$db=db('recommend');
		//查找
		$res=$db->find($id);
		//更新
		if(request()->isPost()){
			$data=input('post.');
			//验证
			$validate=validate('Recommend');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//更新
			$save=$db->update($data);
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
		$del=db('recommend')->delete($id);
		if($del){
			$this->success('删除成功！','lst');
		}else{
			$this->error('删除失败！');
		}

	}


}