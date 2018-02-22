<?php
namespace app\admin\controller;
use think\Controller;
use catetree\Catetree;

class Goods extends Controller{

	public function lst(){
		$lst=db('goods a')
		->field('a.*,b.type_name,c.brand_name,d.cate_name,SUM(e.goods_number) as stock')
		->join('type b','a.type_id=b.id','left')
		->join('brand c','a.brand_id=c.id','left')
		->join('category d','a.category_id=d.id','left')
		->join('stock e','a.id=e.goods_id','left')
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
			//验证
			$validate=validate('Goods');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			
			//插入
			$save=model('goods')->save($data);
			if($save){
				$this->success('添加成功！','lst');
			}else{
				$this->error('添加失败！');
			}
		}
		//视图
		$category=db('category')->select();
		//商品推荐位
		$rec=db('recommend')->where('rec_type',1)->select();
		$cate=new Catetree();
		$category=$cate->catetree($category);
		$brands=db('brand')->select();
		$memberLevel=db('memberLevel')->select();
		$types=db('type')->select();
		$this->assign([
			'category'=>$category,//所属栏目
			'brands'=>$brands,//所属品牌
			'memberLevel'=>$memberLevel,//会员级别
			'types'=>$types,//商品属性
			'rec'=>$rec,//商品推荐
		]);
		return view();
	}

	public function edit(){
		if(request()->isPost()){
			$data=input('post.');
			//验证
			$validate=validate('Goods');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			
			//插入
			$save=model('goods')->update($data);
			if($save){
				$this->success('编辑成功！','lst');
			}else{
				$this->error('编辑失败！');
			}
		}
		//视图
		$gid=input('id');
		$category=db('category')->select();
		$cate=new Catetree();
		$category=$cate->catetree($category);
		$brands=db('brand')->select();
		$memberLevel=db('memberLevel')->select();
		$types=db('type')->select();
		//会员价格查询
		$mprice=db('memberPrice')->where('goods_id',$gid)->select();
		//商品相册查询
		$goodphotos=db('goodsPhoto')->where('goods_id',$gid)->select();
		//查询当前商品信息
		$goods=db('goods')->find($gid);
		//商品推荐位
		$rec=db('recommend')->where('rec_type',1)->select();
		//商品推荐位结果表
		$_recres=db('rec_item')->where(['value_id'=>$gid,'value_type'=>1])->select();
		//重组推荐位结果
		$recarr=array();
		foreach ($_recres as $k => $v) {
			$recarr[]=$v['rec_id'];
		}
		//查询属性
		$attr=db('attr')->where('type_id',$goods['type_id'])->select();
		//商品属性查询
		$_goodsAttr=db('goodsAttr')->where('goods_id',$gid)->select();
		
		//以attr_id对结果进行重组
		$goodsAttr=array();
		foreach ($_goodsAttr as $k => $v) {
			$goodsAttr[$v['attr_id']][]=$v;
		}
		$this->assign([
			'category'=>$category,//所属栏目
			'brands'=>$brands,//所属品牌
			'memberLevel'=>$memberLevel,//会员级别
			'types'=>$types,//商品属性
			'goods'=>$goods,//当前商品
			'mprice'=>$mprice,//会员价格
			'goodsPhoto'=>$goodphotos,//相册
			'attr'=>$attr,//属性集合
			'goodsAttr'=>$goodsAttr,//当前商品属性
			'rec'=>$rec,//商品推荐位
			'recres'=>$recarr,//推荐位结果
		]);
		return view();
	}

	
	public function del($id){
		//删除前，需要删除关联表：商品相册表，商品会员价格表，商品属性表
		$del=model('goods')->destroy($id);
		if($del){
			$this->success('删除成功！','lst');
		}else{
			$this->error('删除失败！');
		}

	}

	//库存
	public function stock($id){
		$db=db('stock');
		if(request()->isPost()){
			//先删除库存，然后再重新添加
			$db->where('goods_id',$id)->delete();
			$data=input('post.');
			//对2个属性分开处理，第一个是三维数组，第二个是二维数组
			$attr=$data['goods_attr'];
			$nums=$data['goods_number'];
			foreach($nums as $k=>$v){
				$attrArr=array();
				foreach($attr as $k1=>$v1){
					//如果某条记录为空，就跳出循环
					if(intval($v1[$k])<=0){
						continue 2;
					}
					$attrArr[]=$v1[$k];
				}
				//把数组转为字符串
				sort($attrArr);
				$attrArr=implode(',',$attrArr);
				//写入数据库
				$db->insert([
					'goods_id'=>$id,
					'goods_number'=>$v,
					'goods_attr'=>$attrArr,
				]);
			}
			$this->success('添加成功！','lst');
		}


		//查询
		$map['goods_id']=$id;
		$map['attr_type']=1;
		$_stock=db('goods_attr a')->field('a.id,a.attr_value,a.attr_id,b.attr_name')
			->join('attr b','a.attr_id=b.id','left')->where($map)->select();
		//对数组重组变为三维数组
		$stock=array();
		foreach($_stock as $k=>$v){
			$stock[$v['attr_name']][]=$v;
		}

		$getstock=$db->where('goods_id',$id)->select();//查询库存记录
		$this->assign([
			'stock'=>$stock,
			'getstock'=>$getstock,
		]);
		return view();
	}

	//异步删除相册图片
	public function ajaxdelphoto($id){
		if(request()->isAjax()){
			$db=db('goodsPhoto');
			$pho=$db->where('id',$id)->find();
			$og=IMG_UPLOADS.$pho['og_photo'];
			$big=IMG_UPLOADS.$pho['big_photo'];
			$md=IMG_UPLOADS.$pho['md_photo'];
			$sm=IMG_UPLOADS.$pho['sm_photo'];
			@unlink($og);
			@unlink($big);
			@unlink($md);
			@unlink($sm);
			$del=$db->delete($id);//删除记录
			if($del){
				echo 1;
			}else{
				echo 2;
			}
		}
	}
	//异步删除属性
	public function ajaxDelGoodsAttr($id){
		if(request()->isAjax()){
			$del=db('goodsAttr')->delete($id);
			if($del){
				echo 1;
			}else{
				echo 2;
			}
		}
	}

}