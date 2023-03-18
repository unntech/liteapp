-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- 主机： 10.100.112.6
-- 生成日期： 2023-03-18 19:10:30
-- 服务器版本： 5.7.39-log
-- PHP 版本： 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- 数据库： `dfgg_lth`
--

-- --------------------------------------------------------

--
-- 表的结构 `alog`
--

CREATE TABLE `alog` (
  `id` int(4) NOT NULL,
  `type` varchar(100) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `log1` text CHARACTER SET utf8mb4,
  `log2` text CHARACTER SET utf8mb4,
  `log3` text CHARACTER SET utf8mb4
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='log';

--
-- 转储表的索引
--

--
-- 表的索引 `alog`
--
ALTER TABLE `alog`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `alog`
--
ALTER TABLE `alog`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
COMMIT;
