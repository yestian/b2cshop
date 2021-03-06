<?php
namespace app\admin\controller;
use think\Controller;

class Brand extends Controller{

	public function lst(){
		$lst=db('brand')->order('id desc')->paginate(10);
		$this->assign([
			'lst'=>$lst
		]);
		return view();
	}

	public function add(){
		if(request()->isPost()){
			$data=input('post.');
			//自动添加http
			if($data['brand_url'] && strpos($data['brand_url'],'http://')===false){
				$data['brand_url']='http://'.$data['brand_url'];
			}
			//上传图片处理
			if($_FILES['brand_img']['tmp_name']){
				$data['brand_img']=$this->upload('brand_img');
			}
			//验证
			$validate=validate('Brand');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//插入
			$save=db('brand')->insert($data);
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
		$res=db('brand')->find($id);
		//更新
		if(request()->isPost()){
			$data=input('post.');
			//自动添加http
			if($data['brand_url'] && strpos($data['brand_url'],'http://')===false){
				$data['brand_url']='http://'.$data['brand_url'];
			}
			//上传图片处理
			if($_FILES['brand_img']['tmp_name']){
				//先删除旧图片
				$olds=db('brand')->field('brand_img')->find($data['id']);
				$oldImg=IMG_UPLOADS.$olds['brand_img'];
				if(file_exists($oldImg)){
					@unlink($oldImg);//@可以忽略错误提示
				}
				//上传新图片
				$data['brand_img']=$this->upload('brand_img');
			}
			//验证
			$validate=validate('Brand');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//更新
			$save=db('brand')->update($data);
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
		$db=db('brand');
		$img=$db->field('brand_img')->find($id);
		$img=IMG_UPLOADS.$img['brand_img'];
		if(file_exists($img)){
			@unlink($img);
		}
		$del=$db->delete($id);
		if($del){
			$this->success('删除成功！','lst');
		}else{
			$this->error('删除失败！');
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