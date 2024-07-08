<?php
declare (strict_types = 1);

namespace app\controller;

use app\lib\Authorization;
use app\lib\JsonBack;
use app\model\CommentModel;
use app\model\PicsModel;
use app\model\ScoreModel;
use app\model\UserModel;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use think\facade\Cache;
use think\facade\Db;
use think\Request;
use think\response\Json;

class Pics
{
    public function index(Request$request): Json {
        $pageSize = (int)$request->get("pageSize", 20);
        $pageNum = (int)$request->get("pageNum", 1);
        $name = $request->get("name", "");
        $auth = Authorization::loginAuth($request);
        $userId = null;
        if ($auth["status"]) {
            $user = $auth["data"];
            $userId = $user->userId;
        }
        $pics = PicsModel::where("delete")
            ->whereLike("name", "%$name%")
            ->limit(($pageNum-1)*$pageSize, $pageSize)
            ->select();
        $picsCount = PicsModel::where("delete")
            ->whereLike("name", "%$name%")
            ->count();
        $scores = ScoreModel::where("delete")->select();
        foreach ($pics as &$picsItem) {
            $scoreSum = 0;
            $scoreCount = 0;
            $picsItem->scored = "N";
            $picsItem->score = 0;
            foreach ($scores as $scoreItem) {
                if ($scoreItem->picId === $picsItem->picId) {
                    if ($scoreItem->userId === $userId) {
                        $picsItem->scored = "Y";
                        $picsItem->myScore = $scoreItem->score;
                    }
                    $scoreSum += $scoreItem->score;
                    $scoreCount++;
                }
                if ($scoreCount) $picsItem->score = number_format($scoreSum / $scoreCount, 2);
            }
            $picsItem->nickname = $picsItem->user->nickname;
            unset($picsItem["data"], $picsItem["type"]);
            $picsItem->url = $request->domain() . "/pics/image/" . $picsItem->picId;
        }
        return JsonBack::jsonBack(200, "数据获取成功", $pics, $picsCount);
    }

    function randomPic(Request$request): Json {
        $auth = Authorization::loginAuth($request);
        $userId = null;
        if ($auth["status"]) {
            $user = $auth["data"];
            $userId = $user->userId;
        }
        $score = ScoreModel::where("delete")
            ->select();
        $picsItem = PicsModel::where("delete")
            ->order("picId", "rand()")
            ->findOrEmpty();
        $scoreSum = 0;
        $scoreCount = 0;
        $picsItem->scored = "N";
        foreach ($score as $scoreItem) {
            if ($scoreItem->picId === $picsItem->picId) {
                if ($scoreItem->userId === $userId) {
                    $picsItem->scored = "Y";
                    $picsItem->myScore = $scoreItem->score;
                }
                $scoreSum+=$scoreItem->score;
                $scoreCount++;
            }
        }
        if ($scoreCount) {
            $picsItem->score = number_format($scoreSum / $scoreCount, 2);
        } else {
            $picsItem->score = 0;
        }
        $picsItem->nickname = $picsItem->user->nickname;
        $picsItem->url = $request->domain()."/pics/image/".$picsItem->picId;
        return JsonBack::jsonBack(200, "数据获取成功", $picsItem);
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
        $data = PicsModel::where("picId", $id)->findOrEmpty();
        if ($data->isEmpty()) {
            return JsonBack::jsonBack(404, "图片不存在");
        } else {
            $img = base64_decode($data->data);
            $type = $data->type;
            return response($img)->header([
                "Content-Type" => $type
            ]);
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
        $comments = CommentModel::where("delete")
            ->where("picId", $pic)
            ->select();
        foreach ($comments as &$comment) {
            $user = $comment->user;
            $comment->nickname = $user->nickname;
            $comment->avatar = "https://cdn.tsinbei.com/gravatar/avatar/" . hash("md5", $user->email);
            if ($comment->reply > 0) {
                $reply = CommentModel::where("commentId", $comment->reply)->findOrEmpty();
                if (!$reply->isEmpty()) {
                    $replyUser = $reply->user;
                    $comment->replyNickname = $replyUser->nickname;
                }
            }
        }
        return JsonBack::jsonBack(200, "数据获取成功", $comments);
    }
}
