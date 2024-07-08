<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\relation\HasOne;

/**
 * @mixin Model
 */
class UserModel extends Model
{
    protected $table = "user";
    protected $pk = "id";
    protected $schema = [
        "id" => "int",
        "username" => "string",
        "password" => "string",
        "nickname" => "string",
        "email" => "string",
        "verified" => "string",
        "group" => "int",
        "ban" => "string",
        "reason" => "string",
        "birth" => "int",
        "sex" => "string",
        "description" => "string",
        "create" => "datetime"
    ];
}
