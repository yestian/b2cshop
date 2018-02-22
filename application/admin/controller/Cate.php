<?php
namespace app\admin\controller;
use think\Controller;
use catetree\Catetree;

class Cate extends Controller{

	public function lst(){
		
		//无限极分类
		$cate=new Catetree();
		$cateObj=db('cate');
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
			//cate_type:1.系统分类，2.帮助分类，3.网店帮助，4.网店信息，5.普通分类
			//id为1,3禁止添加下级分类，pid为2的禁止添加子栏目
			//判断是否可以添加子栏目
			if(in_array($data['pid'],['1','3'])){
				$this->error('此分类不能作为上级分类！');
			}
			//选中的上级栏目的上级栏目不能是2
			$cateId=db('cate')->field('pid')->find($data['pid']);
			$cateId=$cateId['pid'];
			if($cateId==2){
				$this->error('此分类不能作为上级分类！');
			}
			//如果上级栏目是2（网店帮助），强制设置为网店信息类型
			if($data['pid']==2){
				$data['cate_type']=3;
			}
			//如果上级栏目不是普通栏目，添加成功后，禁止创建子栏目
			if($data['pid']==1 || $data['pid']==2 || $data['pid']==3){
				$data['allow_son']=0;
			}
			//上传图片处理
			if($_FILES['thumb']['tmp_name']){
				$data['thumb']=$this->upload('thumb');
			}
			if($_FILES['banner']['tmp_name']){
				$data['banner']=$this->upload('banner');
			}
			//验证
			$validate=validate('Cate');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//插入
			$save=db('cate')->insert($data);
			if($save){
				$this->success('添加成功！','lst');
			}else{
				$this->error('添加失败！');
			}
		}
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
			//上传图片处理
			if($_FILES['thumb']['tmp_name']){
				//先删除旧图片
				$olds=db('cate')->field('thumb')->find($data['id']);
				$oldImg=IMG_UPLOADS.$olds['thumb'];
				if(file_exists($oldImg)){
					@unlink($oldImg);//@可以忽略错误提示
				}
				//上传新图片
				$data['thumb']=$this->upload('thumb');
			}
			if($_FILES['banner']['tmp_name']){
				//先删除旧图片
				$olds=db('cate')->field('banner')->find($data['id']);
				$oldImg=IMG_UPLOADS.$olds['banner'];
				if(file_exists($oldImg)){
					@unlink($oldImg);//@可以忽略错误提示
				}
				//上传新图片
				$data['banner']=$this->upload('banner');
			}
			//验证
			$validate=validate('Cate');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//更新
			$save=db('cate')->update($data);
			if($save!==false){
				$this->success('编辑成功！','lst');
			}else{
				$this->error('编辑失败！');
			}
		}

		//查找
		$res=db('cate')->find($id);

		//无限级分类方式显示（产生level字段）
		$cateres=db('cate')->select();
		$cate=new Catetree();
		$cateres=$cate->catetree($cateres);
		$this->assign([
			'cateres'=>$cateres,
			'res'=>$res
		]);
		return view();
	}
	
	public function del($id){
		//删除分类的时候，删除下级分类
		//1.获取到所有子栏目ID
		$cate=db('cate');
		$cateTree=new Catetree();
		$sonids=$cateTree->childrenids($id,$cate);
		
		$sonids[]=intval($id);//同时把主栏目ID也放进去
		$arrSys=[1,2,3];//系统内置分类
		$res=array_intersect($sonids,$arrSys);//获取数组交集
		if($res){
			$this->error('系统内置分类不允许删除！');
		}
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
			//删除banner
			$bnImg=$cate->field('banner')->find($v);
			$bnImg=IMG_UPLOADS.$bnImg['banner'];
			if(file_exists($bnImg)){
				@unlink($bnImg);
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