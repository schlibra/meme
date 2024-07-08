<?php

namespace app\lib;

use think\response\Json;

class JsonBack
{
    public static function jsonBack($code = 200, $msg = "", $data = null, $count = null, $token = null): Json {
        return json(["code" => $code, "msg" => $msg, "data" => $data, "count" => $count, "token" => $token]);
    }
}