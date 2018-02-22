<?php
namespace app\admin\controller;
use think\Controller;

class Type extends Controller{

	public function lst(){
		$lst=db('type')->order('id desc')->paginate(10);
		$this->assign([
			'lst'=>$lst
		]);
		return view();
	}

	public function add(){
		if(request()->isPost()){
			$data=input('post.');
			//验证
			$validate=validate('type');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//插入
			$save=db('type')->insert($data);
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
		$res=db('type')->find($id);
		//更新
		if(request()->isPost()){
			$data=input('post.');
			//验证
			$validate=validate('type');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//更新
			$save=db('type')->update($data);
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
		
		$del=db('type')->delete($id);
		//删除类型的同时，删除类型下的属性attr表，类型相当于文字栏目，属性相当于栏目下的文章
		db('attr')->where('type_id',$id)->delete();
		if($del){
			$this->success('删除成功！','lst');
		}else{
			$this->error('删除失败！');
		}

	}

}