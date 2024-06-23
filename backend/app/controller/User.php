<?php
declare (strict_types = 1);

namespace app\controller;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\facade\Db;
use think\Request;
use Firebase\JWT\JWT;

class User
{
    /**
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     * @throws DbException
     */
    function login(Request $request) {
        $username = $request->post("username", "");
        $password = $request->post("password", "");
        echo env("DB_USER");
        $data = Db::connect("mysql")
            ->table("user")
            ->where("username", $username)
            ->whereOr("email", $username)
            ->find();
        if ($data) {
            if (password_verify($password, $data["password"])) {
                if ($data["ban"] === "Y") {
                    return json(["code"=>401, "msg"=>"用户已被封禁：".$data['reason']]);
                } else {
                    $url = $request->domain();
                    $payload = [
                        "iss" => $url,
                        "aud" => $url,
                        "iat" => time(),
                        "exp" => time() + 36000,
                        "username" => $data["username"],
                        "email" => $data["email"]
                    ];
                    $token = JWT::encode($payload, "meme", "HS256");
                    return json(["code"=>200, "msg"=>"登录成功", "token"=>$token]);
                }
            } else {
                return json(["code"=>401, "msg"=>"密码错误"]);
            }
        } else {
            return json(["code"=>401, "msg"=>"用户不存在"]);
        }
    }

    function register() {
        return "Register";
    }

    function getInfo(Request $request) {
        $token = $request->header("Authorization", "");
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array) JWT::decode($token, new Key("meme", "HS256"));
                $username = $data["username"];
                $email = $data["email"];
                $row = Db::connect("mysql")
                    ->table("user")
                    ->where("username", $username)
                    ->where("email", $email)
                    ->find();
                if ($row) {
                    unset($row["password"]);
                    unset($row["verified"]);
                    unset($row["ban"]);
                    unset($row["reason"]);
                    return json(["code"=>200, "msg"=>"用户信息获取成功", "data"=>$row]);
                } else {
                    return json(["code"=>401, "msg"=>"用户不存在"]);
                }
            } catch (SignatureInvalidException|\DomainException|BeforeValidException|ExpiredException$e) {
                return json(["code"=>401, "msg"=>"Token信息错误：" . $e->getMessage()]);
            }
        } else {
            return json(["code"=>401, "msg"=>"Token数据错误"]);
        }
    }
}
