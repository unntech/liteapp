
LiteApp 1.1
===============

[![Total Downloads](https://poser.pugx.org/unntech/liteapp/downloads)](https://packagist.org/packages/unntech/liteapp)
[![Latest Stable Version](https://poser.pugx.org/unntech/liteapp/v/stable)](https://packagist.org/packages/unntech/liteapp)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.0-8892BF.svg)](http://www.php.net/)
[![License](https://poser.pugx.org/unntech/liteapp/license)](https://packagist.org/packages/unntech/liteapp)

一个PHP的轻量框架

DEMO:

https://liteapp.unn.tech


## 主要新特性
* 最为接近原生PHP写法，满足习惯原开发的人员开发习惯
* 支持`PHP7`强类型（严格模式）
* 支持更多的`PSR`规范
* 原生多应用支持
* 对IDE更加友好
* 统一和精简大量用法
* 1.1 新增Admin后台管理模块


> LiteApp 1.1的运行环境要求PHP7.0+，兼容PHP8.1

## 安装

~~~
composer create-project unntech/liteapp yourApp
~~~

~~~
将目录config.sample 改名为 config，可以更据需求增加配置文件
读取例子见：tests/sample.config.php
将runtime目录设为可写权限
docs/liteapp.sql 导入至数据库
~~~

如果需要更新框架使用
~~~
composer update unntech/litephp
~~~

## 文档
Admin后台入口：http://{domain}/admin/index.php
用户名：admin 密码：123456

[完全开发手册](#)

## 命名规范

`LiteApp`遵循PSR-2命名规范和PSR-4自动加载规范。

## 参与开发

直接提交PR或者Issue即可

## 版权信息

LiteApp遵循Apache2开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有Copyright © 2022 by Jason Lin All rights reserved。

