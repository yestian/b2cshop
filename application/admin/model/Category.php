<?php
namespace app\admin\model;
use think\Model;

class Category extends Model{
    //忽略不存在的字段
	protected $field=true;
	protected static function init(){
        Category::beforeDelete(function($category){
            $ID=$category->id;
            //删除商品推荐位
			db('rec_item')->where(['value_id'=>$ID,'value_type'=>2])->delete();
        });


        Category::afterInsert(function($category){
            $ID=$category->id;
            $data=input('post.');
            //处理推荐位
			if(isset($data['value_id'])){
				foreach ($data['value_id'] as $k => $v) {
					db('recItem')->insert(['value_type'=>2,'rec_id'=>$v,'value_id'=>$ID]);
				}
			}
        });


        Category::beforeUpdate(function($category){
            $ID=$category->id;
            $data=input('post.');
            //删除商品推荐位
            db('rec_item')->where(['value_id'=>$ID,'value_type'=>2])->delete();
            //处理推荐位
			if(isset($data['value_id'])){
				foreach ($data['value_id'] as $k => $v) {
					db('rec_item')->insert(['value_type'=>2,'rec_id'=>$v,'value_id'=>$ID]);
				}
			}
        });
    }
}