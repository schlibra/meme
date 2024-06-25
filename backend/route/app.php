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
use think\facade\Route;

// 用户
Route::rule("user/", "User", "POST");
Route::rule("user/info", "User/getInfo", "GET");
Route::rule("user/info", "User/updateInfo", "PUT");
// 图片
Route::rule("pics/", "Pics");
Route::rule("pics", "Pics/create", "POST");
Route::rule("pics/:id", "Pics/read", "GET");
Route::rule("pics/:id", "Pics/delete", "DELETE");

// 访问前端
Route::rule("/", "Index", "GET");
Route::rule("login", "Index", "GET");
Route::rule("register", "Index", "GET");
// assets资源加载
Route::rule("assets/:file", "Assets/index", "GET");