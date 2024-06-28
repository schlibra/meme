<?php
declare (strict_types = 1);

namespace app\controller;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\facade\Cache;
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
                        "kid" => $url,
                        "iat" => time(),
                        "exp" => time() + 36000,
                        "username" => $data["username"],
                        "email" => $data["email"]
                    ];
                    $token = JWT::encode($payload, "meme_login_token_key", "HS256");
                    Cache::set($username, $token);
                    return json(["code"=>200, "msg"=>"登录成功", "token"=>$token]);
                }
            } else {
                return json(["code"=>401, "msg"=>"密码错误"]);
            }
        } else {
            return json(["code"=>401, "msg"=>"用户不存在"]);
        }
    }

    function register(Request$request) {
        $token = $request->header("Authorization", "");
        $username = $request->post("username", "");
        $nickname = $request->post("nickname", "");
        $email = $request->post("email", "");
        $code = $request->post("code", "");
        $password = $request->post("password", "");
        $password = password_hash($password, PASSWORD_BCRYPT);
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array) JWT::decode($token, new Key("meme_email_token_key", "HS256"));
                $_email = $data["email"];
                $_code = $data["code"];
                if ($email === $_email) {
                    if ($code == $_code) {
                        if (Db::connect("mysql")
                            ->table("user")
                            ->where("username", $username)
                            ->whereOr("email", $email)
                            ->find()){
                            return json(["code" => 401, "msg" => "用户名或邮箱已存在"]);
                        } else {
                            Db::connect("mysql")
                                ->table("user")
                                ->insert([
                                    "username" => $username,
                                    "nickname" => $nickname,
                                    "password" => $password,
                                    "email" => $email,
                                    "create" => date("Y-m-d H:i:s"),
                                    "group" => 1,
                                    "ban" => "N",
                                    "reason" => ""
                                ]);
                            return json(["code" => 200, "msg" => "注册成功"]);
                        }

                    } else {
                        return json(["code" => 401, "msg" => "邮箱验证码不正确"]);
                    }
                } else {
                    return json(["code" => 401, "msg" => "邮箱地址被修改"]);
                }
            } catch (SignatureInvalidException|\DomainException|BeforeValidException|ExpiredException$e) {
                return json(["code" => 401, "msg" => "Token信息解码失败：" . $e->getMessage()]);
            }
        } else {
            return json(["code" => 401, "msg" => "请发送验证码"]);
        }
    }

    function forget(Request$request) {
        $token = $request->header("Authorization", "");
        $email = $request->post("email");
        $code = $request->post("code");
        $password = $request->post("password");
        $password = password_hash($password, PASSWORD_BCRYPT);
        $username = $request->post("username");
        if (str_starts_with($token, "Bearer ")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array) JWT::decode($token, new Key("meme_email_token_key", "HS256"));
                $_email = $data["email"];
                if (Cache::get($_email) !== $token) {
                    return json(["code" => 401, "msg" => "token无效"]);
                }
                $_code = $data["code"];
                if ($email === $_email) {
                    if ($code == $_code) {
                        $result = Db::connect("mysql")
                            ->table("user")
                            ->where("email", $email)
                            ->find();
                        if ($result) {
                            if ($result["username"] === $username) {
                                Db::connect("mysql")
                                    ->table("user")
                                    ->update([
                                        "id" => $result["id"],
                                        "password" => $password
                                    ]);
                                return json(["code" => 200, "msg" => "密码更新成功"]);
                            } else {
                                return json(["code" => 401, "msg" => "用户名不匹配"]);
                            }
                        } else {
                            return json(["code" => 401, "msg" => "邮箱不存在"]);
                        }
                    } else {
                        return json(["code" => 401, "msg" => "验证码不正确"]);
                    }
                } else {
                    return json(["code" => 401, "msg" => "邮箱不正确"]);
                }

            } catch (SignatureInvalidException|\DomainException|BeforeValidException|ExpiredException$e) {
                return json(["code" => 401, "msg" => "Token信息解码失败：" . $e->getMessage()]);
            }
        } else {
            return json(["code" => 401, "msg" => "请发送验证码"]);
        }
    }

    function sendCode(Request$request)
    {
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
            return json(["code"=>400, "msg"=>"邮箱地址为空"]);
        } else {
            try {
                $mail = new PHPMailer(true);
                $mail->SMTPDebug = SMTP::DEBUG_OFF;
                $mail->isSMTP();
                $mail->Host = env("SMTP_HOST", "");
                $mail->SMTPAuth = true;
                $mail->Username = env("SMTP_USERNAME", "");
                $mail->Password = env("SMTP_PASSWORD", "");
                $mail->SMTPSecure = env("SMTP_SECURE", "ssl");
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
                return json(["code" => 200, "msg" => "发送成功，请及时查收", "token" => $token]);
            } catch (\PHPMailer\PHPMailer\Exception$e) {
                return json(["code" => 500, "msg" => "发送失败：".$e->getMessage()]);
            }
        }
    }

    function getInfo(Request $request) {
        $token = $request->header("Authorization", "");
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array) JWT::decode($token, new Key("meme_login_token_key", "HS256"));
                $username = $data["username"];
                if (Cache::get($username) !== $token) {
                    return json(["code" => 401, "msg" => "token无效"]);
                }
                $email = $data["email"];
                $row = Db::connect("mysql")
                    ->table("user")
                    ->where("username", $username)
                    ->where("email", $email)
                    ->find();
                if ($row) {
                    unset($row["password"]);
                    unset($row["ban"]);
                    unset($row["reason"]);
                    $permission = Db::connect("mysql")
                        ->table("group")
                        ->where("id", $row["group"])
                        ->find();
                    if ($permission) {
                        unset($permission["id"]);
                        $row = array_merge($row, $permission);
                        $row["groupName"] = $row["name"];
                        unset($row["name"]);
                    }
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

    function updateInfo(Request $request) {
        $token = $request->header("Authorization", "");
        $nickname = $request->post("nickname");
        $birth = $request->post("birth");
        $sex = $request->post("sex");
        $description = $request->post("description");
        $email = $request->post("email");
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array) JWT::decode($token, new Key("meme_login_token_key", "HS256"));
                $username = $data["username"];
                if (Cache::get($username) !== $token) {
                    return json(["code" => 401, "msg" => "token无效"]);
                }
                $_email = $data["email"];
                $result = Db::connect("mysql")
                    ->table("user")
                    ->where("username", $username)
                    ->where("email", $_email)
                    ->find();
                if ($result) {
                    if ($email === $_email) $email = null;
                    if ($email && Db::connect("mysql")
                        ->table("user")
                        ->where("email", $email)
                        ->find()){
                        return json(["code" => 401, "msg" => "邮箱已存在"]);
                    }
                    if ($nickname) $result["nickname"] = $nickname;
                    if ($birth) $result["birth"] = $birth;
                    if ($sex) $result["sex"] = $sex;
                    if ($description) $result["description"] = $description;
                    if ($email) {
                        $result["email"] = $email;
                        $result["verified"] = "N";
                    }
                    Db::connect("mysql")
                        ->table("user")
                        ->save($result);
                    return json(["code" => 200, "msg" => "用户信息更新成功"]);
                } else {
                    return json(["code" => 401, "msg" => "用户不存在"]);
                }
            } catch (SignatureInvalidException|\DomainException|BeforeValidException|ExpiredException$e) {
                return json(["code"=>401, "msg"=>"Token信息错误：" . $e->getMessage()]);
            }
        } else {
            return json(["code" => 401, "msg" => "未登录"]);
        }
    }
    function logout(Request$request) {
        $token = $request->header("Authorization", "");
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array) JWT::decode($token, new Key("meme_login_token_key", "HS256"));
                $username = $data["username"];
                Cache::delete($username);
                return json(["code" => 200, "msg" => "已退出登录"]);
            } catch (SignatureInvalidException|\DomainException|BeforeValidException|ExpiredException$e) {
                return json(["code" => 200, "msg" => "已退出登录"]);
            }
        } else {
            return json(["code" => 200, "msg" => "已退出登录"]);
        }
    }
    function changePassword(Request$request) {
        $token = $request->header("Authorization", "");
        $oldPassword = $request->post("oldPassword");
        $newPassword = $request->post("newPassword");
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array) JWT::decode($token, new Key("meme_login_token_key", "HS256"));
                $username = $data["username"];
                $email = $data["email"];
                if (Cache::get($username) !== $token) {
                    return json(["code" => 401, "msg" => "token无效"]);
                }
                $user = Db::connect("mysql")
                    ->table("user")
                    ->where("username", $username)
                    ->where("email", $email)
                    ->find();
                if ($user) {
                    if (password_verify($oldPassword, $user["password"])) {
                        $user["password"] = password_hash($newPassword, PASSWORD_BCRYPT);
                        Db::connect("mysql")
                            ->table("user")
                            ->save($user);
                        Cache::delete($username);
                        return json(["code" => 200, "msg" => "密码修改成功"]);
                    } else {
                        return json(["code" => 401, "msg" => "原密码不正确"]);
                    }
                } else {
                    return json(["code" => 401, "msg" => "用户信息错误"]);
                }
            } catch (SignatureInvalidException|\DomainException|BeforeValidException|ExpiredException$e) {
                return json(["code"=>401, "msg"=>"Token信息错误：" . $e->getMessage()]);
            }
        } else {
            return json(["code" => 401, "msg" => "未登录"]);
        }
    }

    function verify(Request$request)
    {
        $token = $request->header("Authorization", "");
        $code = $request->post("code");
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array)JWT::decode($token, new Key("meme_email_token_key", "HS256"));
                $_email = $data["email"];
                if (Cache::get($_email) !== $token) {
                    return json(["code" => 401, "msg" => "token无效"]);
                }
                $_code = $data["code"];
                if ($code == $_code) {
                    Db::connect("mysql")
                        ->table("user")
                        ->where("email", $_email)
                        ->update([
                            "verified" => "Y"
                        ]);
                    return json(["code" => 200, "msg" => "验证成功"]);
                } else {
                    return json(["code" => 401, "msg" => "验证码不正确"]);
                }
            } catch (SignatureInvalidException|\DomainException|BeforeValidException|ExpiredException$e) {
                return json(["code" => 401, "msg" => "Token信息错误：" . $e->getMessage()]);
            }
        } else {
            return json(["code" => 401, "msg" => "未登录"]);
        }
    }

    function getPicList(Request$request){
        $token = $request->header("Authorization", "");
        $pageSize = (int)$request->get("pageSize", 10);
        $pageNum = (int)$request->get("pageNum", 1);
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array) JWT::decode($token, new Key("meme_login_token_key", "HS256"));
                $username = $data["username"];
                $email = $data["email"];
                if (Cache::get($username) !== $token) {
                    return json(["code" => 401, "msg" => "token无效"]);
                }
                $user = Db::connect("mysql")
                    ->table("user")
                    ->where("username", $username)
                    ->where("email", $email)
                    ->find();
                if ($user) {
                    $pic = Db::connect("mysql")
                        ->table("pics")
                        ->where("user", $user["id"])
                        ->limit(($pageNum-1)*$pageSize, $pageSize)
                        ->select();
                    $picCount = Db::connect()
                        ->table("pics")
                        ->where("user", $user["id"])
                        ->count();
                    $score = Db::connect("mysql")
                        ->table("score")
                        ->select();
                    for ($i = 0; $i < count($pic); ++$i) {
                        $pic_item = $pic[$i];
                        $pic_item["score"] = 0;
                        $scoreSum = 0;
                        $scoreCount = 0;
                        $pic_item["url"] = $request->domain() . "/pics/image/" . $pic_item["id"];
                        for ($j = 0; $j < count($score); ++$j) {
                            $score_item = $score[$j];
                            if ($score_item["pic"] === $pic_item["id"]) {
                                $scoreSum += $score_item["score"];
                                $scoreCount++;
                            }
                        }
                        if ($scoreCount) {
                            $pic_item["score"] = $scoreSum / $scoreCount;
                        }
                        $pic_item["create"] = explode(" ", $pic_item["create"])[0];
                        $pic_item["update"] = explode(" ", $pic_item["update"])[0];
                        unset($pic_item["data"]);
                        unset($pic_item["user"]);
                        unset($pic_item["type"]);
                        $pic[$i] = $pic_item;
                    }
                    return json(["code" => 200, "msg" => "数据获取成功", "data" => $pic, "total" => $picCount]);
                } else {
                    return json(["code" => 401, "msg" => "用户信息错误"]);
                }
            } catch (SignatureInvalidException|\DomainException|BeforeValidException|ExpiredException$e) {
                return json(["code" => 401, "msg" => "Token信息错误：" . $e->getMessage()]);
            }
        } else {
            return json(["code" => 401, "msg" => "未登录"]);
        }
    }
    function deletePic(Request$request) {
        $token = $request->header("Authorization", "");
        $pic_id = $request->get("pic", "");
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array) JWT::decode($token, new Key("meme_login_token_key", "HS256"));
                $username = $data["username"];
                $email = $data["email"];
                if (Cache::get($username) !== $token) {
                    return json(["code" => 401, "msg" => "token无效"]);
                }
                $user = Db::connect("mysql")
                    ->table("user")
                    ->where("username", $username)
                    ->where("email", $email)
                    ->find();
                if ($user) {
                    $group = Db::connect("mysql")
                        ->table("group")
                        ->where("id", $user["group"])
                        ->find();
                    if ($group) {
                        if ($group["deletePic"] === "Y") {
                            $pic = Db::connect("mysql")
                                ->table("pics")
                                ->where("delete", "=", null)
                                ->where("id", $pic_id)
                                ->find();
                            if ($pic) {
                                if ($pic["user"] === $user["id"]) {
                                    $pic["delete"] = date("Y-m-d H:i:s");
                                    Db::connect("mysql")
                                        ->table("pics")
                                        ->save($pic);
                                    return json(["code" => 200, "msg" => "删除成功"]);
                                } else {
                                    return json(["code" => 403, "msg" => "没有权限操作该图片"]);
                                }
                            } else {
                                return json(["code" => 404, "msg" => "图片不存在"]);
                            }
                        } else {
                            return json(["code" => 403, "msg" => "没有删除权限"]);
                        }
                    } else {
                        return json(["code" => 401, "msg" => "没有权限"]);
                    }
                } else {
                    return json(["code" => 401, "msg" => "用户信息错误"]);
                }
            } catch (SignatureInvalidException|\DomainException|BeforeValidException|ExpiredException$e) {
                return json(["code" => 401, "msg" => "Token信息错误：" . $e->getMessage()]);
            }
        } else {
            return json(["code" => 401, "msg" => "未登录"]);
        }
    }
    function restorePic(Request$request) {
        $token = $request->header("Authorization", "");
        $pic_id = $request->post("pic", "");
        if (str_starts_with($token, "Bearer")) {
            $token = str_replace("Bearer ", "", $token);
            try {
                $data = (array) JWT::decode($token, new Key("meme_login_token_key", "HS256"));
                $username = $data["username"];
                $email = $data["email"];
                if (Cache::get($username) !== $token) {
                    return json(["code" => 401, "msg" => "token无效"]);
                }
                $user = Db::connect("mysql")
                    ->table("user")
                    ->where("username", $username)
                    ->where("email", $email)
                    ->find();
                if ($user) {
                    $group = Db::connect("mysql")
                        ->table("group")
                        ->where("id", $user["group"])
                        ->find();
                    if ($group) {
                        if ($group["restorePic"] === "Y") {
                            $pic = Db::connect("mysql")
                                ->table("pics")
                                ->whereNotNull("delete")
                                ->where("id", $pic_id)
                                ->find();
                            if ($pic) {
                                if ($pic["user"] === $user["id"]) {
                                    $pic["delete"] = null;
                                    Db::connect("mysql")
                                        ->table("pics")
                                        ->save($pic);
                                    return json(["code" => 200, "msg" => "还原成功"]);
                                } else {
                                    return json(["code" => 403, "msg" => "没有权限操作该图片"]);
                                }
                            } else {
                                return json(["code" => 404, "msg" => "图片不存在"]);
                            }
                        } else {
                            return json(["code" => 403, "msg" => "没有还原权限"]);
                        }
                    } else {
                        return json(["code" => 401, "msg" => "没有权限"]);
                    }
                } else {
                    return json(["code" => 401, "msg" => "用户信息错误"]);
                }
            } catch (SignatureInvalidException|\DomainException|BeforeValidException|ExpiredException$e) {
                return json(["code" => 401, "msg" => "Token信息错误：" . $e->getMessage()]);
            }
        } else {
            return json(["code" => 401, "msg" => "未登录"]);
        }
    }
}
