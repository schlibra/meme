<?php

namespace app\controller;

use app\model\GroupModel;
use app\model\UserModel;
use app\Request;
use think\response\Json;

class Admin
{
    function getBasic(): Json {
        return jb();
    }
    function getSecurity(): Json{
        return jb();
    }
    function getGroup(Request$request): Json {
        $auth = loginAuth($request);
        if ($auth["status"]) {
            $user = $auth["data"];
            if ($user->group->admin === "Y") {
                $group = GroupModel::select();
                foreach ($group as &$item) {
                    $userCount = UserModel::where("groupId", $item->groupId)->count();
                    $item->userCount = $userCount;
                }
                if ($group->isEmpty()) {
                    return jb(400, "数据异常");
                } else {
                    return jb(200, "数据获取成功", $group->toArray());
                }
            } else {
                return jb(403, "没有管理员权限");
            }
        } else {
            return jb(401, $auth["msg"]);
        }
    }
    function getUser(Request$request): Json {
        $auth = loginAuth($request);
        if ($auth["status"]) {
            $user = $auth["data"];
            if ($user->group->admin === "Y") {
                $users = UserModel::select();
                if ($users->isEmpty()){
                    return jb(400, "数据异常");
                } else {
                    foreach ($users as &$_user) {
                        $_user->groupName = $_user->group->groupName;
                    }
                    return jb(200, "数据获取成功", $users->toArray());
                }

            } else {
                return jb(403, "没有管理员权限");
            }
        } else {
            return jb(401, $auth["msg"]);
        }
    }
}