<?php

namespace app\controller;

use app\BaseController;
use app\Request;
use think\response\Redirect;

class Before extends BaseController {
    function sckurBefore(Request$request): Redirect {
        $redirect_uri = $request->domain() . "/api/login/callback/sckur";
        $url = "https://passport.sckur.com/?callback={$redirect_uri}";
        return redirect($url);
    }
    function giteeBefore(Request$request): Redirect {
        $setting = getSetting();
        $client_id = $setting["giteeClientId"];
        $redirect_uri = $request->domain() . "/api/login/callback/gitee";
        $url = "https://gitee.com/oauth/authorize?client_id={$client_id}&redirect_uri={$redirect_uri}&response_type=code";
        return redirect($url);
    }
    function githubBefore(): Redirect {
        $setting = getSetting();
        $client_id = $setting["githubClientId"];
        $url = "https://github.com/login/oauth/authorize?scope=user%3Aemail&client_id={$client_id}";
        return redirect($url);
    }
    function gitlabBefore(Request$request): Redirect {
        $setting = getSetting();
        $client_id = $setting["gitlabClientId"];
        $redirect_uri = $request->domain() . "/api/login/callback/gitlab";
        $url = "https://gitlab.com/oauth/authorize?client_id={$client_id}&redirect_uri={$redirect_uri}&response_type=code&scope=read_user";
        return redirect($url);
    }
    function microsoftBefore(Request$request): Redirect {
        $setting = getSetting();
        $client_id = $setting["microsoftClientId"];
        $redirect_uri = $request->domain() . "/api/login/callback/microsoft";
        $url = "https://login.microsoftonline.com/consumers/oauth2/v2.0/authorize?client_id=$client_id&response_type=code&redirect_uri=$redirect_uri&response_mode=query&scope=https://graph.microsoft.com/User.Read&state=12345";
        return redirect($url);
    }
}