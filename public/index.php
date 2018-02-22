<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
//定义常量供供controller使用，模板中使用的常量在config中定义
define('IMG_UPLOADS',__DIR__ . '/../public/static/uploads/');
define('UEDITOR',__DIR__ . '/ueditor');//遍历图片时候的目录循环
define('HTTP_UEDITOR','/ueditor');//展示图片时候的物理路径转换
define('DEL_UEDITOR', __DIR__.'/.');//图片删除的时候拼接的目录
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
