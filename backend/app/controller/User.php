<?php
/**
 * @noinspection all
 */
declare (strict_types=1);

namespace app\controller;

use app\lib\Authorization;
use app\lib\JsonBack;
use app\model\CommentModel;
use app\model\GroupModel;
use app\model\PicsModel;
use app\model\ScoreModel;
use app\model\UserModel;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use think\facade\Cache;
use think\facade\Db;
use think\Request;
use Firebase\JWT\JWT;
use think\response\Json;

class User {
    function login(Request $request): Json {
        $username = $request->post("username", "");
        $password = $request->post("password", "");
        $user = UserModel::where("username", $username)
            ->whereOr("email", $username)
            ->findOrEmpty();
        if ($user->isEmpty()) {
            return JsonBack::jsonBack(401, "用户不存在");
        } else {
            if (password_verify($password, $user->password)) {
                if ($user->ban === "Y") {
                    return JsonBack::jsonBack(401, "用户已被封禁：".$user->reason);
                } else {
                    if ($user->email === $username && $user->verified !== "Y") {
                        return JsonBack::jsonBack(401, "邮箱未通过验证，请先使用用户名登录");
                    } else {
                        $url = $request->domain();
                        $payload = [
                            "iss" => $url,
                            "aud" => $url,
                            "kid" => $url,
                            "iat" => time(),
                            "exp" => time() + 36000,
                            "username" => $user->username,
                            "email" => $user->email
                        ];
                        $token = JWT::encode($payload, "meme_login_token_key", "HS256");
                        Cache::set($username, $token);
                        return JsonBack::jsonBack(200, "登录成功", null, null, $token);
                    }
                }
            } else {
                return JsonBack::jsonBack(401, "密码错误");
            }
        }
    }
    function register(Request $request): Json{
        $username = $request->post("username", "");
        $nickname = $request->post("nickname", "");
        $email = $request->post("email", "");
        $code = $request->post("code", "");
        $password = $request->post("password", "");
        $password = password_hash($password, PASSWORD_BCRYPT);
        $auth = Authorization::emailAuth($request);
        if ($auth["status"]) {
            $data = $auth["data"];
            if ($data["email"] === $email && (string)$data["code"] === $code) {
                if (UserModel::where("username", $username)->whereOr("email", $email)->findOrEmpty()->isEmpty()) {
                    $user = new UserModel;
                    $user->username = $username;
                    $user->nickname = $nickname;
                    $user->password = $password;
                    $user->email = $email;
                    $user->verified = "Y";
                    $user->create = date("Y-m-d H:i:s");
                    $user->groupId = 1;
                    $user->ban = "N";
                    $user->reason = "";
                    $user->save();
                    return JsonBack::jsonBack(200, "用户注册成功");
                } else {
                    return JsonBack::jsonBack(401, "用户名或邮箱已存在");
                }
            } else {
                return JsonBack::jsonBack(401, "验证码不正确");
            }
        } else {
            return JsonBack::jsonBack(401, $auth["msg"]);
        }
    }
    function forget(Request $request): Json {
        $email = $request->post("email");
        $code = $request->post("code");
        $password = $request->post("password");
        $password = password_hash($password, PASSWORD_BCRYPT);
        $username = $request->post("username");
        $auth = Authorization::emailAuth($request);
        if ($auth["status"]) {
            $data = $auth["data"];
            $_email = $data["email"];
            $_code = $data["code"];
            if ($email === $_email && $code === $_code) {
                $user = UserModel::where("username", $username)
                    ->where("email", $email)
                    ->findOrEmpty();
                if ($user->isEmpty()) {
                    return JsonBack::jsonBack(401, "账号不存在");
                } else {
                    $user->password = $password;
                    $user->save();
                    return JsonBack::jsonBack(200, "密码重置成功");
                }
            } else {
                return JsonBack::jsonBack(401, "验证码不正确");
            }
        } else {
            return JsonBack::jsonBack(401, $auth["msg"]);
        }
    }
    function sendCode(Request $request): Json {
        $action = $request->post("action");
        $actionText = match ($action) {
            "register" => "注册IURT meme 2.0账号",
            "forget" => "重置IURT meme 2.0密码",
            "verify" => "验证IURT meme 2.0邮箱",
            default => ""
        };
        $actionShort = match ($action) {
            "register" => "注册",
            "forget" => "重置密码",
            "verify" => "验证邮箱",
            default => ""
        };
        $email = $request->post("email");
        if (empty($email)) {
            return json(["code" => 400, "msg" => "邮箱地址为空"]);
        } else {
            try {
                $mail = new PHPMailer(true);
                $mail->SMTPDebug = SMTP::DEBUG_OFF;
                $mail->isSMTP();
                $mail->Host = env("SMTP_HOST", "");
                $mail->SMTPAuth = true;
                $mail->Username = env("SMTP_USERNAME", "");
                $mail->Password = env("SMTP_PASSWORD", "");
                $mail->SMTPSecure = env("SMTP_SECURE", "");
                $mail->Port = env("SMTP_PORT", 25);
                $mail->setFrom(env("SMTP_USERNAME"), "IURT meme");
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = "{$actionText}验证码";
                $code = rand(111111, 999999);
                $mail->Body = "您正在{$actionText}，这是你的验证码：<mark>{$code}</mark>，验证码在10分钟内有效，请不要将验证码泄露给他人。";
                $mail->AltBody = $actionShort;
                $mail->CharSet = "UTF-8";
                $mail->send();
                $url = $request->domain();
                $payload = [
                    "iss" => $url,
                    "aud" => $url,
                    "kid" => $url,
                    "iat" => time(),
                    "exp" => time() + 600,
                    "code" => $code,
                    "email" => $email
                ];
                $token = JWT::encode($payload, "meme_email_token_key", "HS256");
                Cache::set($email, $token);
                return JsonBack::jsonBack(200, "发送成功，请及时查收", null, null, $token);
            } catch (Exception$e) {
                return JsonBack::jsonBack(500, "发送失败：".$e->getMessage());
            }
        }
    }
    function getInfo(Request $request): Json {
        $auth = Authorization::loginAuth($request);
        if ($auth["status"]) {
            $user = $auth["data"];
            unset($user["password"], $user["ban"], $user["reason"]);
            $user->avatar = "https://cdn.tsinbei.com/gravatar/avatar/" . hash("md5", $user->email);
            $group = $user->group;
            if ($group) {
                $user = array_merge($user->toArray(), $group->toArray());
            }
            return JsonBack::jsonBack(200, "数据获取成功", $user);
        } else {
            return JsonBack::jsonBack(401, $auth["msg"]);
        }
    }
    function updateInfo(Request $request): Json {
        $nickname = $request->post("nickname");
        $birth = $request->post("birth");
        $sex = $request->post("sex");
        $description = $request->post("description");
        $email = $request->post("email");
        $auth = Authorization::loginAuth($request);
        if ($auth["status"]) {
            $user = $auth["data"];
            $_email = $user->email;
            if ($email === $_email) $email = null;
            if ($email && !UserModel::where("email", $email)->findOrEmpty()->isEmpty()) {
                return json(["code" => 401, "msg" => "邮箱已存在"]);
            }
            if ($nickname) $user->nickname = $nickname;
            if ($birth) $user->birth = $birth;
            if ($sex) $user->sex = $sex;
            if ($description) $user->description = $description;
            if ($email) {
                $user->email = $email;
                $user->verified = "N";
            }
            $user->save();
            return JsonBack::jsonBack(200, "用户信息更新成功");
        } else {
            return JsonBack::jsonBack(401, $auth["msg"]);
        }
    }
    function logout(Request $request):Json {
        $auth = Authorization::loginAuth($request);
        if ($auth["status"]) {
            Cache::delete($auth["data"]->username);
        }
        return JsonBack::jsonBack(200, "已退出登录");
    }
    function changePassword(Request $request): Json {
        $newPassword = $request->post("newPassword");
        $oldPassword = $request->post("oldPassword");
        $auth = Authorization::loginAuth($request);
        if ($auth["status"]) {
            $user = $auth["data"];
            if (password_verify($oldPassword, $user->password)) {
                $user->password = password_hash($newPassword, PASSWORD_BCRYPT);
                $user->save();
                Cache::delete($user->username);
                return JsonBack::jsonBack(200, "密码修改成功");
            } else {
                return JsonBack::jsonBack(401, "原密码不正确");
            }
        } else {
            return JsonBack::jsonBack(401, $auth["msg"]);
        }
    }
    function verify(Request $request): Json {
        $code = $request->post("code");
        $auth = Authorization::emailAuth($request);
        if ($auth["status"]) {
            $data = $auth["data"];
            $_code = $data["code"];
            $_email = $data["email"];
            if ($code === $_code) {
                $user = UserModel::where("email", $_email)->findOrEmpty();
                if ($user->isEmpty()) {
                    return JsonBack::jsonBack(401, "用户不存在");
                } else {
                    $user->verified = "Y";
                    return JsonBack::jsonBack(200, "验证成功");
                }
            } else {
                return JsonBack::jsonBack(401, "验证码错误");
            }
        } else {
            return JsonBack::jsonBack(401, $auth["msg"]);
        }
    }
    function getPicList(Request $request): Json {
        $auth = Authorization::loginAuth($request);
        if ($auth["status"]) {
            $user = $auth["data"];
            $pageSize = (int)$request->get("pageSize", 10);
            $pageNum = (int)$request->get("pageNum", 1);
            $pic = PicsModel::where("userId", $user->userId)
                ->limit(($pageNum - 1) * $pageSize, $pageSize)
                ->select();
            $picCount = PicsModel::where("userId", $user->id)->count();
            $score = ScoreModel::where("delete")->select();
            foreach ($pic as &$picsItem) {
                $picsItem->score = 0;
                $scoreSum = 0;
                $scoreCount = 0;
                $picsItem->url = $request->domain() . "/pics/image/" . $picsItem->picId;
                foreach ($score as $scoreItem) {
                    if ($scoreItem->picId === $picsItem->picId) {
                        $scoreSum += $scoreItem->score;
                        $scoreCount++;
                    }
                }
                if ($scoreCount) $picsItem->score = $scoreSum / $scoreCount;
                $picsItem->create = explode(" ", $picsItem->create)[0];
                $picsItem->update = explode(" ", $picsItem->update)[0];
                unset($picsItem["data"], $picsItem["user"], $picsItem["type"]);
            }
            return JsonBack::jsonBack(200, "数据获取成功", $pic, $picCount);
        } else {
            return JsonBack::jsonBack(401, $auth["msg"]);
        }
    }
    function deletePic(Request $request): Json {
        $auth = Authorization::loginAuth($request);
        $picId = $request->get("pic", "");
        if ($auth["status"]) {
            $user = $auth["data"];
            $group = $user->group;
            if ($group) {
                if ($group->deletePic === "Y") {
                    $pic = PicsModel::where("picId", $picId)
                        ->findOrEmpty();
                    if ($pic->isEmpty()) {
                        return JsonBack::jsonBack(404, "找不到指定的图片");
                    } else {
                        if ($pic->userId === $user->userId) {
                            $pic->delete = date("Y-m-d H:i:s");
                            $pic->save();
                            return JsonBack::jsonBack(200, "图片删除成功");
                        } else {
                            return JsonBack::jsonBack(403, "没有权限操作该图片");
                        }
                    }
                } else {
                    return JsonBack::jsonBack(403, "没有删除权限");
                }
            } else {
                return JsonBack::jsonBack(401, "没有权限");
            }
        } else {
            return JsonBack::jsonBack(401, $auth["msg"]);
        }
    }
    function restorePic(Request $request): Json {
        $auth = Authorization::loginAuth($request);
        $picId = $request->post("pic", "");
        if ($auth["status"]) {
            $user = $auth["data"];
            $group = $user->group;
            if ($group) {
                if ($group->restorePic === "Y") {
                    $pic = PicsModel::where("picId", $picId)
                        ->findOrEmpty();
                    if ($pic->isEmpty()) {
                        return JsonBack::jsonBack(404, "找不到指定的图片");
                    } else {
                        if ($pic->userId === $user->userId) {
                            $pic->delete = null;
                            $pic->save();
                            return JsonBack::jsonBack(200, "图片还原成功");
                        } else {
                            return JsonBack::jsonBack(403, "没有权限操作该图片");
                        }
                    }
                } else {
                    return JsonBack::jsonBack(403, "没有还原权限");
                }
            } else {
                return JsonBack::jsonBack(401, "没有权限");
            }
        } else {
            return JsonBack::jsonBack(401, $auth["msg"]);
        }
    }

