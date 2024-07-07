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

class Pics
{
    public function index(Request$request)
    {
        $token = $request->header("Authorization", "");
        $pageSize = (int)$request->get("pageSize", 20);
        $pageNum = (int)$request->get("pageNum", 1);
        $name = $request->get("name", "");
        $userId = null;
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array)JWT::decode($token, new Key("meme_login_token_key", "HS256"));
                $_username = $data["username"];
                $_email = $data["email"];
                $user = Db::connect()
                    ->table("user")
                    ->where("username", $_username)
                    ->where("email", $_email)
                    ->find();
                if ($user) {
                    $userId = $user["id"];
                }
            } catch (SignatureInvalidException|\DomainException|BeforeValidException|ExpiredException$e) {}
        }
        $allUser = Db::connect()
            ->table("user")
            ->select();
        $userList = [];
        foreach ($allUser as $user) {
            $userList[$user["id"]] = $user;
        }
        $pics = Db::connect()
            ->table("pics")
            ->where("delete")
            ->whereLike("name", "%$name%")
            ->limit(($pageNum-1)*$pageSize, $pageSize)
            ->select();
        $count = Db::connect()
            ->table("pics")
            ->where("delete")
            ->whereLike("name", "%$name%")
            ->count();
        $score = Db::connect()
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
                if ($score_item["pic"] === $pic["id"]) {
                    if ($score_item["user"] === $userId) {
                        $pic["scored"] = "Y";
                        $pic["myScore"] = $score_item["score"];
                    }
                    if (!$score_item["delete"]) {
                        $_score += $score_item["score"];
                        $scoreCount++;
                    }
                }
                if ($scoreCount) {
                    $pic["score"] = number_format($_score / $scoreCount, 2);
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

    function randomPic(Request$request) {
        $token = $request->header("Authorization", "");
        $userId = null;
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array)JWT::decode($token, new Key("meme_login_token_key", "HS256"));
                $_username = $data["username"];
                $_email = $data["email"];
                $user = Db::connect()
                    ->table("user")
                    ->where("username", $_username)
                    ->where("email", $_email)
                    ->find();
                if ($user) {
                    $userId = $user["id"];
                }
            } catch (SignatureInvalidException|\DomainException|BeforeValidException|ExpiredException$e) {}
        }
        $allUser = Db::connect()
            ->table("user")
            ->select();
        $score = Db::connect()
            ->table("score")
            ->select();
        $item = Db::connect()
            ->table("pics")
            ->where("delete")
            ->orderRaw("rand()")
            ->find();
        $score_sum = 0;
        $score_count = 0;
        $item["scored"] = "N";
        foreach ($score as $score_item) {
            if ($score_item["pic"] === $item["id"]) {
                if ($score_item["user"] === $userId) {
                    $item["scored"] = "Y";
                    $item["myScore"] = $score_item["score"];
                }
                if (!$score_item["delete"]) {
                    $score_sum += $score_item["score"];
                    $score_count++;
                }
            }
        }
        if ($score_count) {
            $item["score"] = number_format($score_sum / $score_count, 2);
        } else {
            $item["score"] = 0;
        }
        $item["nickname"] = "未知用户";
        foreach ($allUser as $user) {
            if ($user["id"] === $item["user"]) {
                $item["nickname"] = $user["nickname"];
            }
        }
        unset($item["data"]);
        unset($item["type"]);
        $item["url"] = $request->domain() . "/pics/image/" . $item["id"];
        return json(["code" => 200, "msg" => "数据获取成功", "data" => $item]);
    }

    public function create(Request$request)
    {
        if (!$request->file("image")) {
            return json(["code" => 401, "msg" => "未选择图片"]);
        }
        $image = chunk_split(base64_encode(file_get_contents($request->file("image")->getPathname())));
        $type = $request->file("image")->getMime();
        if (empty($image)) {
            return json(["code" => 400, "msg" => "未选择图片"]);
        }
        $token = $request->header("Authorization", "");
        $description = $request->post("description", "");
        $name = $request->post("name", "");
        if (empty($name)) {
            return json(["code" => 401, "msg" => "图片名称不能为空"]);
        }
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array) JWT::decode($token, new Key("meme_login_token_key", "HS256"));
                $_username = $data["username"];
                $_email = $data["email"];
                $user = Db::connect()
                    ->table("user")
                    ->where("username", $_username)
                    ->where("email", $_email)
                    ->find();
                if ($user) {
                    $permission = Db::connect()
                        ->table("group")
                        ->where("id", $user["group"])
                        ->find();
                    if ($permission) {
                        if ($permission["upload"] === "Y") {
                            Db::connect()
                                ->table("pics")
                                ->insert([
                                    "name" => $name,
                                    "description" => $description,
                                    "user" => $user["id"],
                                    "data" => $image,
                                    "type" => $type,
                                    "verified" => "N",
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

    public function read($id)
    {
        $data = Db::connect()
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
    public function delete(Request$request,$id)
    {
        $token = $request->header("Authorization", "");
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array)JWT::decode($token, new Key("meme_login_token_key", "HS256"));
                $_username = $data["username"];
                $_email = $data["email"];
                $user = Db::connect()
                    ->table("user")
                    ->where("username", $_username)
                    ->where("email", $_email)
                    ->find();
                if ($user) {
                    $group = Db::connect()
                        ->table("group")
                        ->where("id", $user["group"])
                        ->find();
                    if ($group) {
                        $pic = Db::connect()
                            ->table("pics")
                            ->where("id", $id)
                            ->find();
                        if ($pic) {
                            if ($group["deleteComment"] === "Y" && $pic["user"] === $user["id"]) {
                                Db::connect()
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
                    return json(["code" => 401, "msg" => "登录状态过期"]);
                }
                $_email = $data["email"];
                $user = Db::connect()
                    ->table("user")
                    ->where("username", $_username)
                    ->where("email", $_email)
                    ->find();
                $pics = Db::connect()
                    ->table("pics")
                    ->where("delete")
                    ->select();
                if ($user) {
                    $score = Db::connect()
                        ->table("score")
                        ->where("user", $user["id"])
                        ->select();
                    for ($i = 0; $i < count($score); ++$i) {
                        $item = $score[$i];
                        $item["url"] = $request->domain() . "/pics/image/" . $item["pic"];
                        foreach ($pics as $pic) {
                            if ($item["pic"] === $pic["id"]) {
                                $item["name"] = $pic["name"];
                            }
                        }
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
                    return json(["code" => 401, "msg" => "登录状态过期"]);
                }
                $_email = $data["email"];
                $user = Db::connect()
                    ->table("user")
                    ->where("username", $_username)
                    ->where("email", $_email)
                    ->find();
                if ($user) {
                    $group = Db::connect()
                        ->table("group")
                        ->where("id", $user["group"])
                        ->find();
                    if ($group) {
                        if ($group["score"] === "Y") {
                            $_score = Db::connect()
                                ->table("score")
                                ->where("pic", $pic)
                                ->where("user", $user["id"])
                                ->find();
                            if ($_score) {
                                return json(["code" => 401, "msg" => "不能重复评分"]);
                            }
                            Db::connect()
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
    function addComment(Request$request) {
        $token = $request->header("Authorization", "");
        $pic = $request->post("pic");
        $reply = $request->post("reply");
        $comment = $request->post("comment");
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array)JWT::decode($token, new Key("meme_login_token_key", "HS256"));
                $_username = $data["username"];
                if (Cache::get($_username) !== $token) {
                    return json(["code" => 401, "msg" => "登录状态过期"]);
                }
                $_email = $data["email"];
                $user = Db::connect()
                    ->table("user")
                    ->where("username", $_username)
                    ->where("email", $_email)
                    ->find();
                if ($user) {
                    $permission = Db::connect()
                        ->table("group")
                        ->where("id", $user["group"])
                        ->find();
                    if ($permission) {
                        if ($permission["comment"] === "Y") {
                            Db::connect()
                                ->table("comment")
                                ->insert([
                                    "pic" => $pic,
                                    "user" => $user["id"],
                                    "reply" => $reply,
                                    "comment" => $comment,
                                    "verified" => "N",
                                    "create" => date("Y-m-d H:i:s"),
                                    "update" => date("Y-m-d H:i:s")
                                ]);
                            return json(["code" => 200, "msg" => "评论发送成功"]);
                        } else {
                            return json(["code" => 403, "msg" => "没有评论权限"]);
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
    function getComment(Request$request) {
        $pic = $request->get("pic");
        $comments = Db::connect()
            ->table("comment")
            ->where("delete")
            ->where("pic", $pic)
            ->select();
        $users = Db::connect()
            ->table("user")
            ->select();
        for ($i = 0; $i < count($comments); ++$i) {
            $comment = $comments[$i];
            for ($j = 0; $j < count($users); ++$j) {
                $user = $users[$j];
                if ($comment["user"] === $user["id"]) {
                    $comment["nickname"] = $user["nickname"];
                    $comment["avatar"] = "https://cdn.tsinbei.com/gravatar/avatar/" . hash("sha256", $user["email"]);
                }
            }
            if ($comment["reply"] > 0) {
                for ($j = 0; $j < count($comments); ++$j) {
                    for ($k = 0; $k < count($users); ++$k) {
                        if ($comments[$j]["user"] === $users[$k]["id"]) {
                            $comment["replyNickname"] = $users[$k]["nickname"];
                        }
                    }
                }
            }
            $comments[$i] = $comment;
        }
        return json(["code" => 200, "msg" => "数据获取成功", "data" => $comments]);
    }
}
