<?php

namespace app\controller;

use app\BaseController;
use app\Request;
use think\facade\View;

class Index extends BaseController
{
    function index(Request$request): string {

        return View::fetch("dist/index", [
            "siteName" => 'IURT meme',
            "siteLogo" => "",
            "enableHomeType" => "Y",
            "enableGravatarCDN" => "Y",
            "gravatarCDNAddress" => 'https://cdn.tsinbei.com/gravatar',
            "enablePicCompress" => "N",
            "picCompressType" => "no",
            "enablePictureVerify" => "N",
            "enableCommentVerify" => "N",
            "enableCaptcha" => "Y",
            "enableUserLog" => "Y",
            "enableAdminLog" => "Y",
            "enableEmail" => "Y",
            "smtpHost" => "",
            "smtpPort" => "",
            "smtpUsername" => "",
            "smtpPassword" => "",
            "smtpEncrypt" => "",
        ]);
    }
}
