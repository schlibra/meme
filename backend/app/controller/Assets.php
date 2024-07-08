<?php
declare (strict_types = 1);

namespace app\controller;

use app\lib\JsonBack;
use think\Request;
use think\Response;

class Assets {
    public function index(Request$request):Response {
        $filename = $request->url();
        $filepath = root_path() . "view/dist/$filename";
        if (file_exists($filepath)) {
            $ext = explode(".", $filename);
            $ext = end($ext);
            $type = match ($ext) {
                "js" => "application/javascript",
                "css" => "text/css",
                default => "text/plain",
            };
            header("Content-Type: $type");
            $data = file_get_contents($filepath);
            return response($data)->header([
                "Content-Type" => $type
            ]);
        } else {
            return JsonBack::jsonBack(404, "文件不存在", [
                "filename" => $filename,
                "realPath" => $filepath
            ]);
        }
    }
}
