<?php

namespace app\model;

use think\Model;

class GroupModel extends Model
{
    protected $table = "group";
    protected $pk = "id";
    protected $connection = "mysql";
    protected $schema = [
        "id"                => "int",
        "name"              => "string",
        "admin"             => "string",
        "upload"            => "string",
        "updatePic"         => "string",
        "deletePic"         => "string",
        "restorePic"        => "string",
        "comment"           => "string",
        "updateComment"     => "string",
        "deleteComment"     => "string",
        "restoreComment"    => "string",
        "score"             => "string",
        "updateScore"       => "string",
        "deleteScore"       => "string",
        "restoreScore"      => "string",
        "create"            => "datetime",
        "update"            => "datetime"
    ];
}