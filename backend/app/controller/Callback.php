<?php

namespace app\controller;

use app\Request;
use WpOrg\Requests\Requests;

class Callback {
    function sckurCallback(Request$request) {
        // https://passport.sckur.com/?callback=*/api/login/callback/sckur
        $setting = getSetting();
        $apiKey = $setting["sckurApiKey"];
        $tokenType = $request->post("token_type");
        $accessToken = $request->post("access_token");
        $refreshToken = $request->post("refresh_token");
        $res = Requests::get("https://api.sckur.com/passport/get?api_key=$apiKey&action=get_userinfo&params=[\"all\"]", [
            "Authorization" => "Bearer $accessToken"
        ]);
        return json(json_decode($res->body, true));
    }
    function giteeCallback(Request$request) {
        // https://gitee.com/oauth/authorize?client_id={client_id}&redirect_uri={redirect_uri}&response_type=code
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
}