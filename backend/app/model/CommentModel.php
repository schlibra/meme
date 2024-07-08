<?php

namespace app\model;

use think\Model;

class CommentModel extends Model
{
    protected $table = "comment";
    protected $pk = "id";
    protected $schema = [
        "id"        => "int",
        "pic"       => "int",
        "user"      => "int",
        "reply"     => "int",
        "comment"   => "string",
        "verified"  => "string",
        "create"    => "datetime",
        "update"    => "datetime",
        "delete"    => "datetime"
    ];
}