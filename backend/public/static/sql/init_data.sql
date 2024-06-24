-- 用户表结构
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
    `id` int NOT NULL AUTO_INCREMENT COMMENT '用户ID',
    `username` varchar(50) NOT NULL COMMENT '用户名',
    `password` longtext NOT NULL COMMENT '密码',
    `nickname` varchar(50) NULL COMMENT '昵称',
    `email` varchar(50) NOT NULL COMMENT '邮箱',
    `create` datetime NOT NULL COMMENT '创建时间',
    `group` int NOT NULL COMMENT '用户组',
    `ban` varchar(1) NOT NULL COMMENT '用户是否封禁',
    `reason` varchar(100) NULL COMMENT '用户封禁原因',
    `birth` int NOT NULL DEFAULT 2004 COMMENT '出生年份',
    `sex` int NULL COMMENT '性别',
    `description` varchar(500) NULL COMMENT '个人介绍',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8;
-- 用户表数据
INSERT INTO `user`
(`username`, `password`, `nickname`, `email`, `create`, `group`, `ban`)
VALUES
('user', '$2y$10$tycuij9Esug9UsdWcC48RuKXLXD3kHjNQjA/0aCb6h9qNaU3f3mOu', '用户1', 'user@example.com', NOW(), 1, 'N');
