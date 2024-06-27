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
Route::post("user/login", "User/login");
Route::post("user/register", "User/register");
Route::post("user/forget", "User/forget");
Route::post("user/sendCode", "User/sendCode");
Route::get("user/info", "User/getInfo");
Route::put("user/info", "User/updateInfo");
Route::put("user/password", "User/changePassword");
Route::post("user/logout", "User/logout");
Route::post("user/verify", "User/verify");
// 图片
Route::any("pics/", "Pics");
Route::post("pics", "Pics/create");
Route::get("pics/:id", "Pics/read");
Route::delete("pics/:id", "Pics/delete");

// 访问前端
Route::get("/", "Index");
Route::get("login", "Index");
Route::get("register", "Index");
Route::get("user/basic", "Index");
Route::get("user/permission", "Index");
// assets资源加载
Route::get("assets/:file", "Assets/index");