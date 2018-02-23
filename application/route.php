<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;
Route::rule('article/:id','article/index','get',['ext'=>'html'],['id'=>'\d{1,4}']);
Route::rule('goods/:id','goods/index','get',['ext'=>'html'],['id'=>'\d{1,4}']);
Route::rule('cate/:id','cate/index','get',['id'=>'\d{1,4}']);
Route::rule('category/:id','category/index','get',['id'=>'\d{1,4}']);