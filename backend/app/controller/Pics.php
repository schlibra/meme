<?php
declare (strict_types = 1);

namespace app\controller;

use app\lib\Authorization;
use app\lib\JsonBack;
use app\model\CommentModel;
use app\model\PicsModel;
use app\model\ScoreModel;
use think\Request;
use think\Response;
use think\response\Json;

class Pics {
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
    public function create(Request$request): Json {
        $auth = Authorization::loginAuth($request);
        if ($auth["status"]) {
            $user = $auth["data"];
            $group = $user->group;
            if ($group) {
                if ($group->uploadPic === "Y") {
                    if ($request->file("image")) {
                        $image = chunk_split(base64_encode(file_get_contents($request->file("image")->getPathname())));
                        $type = $request->file("image")->getMime();
                        $name = $request->post("name");
                        $description = $request->post("description");
                        if ($name) {
                            $pic = new PicsModel;
                            $pic->name = $name;
                            $pic->description = $description;
                            $pic->userId = $user->userId;
                            $pic->data = $image;
                            $pic->type = $type;
                            $pic->verified = "N";
                            $pic->create = date("Y-m-d H:i:s");
                            $pic->update = date("Y-m-d H:i:s");
                            $pic->save();
                            return JsonBack::jsonBack(200, "上传成功");
                        } else {
                            return JsonBack::jsonBack(401, "图片名称不能为空");
                        }
                    } else {
                        return JsonBack::jsonBack(401, "未选择图片");
                    }
                } else {
                    return JsonBack::jsonBack(403, "没有上传图片权限");
                }
            } else {
                return JsonBack::jsonBack(401, "没有权限");
            }
        } else {
            return JsonBack::jsonBack(401, $auth["msg"]);
        }
    }
    public function read($id): Json|Response {
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
    public function addScore(Request$request): Json {
        $auth = Authorization::loginAuth($request);
        $pic = $request->post("pic");
        $score = $request->post("score");
        if ($auth["status"]) {
            $user = $auth["data"];
            $group = $user->group;
            if ($group) {
                if ($group->sendScore === "Y") {
                    $_score = new ScoreModel;
                    $_score->picId = $pic;
                    $_score->userId = $user->userId;
                    $_score->score = $score;
                    $_score->create = date("Y-m-d H:i:s");
                    $_score->update = date("Y-m-d H:i:s");
                    $_score->save();
                    return JsonBack::jsonBack(200, "评分成功");
                } else {
                    return JsonBack::jsonBack(403, "没有评分权限");
                }
            } else {
                return JsonBack::jsonBack(401, "没有权限");
            }
        } else {
            return JsonBack::jsonBack(401, $auth["msg"]);
        }
    }
    function getComment(Request$request): Json {
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
    function addComment(Request$request): Json {
        $auth = Authorization::loginAuth($request);
        $pic = $request->post("pic");
        $comment = $request->post("comment");
        $reply = $request->post("reply");
        if ($auth["status"]) {
            $user = $auth["data"];
            $group = $user->group;
            if ($group) {
                if ($group->sendComment === "Y") {
                    $_comment = new CommentModel;
                    $_comment->userId = $user->userId;
                    $_comment->picId = $pic;
                    $_comment->comment = $comment;
                    $_comment->reply = $reply;
                    $_comment->verified = "N";
                    $_comment->create = date("Y-m-d H:i:s");
                    $_comment->update = date("Y-m-d H:i:s");
                    $_comment->save();
                    return JsonBack::jsonBack(200, "评论发送成功");
                } else {
                    return JsonBack::jsonBack(403, "没有评论权限");
                }
            } else {
                return JsonBack::jsonBack(401, "没有权限");
            }
        } else {
            return JsonBack::jsonBack(401, $auth["msg"]);
        }
    }
}
