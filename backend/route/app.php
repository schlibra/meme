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
use think\Request;

// 用户
Route::post("user/login", "User/login");
Route::post("user/register", "User/register");
Route::post("user/forget", "User/forget");
Route::get("user/info", "User/getInfo");
Route::put("user/info", "User/updateInfo");
Route::put("user/password", "User/changePassword");
Route::post("user/logout", "User/logout");
Route::post("user/verify", "User/verify");
Route::post("user/sendCode", "User/sendCode");
// 用户-图片
Route::get("user/pics", "User/getPicList");
Route::delete("user/pics", "User/deletePic");
Route::patch("user/pics", "User/restorePic");
Route::put("user/pics", "User/updatePic");
// 用户-评分
Route::get("user/scores", "User/getScore");
Route::put("user/scores", "User/updateScore");
Route::delete("user/scores", "User/deleteScore");
Route::patch("user/scores", "User/restoreScore");
// 用户-评论
Route::get("user/comment", "User/getComment");
// 图片
Route::post("pics/pic", "Pics/create");
Route::get("pics/pic", "Pics/index");
Route::get("pics/image/:id", "Pics/read");
Route::post("pics/score", "Pics/addScore");
Route::get("pics/comment", "Pics/getComment");
Route::post("pics/comment", "Pics/addComment");
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