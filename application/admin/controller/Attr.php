<?php
namespace app\admin\controller;
use think\Controller;

class Attr extends Controller{

	public function lst(){
		if(input('type_id')){
			$map['type_id']=input('type_id');
		}else{
			$map['type_id']=1;
		}
		$lst=db('attr a')
			->field('a.*,b.type_name')
			->join('type b','a.type_id=b.id','left')
			->where($map)
			->order('id desc')
			->paginate(10);
		$this->assign([
			'lst'=>$lst,
		]);
		return view();
	}

	public function add(){
		if(request()->isPost()){
			$data=input('post.');
			//修改逗号
			$data['attr_values']=str_replace('，',',',$data['attr_values']);
			//验证
			$validate=validate('Attr');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//插入
			$save=db('attr')->insert($data);
			if($save){
				//添加成功的时候，跳转到对应的列表
				$this->redirect('attr/lst',array('type_id'=>$data['type_id']),2,'添加成功！');
			}else{
				$this->error('添加失败！');
			}
		}
		//属性类型
		$this->assign([
			'type'=>db('type')->select()
		]);
		return view();
	}

	public function edit($id){
		//查找
		$res=db('attr')->find($id);
		//更新
		if(request()->isPost()){
			$data=input('post.');
			//修改逗号
			$data['attr_values']=str_replace('，',',',$data['attr_values']);
			//验证
			$validate=validate('Attr');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			//更新
			$save=db('attr')->update($data);
			if($save!==false){
				$this->redirect('attr/lst',array('type_id'=>$data['type_id']),2,'添加成功！');
			}else{
				$this->error('编辑失败！');
			}
		}
		$this->assign([
			'res'=>$res,
			'type'=>db('type')->select()
		]);
		return view();
	}
	
	public function del($id){
		$attr=db('attr');
		
		//跳转回当前列表
		$typeid=$attr->field('type_id')->find($id);
		$typeid=$typeid['type_id'];

		$del=$attr->delete($id);
		if($del){
			$this->redirect('attr/lst',array('type_id'=>$typeid));
		}else{
			$this->error('删除失败！');
		}

	}

	//添加商品的时候选择属性
	public function ajaxGetAttr(){
		if(request()->isAjax()){
			$id=input('type_id');
			$res=db('attr')->where('type_id',$id)->select();
			echo json_encode($res);
		}
	}

}