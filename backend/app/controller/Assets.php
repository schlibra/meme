<?php
declare (strict_types = 1);

namespace app\controller;

use think\Request;

class Assets
{
    public function index(Request$request)
    {
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
            return json([
                "code" => 404,
                "msg" => "文件不存在",
                "filename" => $filename,
                "realPath" => $filepath
            ])->code(404);
        }
    }
}
