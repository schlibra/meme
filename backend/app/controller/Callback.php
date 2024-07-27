<?php

namespace app\controller;

use app\BaseController;
use app\Request;
use Firebase\JWT\JWT;
use think\facade\Cache;
use think\Response;
use WpOrg\Requests\Requests;

class Callback extends BaseController {
    function sckurCallback(Request$request): Response {
        $setting = getSetting();
        $apiKey = $setting["sckurApiKey"];
        $accessToken = $request->post("access_token");
        $userinfo = Requests::get("https://api.sckur.com/passport/get?api_key=$apiKey&action=get_userinfo&params=[\"all\"]", [
            "Authorization" => "Bearer $accessToken"
        ])->body;
        $userinfo = json_decode($userinfo, true);
        $username = $userinfo["data"]["all"]["username"];
        $nickname = $userinfo["data"]["all"]["nickname"];
        $url = $request->domain();
        $payload = [
            "iss" => $url,
            "aud" => $url,
            "kid" => $url,
            "iat" => time(),
            "exp" => time() + 36000,
            "username" => $username,
            "nickname" => $nickname,
        ];
        $token = JWT::encode($payload, thirdPartySecret, "HS256");
        Cache::set("sckur_" . $username, $token);
        $view = file_get_contents(root_path() . "view/dist/index.html");
        $view = str_replace([
            "{\$thirdPartyLoginUsername}",
            "{\$thirdPartyLoginToken}",
            "{\$thirdPartyLoginError}"
        ], [
            $username,
            $token
        ], $view);
        return response($view);
    }
    function giteeCallback(Request$request) {
        $redirect_uri = explode("?", $request->url(true))[0];
        $setting = getSetting();
        $client_id = $setting["giteeClientId"];
        $client_secret = $setting["giteeClientSecret"];
        $code = $request->get("code");
        if ($code) {
            $token = Requests::post("https://gitee.com/oauth/token?grant_type=authorization_code&code={$code}&client_id={$client_id}&redirect_uri={$redirect_uri}&client_secret={$client_secret}")->body;
            $token = json_decode($token, true);
            if (isset($token["error"])) {
                return json(["code" => 401, "msg" => $token["error_description"]]);
            } else {
                $access_token = $token["access_token"];
                $userInfo = Requests::get("https://gitee.com/api/v5/user?access_token=$access_token")->body;
                return json(json_decode($userInfo, true));
            }
        } else {
            return json(["code" => 401, "msg" => "no code"]);
        }
    }
    function githubCallback(Request$request) {
        $setting = getSetting();
        $client_id = $setting["githubClientId"];
        $client_secret = $setting["githubClientSecret"];
        $code = $request->get("code");
        $token = Requests::post("https://github.com/login/oauth/access_token", [], [
            "client_id" => $client_id,
            "client_secret" => $client_secret,
            "code" => $code,
            "accept" => "json"
        ])->body;
        $tokenList = [];
        foreach (explode("&", $token) as $item) {
            $value = explode("=", $item);
            if (count($value) === 2) {
                $tokenList[$value[0]] = $value[1];
            }
        }
        if (isset($tokenList["error"])) {
            return jb(401, urldecode($tokenList["error_description"]));
        }
        $access_token = $tokenList["access_token"];
        $userInfo = Requests::get("https://api.github.com/user", [
            "Authorization" => "Bearer $access_token"
        ])->body;
        return json(json_decode($userInfo, true));
    }
    function gitlabCallback(Request$request) {
        $setting = getSetting();
        $client_id = $setting["gitlabClientId"];
        $client_secret = $setting["gitlabClientSecret"];
        $code = $request->get("code");
        $redirect_uri = explode("?", $request->url(true))[0];
        $token = Requests::post("https://gitlab.com/oauth/token", [], [
            "client_id" => $client_id,
            "client_secret" => $client_secret,
            "code" => $code,
            "grant_type" => "authorization_code",
            "redirect_uri" => $redirect_uri
        ])->body;
        $token = json_decode($token, true);
        if (isset($token["error"])) {
            return jb(401, $token["error_description"]);
        } else {
            $access_token = $token["access_token"];
            $userinfo = Requests::get("https://gitlab.com/api/v4/user?access_token=$access_token")->body;
            return json(json_decode($userinfo));
        }
    }
    function microsoftCallback(Request$request) {
        $setting = getSetting();
        $client_id = $setting["microsoftClientId"];
        $client_secret = $setting["microsoftClientSecret"];
        $code = $request->get("code");
        $redirect_uri = explode("?", $request->url(true))[0];
        $token = Requests::post("https://login.microsoftonline.com/consumers/oauth2/v2.0/token", [], [
            "client_id" => $client_id,
            "client_secret" => $client_secret,
            "scope" => "https://graph.microsoft.com/User.Read",
            "redirect_uri" => $redirect_uri,
            "grant_type" => "authorization_code",
            "code" => $code
        ])->body;
        $token = json_decode($token, true);
        if (isset($token["error"])) {
            return $token["error_description"];
        }
        $access_token = $token["access_token"];
        $userinfo = Requests::get("https://graph.microsoft.com/v1.0/me", [
            "Authorization" => "Bearer $access_token"
        ])->body;
        return json(json_decode($userinfo, true));
    }
}