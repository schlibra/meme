<?php
declare (strict_types = 1);

namespace app\controller;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use think\facade\Db;
use think\Request;
use function Symfony\Component\VarDumper\Dumper\esc;

class Pics
{
    public function index(Request$request)
    {
        $token = $request->header("Authorization", "");
        $showDelete = $request->get("showDelete", "");
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
        if ($showDelete === "Y") {
            $pics = Db::connect("mysql")
                ->table("pics")
                ->limit(($pageNum-1)*$pageSize, $pageSize)
                ->select();
            $count = Db::connect("mysql")
                ->table("pics")
                ->count();
        } else {
            $pics = Db::connect("mysql")
                ->table("pics")
                ->where("delete", "=", null)
                ->limit(($pageNum-1)*$pageSize, $pageSize)
                ->select();
            $count = Db::connect("mysql")
                ->table("pics")
                ->where("delete", "=", null)
                ->count();
        }
        for ($i = 0; $i < count($pics); ++$i) {
            $pic = $pics[$i];
            $userId = $pic["user"];
            if (isset($userList[$userId])) {
                $pic["nickname"] = $userList[$userId]["nickname"];
            } else {
                $pic["nickname"] = "未知用户";
            }
            unset($pic["data"]);
            unset($pic["type"]);
            $pic["url"] = $request->domain() . "/pics/" . $pic["id"];
            $pic["score"] = rand(4, 5);
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
                            if ($group["admin"] === "Y" || ($group["deleteComment"] === "Y" && $pic["user"] === $user["id"])) {
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
}