    function updatePic(Request $request): Json {
        $auth = Authorization::loginAuth($request);
        $picId = $request->post("pic");
        $name = $request->post("name");
        $description = $request->post("description");
        $image = $request->file("image");
        if ($image) {
            chunk_split(base64_encode(file_get_contents($request->file("image")->getPathname())));
        }
        if ($auth["status"]) {
            $user = $auth["data"];
            $group = $user->group;
            if ($group) {
                if ($group->updatePic === "Y") {
                    $pic = PicsModel::where("picId", $picId)->findOrEmpty();
                    if ($pic) {
                        if ($pic->userId === $user->userId) {
                            if ($name) $pic->name = $name;
                            if ($description) $pic->description = $description;
                            if ($image) $pic->data = $image;
                            $pic->save();
                            return JsonBack::jsonBack(200, "图片更新成功");
                        } else {
                            return JsonBack::jsonBack(403, "没有权限操作该图片");
                        }
                    } else {
                        return JsonBack::jsonBack(404, "找不到指定的图片");
                    }
                } else {
                    return JsonBack::jsonBack(403, "没有更新图片权限");
                }
            } else {
                return JsonBack::jsonBack(401, "没有权限");
            }
        } else {
            return JsonBack::jsonBack(401, $auth["msg"]);
        }
    }
    function getScore(Request $request): Json {
        $auth = Authorization::loginAuth($request);
        $pageSize = (int)$request->get("pageSize", 20);
        $pageNum = (int)$request->get("pageNum", 1);
        if ($auth["status"]) {
            $user = $auth["data"];
            $score = ScoreModel::where("userId", $user->userId)
                ->limit(($pageNum - 1) * $pageSize, $pageSize)
                ->select();
            $scoreCount = ScoreModel::where("userId", $user->userId)->count();
            foreach ($score as &$scoreItem) {
                $scoreItem->url = $request->domain()."/pics/image/".$scoreItem->picId;
                $pic = $scoreItem->pic;
                if ($pic) $scoreItem["name"] = $pic->name;
            }
            return JsonBack::jsonBack(200, "数据获取成功", $score, $scoreCount);
        } else {
            return JsonBack::jsonBack(401, $auth["msg"]);
        }
    }
    function updateScore(Request $request): Json {
        $auth = Authorization::loginAuth($request);
        $id = $request->post("id");
        $score = $request->post("score");
        if ($auth["status"]) {
            $user = $auth["data"];
            $group = $user->group;
            if ($group) {
                if ($group->updateScore === "Y") {
                    $scoreItem = ScoreModel::where("scoreId", $id)->findOrEmpty();
                    if ($scoreItem->isEmpty()) {
                        return JsonBack::jsonBack(404, "找不到指定的评分");
                    } else {
                        if ($scoreItem->userId === $user->userId) {
                            $scoreItem->score = $score;
                            $scoreItem->save();
                            return JsonBack::jsonBack(200, "评分修改成功");
                        } else {
                            return JsonBack::jsonBack(403, "没有权限操作这条评分");
                        }
                    }
                } else {
                    return JsonBack::jsonBack(403, "没有修改评分权限");
                }
            } else {
                return JsonBack::jsonBack(401, "没有权限");
            }
        } else {
            return JsonBack::jsonBack(401, $auth["msg"]);
        }
    }
    function deleteScore(Request $request): Json{
        $auth = Authorization::loginAuth($request);
        $id = $request->get("id");
        if ($auth["status"]) {
            $user = $auth["data"];
            $group = $user->group;
            if ($group) {
                if ($group->deleteScore === "Y") {
                    $scoreItem = ScoreModel::where("scoreId", $id)->findOrEmpty();
                    if ($scoreItem->isEmpty()) {
                        return JsonBack::jsonBack(404, "找不到指定的评分");
                    } else {
                        if ($scoreItem->userId === $user->userId) {
                            $scoreItem->delete = date("Y-m-d H:i:s");
                            $scoreItem->save();
                            return JsonBack::jsonBack(200, "评分删除成功");
                        } else {
                            return JsonBack::jsonBack(403, "没有权限操作这条评分");
                        }
                    }
                } else {
                    return JsonBack::jsonBack(403, "没有删除评分权限");
                }
            } else {
                return JsonBack::jsonBack(401, "没有权限");
            }
        } else {
            return JsonBack::jsonBack(401, $auth["msg"]);
        }
    }
    function restoreScore(Request $request): Json {
        $auth = Authorization::loginAuth($request);
        $id = $request->post("id");
        if ($auth["status"]) {
            $user = $auth["data"];
            $group = $user->group;
            if ($group) {
                if ($group->restoreScore === "Y") {
                    $scoreItem = ScoreModel::where("scoreId", $id)->findOrEmpty();
                    if ($scoreItem->isEmpty()) {
                        return JsonBack::jsonBack(404, "找不到指定的评分");
                    } else {
                        if ($scoreItem->userId === $user->userId) {
                            $scoreItem->delete = null;
                            $scoreItem->save();
                            return JsonBack::jsonBack(200, "评分还原成功");
                        } else {
                            return JsonBack::jsonBack(403, "没有权限操作这条评分");
                        }
                    }
                } else {
                    return JsonBack::jsonBack(403, "没有还原评分权限");
                }
            } else {
                return JsonBack::jsonBack(401, "没有权限");
            }
        } else {
            return JsonBack::jsonBack(401, $auth["msg"]);
        }
    }
    function getComment(Request$request): Json {
        $auth = Authorization::loginAuth($request);
        $pageSize = (int)$request->get("pageSize", 20);
        $pageNum = (int)$request->get("pageNum", 1);
        if ($auth["status"]) {
            $user = $auth["data"];
            $comment = CommentModel::where("userId", $user->userId)->limit(($pageNum - 1) * $pageSize, $pageSize)->select();
            $count = CommentModel::where("userId", $user->userId)->count();
            foreach ($comment as &$item) {
                $pic = $item->pic;
                if ($pic) $item->name = $pic->name;
                $item->url = $request->domain()."/pics/image/".$pic->picId;
            }
            return JsonBack::jsonBack(200, "数据获取成功", $comment, $count);
        }else{
            return JsonBack::jsonBack(401, $auth["msg"]);
        }
    }
}
