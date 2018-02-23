<?php
namespace app\admin\controller;
use think\Controller;
use catetree\Catetree;

class Slider extends Controller{

	public function lst(){
		//无限极分类
		$cate=new Catetree();
		$db=db('slider');
		//排序数据
		if(request()->isPost()){
			$data=input('post.');
			$res=$cate->cateSort($data['sort'],$db);
			$this->success('排序成功！','lst');
		}
		$lst=$db->order('sort desc')->paginate(10);
		$this->assign([
			'lst'=>$lst
		]);
		return view();
	}

	public function add(){
		if(request()->isPost()){
			$data=input('post.');
			//自动添加http
			if($data['slider_url'] && strpos($data['slider_url'],'http://')===false){
				$data['slider_url']='http://'.$data['slider_url'];
			}
			//上传图片处理
			if($_FILES['slider_img']['tmp_name']){
				$data['slider_img']=$this->upload('slider_img');
			}
			// //验证
			// $validate=validate('slider');
			// if(!$validate->check($data)){
			// 	$this->error($validate->getError());
			// }
			//插入
			$save=db('slider')->insert($data);
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
		$res=db('slider')->find($id);
		//更新
		if(request()->isPost()){
			$data=input('post.');
			//自动添加http
			if($data['slider_url'] && strpos($data['slider_url'],'http://')===false){
				$data['slider_url']='http://'.$data['slider_url'];
			}
			//上传图片处理
			if($_FILES['slider_img']['tmp_name']){
				//先删除旧图片
				$olds=db('slider')->field('slider_img')->find($data['id']);
				$oldImg=IMG_UPLOADS.$olds['slider_img'];
				if(file_exists($oldImg)){
					@unlink($oldImg);//@可以忽略错误提示
				}
				//上传新图片
				$data['slider_img']=$this->upload('slider_img');
			}
			//更新
			$save=db('slider')->update($data);
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
		//删除图片前，删除图片
		$db=db('slider');
		//同步删除图片
		$img=$db->field('slider_img')->find($id);
		$img=IMG_UPLOADS.$img['slider_img'];
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