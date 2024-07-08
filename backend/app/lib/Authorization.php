<?php

namespace app\lib;

use app\model\UserModel;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use think\Request;

class Authorization
{
    private static $loginSecret = "meme_login_token_key";
    private $emailSecret = "";
    private static function returnData($status = true, $msg = "", $data = []) {
        return [
            "status"    => $status,
            "msg"       => $msg,
            "data"      => $data
        ];
    }
    public static function loginAuth(Request$request) {
        $token = $request->header("Authorization", "");
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array)JWT::decode($token, new Key(self::$loginSecret, "HS256"));
                $_username = $data["username"];
                $_email = $data["email"];
                $user = UserModel::where("username", $_username)
                    ->where("email", $_email)
                    ->find();
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
}