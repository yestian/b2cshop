<?php
namespace app\admin\controller;
use think\Controller;

class CategoryBrands extends Controller{

	public function lst(){
		$lst=db('CategoryBrands a')
		->field('a.*,b.cate_name,group_concat(c.brand_name) as brand_name')
		->join('category b','a.category_id=b.id','left')
		->join('brand c','find_in_set(c.id,a.brand_ids)','left')
		->group('a.id')
		->order('id desc')->paginate(10);
		$this->assign([
			'lst'=>$lst
		]);
		return view();
	}

	public function add(){
		if(request()->isPost()){
			$data=input('post.');
			//自动添加http
			if($data['pro_url'] && strpos($data['pro_url'],'http://')===false){
				$data['pro_url']='http://'.$data['pro_url'];
			}
			//checkbox合并为字符串
			if(isset($data['brand_ids'])){
				$data['brand_ids']=implode(',',$data['brand_ids']);
			}
			//上传图片处理
			if($_FILES['pro_img']['tmp_name']){
				$data['pro_img']=$this->upload('pro_img');
			}
			//验证
			$validate=validate('CategoryBrands');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//插入
			$save=db('CategoryBrands')->insert($data);
			if($save){
				$this->success('添加成功！','lst');
			}else{
				$this->error('添加失败！');
			}
		}
		//顶级栏目列表
		$cates=db('category')->where('pid',0)->select();
		//品牌列表
		$brands=db('brand')->where('brand_img','neq','')->select();
		$this->assign([
			'cates'=>$cates,
			'brands'=>$brands,
		]);
		return view();
	}

	public function edit($id){
		//查找
		$res=db('CategoryBrands')->find($id);
		//更新
		if(request()->isPost()){
			$data=input('post.');
			//自动添加http
			if($data['pro_url'] && strpos($data['pro_url'],'http://')===false){
				$data['pro_url']='http://'.$data['pro_url'];
			}
			//checkbox合并为字符串
			if(isset($data['brand_ids'])){
				$data['brand_ids']=implode(',',$data['brand_ids']);
			}else{
				$data['brand_ids']='';
			}
			//验证
			$validate=validate('CategoryBrands');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//上传图片处理
			if($_FILES['pro_img']['tmp_name']){
				//先删除旧图片
				$olds=db('CategoryBrands')->field('pro_img')->find($data['id']);
				$oldImg=IMG_UPLOADS.$olds['pro_img'];
				if(file_exists($oldImg)){
					@unlink($oldImg);
				}
				//上传新图片
				$data['pro_img']=$this->upload('pro_img');
			}
			//更新
			$save=db('CategoryBrands')->update($data);
			if($save!==false){
				$this->success('编辑成功！','lst');
			}else{
				$this->error('编辑失败！');
			}
		}

		//顶级栏目列表
		$cates=db('category')->where('pid',0)->select();
		//品牌列表
		$brands=db('brand')->where('brand_img','neq','')->select();
		$this->assign([
			'cates'=>$cates,
			'brands'=>$brands,
			'res'=>$res,
		]);
		return view();
	}
	
	public function del($id){
		$deldb=db('CategoryBrands');
		$img=$deldb->field('pro_img')->find($id);
		if($img['pro_img']){
			$oldImg=IMG_UPLOADS.$img['pro_img'];
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