<?php

namespace app\model;

use think\Model;

class ScoreModel extends Model
{
    protected $table = "score";
    protected $pk = "id";
    protected $schema = [
        "id"        => "int",
        "pic"       => "int",
        "user"      => "int",
        "score"     => "float",
        "create"    => "datetime",
        "update"    => "datetime",
        "delete"    => "datetime"
    ];
}