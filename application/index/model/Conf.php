<?php
namespace app\index\model;
use think\Model;

class Conf extends Model{
    //获取配置项
    public function getConfs(){
        $_res=$this->field('enname,value')->select();
        $res=array();
        foreach ($_res as $k => $v) {
            $res[$v['enname']]=$v['value'];
        }
        return $res;
    }
}