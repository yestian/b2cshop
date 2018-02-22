<?php
namespace app\admin\model;
use think\Model;

class Goods extends Model{
	//忽略不存在的字段
	protected $field=true;

	protected static function init(){
		//删除商品前删除关联表记录
		Goods::beforeDelete(function($goods){
			$goodsId=$goods->id;
			//删除商品推荐位
			db('rec_item')->where(['value_id'=>$goodsId,'value_type'=>1])->delete();
			$thumb=[];
			//有原图，才会有另外三张图，删除文件中的图片
			if($goods->og_thumb){
				$thumb[]=IMG_UPLOADS.$goods->og_thumb;
				$thumb[]=IMG_UPLOADS.$goods->sm_thumb;
				$thumb[]=IMG_UPLOADS.$goods->md_thumb;
				$thumb[]=IMG_UPLOADS.$goods->big_thumb;
				foreach($thumb as $k=>$v){
					if(file_exists($v)){
						@unlink($v);
					}
				}
			}
			//删除商品相册
			$photos=model('goodsPhoto')->where('goods_id',$goodsId)->select();
			if(!empty($photos)){
				foreach($photos as $k=>$v){
					if($v->og_photo){
						$photo=[];
						$photo[]=IMG_UPLOADS.$v->og_photo;
						$photo[]=IMG_UPLOADS.$v->sm_photo;
						$photo[]=IMG_UPLOADS.$v->md_photo;
						$photo[]=IMG_UPLOADS.$v->big_photo;
						foreach($photo as $k1=>$v1){
							if(file_exists($v1)){
								@unlink($v1);
							}
						}
					}
				}
			}
			db('memberPrice')->where('goods_id',$goodsId)->delete();//会员价格
			db('goodsAttr')->where('goods_id',$goodsId)->delete();//属性
			db('goodsPhoto')->where('goods_id',$goodsId)->delete();//相册记录
		});


		Goods::beforeInsert(function($goods){
			//上传了原始大图，才进行下面的操作
			//生成商品主图的三张缩略图
			if($_FILES['og_thumb']['tmp_name']){
				$ogImgName=$goods->upload('og_thumb');
				$ogthumb=date('Ymd') . DS . $ogImgName;
				$bigthumb=date('Ymd') . DS . 'big_'.$ogImgName;
				$mdthumb=date('Ymd') . DS . 'md_'.$ogImgName;
				$smthumb=date('Ymd') . DS . 'sm_'.$ogImgName;
				//存入文件
				$image = \think\Image::open(IMG_UPLOADS.$ogthumb);
				$image->thumb(500, 500)->save(IMG_UPLOADS.$bigthumb);
				$image->thumb(300, 300)->save(IMG_UPLOADS.$mdthumb);
				$image->thumb(100, 100)->save(IMG_UPLOADS.$smthumb);
				//存入数据库
				$goods->og_thumb=$ogthumb;
				$goods->big_thumb=$bigthumb;
				$goods->md_thumb=$mdthumb;
				$goods->sm_thumb=$smthumb;
			}
			//随机验证码
			$goods->goods_code=time().rand(111111,999999);
		});
		//修改记录
		Goods::beforeUpdate(function($goods){
			//商品ID
			$goodsId=$goods->id;
			//商品属性处理---新增商品属性
			$goodsDate=input('post.');//$goods->goods_attr方式为空的时候会报错
			//修改推荐位，先删除，后添加
			db('rec_item')->where(['value_id'=>$goodsId,'value_type'=>1])->delete();
			//处理推荐位
			if(isset($goodsDate['value_id'])){
				foreach ($goodsDate['value_id'] as $k => $v) {
					db('recItem')->insert(['value_type'=>1,'rec_id'=>$v,'value_id'=>$goodsId]);
				}
			}
			
			if(isset($goodsDate['goods_attr'])){//属性存在
				$i=0;//价格下标循环变量
				foreach($goodsDate['goods_attr'] as $k=>$v){
					if(is_array($v)){
						$v=array_filter($v);//过滤数组的空值
						if(!empty($v)){//单选值
							foreach($v as $k1=>$v1){
								//属性值为空的时候，跳过插入，同时价格下标向前移动一位
								if(!$v1){
									$i++;
									continue;
								}
								db('goods_attr')->insert(['attr_price'=>$goodsDate['attr_price'][$i],'attr_id'=>$k,'attr_value'=>$v1,'goods_id'=>$goodsId]);
								$i++;
							}
						}
					}else{
						//唯一值，没有价格
						db('goods_attr')->insert(['attr_id'=>$k,'attr_value'=>$v,'goods_id'=>$goodsId]);//模型方法写入数据库
					}
				}
			}
			//修改商品属性，主要处理name为old的值
			if(isset($goodsDate['old_goods_attr'])){//属性存在
				$attrprice=$goodsDate['old_attr_price'];
				$idsArr=array_keys($attrprice);//键组成数组
				$valuesArr=array_values($attrprice);//值组成数组
				$i=0;//价格下标循环变量
				foreach($goodsDate['old_goods_attr'] as $k=>$v){
					if(is_array($v)){
						$v=array_filter($v);//过滤数组的空值
						if(!empty($v)){//单选值
							foreach($v as $k1=>$v1){
								//属性值为空的时候，跳过插入，同时价格下标向前移动一位
								if(!$v1){
									$i++;
									continue;
								}
								db('goods_attr')->where('id',$idsArr[$i])->update(['attr_price'=>$valuesArr[$i],'attr_value'=>$v1]);
								$i++;
							}
						}
					}else{
						//唯一值，没有价格
						db('goods_attr')->where('id',$idsArr[$i])->update(['attr_value'=>$v,'attr_price'=>$valuesArr[$i]]);//模型方法写入数据库
						$i++;//修改的时候有隐藏字段price
					}
				}
			}
			//商品相册处理
			if($goods->_hasImgs($_FILES['goods_photo']['tmp_name'])){
				$files = request()->file('goods_photo');
				foreach($files as $file){
					// 移动到框架应用根目录/public/uploads/ 目录下
					$info = $file->move(ROOT_PATH . 'public' . DS . 'static' . DS . 'uploads');
					if($info){
						$photo=$info->getFilename();
						$ogPhoto=date('Ymd') . DS . $photo;
						$bigPhoto=date('Ymd') . DS . 'big_'.$photo;
						$mdPhoto=date('Ymd') . DS . 'md_'.$photo;
						$smPhoto=date('Ymd') . DS . 'sm_'.$photo;
						//存入文件
						$image = \think\Image::open(IMG_UPLOADS.$ogPhoto);
						$image->thumb(500, 500)->save(IMG_UPLOADS.$bigPhoto);
						$image->thumb(300, 300)->save(IMG_UPLOADS.$mdPhoto);
						$image->thumb(100, 100)->save(IMG_UPLOADS.$smPhoto);
						//删除原图
						@unlink(IMG_UPLOADS.$ogPhoto);
						//写入数据库
						db('goodsPhoto')->insert(['og_photo'=>$ogPhoto,'goods_id'=>$goodsId,'big_photo'=>$bigPhoto,'sm_photo'=>$smPhoto,'md_photo'=>$mdPhoto]);
					}else{
						// 上传失败获取错误信息
						echo $file->getError();
					}    
				}
			}
			//处理会员价格
			$mp=$goods->mprice;
			
			$db=db('memberPrice');
			//删除以前的价格
			$db->where('goods_id',$goodsId)->delete();
			//插入新的价格
			if($mp){
				foreach($mp as $k=>$v){
					if(trim($v)==''){
						continue;
					}else{
						$db->insert(['mlevel_id'=>$k,'goods_id'=>$goodsId,'mprice'=>$v]);
					}
				}
			}
			//上传了原始大图，才进行下面的操作
			//生成商品主图的三张缩略图
			if($_FILES['og_thumb']['tmp_name']){
				//删除旧的图片
				@unlink(IMG_UPLOADS.$goods->og_thumb);
				@unlink(IMG_UPLOADS.$goods->big_thumb);
				@unlink(IMG_UPLOADS.$goods->sm_thumb);
				@unlink(IMG_UPLOADS.$goods->md_thumb);
				//上传新的图片
				$ogImgName=$goods->upload('og_thumb');
				$ogthumb=date('Ymd') . DS . $ogImgName;
				$bigthumb=date('Ymd') . DS . 'big_'.$ogImgName;
				$mdthumb=date('Ymd') . DS . 'md_'.$ogImgName;
				$smthumb=date('Ymd') . DS . 'sm_'.$ogImgName;
				//存入文件
				$image = \think\Image::open(IMG_UPLOADS.$ogthumb);
				$image->thumb(500, 500)->save(IMG_UPLOADS.$bigthumb);
				$image->thumb(300, 300)->save(IMG_UPLOADS.$mdthumb);
				$image->thumb(100, 100)->save(IMG_UPLOADS.$smthumb);
				//存入数据库
				$goods->og_thumb=$ogthumb;
				$goods->big_thumb=$bigthumb;
				$goods->md_thumb=$mdthumb;
				$goods->sm_thumb=$smthumb;
			}
		});
		Goods::afterInsert(function($goods){
			$goodsDate=input('post.');//$goods->goods_attr方式为空的时候会报错
			//批量插入会员价格
			$mp=$goods->mprice;
			$goodsId=$goods->id;
			if($mp){
				foreach($mp as $k=>$v){
					if(trim($v)==''){
						continue;
					}else{
						db('memberPrice')->insert(['mlevel_id'=>$k,'goods_id'=>$goodsId,'mprice'=>$v]);
					}
				}
			}

			//商品相册处理
			if($goods->_hasImgs($_FILES['goods_photo']['tmp_name'])){
				$files = request()->file('goods_photo');
				foreach($files as $file){
					// 移动到框架应用根目录/public/uploads/ 目录下
					$info = $file->move(ROOT_PATH . 'public' . DS . 'static' . DS . 'uploads');
					if($info){
						$photo=$info->getFilename();
						$ogPhoto=date('Ymd') . DS . $photo;
						$bigPhoto=date('Ymd') . DS . 'big_'.$photo;
						$mdPhoto=date('Ymd') . DS . 'md_'.$photo;
						$smPhoto=date('Ymd') . DS . 'sm_'.$photo;
						//存入文件
						$image = \think\Image::open(IMG_UPLOADS.$ogPhoto);
						$image->thumb(500, 500)->save(IMG_UPLOADS.$bigPhoto);
						$image->thumb(300, 300)->save(IMG_UPLOADS.$mdPhoto);
						$image->thumb(100, 100)->save(IMG_UPLOADS.$smPhoto);
						//删除原图
						@unlink(IMG_UPLOADS.$ogPhoto);
						//写入数据库
						db('goodsPhoto')->insert(['og_photo'=>$ogPhoto,'goods_id'=>$goodsId,'big_photo'=>$bigPhoto,'sm_photo'=>$smPhoto,'md_photo'=>$mdPhoto]);
					}else{
						// 上传失败获取错误信息
						echo $file->getError();
					}    
				}
			}
			//商品属性处理
			
			//属性存在
			//$v：颜色，$v1:红色
			if(isset($goodsDate['goods_attr'])){
				$i=0;//价格下标循环变量
				foreach($goodsDate['goods_attr'] as $k=>$v){
					if(is_array($v)){
						//过滤数组的空值
						$v=array_filter($v);
						//单选值
						if(!empty($v)){
							foreach($v as $k1=>$v1){
								//属性值为空的时候，跳过插入，同时价格下标向前移动一位
								if(!$v1){
									$i++;
									continue;
								}
								db('goods_attr')->insert(['attr_price'=>$goodsDate['attr_price'][$i],'attr_id'=>$k,'attr_value'=>$v1,'goods_id'=>$goodsId]);
								$i++;
							}
						}
					}else{
						//唯一值，没有价格
						db('goods_attr')->insert(['attr_id'=>$k,'attr_value'=>$v,'goods_id'=>$goodsId]);//模型方法写入数据库
					}
				}
			}
			//处理推荐位
			if(isset($goodsDate['value_id'])){
				foreach ($goodsDate['value_id'] as $k => $v) {
					db('recItem')->insert(['value_type'=>1,'rec_id'=>$v,'value_id'=>$goodsId]);
				}
			}
		});
	}
/**
 * 多图上传的时候，判断是否有图片
 */
private function _hasImgs($tmpName){
	foreach($tmpName as $k=>$v){
		if($v){
			return true;
		}
	}
	return false;
}


	public function upload($imgName){
		$file = request()->file($imgName);
		// 移动到框架应用根目录/public/static/uploads/ 目录下
		if($file){
			$info = $file->move(ROOT_PATH . 'public' . DS . 'static'. DS . 'uploads');
			if($info){
				return $info->getFilename(); 
			}else{
				// 上传失败获取错误信息
				echo $file->getError();
			}
		}
	}
}