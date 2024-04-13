
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
* 最为接近原生PHP写法，满足习惯原生开发的人员开发习惯
* 支持`PHP7`强类型（严格模式）
* 支持更多的`PSR`规范
* 原生多应用支持
* 对IDE更加友好
* 统一和精简大量用法
* 1.1 新增Admin后台管理模块
* 1.1.6 新增api接口控制器
* 1.1.8 新增Model支持


> LiteApp 1.1的运行环境要求PHP7.2+，兼容PHP8

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

目录结构
~~~
yourApp/
├── admin                                   #Admin后台管理模块基本程序
├── app                                     #LiteApp命名空间
│   ├── admin                               #Admin模块基础类
│   ├── api                                 #Api接口
│   │   ├── controller                      #接口控制器目录，支持分项多级子目录
│   │   └── ApiBase.php                     #接口基础类
│   ├── controller                          #控制器方法目录，支持分项多级子目录
│   ├── traits
│   ├── ...                                 #其它子模块
│   ├── app.php                             #app基础父类
│   ├── Controller.php                      #控制器调用基础类
│   ├── LiteApp.php                         #LiteApp通用类，自动载入，默认全局变量$Lite
├── config                                  #配置文件
│   ├── admin.php                           #Admin后台管理模块配置
│   ├── app.php                             #项目基础配置
│   ├── db.php                              #数据库配置文件
│   ├── redis.php                           #redis配置文件
│   ├── session.php                         #Session配置文件
├── docs                                    #文档
│   ├── liteapp.sql.gz                      #Admin模块数据库
├── include                                 #通用函数库
│   ├── common.php                          #全局通用函数
├── runtime                                 #运行临时目录，需可写权限
├── template                                #视图模板文件
│   ├── default                             #默认模板目标
│   │   ├── skin                            #样式css文件目录
│   │   ├── admin                           #Admin模块视图文件
│   │   └── ...                             #对应视图文件目录
│   ├── static                              #静态资源目录
├── tests                                   #测试样例，可删除
├── vendor                                  #composer目录
├── index.php                               #主页
├── api.php                                 #接口API方法主入口程序
├── authorize.php                           #接口API获取secret示例
├── autoload.php                            #autoload载入主程序
├── qrcode.php                              #二维码生成程序
├── route.php                               #控制器方法主入口路由程序
├── composer.json                           #
└── README.md
~~~

## 文档
~~~
Admin后台入口：http://{domain}/admin/index.php
用户名：admin 密码：123456
~~~
接口Api使用方法
~~~
http://{domain}/api.php/sample/test
采用PATH_INFO规则RESTful，接口控制器名，支持多级目录，最后一项为方法名
~~~
http控制器使用方法
~~~
http://{domain}/route.php/sample/test
采用PATH_INFO规则，控制器名，支持多级目录，最后一项为方法名，
方法名后面也可以加后缀 .php|.html (如：test.html)，不影响路由规则
~~~

[完全开发手册](#)

## 命名规范

`LiteApp`遵循PSR-2命名规范和PSR-4自动加载规范。

## 参与开发

直接提交PR或者Issue即可

## 版权信息

LiteApp遵循MIT开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有Copyright © 2022 by Jason Lin All rights reserved。

