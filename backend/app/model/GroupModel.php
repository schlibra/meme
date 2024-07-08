<?php

namespace app\model;

use think\Model;

/**
 * @mixin Model
 * @property mixed groupId
 * @property mixed name
 * @property mixed admin
 * @property mixed uploadPic
 * @property mixed updatePic
 * @property mixed deletePic
 * @property mixed restorePic
 * @property mixed sendComment
 * @property mixed updateComment
 * @property mixed deleteComment
 * @property mixed restoreComment
 * @property mixed sendScore
 * @property mixed updateScore
 * @property mixed deleteScore
 * @property mixed restoreScore
 * @property mixed create
 * @property mixed update
 * @property mixed delete
 */
class GroupModel extends Model
{
    protected $table = "group";
    protected $pk = "groupId";
    protected $schema = [
        "groupId"           => "int",
        "name"              => "string",
        "admin"             => "string",
        "uploadPic"         => "string",
        "updatePic"         => "string",
        "deletePic"         => "string",
        "restorePic"        => "string",
        "sendComment"       => "string",
        "updateComment"     => "string",
        "deleteComment"     => "string",
        "restoreComment"    => "string",
        "sendScore"         => "string",
        "updateScore"       => "string",
        "deleteScore"       => "string",
        "restoreScore"      => "string",
        "create"            => "datetime",
        "update"            => "datetime"
    ];
}