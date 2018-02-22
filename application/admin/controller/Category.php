<?php
namespace app\admin\controller;
use think\Controller;
use catetree\Catetree;

class Category extends Controller{

	public function lst(){
		
		//无限极分类
		$cate=new Catetree();
		$cateObj=db('category');
		//排序数据
		if(request()->isPost()){
			$data=input('post.');
			$res=$cate->cateSort($data['sort'],$cateObj);
			$this->success('排序成功！','lst');
		}
		//无限极分类
		$lst=$cateObj->order('sort desc')->select();//同级栏目降序排序
		$lst=$cate->catetree($lst);
		$this->assign([
			'lst'=>$lst
		]);
		return view();
	}

	public function add(){
		if(request()->isPost()){
			$data=input('post.');
			//上传图片处理
			if($_FILES['thumb']['tmp_name']){
				$data['thumb']=$this->upload('thumb');
			}
			if($_FILES['banner']['tmp_name']){
				$data['banner']=$this->upload('banner');
			}
			//验证
			$validate=validate('Category');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			
			//插入
			$save=model('Category')->save($data);
			if($save){
				$this->success('添加成功！','lst');
			}else{
				$this->error('添加失败！');
			}
		}
		//无限级分类方式显示（产生level字段）
		$cateres=db('category')->select();
		$cate=new Catetree();
		$cateres=$cate->catetree($cateres);
		//商品推荐位
		$rec=db('recommend')->where('rec_type',2)->select();
		$this->assign([
			'cateres'=>$cateres,
			'rec'=>$rec,//推荐位
		]);
		return view();
	}

	public function edit($id){
		$db=model('Category');
		//更新
		if(request()->isPost()){
			$data=input('post.');
			//上传图片处理
			if($_FILES['thumb']['tmp_name']){
				//先删除旧图片
				$olds=db('category')->field('thumb')->find($data['id']);
				$oldImg=IMG_UPLOADS.$olds['thumb'];
				if(file_exists($oldImg)){
					@unlink($oldImg);//@可以忽略错误提示
				}
				//上传新图片
				$data['thumb']=$this->upload('thumb');
			}
			if($_FILES['banner']['tmp_name']){
				//先删除旧图片
				$olds=db('category')->field('banner')->find($data['id']);
				$oldImg=IMG_UPLOADS.$olds['banner'];
				if(file_exists($oldImg)){
					@unlink($oldImg);//@可以忽略错误提示
				}
				//上传新图片
				$data['banner']=$this->upload('banner');
			}
			//验证
			$validate=validate('Category');
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

		//查找
		$res=$db->find($id);
		//商品推荐位
		$rec=db('recommend')->where('rec_type',2)->select();
		//商品推荐位结果表
		$_recres=db('rec_item')->where(['value_id'=>$id,'value_type'=>2])->select();
		//重组推荐位结果
		$recarr=array();
		foreach ($_recres as $k => $v) {
			$recarr[]=$v['rec_id'];
		}
		//无限级分类方式显示（产生level字段）
		$cateres=$db->select();
		$cate=new Catetree();
		$cateres=$cate->catetree($cateres);
		$this->assign([
			'cateres'=>$cateres,
			'res'=>$res,
			'rec'=>$rec,//推荐位
			'recres'=>$recarr,//推荐结果
		]);
		return view();
	}
	
	public function del($id){
		//删除分类的时候，删除下级分类
		//1.获取到所有子栏目ID
		$cate=db('category');
		$cateTree=new Catetree();
		$sonids=$cateTree->childrenids($id,$cate);
		
		$sonids[]=intval($id);//同时把主栏目ID也放进去

		//删除栏目前，先删除栏目下的所有文章和缩略图
		$article=db('article');
		//循环得到每个栏目ID
		foreach($sonids as $k=>$v){
			//先删除栏目缩略图,（banner是高清大图，就留在硬盘，备用）
			$cateImg=$cate->field('thumb')->find($v);
			$cateImg=IMG_UPLOADS.$cateImg['thumb'];
			if(file_exists($cateImg)){
				@unlink($cateImg);
			}
			//再删除文章缩略图
			$artRes=$article->field('id,thumb')->where('cate_id',$v)->select();//得到在sonids所有栏目下的所有文章
			//循环获得每篇文章下的缩略图
			foreach($artRes as $k1=>$v1){
				$thumbSrc=IMG_UPLOADS.$v1['thumb'];
				if(file_exists($thumbSrc)){
					@unlink($thumbSrc);//删除图片
				}
				$article->delete($v1['id']);//删除文章
			}
		}

		$del=$cate->delete($sonids);//批量删除栏目
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