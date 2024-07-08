<?php

namespace app\model;

use think\Model;

class PicsModel extends Model
{
    protected $table = "pics";
    protected $pk = "id";
    protected $schema = [
        "id"            => "int",
        "name"          => "string",
        "description"   => "string",
        "user"          => "int",
        "data"          => "string",
        "type"          => "string",
        "verified"      => "string",
        "create"        => "datetime",
        "update"        => "datetime",
        "delete"        => "datetime",
    ];
}