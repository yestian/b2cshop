<?php
namespace app\member\controller;
use think\Controller;

class Account extends Controller{

    public function reg(){

        return view();
    }

    public function login(){

        return view();
    }

    //异步验证注册用户名是否存在
    public function checkusername(){
        if(request()->isAjax()){
            echo 1;
        }
    }
}