<?php

namespace app\lib;

use app\model\UserModel;
use ArrayObject;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use think\facade\Cache;
use think\Request;

class Authorization
{
    private static $loginSecret = "meme_login_token_key";
    private static $emailSecret = "meme_email_token_key";
    private static function returnData($status = true, $msg = "", $data = []): array {
        return [
            "status"    => $status,
            "msg"       => $msg,
            "data"      => $data
        ];
    }
    public static function loginAuth(Request$request): array {
        $token = $request->header("Authorization", "");
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array)JWT::decode($token, new Key(self::$loginSecret, "HS256"));
                $_username = $data["username"];
                if (Cache::get($_username) !== $token) {
                    return self::returnData(false, "登录状态无效");
                }
                $_email = $data["email"];
                $user = UserModel::where("username", $_username)
                    ->where("email", $_email)
                    ->findOrEmpty();
                if ($user->isEmpty()) {
                    return self::returnData(false, "用户不存在");
                } else {
                    return self::returnData(true, "获取成功", $user);
                }
            } catch (SignatureInvalidException|\DomainException|BeforeValidException|ExpiredException$e) {
                return self::returnData(false, "登录状态过期");
            }
        } else {
            return self::returnData(false, "未登录");
        }
    }

    public static function emailAuth(Request$request): array
    {
        $token = $request->header("Authorization", "");
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array)JWT::decode($token, new Key(self::$emailSecret, "HS256"));
                $_email = $data["email"];
                if (Cache::get($_email) !== $token) {
                    return self::returnData(false, "验证码信息无效");
                }
                $_code = $data["code"];
                return self::returnData(true, "获取成功", ["code" => $_code, "email" => $_email]);
            } catch (SignatureInvalidException|\DomainException|BeforeValidException|ExpiredException) {
                return self::returnData(false, "验证码信息过期");
            }
        } else {
            return self::returnData(false, "验证码信息无效");
        }
    }
}