<?php
namespace app\admin\controller;
use think\Controller;

class Conf extends Controller{

	public function lst(){
		$conf=db('conf');
		if(request()->isPost()){
			$data=input('post.');
			foreach($data['sort'] as $k=>$v){
				$conf->where('id',$k)->update(['sort'=>$v]);
			}
			$this->success('排序成功！');
		}

		$lst=$conf->order('sort desc')->select();
		$this->assign([
			'lst'=>$lst
		]);
		return view();
	}

	public function add(){
		if(request()->isPost()){
			$data=input('post.');
			//替换
			if($data['form_type']=='radio'||$data['form_type']=='checkbox'||$data['form_type']=='select'){
				$data['values']=str_replace('，',',',$data['values']);
				$data['value']=str_replace('，',',',$data['value']);
			}
			//验证
			$validate=validate('Conf');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//插入
			$save=db('conf')->insert($data);
			if($save){
				$this->success('添加成功！','lst');
			}else{
				$this->error('添加失败！');
			}
		}
		return view();
	}

	public function edit($id){
		//更新
		if(request()->isPost()){
			$data=input('post.');
			//替换
			if($data['form_type']=='radio'||$data['form_type']=='checkbox'||$data['form_type']=='select'){
				$data['values']=str_replace('，',',',$data['values']);
				$data['value']=str_replace('，',',',$data['value']);
			}
			//验证
			$validate=validate('Conf');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//更新
			$save=db('conf')->update($data);
			if($save!==false){
				$this->success('编辑成功！','lst');
			}else{
				$this->error('编辑失败！');
			}
		}
		$this->assign([
			'res'=>db('conf')->find($id)
		]);
		return view();
	}
	
	public function del($id){
		$del=db('conf')->delete($id);
		if($del){
			$this->success('删除成功！','lst');
		}else{
			$this->error('删除失败！');
		}

	}

	//配置项目
	public function conflst(){
		$db=db('conf');
		if(request()->isPost()){
			$data=input('post.');
			//如果checkbox选择为空的时候，没有数据发送过来
			$checkbox2D=$db->field('enname')->where('form_type','checkbox')->select();
			$checkbox=array();
			//把二维数组变为一维数组
			if($checkbox2D){
				foreach($checkbox2D as $k=>$v){
					$checkbox[]=$v['enname'];
				}
			}
			//所有发送过来的字段
			$allpost=array();
			//循环更新所有记录
			foreach($data as $k=>$v){
				$allpost[]=$k;
				//存在checkbox记录
				if(is_array($v)){
					$value=implode(',',$v);
					$db->where('enname',$k)->update(['value'=>$value]);
				}else{
					$db->where('enname',$k)->update(['value'=>$v]);//更新value在conflst里面，更新values在edit里面
				}
			}
			
			//如果checkbox置空，则清空它的value
			foreach($checkbox as $k=>$v){
				if(!in_array($v,$allpost)){
					$db->where('enname',$v)->update(['value'=>'']);
				}
			}
			//处理批量图片上传
			if($_FILES){
				//存在图片
				foreach($_FILES as $k=>$v){
					//每个存在的图片
					if($v['tmp_name']){
						//先删除旧图片
						$old=$db->where('enname',$k)->field('value')->find();
						if($old['value']){
							$oldimg=IMG_UPLOADS.$old['value'];
							if(file_exists($oldimg)){
								@unlink($oldimg);
							}
						}
						$imgSrc=$this->upload($k);
						$db->where('enname',$k)->update(['value'=>$imgSrc]);
					}
				}
			}
			$this->success('配置成功！');
		}
		$siteConf=$db->where('conf_type',1)->select();
		$goodsConf=$db->where('conf_type',2)->select();
		$memberConf=$db->where('conf_type',3)->select();
		$smsConf=$db->where('conf_type',4)->select();
		$emailConf=$db->where('conf_type',5)->select();
		$this->assign([
			'site'=>$siteConf,
			'goods'=>$goodsConf,
			'member'=>$memberConf,
			'sms'=>$smsConf,
			'email'=>$emailConf,
		]);
		return view();
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
				return $str;//返回存储地址，存放到数据库
			}else{
				// 上传失败获取错误信息
				echo $file->getError();
				die;
			}
		}
	}
}