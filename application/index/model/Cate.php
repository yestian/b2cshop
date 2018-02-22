<?php
namespace app\index\model;
use think\Model;

class Cate extends Model{
    
    public function getComCates(){
        //获取普通分类的顶级分类
        $comCates=db('cate')->where(['cate_type'=>5,'pid'=>0])->select();
        //如果有二级分类获取二级分类
        foreach ($comCates as $k => $v) {
            $comCates[$k]['children']=$this->where(['pid'=>$v['id']])->select();
        }
        return $comCates;
    }
    //系统帮助分类
    public function helpCates(){
        $res=db('cate')->where(['cate_type'=>'3','pid'=>'2'])->select();
        return $res;
    }
    //获取面包屑导航
    public function getBread($cateid){
        $data=$this->field('id,pid,cate_name')->select();
        return $this->_getBread($data,$cateid);
    }

    private function _getBread($data,$cateid){
        static $arr=array();//静态方法，每次结果都能存在数组里面
        $cates=$this->field('id,pid,cate_name')->find($cateid);//获取当前栏目字段
        if(empty($arr)){
            $arr[]=$cates;//第一次执行，把当前栏目放进去
        }
        foreach ($data as $k => $v) {
            if($v['id']==$cates['pid']){
                $arr[]=$v;
                $this->_getBread($data,$v['id']);
            }
        }
        return array_reverse($arr);
    }
}