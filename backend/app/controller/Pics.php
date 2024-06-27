<?php
declare (strict_types = 1);

namespace app\controller;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use think\facade\Cache;
use think\facade\Db;
use think\Request;
use function Symfony\Component\VarDumper\Dumper\esc;

class Pics
{
    public function index(Request$request)
    {
        $token = $request->header("Authorization", "");
        $pageSize = (int)$request->get("pageSize", 20);
        $pageNum = (int)$request->get("pageNum", 1);
        $userId = null;
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array)JWT::decode($token, new Key("meme_login_token_key", "HS256"));
                $_username = $data["username"];
                $_email = $data["email"];
                $user = Db::connect("mysql")
                    ->table("user")
                    ->where("username", $_username)
                    ->where("email", $_email)
                    ->find();
                if ($user) {
                    $userId = $user["id"];
                }
            } catch (SignatureInvalidException|\DomainException|BeforeValidException|ExpiredException$e) {}
        }
        $allUser = Db::connect("mysql")
            ->table("user")
            ->select();
        $userList = [];
        foreach ($allUser as $user) {
            $userList[$user["id"]] = $user;
        }
        $pics = Db::connect("mysql")
            ->table("pics")
            ->where("delete", "=", null)
            ->limit(($pageNum-1)*$pageSize, $pageSize)
            ->select();
        $count = Db::connect("mysql")
            ->table("pics")
            ->where("delete", "=", null)
            ->count();
        $score = Db::connect("mysql")
            ->table("score")
            ->select();
        for ($i = 0; $i < count($pics); ++$i) {
            $pic = $pics[$i];
            $_score = 0;
            $scoreCount = 0;
            $pic["scored"] = "N";
            $pic["score"] = 0;
            for ($j = 0; $j < count($score); ++$j) {
                $score_item = $score[$j];
                if ($score_item["user"] === $userId) {
                    $pic["scored"] = "Y";
                }
                if ($score_item["pic"] === $pic["id"]) {
                    $_score += $score_item["score"];
                    $scoreCount++;
                }
                if ($scoreCount) {
                    $pic["score"] = $_score / $scoreCount;
                }
            }
            $_userId = $pic["user"];
            if (isset($userList[$_userId])) {
                $pic["nickname"] = $userList[$_userId]["nickname"];
            } else {
                $pic["nickname"] = "未知用户";
            }
            unset($pic["data"]);
            unset($pic["type"]);
            $pic["url"] = $request->domain() . "/pics/image/" . $pic["id"];
            $pics[$i] = $pic;
        }
        return json(["code" => 200, "msg" => "数据获取成功", "data" => $pics, "total" => $count]);
    }

    public function create(Request$request)
    {
        $image = chunk_split(base64_encode(file_get_contents($request->file("image")->getPathname())));
        $type = $request->file("image")->getMime();
        if (empty($image)) {
            return json(["code" => 400, "msg" => "未选择图片"]);
        }
        $token = $request->header("Authorization", "");
        $description = $request->post("description", "");
        $name = $request->post("name", "");
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array) JWT::decode($token, new Key("meme_login_token_key", "HS256"));
                $_username = $data["username"];
                $_email = $data["email"];
                $user = Db::connect("mysql")
                    ->table("user")
                    ->where("username", $_username)
                    ->where("email", $_email)
                    ->find();
                if ($user) {
                    $permission = Db::connect("mysql")
                        ->table("group")
                        ->where("id", $user["group"])
                        ->find();
                    if ($permission) {
                        if ($permission["upload"] === "Y") {
                            Db::connect("mysql")
                                ->table("pics")
                                ->insert([
                                    "name" => $name,
                                    "description" => $description,
                                    "user" => $user["id"],
                                    "data" => $image,
                                    "type" => $type,
                                    "create" => date("Y-m-d H:i:s"),
                                    "update" => date("Y-m-d H:i:s")
                                ]);
                            return json(["code" => 200, "msg" => "上传成功"]);
                        } else {
                            return json(["code" => 403, "msg" => "没有上传权限"]);
                        }
                    } else {
                        return json(["code" => 401, "msg" => "没有权限"]);
                    }
                } else {
                    return json(["code" => 401, "msg" => "用户不存在"]);
                }
            } catch (SignatureInvalidException|\DomainException|BeforeValidException|ExpiredException$e) {
                return json(["code"=>401, "msg"=>"Token信息错误：" . $e->getMessage()]);
            }
        } else {
            return json(["code" => 403, "msg" => "未登录账号"]);
        }
    }

    public function save(Request $request)
    {
        //
    }
    public function read($id)
    {
        $data = Db::connect("mysql")
            ->table("pics")
            ->where("id", $id)
            ->find();
        if ($data) {
            $img = base64_decode($data["data"]);
            $type = $data["type"];
            return response($img)->header([
                "Content-Type" => $type
            ]);
        } else {
            return json(["code" => 404, "msg" => "图片不存在"]);
        }
    }
    public function update(Request $request, $id)
    {
        //
    }
    public function delete(Request$request,$id)
    {
        $token = $request->header("Authorization", "");
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array)JWT::decode($token, new Key("meme_login_token_key", "HS256"));
                $_username = $data["username"];
                $_email = $data["email"];
                $user = Db::connect("mysql")
                    ->table("user")
                    ->where("username", $_username)
                    ->where("email", $_email)
                    ->find();
                if ($user) {
                    $group = Db::connect("mysql")
                        ->table("group")
                        ->where("id", $user["group"])
                        ->find();
                    if ($group) {
                        $pic = Db::connect("mysql")
                            ->table("pics")
                            ->where("id", $id)
                            ->find();
                        if ($pic) {
                            if ($group["deleteComment"] === "Y" && $pic["user"] === $user["id"]) {
                                Db::connect("mysql")
                                    ->table("pics")
                                    ->where("id", $id)
                                    ->update([
                                        "delete" => date("Y-m-d H:i:s")
                                    ]);
                                return json(["code" => 200, "msg" => "图片删除成功"]);
                            } else {
                                return json(["code" => 403, "msg" => "没有删除权限"]);
                            }
                        } else {
                            return json(["code" => 404, "msg" => "图片不存在"]);
                        }
                    } else {
                        return json(["code" => 401, "msg" => "没有权限"]);
                    }
                } else {
                    return json(["code" => 401, "msg", "用户不存在"]);
                }
            } catch (SignatureInvalidException|\DomainException|BeforeValidException|ExpiredException$e) {
                return json(["code" => 401, "msg" => "Token信息错误：" . $e->getMessage()]);
            }
        } else {
            return json(["code" => 403, "msg" => "未登录账号"]);
        }
    }
    public function getScore(Request$request) {
        $token = $request->header("Authorization", "");
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array)JWT::decode($token, new Key("meme_login_token_key", "HS256"));
                $_username = $data["username"];
                if (Cache::get($_username) !== $token) {
                    return json(["code" => 401, "登录状态过期"]);
                }
                $_email = $data["email"];
                $user = Db::connect("mysql")
                    ->table("user")
                    ->where("username", $_username)
                    ->where("email", $_email)
                    ->find();
                if ($user) {
                    $score = Db::connect("mysql")
                        ->table("score")
                        ->where("user", $user["id"])
                        ->select();
                    for ($i = 0; $i < count($score); ++$i) {
                        $item = $score[$i];
                        $item["url"] = $request->domain() . "/pics/image/" . $item["pic"];
                        $score[$i] = $item;
                    }
                    return json(["code" => 200, "msg" => "数据获取成功", "data" => $score]);
                } else {
                    return json(["code" => 401, "msg" => "用户信息错误"]);
                }
            } catch (SignatureInvalidException|\DomainException|BeforeValidException|ExpiredException$e) {
                return json(["code" => 401, "msg" => "登录状态过期", "exception" => $e->getMessage()]);
            }
        }else{
            return json(["code" => 403, "msg" => "未登录"]);
        }
    }
    public function addScore(Request$request) {
        $token = $request->header("Authorization", "");
        $pic = $request->post("pic");
        $score = $request->post("score");
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array)JWT::decode($token, new Key("meme_login_token_key", "HS256"));
                $_username = $data["username"];
                if (Cache::get($_username) !== $token) {
                    return json(["code" => 401, "登录状态过期"]);
                }
                $_email = $data["email"];
                $user = Db::connect("mysql")
                    ->table("user")
                    ->where("username", $_username)
                    ->where("email", $_email)
                    ->find();
                if ($user) {
                    $group = Db::connect("mysql")
                        ->table("group")
                        ->where("id", $user["group"])
                        ->find();
                    if ($group) {
                        if ($group["score"] === "Y") {
                            $_score = Db::connect("mysql")
                                ->table("score")
                                ->where("pic", $pic)
                                ->where("user", $user["id"])
                                ->find();
                            if ($_score) {
                                return json(["code" => 401, "msg" => "不能重复评分"]);
                            }
                            Db::connect("mysql")
                                ->table("score")
                                ->insert([
                                    "pic" => $pic,
                                    "user" => $user["id"],
                                    "score" => $score,
                                    "create" => date("Y-m-d H:i:s"),
                                    "update" => date("Y-m-d H:i:s")
                                ]);
                            return json(["code" => 200, "msg" => "评分成功"]);
                        } else{
                            return json(["code" => 403, "msg" => "没有评分权限"]);
                        }
                    } else {
                        return json(["code" => 401, "msg" => "没有权限"]);
                    }
                } else {
                    return json(["code" => 401, "msg" => "用户信息错误"]);
                }
            } catch (SignatureInvalidException|\DomainException|BeforeValidException|ExpiredException$e) {
                return json(["code" => 401, "msg" => "登录状态过期", "exception" => $e->getMessage()]);
            }
        }else{
            return json(["code" => 403, "msg" => "未登录"]);
        }
    }
    public function updateScore(Request$request) {
        return json(["code" => 200, "msg" => "更新评分"]);
    }
}
