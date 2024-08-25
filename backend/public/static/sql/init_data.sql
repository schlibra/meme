# 关闭外键约束
SET FOREIGN_KEY_CHECKS=0;
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
    CONSTRAINT `user_group` FOREIGN KEY (`groupId`) REFERENCES `group`(`groupId`) ON DELETE NO ACTION ON UPDATE NO ACTION ,
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

-- 图片表
DROP TABLE IF EXISTS `pics`;
CREATE TABLE IF NOT EXISTS `pics`  (
    `picId` int NOT NULL AUTO_INCREMENT COMMENT '数据id',
    `name` varchar(50) NOT NULL COMMENT '图片名称',
    `description` varchar(200) NOT NULL COMMENT '图片描述',
    `userId` int NOT NULL COMMENT '上传者id',
    CONSTRAINT `pics_user` FOREIGN KEY (`userId`) REFERENCES `user`(`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    `data` longtext NOT NULL COMMENT '图片数据(base64)',
    `compressed` VARCHAR(1) NULL COMMENT  '图片已压缩',
    `compressType` varchar(50) NULL COMMENT '图片压缩算法',
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
    CONSTRAINT `score_pic` FOREIGN KEY (`picId`) REFERENCES `pics`(`picId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    `userId` int NOT NULL COMMENT '用户id',
    CONSTRAINT `score_user` FOREIGN KEY (`userId`) REFERENCES `user`(`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
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
    CONSTRAINT `comment_pic` FOREIGN KEY (`picId`) REFERENCES `pics`(`picId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    `userId` int NOT NULL COMMENT '用户id',
    CONSTRAINT `comment_user` FOREIGN KEY (`userId`) REFERENCES `user`(`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    `reply` int NOT NULL COMMENT '回复评论id',
    `comment` varchar(500) NOT NULL COMMENT '评论内容',
    `verified` varchar(1) NOT NULL COMMENT '评论通过审核',
    `update` datetime NOT NULL COMMENT '评论更新时间',
    `create` datetime NOT NULL COMMENT '评论发送时间',
    `delete` datetime NULL COMMENT '评论删除时间',
    PRIMARY KEY (`commentId`)
) CHARACTER SET = utf8;

-- 基本设置表
DROP TABLE IF EXISTS `basic`;
CREATE TABLE IF NOT EXISTS `basic`  (
    `settingId` int NOT NULL COMMENT '设置ID',
    `siteName` varchar(255) NULL COMMENT '站点名称',
    `siteLogo` varchar(255) NULL COMMENT '站点logo',
    `language` varchar(50) NULL COMMENT '站点语言',
    `enableHomeTyping` varchar(1) NULL COMMENT '开启首页打字效果',
    `enableGravatarCDN` varchar(1) NULL COMMENT '开启gravatar头像CDN',
    `gravatarCDNAddress` varchar(500) NULL COMMENT 'gravatar头像CDN地址',
    `enablePicCompress` varchar(1) NULL COMMENT '开启图片压缩',
    `picCompressType` varchar(10) NULL COMMENT '图片压缩方式',
    `enablePictureVerify` varchar(1) NULL COMMENT '开启图片审核',
    `enableCommentVerify` varchar(1) NULL COMMENT '开启评论审核',
    `enableCaptcha` varchar(1) NULL COMMENT '开启登录验证码',
    `enableUserLog` varchar(1) NULL COMMENT '开启用户日志',
    `enableAdminLog` varchar(1) NULL COMMENT '开启管理员日志',
    PRIMARY KEY (`settingId`)
) MAX_ROWS = 1;
-- 基本设置数据
INSERT INTO `basic` (
    `settingId`, `siteName`, `siteLogo`, `enableHomeTyping`, `enableGravatarCDN`, `gravatarCDNAddress`, `enablePicCompress`, `picCompressType`, `enablePictureVerify`, `enableCommentVerify`, `enableCaptcha`, `enableUserLog`, `enableAdminLog`
) VALUES (
    '1', 'IURT meme 2.0', '', 'Y', 'Y', '', 'Y', 'gzip', 'N', 'N', 'N', 'Y', 'Y'
);

-- 安全设置表
DROP TABLE IF EXISTS `security`;
CREATE TABLE IF NOT EXISTS `security` (
    `settingId` int NOT NULL COMMENT '设置ID',
    `enableEmail` varchar(1) NULL COMMENT '启用邮箱验证',
    `smtpHost` varchar(100) NULL COMMENT 'SMTP服务器',
    `smtpPort` varchar(10) NULL COMMENT 'SMTP端口',
    `smtpUsername` varchar(300) NULL COMMENT 'SMTP用户名',
    `smtpPassword` varchar(500) NULL COMMENT 'SMTP密码',
    `smtpEncrypt` varchar(50) NULL COMMENT 'SMTP加密方式',
    PRIMARY KEY (`settingId`)
) MAX_ROWS = 1;
-- 安全设置数据
INSERT INTO `security` (
    `settingId`, `enableEmail`
) VALUES (
    1, 'N'
);

-- 第三方平台表
DROP TABLE IF EXISTS `thirdParty`;
CREATE TABLE IF NOT EXISTS `thirdParty` (
    `settingId` int NOT NULL COMMENT '设置ID',
    `enableSckur` varchar(1) NULL COMMENT '开启思刻通行证',
    `sckurApiKey` varchar(300) NULL COMMENT '思刻通行证api key',
    `sckurClientId` varchar(300) NULL COMMENT '思刻通行证client id',
    `enableGitee` varchar(1) NULL COMMENT '开启gitee',
    `giteeClientId` varchar(300) NULL COMMENT 'gitee client id',
    `giteeClientSecret` varchar(300) NULL COMMENT 'gitee client secret',
    `enableGithub` varchar(1) NULL COMMENT '开启github',
    `githubClientId` varchar(300) NULL COMMENT 'github client id',
    `githubClientSecret` varchar(300) NULL COMMENT 'github client secret',
    `enableGitlab` varchar(1) NULL COMMENT '开启gitlab',
    `gitlabClientId` varchar(300) NULL COMMENT 'gitlab client id',
    `gitlabClientSecret` varchar(300) NULL COMMENT 'gitlab client secret',
    `enableMicrosoft` varchar(1) NULL COMMENT '开启微软登录',
    `microsoftClientId` varchar(300) NULL COMMENT '微软client id',
    `microsoftClientSecret` varchar(300) NULL COMMENT '微软client secret',
    PRIMARY KEY (`settingId`)
) MAX_ROWS = 1;
-- 第三方平台数据
INSERT INTO `thirdParty` (
    `settingId`
) VALUES (
    '1'
);

-- 用户绑定表
DROP TABLE IF EXISTS `bind`;
CREATE TABLE IF NOT EXISTS `bind` (
    `userId` int NOT NULL COMMENT '用户id',
    CONSTRAINT `bind_user` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON UPDATE NO ACTION ON DELETE NO ACTION ,
    `sckurBind` varchar(1) NULL COMMENT 'sckur绑定',
    `sckurUsername` varchar(200) NULL COMMENT 'sckur用户名',
    `sckurNickname` varchar(200) NULL COMMENT 'sckur昵称',
    `sckurAvatar` longtext NULL COMMENT '思刻通行证头像',
    `giteeBind` varchar(1) NULL COMMENT 'gitee绑定',
    `giteeUsername` varchar(200) NULL COMMENT 'gitee用户名',
    `giteeNickname` varchar(200) NULL COMMENT 'gitee昵称',
    `giteeAvatar` longtext NULL COMMENT 'gitee 头像',
    `githubBind` varchar(1) NULL COMMENT 'github绑定',
    `githubUsername` varchar(200) NULL COMMENT 'github用户名',
    `githubNickname` varchar(200) NULL COMMENT 'github昵称',
    `githubAvatar` longtext NULL COMMENT 'github 头像',
    `gitlabBind` varchar(1) NULL COMMENT 'gitlab绑定',
    `gitlabUsername` varchar(200) NULL COMMENT 'gitlab用户名',
    `gitlabNickname` varchar(200) NULL COMMENT 'gitlab昵称',
    `gitlabAvatar` longtext NULL COMMENT 'gitlab 头像',
    `microsoftBind` varchar(1) NULL COMMENT 'microsoft绑定',
    `microsoftUsername` varchar(200) NULL COMMENT 'microsoft用户名',
    `microsoftNickname` varchar(200) NULL COMMENT 'microsoft昵称',
    `microsoftAvatar` longtext NULL COMMENT '微软头像',
    PRIMARY KEY (`userId`)
);

CREATE TRIGGER `basicNotInsert`
    BEFORE INSERT
    ON `basic`
    FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE 'HY000' SET MESSAGE_TEXT = '禁止插入';
end;
CREATE TRIGGER `basicNotDelete`
    BEFORE DELETE
    ON `basic`
    FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE 'HY000' SET MESSAGE_TEXT = '禁止删除';
end;
CREATE TRIGGER `securityNotInsert`
    BEFORE INSERT
    ON `security`
    FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE 'HY000' SET MESSAGE_TEXT = '禁止插入';
end;
CREATE TRIGGER `securityNotDelete`
    BEFORE DELETE
    ON `security`
    FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE 'HY000' SET MESSAGE_TEXT = '禁止删除';
end;
CREATE TRIGGER `thirdPartyNotInsert`
    BEFORE INSERT
    ON `thirdParty`
    FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE 'HY000' SET MESSAGE_TEXT = '禁止插入';
end;
CREATE TRIGGER `thirdPartyNotDelete`
    BEFORE DELETE
    ON `thirdParty`
    FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE 'HY000' SET MESSAGE_TEXT = '禁止删除';
END;

# 开启外键约束
SET FOREIGN_KEY_CHECKS=1;