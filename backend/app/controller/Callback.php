<?php

namespace app\controller;

use app\Request;
use WpOrg\Requests\Requests;

class Callback {
    function sckurCallback(Request$request) {
        $apiKey = "9c6f72ca65ee27f298afd6bf0c1b3506";
        $tokenType = $request->post("token_type");
        $accessToken = $request->post("access_token");
        $refreshToken = $request->post("refresh_token");
        $res = Requests::get("https://passport.sckur.com/api/get?api_key=$apiKey&request=get_userinfo&params=[\"all\"]", [
            "Authorization" => "Bearer $accessToken"
        ]);
        return json(json_decode($res->body, true));
    }
}