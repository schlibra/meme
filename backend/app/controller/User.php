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
            default => ""
        };
        $actionShort = match ($action) {
            "register" => "注册",
            "forget" => "重置密码",
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
                return json(["code" => 200, "msg" => "发送成功", "token" => $token]);
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
                $_email = $data["email"];
                $result = Db::connect("mysql")
                    ->table("user")
                    ->where("username", $username)
                    ->where("email", $_email)
                    ->find();
                if ($result) {
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
}
