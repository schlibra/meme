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
Route::post("api/user/login", "User/login");
Route::post("api/user/register", "User/register");
Route::post("api/user/forget", "User/forget");
Route::get("api/user/info", "User/getInfo");
Route::put("api/user/info", "User/updateInfo");
Route::put("api/user/password", "User/changePassword");
Route::post("api/user/logout", "User/logout");
Route::post("api/user/verify", "User/verify");
Route::post("api/user/sendCode", "User/sendCode");
// 用户-图片
Route::get("api/user/pic", "User/getPicList");
Route::delete("api/user/pic", "User/deletePic");
Route::patch("api/user/pic", "User/restorePic");
Route::put("api/user/pic", "User/updatePic");
// 用户-评分
Route::get("api/user/score", "User/getScore");
Route::put("api/user/score", "User/updateScore");
Route::delete("api/user/score", "User/deleteScore");
Route::patch("api/user/score", "User/restoreScore");
// 用户-评论
Route::get("api/user/comment", "User/getComment");
Route::put("api/user/comment", "User/updateComment");
Route::delete("api/user/comment", "User/deleteComment");
Route::patch("api/user/comment", "User/restoreComment");
// 图片
Route::post("api/pics/pic", "Pics/create");
Route::get("api/pics/pic", "Pics/index");
Route::get("api/pics/image/:id", "Pics/read");
Route::post("api/pics/score", "Pics/addScore");
Route::get("api/pics/comment", "Pics/getComment");
Route::post("api/pics/comment", "Pics/addComment");
Route::get("api/pics/random", "Pics/randomPic");
// 管理员
Route::get("api/admin/group", "Admin/getGroup");
Route::put("api/admin/group", "Admin/updateGroup");
Route::post("api/admin/group", "Admin/createGroup");
Route::delete("api/admin/group", "Admin/deleteGroup");
Route::get("api/admin/user", "Admin/getUser");
// 访问前端
Route::get("/", "Index");
Route::get("login", "Index");
Route::get("register", "Index");
Route::get("user/basic", "Index");
Route::get("user/permission", "Index");
Route::get("user/security", "Index");
Route::get("user/picture", "Index");
Route::get("user/comment", "Index");
Route::get("about", "Index");
// assets资源加载
Route::get("assets/:file", "Assets/index");
// 验证码
Route::get("api/captcha/[:config]", "\\think\\captcha\\CaptchaController@index");