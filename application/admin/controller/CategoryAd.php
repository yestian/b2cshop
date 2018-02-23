<?php
namespace app\admin\controller;
use think\Controller;

class CategoryAd extends Controller{

	public function lst(){
		$lst=db('CategoryAd a')->field('a.*,b.cate_name')->join('category b','a.category_id=b.id','left')->paginate(10);
		$this->assign([
			'lst'=>$lst
		]);
		return view();
	}

	public function add(){
		if(request()->isPost()){
			$data=input('post.');
			//自动添加http
			if($data['img_url'] && strpos($data['img_url'],'http://')===false){
				$data['img_url']='http://'.$data['img_url'];
			}
			//上传图片处理
			if($_FILES['img_src']['tmp_name']){
				$data['img_src']=$this->upload('img_src');
			}
			//验证
			$validate=validate('CategoryAd');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//插入
			$save=db('CategoryAd')->insert($data);
			if($save){
				$this->success('添加成功！','lst');
			}else{
				$this->error('添加失败！');
			}
		}
		//顶级栏目列表
		$cates=db('category')->where('pid',0)->select();
		$this->assign([
			'cates'=>$cates,
		]);
		return view();
	}

	public function edit($id){
		//更新
		if(request()->isPost()){
			$data=input('post.');
			//自动添加http
			if($data['img_url'] && strpos($data['img_url'],'http://')===false){
				$data['img_url']='http://'.$data['img_url'];
			}
			//验证
			$validate=validate('CategoryAd');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//上传图片处理
			if($_FILES['img_src']['tmp_name']){
				//先删除旧图片
				$olds=db('CategoryAd')->field('img_src')->find($data['id']);
				$oldImg=IMG_UPLOADS.$olds['img_src'];
				if(file_exists($oldImg)){
					@unlink($oldImg);
				}
				//上传新图片
				$data['img_src']=$this->upload('img_src');
			}
			//更新
			$save=db('CategoryAd')->update($data);
			if($save!==false){
				$this->success('编辑成功！','lst');
			}else{
				$this->error('编辑失败！');
			}
		}

		//顶级栏目列表
		$cates=db('category')->where('pid',0)->select();
		//查找
		$res=db('CategoryAd')->find($id);
		$this->assign([
			'cates'=>$cates,
			'res'=>$res,
		]);
		return view();
	}
	
	public function del($id){
		$deldb=db('CategoryAd');
		$img=$deldb->field('img_src')->find($id);
		if($img['img_src']){
			$oldImg=IMG_UPLOADS.$img['img_src'];
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