<?php

use app\model\UserModel;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use think\captcha\facade\Captcha;
use think\facade\Cache;
use think\Model;
use think\Request;
use think\response\Json;

// 应用公共文件
const loginSecret = "meme_login_token_key";
const emailSecret = "meme_email_token_key";
/**
 * JsonBack，返回JSON格式助手
 * @param int $code 返回状态码
 * @param string $msg 返回消息
 * @param mixed|null $data 返回数据
 * @param int|null $count 返回数据数量
 * @param int|string|null $token 登录token
 * @return Json
 */
function jb(int $code = 200, string $msg = "", mixed $data = null, int $count = null, int|string $token = null): Json {
    return json(["code" => $code, "msg" => $msg, "data" => $data, "count" => $count, "token" => $token]);
}

/**
 * 认证函数返回数据
 * @param bool $status 返回状态，是否成功
 * @param string $msg 返回消息
 * @param mixed|null $data 返回数据
 * @return array 构造后的数组
 */
function returnData(bool $status = true, string $msg = "", mixed $data = null): array {
    return [
        "status"    => $status,
        "msg"       => $msg,
        "data"      => $data
    ];
}

function captchaCheck ($code): array {
    if ($code) {
        if (captcha_check($code)) {
            return returnData();
        } else {
            return returnData(false, "验证码不正确");
        }
    } else {
        return returnData(false, "未输入验证码");
    }
}

/**
 * 登录认证器
 * @param Request $request 登录请求的请求对象
 * @return array 返回认证状态数组
 */
function loginAuth(Request$request): array {
    $token = $request->header("Authorization", "");
    if (str_starts_with($token, "Bearer")) {
        $token = str_replace("Bearer ", "", $token);
        try {
            $data = (array)JWT::decode($token, new Key(loginSecret, "HS256"));
            $_username = $data["username"];
            if (Cache::get($_username) !== $token) {
                return returnData(false, "登录状态无效");
            }
            $_email = $data["email"];
            $user = UserModel::where("username", $_username)
                ->where("email", $_email)
                ->findOrEmpty();
            if ($user->isEmpty()) {
                return returnData(false, "用户不存在");
            } else {
                return returnData(true, "获取成功", $user);
            }
        } catch (SignatureInvalidException|DomainException|BeforeValidException|ExpiredException) {
            return returnData(false, "登录状态过期");
        }
    } else {
        return returnData(false, "未登录");
    }
}

/**
 * 邮箱验证器
 * @param Request $request 邮箱请求体
 * @return array 返回验证状态数组
 */
function emailAuth(Request$request): array
{
    $token = $request->header("Authorization", "");
    if (str_starts_with($token, "Bearer")) {
        $token = str_replace("Bearer ", "", $token);
        try {
            $data = (array)JWT::decode($token, new Key(emailSecret, "HS256"));
            $_email = $data["email"];
            if (Cache::get($_email) !== $token) {
                return returnData(false, "验证码信息无效");
            }
            $_code = $data["code"];
            return returnData(true, "获取成功", ["code" => $_code, "email" => $_email]);
        } catch (SignatureInvalidException|DomainException|BeforeValidException|ExpiredException) {
            return returnData(false, "验证码信息过期");
        }
    } else {
        return returnData(false, "验证码信息无效");
    }
}