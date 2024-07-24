<?php

namespace app\model;

use think\Model;

class BasicModel extends Model
{
    protected $table = "basic";
    protected $pk = "settingId";
    protected $schema = [
        "settingId" => "int",
        "siteName" => "string",
        "siteLogo" => "string",
        "enableHomeTyping" => "string",
        "enableGravatarCDN" => "string",
        "gravatarCDNAddress" => "string",
        "enablePicCompress" => "string",
        "picCompressType" => "string",
        "enablePictureVerify" => "string",
        "enableCommentVerify" => "string",
        "enableCaptcha" => "string",
        "enableUserLog" => "string",
        "enableAdminLog" => "string",
    ];
}