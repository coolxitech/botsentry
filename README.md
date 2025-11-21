![](https://www.spigotmc.org/data/resource_icons/55/55924.jpg)

BotSentry for China
===============
本项目是适配BotSentry插件的网页端，让你的服务器鉴权支持国内的验证码。

## 环境

* 基于PHP`8.4+`开发

> 运行环境要求PHP8.4+

## 文档

[ThinkPHP开发手册](https://doc.thinkphp.cn)


## 赞助

![扫码打赏](https://cdn.jsdelivr.net/gh/coolxitech/coolxitech/rewarding.png)

## 安装

~~~
composer create-project coolxitech/botsentry ./
~~~

启动服务

~~~
php think run
~~~

然后就可以在浏览器中访问

~~~
http://localhost:8000
~~~

如果需要更新使用
~~~
composer update coolxitech/botsentry
~~~

## 使用

需要注册[YesCaptcha](https://yescaptcha.com/i/AuTgRK)的账号并充值,用于二次验证提交数据.

在config/captcha.php中配置你想用的验证码，相关配置需要自行搜索.
在config/crack.php中配置YesCaptcha的密钥.

## 命名规范

遵循PSR-2命名规范和PSR-4自动加载规范。

## 参与开发

直接提交PR或者Issue即可

## 版权信息

BotSentry for China遵循Apache2开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有Copyright © 2025 by [酷曦科技](https://www.kuxi.tech) All rights reserved。

更多细节参阅 [LICENSE.txt](LICENSE.txt)
