<?php

namespace app\controller;

use app\BaseController;
use app\model\BasicModel;
use app\model\SecurityModel;
use app\model\ThirdPartyModel;
use app\Request;
use think\facade\View;
use think\Response;
use WpOrg\Requests\Requests;

class Index extends BaseController
{
    function index(Request$request): string {
        if ($request->get("dev") === "Y") {
            $view = file_get_contents("http://localhost:5173/");
            $view = str_replace("<script type=\"module\" src=\"/@id/virtual:vue-devtools-path:overlay.js\"></script>", "", $view);
        } else {
            $view = file_get_contents(root_path() . "view/dist/index.html");
        }
        $data = [];
        $basic = BasicModel::find(1);
        $security = SecurityModel::find(1);
        $thirdParty = ThirdPartyModel::find(1);
        if ($basic) $data = array_merge($data, $basic->toArray());
        if ($security) $data = array_merge($data, $security->toArray());
        if ($thirdParty) $data = array_merge($data, $thirdParty->toArray());
        foreach ($data as $key => $value) {
            $view = str_replace("{\$$key}", $value ?? "", $view);
        }
        return $view;
    }
    function js(Request$request): Response {
        $js = file_get_contents("http://localhost:5173/{$request->pathinfo()}");
        $js = str_replace("WebSocket(`\${protocol}://\${hostAndPath}`, \"vite-hmr\");", "WebSocket(`\${protocol}://localhost:5173/`, \"vite-hmr\");", $js);
        return response($js)->header([
            "Content-Type" => "application/javascript"
        ]);
    }
}
