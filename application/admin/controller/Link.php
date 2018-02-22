<?php
namespace app\admin\controller;
use think\Controller;

class Link extends Controller{

	public function lst(){
		$lst=db('link')->order('id desc')->paginate(30);
		$this->assign([
			'lst'=>$lst
		]);
		return view();
	}

	public function add(){
		if(request()->isPost()){
			$data=input('post.');
			//自动添加http
			if($data['url'] && strpos($data['url'],'http://')===false){
				$data['url']='http://'.$data['url'];
			}
			//上传图片处理
			if($_FILES['logo']['tmp_name']){
				$data['logo']=$this->upload('logo');
			}
			//验证
			$validate=validate('Link');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//插入
			$save=db('link')->insert($data);
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
		$res=db('link')->find($id);
		//更新
		if(request()->isPost()){
			$data=input('post.');
			//自动添加http
			if($data['url'] && strpos($data['url'],'http://')===false){
				$data['url']='http://'.$data['url'];
			}
			//上传图片处理
			if($_FILES['logo']['tmp_name']){
				//先删除旧图片
				$olds=db('link')->field('logo')->find($data['id']);
				$oldImg=IMG_UPLOADS.$olds['logo'];
				if(file_exists($oldImg)){
					@unlink($oldImg);//@可以忽略错误提示
				}
				//上传新图片
				$data['logo']=$this->upload('logo');
			}
			//验证
			$validate=validate('Link');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//更新
			$save=db('link')->update($data);
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
		$deldb=db('link');
		$img=$deldb->field('logo')->find($id);
		if($img['logo']){
			$oldImg=IMG_UPLOADS.$img['logo'];
			if(file_exists($oldImg)){
				@unlink($oldImg);
			}
			$del=$deldb->delete($id);
			if($del){
				$this->success('删除成功！','lst');
			}else{
				$this->error('删除失败！');
			}
		}
	}
	
	public function upload($imgname){

			// 获取表单上传文件 例如上传了001.jpg
			$file = request()->file($imgname);
					
			// 移动到框架应用根目录/public/uploads/ 目录下
			if($file){
				$info = $file->move(ROOT_PATH . 'public' . DS .'static'. DS . 'uploads');
				if($info){
					$str=$info->getSaveName();
					$str=str_replace('\\','/',$str);
					return $str;
				}else{
					// 上传失败获取错误信息
					echo $file->getError();
					die;
				}
			}
		
		
	}
}