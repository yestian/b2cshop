<?php
namespace app\member\model;
use think\Model;

class Conf extends Model{
    //获取配置项
    public function getConf(){
        $_res=$this->field('enname,value')->select();
        $res=array();
        foreach ($_res as $k => $v) {
            $res[$v['enname']]=$v['value'];
        }
        return $res;
    }
}