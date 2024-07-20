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
        $auth = loginAuth($request, true);
        if ($auth["status"]) {
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
            return jb(401, $auth["msg"]);
        }
    }
    function createGroup(Request$request):Json {
        $auth = loginAuth($request, true);
        $groupName = $request->post("groupName", "");
        $admin = $request->post("admin", "N");
        $uploadPic = $request->post("uploadPic", "N");
        $updatePic = $request->post("updatePic", "N");
        $deletePic = $request->post("deletePic", "N");
        $restorePic = $request->post("restorePic", "N");
        $sendComment = $request->post("sendComment", "N");
        $updateComment = $request->post("updateComment", "N");
        $deleteComment = $request->post("deleteComment", "N");
        $restoreComment = $request->post("restoreComment", "N");
        $sendScore = $request->post("sendScore", "N");
        $updateScore = $request->post("updateScore", "N");
        $deleteScore = $request->post("deleteScore", "N");
        $restoreScore = $request->post("restoreScore", "N");
        if ($auth["status"]) {
            $group = new GroupModel;
            $group->groupName = $groupName;
            $group->default = "N";
            $group->admin = $admin;
            $group->uploadPic = $uploadPic;
            $group->updatePic = $updatePic;
            $group->deletePic = $deletePic;
            $group->restorePic = $restorePic;
            $group->sendComment = $sendComment;
            $group->updateComment = $updateComment;
            $group->deleteComment = $deleteComment;
            $group->restoreComment = $restoreComment;
            $group->sendScore = $sendScore;
            $group->updateScore = $updateScore;
            $group->deleteScore = $deleteScore;
            $group->restoreScore = $restoreScore;
            $group->create = now();
            $group->update = now();
            $group->save();
            return jb(200, "用户组创建成功");
        } else {
            return jb(401, $auth["msg"]);
        }
    }
    function deleteGroup(Request$request): Json{
        $auth = loginAuth($request, true);
        $groupId = $request->get("groupId");
        if ($auth["status"]) {
            $group = GroupModel::where("groupId", $groupId)->findOrEmpty();
            if ($group->isEmpty()) {
                return jb(404, "指定的用户组不存在");
            } else {
                if ($group->default === "Y") {
                    return jb(403, "默认用户组不能删除");
                } else {
                    $group->delete();
                    return jb(200, "用户组删除成功");
                }
            }
        } else {
            return jb(401, $auth["msg"]);
        }
    }
    function updateGroup(Request$request):Json {
        $auth = loginAuth($request, true);
        $groupId = $request->post("groupId");
        $groupName = $request->post("groupName");
        $admin = $request->post("admin");
        $default = $request->post("default");
        $uploadPic = $request->post("uploadPic");
        $updatePic = $request->post("updatePic");
        $deletePic = $request->post("deletePic");
        $restorePic = $request->post("restorePic");
        $sendComment = $request->post("sendComment");
        $updateComment = $request->post("updateComment");
        $deleteComment = $request->post("deleteComment");
        $restoreComment = $request->post("restoreComment");
        $sendScore = $request->post("sendScore");
        $updateScore = $request->post("updateScore");
        $deleteScore = $request->post("deleteScore");
        $restoreScore = $request->post("restoreScore");
        if ($auth["status"]) {
            if ($default) {
                $defaultGroup = GroupModel::where("groupId", $default)->findOrEmpty();
                if ($defaultGroup->isEmpty()) {
                    return jb(404, "指定用户组不存在");
                } else {
                    GroupModel::where("default", "Y")->update(["default" => "N"]);
                    GroupModel::where("groupId", $default)->update(["default" => "Y"]);
                    return jb(200, "默认用户组更新成功");
                }
            } else {
                $group = GroupModel::where("groupId", $groupId)->findOrEmpty();
                if ($group->isEmpty()) {
                    return jb(404, "指定的用户组不存在");
                } else {
                    if ($groupName) $group->groupName = $groupName;
                    if ($admin) $group->admin = $admin;
                    if ($uploadPic) $group->uploadPic = $uploadPic;
                    if ($updatePic) $group->updatePic = $updatePic;
                    if ($deletePic) $group->deletePic = $deletePic;
                    if ($restorePic) $group->restorePic = $restorePic;
                    if ($sendComment) $group->sendComment = $sendComment;
                    if ($updateComment) $group->updateComment = $updateComment;
                    if ($deleteComment) $group->deleteComment = $deleteComment;
                    if ($restoreComment) $group->restoreComment = $restoreComment;
                    if ($sendScore) $group->sendScore = $sendScore;
                    if ($updateScore) $group->updateScore = $updateScore;
                    if ($deleteScore) $group->deleteScore = $deleteScore;
                    if ($restoreScore) $group->restoreScore = $restoreScore;
                    $group->update = now();
                    $group->save();
                    return jb(200, "用户组更新成功");
                }
            }
        } else {
            return jb(401, $auth["msg"]);
        }
    }
    function getUser(Request$request): Json {
        $auth = loginAuth($request, true);
        if ($auth["status"]) {
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
            return jb(401, $auth["msg"]);
        }
    }
}