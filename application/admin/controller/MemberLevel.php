<?php
namespace app\admin\controller;
use think\Controller;

class MemberLevel extends Controller{

	public function lst(){
		$lst=db('memberLevel')->order('id desc')->paginate(10);
		$this->assign([
			'lst'=>$lst
		]);
		return view();
	}

	public function add(){
		if(request()->isPost()){
			$data=input('post.');
			//验证
			$validate=validate('memberLevel');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//插入
			$save=db('memberLevel')->insert($data);
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
		$res=db('memberLevel')->find($id);
		//更新
		if(request()->isPost()){
			$data=input('post.');
			//验证
			$validate=validate('memberLevel');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//更新
			$save=db('memberLevel')->update($data);
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
		
		$del=db('memberLevel')->delete($id);
		if($del){
			$this->success('删除成功！','lst');
		}else{
			$this->error('删除失败！');
		}

	}

}