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
// 用户-图片
Route::get("user/pics", "User/getPicList");
Route::delete("user/pics", "User/deletePic");
Route::patch("user/pics", "User/restorePic");
Route::put("user/pics", "User/updatePic");
// 用户-评分
Route::get("user/score", "User/getScore");
// 图片
Route::post("pics/upload", "Pics/create");
Route::get("pics/list", "Pics/index");
Route::get("pics/image/:id", "Pics/read");
Route::delete("pics/image/:id", "Pics/delete");
Route::post("pics/score", "Pics/addScore");
Route::put("pics/score", "Pics/updateScore");
Route::get("pics/score", "Pics/getScore");
Route::get("pics/random", "Pics/randomPic");
// 访问前端
Route::get("/", "Index");
Route::get("login", "Index");
Route::get("register", "Index");
Route::get("user/basic", "Index");
Route::get("user/permission", "Index");
Route::get("user/security", "Index");
Route::get("user/picture", "Index");
Route::get("about", "Index");
// assets资源加载
Route::get("assets/:file", "Assets/index");