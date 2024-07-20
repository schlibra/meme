-- 用户表结构
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
    `userId` int NOT NULL AUTO_INCREMENT COMMENT '用户ID',
    `username` varchar(50) NOT NULL COMMENT '用户名',
    `password` longtext NOT NULL COMMENT '密码',
    `nickname` varchar(50) NULL COMMENT '昵称',
    `email` varchar(50) NOT NULL COMMENT '邮箱',
    `verified` varchar(1) NULL COMMENT '邮箱是否验证',
    `create` datetime NOT NULL COMMENT '创建时间',
    `groupId` int NOT NULL COMMENT '用户组',
    CONSTRAINT `group` FOREIGN KEY (`groupId`) REFERENCES `group`(`groupId`) ON DELETE NO ACTION ON UPDATE NO ACTION ,
    `ban` varchar(1) NOT NULL COMMENT '用户是否封禁',
    `reason` varchar(100) NULL COMMENT '用户封禁原因',
    `birth` int NOT NULL DEFAULT 2004 COMMENT '出生年份',
    `sex` varchar(10) NULL COMMENT '性别',
    `description` varchar(500) NULL COMMENT '个人介绍',
    PRIMARY KEY (`userId`)
) CHARACTER SET = utf8;
-- 用户表数据
/*
 默认用户：user 123456
 */
INSERT INTO `user`
(`username`, `password`, `nickname`, `email`, `create`, `groupId`, `ban`)
VALUES
('user', '$2y$10$tycuij9Esug9UsdWcC48RuKXLXD3kHjNQjA/0aCb6h9qNaU3f3mOu', '用户1', 'user@example.com', NOW(), 1, 'N'),
('admin', '$2y$10$Az47ZzIHReD7BRTx5sfz6uJ.O6hqH98W5usfSdU1mNJe/EBBTQ7AW', '管理员', 'admin@example.com', now(), '2', 'N');

-- 用户组表
DROP TABLE IF EXISTS `group`;
CREATE TABLE IF NOT EXISTS `group`  (
    `groupId` int NOT NULL AUTO_INCREMENT COMMENT '用户组id',
    `groupName` varchar(30) NOT NULL COMMENT '用户组名称',
    `default` varchar(1) NOT NULL COMMENT '默认用户组',
    `admin` varchar(1) NOT NULL COMMENT '是否管理员',
    `uploadPic` varchar(1) NOT NULL COMMENT '允许上传图片',
    `updatePic` varchar(1) NOT NULL COMMENT '允许更新图片',
    `deletePic` varchar(1) NOT NULL COMMENT '允许删除图片',
    `restorePic` varchar(1) NOT NULL COMMENT '允许还原图片',
    `sendComment` varchar(1) NOT NULL COMMENT '允许评论',
    `updateComment` varchar(1) NOT NULL COMMENT '允许更新评论',
    `deleteComment` varchar(1) NOT NULL COMMENT '允许删除评论',
    `restoreComment` varchar(1) NOT NULL COMMENT '允许还原评论',
    `sendScore` varchar(1) NOT NULL COMMENT '允许评分',
    `updateScore` varchar(1) NOT NULL COMMENT '允许更新评分',
    `deleteScore` varchar(1) NOT NULL COMMENT '允许删除评分',
    `restoreScore` varchar(1) NOT NULL COMMENT '允许还原评分',
    `create` datetime NOT NULL COMMENT '创建时间',
    `update` datetime NOT NULL COMMENT '修改时间',
    PRIMARY KEY (`groupId`)
) CHARACTER SET = utf8;
-- 用户组数据
INSERT INTO `group`
(`groupName`, `default`, `admin`, `uploadPic`, `updatePic`, `deletePic`, `restorePic`, `sendComment`, `updateComment`, `deleteComment`, `restoreComment`, `sendScore`, `updateScore`, `deleteScore`, `restoreScore`, `create`, `update`)
VALUES
('普通用户', 'Y', 'N', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'N', NOW(), NOW()),
('管理员', 'N', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', NOW(), NOW());

-- 图片表
DROP TABLE IF EXISTS `pics`;
CREATE TABLE IF NOT EXISTS `pics`  (
    `picId` int NOT NULL AUTO_INCREMENT COMMENT '数据id',
    `name` varchar(50) NOT NULL COMMENT '图片名称',
    `description` varchar(200) NOT NULL COMMENT '图片描述',
    `userId` int NOT NULL COMMENT '上传者id',
    CONSTRAINT `userId` FOREIGN KEY (`userId`) REFERENCES `user`(`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    `data` longtext NOT NULL COMMENT '图片数据(base64)',
    `type` varchar(50) NOT NULL COMMENT '文件类型',
    `verified` varchar(1) NOT NULL COMMENT '图片是否审核',
    `create` datetime NOT NULL COMMENT '创建时间',
    `update` datetime NOT NULL COMMENT '更新时间',
    `delete` datetime NULL COMMENT '删除时间',
    PRIMARY KEY (`picId`)
) CHARACTER SET = utf8;

-- 评分表
DROP TABLE IF EXISTS `score`;
CREATE TABLE IF NOT EXISTS `score`  (
    `scoreId` int NOT NULL AUTO_INCREMENT COMMENT '数据id',
    `picId` int NOT NULL COMMENT '图片id',
    CONSTRAINT `picId` FOREIGN KEY (`picId`) REFERENCES `pics`(`picId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    `userId` int NOT NULL COMMENT '用户id',
    CONSTRAINT `userId` FOREIGN KEY (`userId`) REFERENCES `user`(`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    `score` float NOT NULL COMMENT '评分',
    `create` datetime NOT NULL COMMENT '创建时间',
    `update` datetime NOT NULL COMMENT '修改时间',
    `delete` datetime NULL COMMENT '删除时间',
    PRIMARY KEY (`scoreId`)
) CHARACTER SET = utf8;

-- 评论表
DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment`  (
    `commentId` int NOT NULL AUTO_INCREMENT COMMENT '数据id',
    `picId` int NOT NULL COMMENT '图片id',
    CONSTRAINT `picId` FOREIGN KEY (`picId`) REFERENCES `pics`(`picId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    `userId` int NOT NULL COMMENT '用户id',
    CONSTRAINT `userId` FOREIGN KEY (`userId`) REFERENCES `user`(`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    `reply` int NOT NULL COMMENT '回复评论id',
    `comment` varchar(500) NOT NULL COMMENT '评论内容',
    `verified` varchar(1) NOT NULL COMMENT '评论通过审核',
    `update` datetime NOT NULL COMMENT '评论更新时间',
    `create` datetime NOT NULL COMMENT '评论发送时间',
    `delete` datetime NULL COMMENT '评论删除时间',
    PRIMARY KEY (`commentId`)
) CHARACTER SET = utf8;