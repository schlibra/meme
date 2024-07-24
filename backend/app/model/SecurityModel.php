<?php

namespace app\model;

use think\Model;

class SecurityModel extends Model
{
    protected $table = "security";
    protected $pk = "settingId";
    protected $schema = [
        "settingId" => "int",
        "enableEmail" => "string",
        "smtpHost" => "string",
        "smtpPort" => "string",
        "smtpUsername" => "string",
        "smtpPassword" => "string",
        "smtpEncrypt" => "string",
    ];
}