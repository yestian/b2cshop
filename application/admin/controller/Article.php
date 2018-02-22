<?php
namespace app\admin\controller;
use think\Controller;
use catetree\Catetree;

class Article extends Controller{

	public function lst(){
		$lst=db('article a')->field('a.*,b.cate_name')->join('cate b','a.cate_id=b.id','left')->order('id desc')->paginate(10);
		$this->assign([
			'lst'=>$lst
		]);
		return view();
	}

	public function add(){
		if(request()->isPost()){
			$data=input('post.');
			$data['addtime']=time();
			//自动添加http
			if($data['link_url'] && strpos($data['link_url'],'http://')===false){
				$data['link_url']='http://'.$data['link_url'];
			}
			//上传图片处理
			if($_FILES['thumb']['tmp_name']){
				$data['thumb']=$this->upload('thumb');
			}
			//验证
			$validate=validate('Article');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//插入
			$save=db('article')->insert($data);
			if($save){
				$this->success('添加成功！','lst');
			}else{
				$this->error('添加失败！');
			}
		}
		//查询
		//无限级分类方式显示（产生level字段）
		$cateres=db('cate')->select();
		$cate=new Catetree();
		$cateres=$cate->catetree($cateres);
		$this->assign([
			'cateres'=>$cateres,
		]);
		return view();
	}

	public function edit($id){
		
		//更新
		if(request()->isPost()){
			$data=input('post.');
			//自动添加http
			if($data['link_url'] && strpos($data['link_url'],'http://')===false){
				$data['link_url']='http://'.$data['link_url'];
			}
			//上传图片处理
			if($_FILES['thumb']['tmp_name']){
				//先删除旧图片
				$olds=db('article')->field('thumb')->find($data['id']);
				$oldImg=IMG_UPLOADS.$olds['thumb'];
				if(file_exists($oldImg)){
					@unlink($oldImg);//@可以忽略错误提示
				}
				//上传新图片
				$data['thumb']=$this->upload('thumb');
			}
			//验证
			$validate=validate('Article');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//更新
			$save=db('article')->update($data);
			if($save!==false){
				$this->success('编辑成功！','lst');
			}else{
				$this->error('编辑失败！');
			}
		}
		//无限级分类方式显示（产生level字段）
		$cateres=db('cate')->select();
		$cate=new Catetree();
		$cateres=$cate->catetree($cateres);
		//查找
		$this->assign([
			'cateres'=>$cateres,
			'res'=>db('article')->find($id)
		]);
		return view();
	}
	
	public function del($id){
		$art=db('article');
		//同步删除图片
		$img=$art->field('thumb')->find($id);
		$img=IMG_UPLOADS.$img['thumb'];
		if(file_exists($img)){
			@unlink($img);
		}
		$del=$art->delete($id);
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
	//ueditor产生的图片管理
	public function imglist(){
		$files=my_scandir();
		$imgs=array();
		foreach($files as $v){
			if(is_array($v)){
				//有几层目录（几维数组），写几次循环
				foreach($v as $v1){
					//赋值之前，把物理路径替换为网站路径
					$v1=str_replace(UEDITOR,HTTP_UEDITOR,$v1);
					$imgs[]=$v1;
				}
			}else{
				$v=str_replace(UEDITOR,HTTP_UEDITOR,$v);
				$imgs[]=$v;
			}
		}
		$this->assign([
			'lst'=>$imgs,
		]);
		return view();
	}

/**
 * 删除图片，ueditor产生的图片
 */
	public function delimg(){
		if(request()->isAjax()){
			$img=input('imgSrc');
			$img=DEL_UEDITOR.$img;
			if(file_exists($img)){
				if(@unlink($img)){
					return 1;
				}else{
					return 2;
				}
			}else{
				return 3;
			}
		}
	}
}