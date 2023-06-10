-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 10.100.112.6
-- 生成日期： 2023-06-11 02:49:29
-- 服务器版本： 5.7.39-log
-- PHP 版本： 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `litephp`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE `admin` (
  `id` int(4) NOT NULL,
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(32) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '昵称',
  `psw` varchar(64) NOT NULL DEFAULT '' COMMENT '密码',
  `login_num` int(4) NOT NULL DEFAULT '0' COMMENT '登入次数',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0正常1禁用2锁定，-1删除',
  `authenticator` varchar(32) NOT NULL DEFAULT '' COMMENT '谷歌二次验证',
  `auth_ids` varchar(200) NOT NULL DEFAULT '' COMMENT '角色权限ID',
  `admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '标识：0标准用户，1超级管理员，2自定义'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='管理登入表';

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`id`, `username`, `nickname`, `psw`, `login_num`, `status`, `authenticator`, `auth_ids`, `admin`) VALUES
(1, 'ADMIN', 'Admin', '7c4a8d09ca3762af61e59520943dc26494f8941b', 33, 0, '', '', 1),
(2, 'USER', 'User', '7c4a8d09ca3762af61e59520943dc26494f8941b', 8, 0, '', '1', 0),
(3, 'ABC', 'DDDD', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', 0, -1, '', '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `admin_auth`
--

CREATE TABLE `admin_auth` (
  `id` int(4) NOT NULL,
  `title` varchar(32) CHARACTER SET utf8mb4 NOT NULL COMMENT '权限名称',
  `remark` varchar(100) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '备注说明',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0禁用1启用-1删除',
  `rules` varchar(1000) NOT NULL DEFAULT '' COMMENT '节点ID'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='角色权限表';

--
-- 转存表中的数据 `admin_auth`
--

INSERT INTO `admin_auth` (`id`, `title`, `remark`, `status`, `rules`) VALUES
(1, '测试', '测试角色组', 1, '1,2,3,17,4,5,6'),
(2, '示例', '示例角色组', 1, '5,7');

-- --------------------------------------------------------

--
-- 表的结构 `admin_log`
--

CREATE TABLE `admin_log` (
  `id` int(4) NOT NULL,
  `admin_id` int(4) NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `nickname` varchar(32) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '管理员名称',
  `url` varchar(1500) NOT NULL DEFAULT '' COMMENT '操作页面',
  `title` varchar(100) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '标题',
  `content` json DEFAULT NULL COMMENT '内容',
  `ip` varchar(40) NOT NULL COMMENT 'IP',
  `addtime` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '操作时间'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='管理员日志表';

-- --------------------------------------------------------

--
-- 表的结构 `admin_node`
--

CREATE TABLE `admin_node` (
  `id` int(4) NOT NULL,
  `pid` int(4) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `node` varchar(100) NOT NULL DEFAULT '' COMMENT '节点代码',
  `title` varchar(32) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '节点标题',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0禁用1正常-1删除',
  `is_menu` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1为菜单0为权限节点',
  `params` json DEFAULT NULL COMMENT '参数',
  `condit` varchar(200) NOT NULL COMMENT '规则表达式',
  `icon` varchar(100) NOT NULL DEFAULT '',
  `sort` int(4) NOT NULL DEFAULT '0' COMMENT '排序'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='节点表';

--
-- 转存表中的数据 `admin_node`
--

INSERT INTO `admin_node` (`id`, `pid`, `node`, `title`, `status`, `is_menu`, `params`, `condit`, `icon`, `sort`) VALUES
(1, 0, 'admin/main', '控制台', 1, 1, NULL, '', '', 0),
(2, 0, '', '系统管理', 1, 1, NULL, '', '', 0),
(3, 2, 'admin/menu', '菜单管理', 1, 1, NULL, '', '', 0),
(4, 2, 'admin/setting', '基本配置', 1, 1, NULL, '', '', 0),
(5, 0, '', '权限管理', 1, 1, NULL, '', '', 0),
(6, 5, 'admin/admin', '管理员管理', 1, 1, NULL, '', '', 0),
(7, 5, 'admin/adminlog', '管理员日志', 1, 1, NULL, '', '', 0),
(8, 5, 'admin/node', '权限节点管理', 1, 1, NULL, '', '', 0),
(9, 5, 'admin/auth', '角色组管理', 1, 1, NULL, '', '', 0),
(10, 6, 'admin/admin#add', '添加', 1, 0, NULL, '', '', 0),
(11, 6, 'admin/admin#edit', '编辑', 1, 0, NULL, '', '', 0),
(12, 9, 'admin/auth#add', '添加', 1, 0, NULL, '', '', 0),
(13, 9, 'admin/auth#edit', '编辑', 1, 0, NULL, '', '', 0),
(14, 9, 'admin/auth#del', '删除', 1, 0, NULL, '', '', 0),
(15, 9, 'admin/auth_alloc', '分配权限', 1, 0, NULL, '', '', 0),
(16, 8, 'admin/node', '编辑', 1, 0, NULL, '', '', 0),
(17, 3, 'admin/menu', '编辑', 1, 0, NULL, '', '', 0),
(18, 6, 'admin/admin#authentic', '解绑动态码', 1, 0, NULL, '', '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `alog`
--

CREATE TABLE `alog` (
  `id` int(4) NOT NULL,
  `type` varchar(32) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `log1` text CHARACTER SET utf8mb4,
  `log2` text CHARACTER SET utf8mb4,
  `log3` text CHARACTER SET utf8mb4
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='log';

--
-- 转储表的索引
--

--
-- 表的索引 `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- 表的索引 `admin_auth`
--
ALTER TABLE `admin_auth`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `admin_log`
--
ALTER TABLE `admin_log`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `admin_node`
--
ALTER TABLE `admin_node`
  ADD PRIMARY KEY (`id`),
  ADD KEY `is_menu` (`is_menu`),
  ADD KEY `status` (`status`);

--
-- 表的索引 `alog`
--
ALTER TABLE `alog`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `admin_auth`
--
ALTER TABLE `admin_auth`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `admin_log`
--
ALTER TABLE `admin_log`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `admin_node`
--
ALTER TABLE `admin_node`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- 使用表AUTO_INCREMENT `alog`
--
ALTER TABLE `alog`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
