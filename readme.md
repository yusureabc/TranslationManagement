# 译文管理平台


|Author|Sure Yu|
|---|---
|E-mail|yusureyes@163.com

#### 项目介绍
　　这是一个用于管理多语言资源的译文管理系统，由于用户分布于全球各地，公司的 App 需要显示多语言，Android 和 iOS 有大量的译文需要管理，手工维护极其麻烦，于是这个系统诞生了，方便 translator 在平台翻译，翻译完成之后，开发者将一键导出代码，直接放置在项目中。

* 注意 translator 需要自己找人翻译，本系统只是维护译文资源，并不会自动翻译。

  **公司 App 下载方式：软件商店搜索  `Yeelight`**

#### 软件架构
PHP 7.1  
Mysql 5.6+  
框架:　Laravel 5.3  
后台系统:　[iDashboard](https://github.com/lanceWan/iDashboard "iDashboard")  

#### 安装教程

1. `git clone` 项目至本地目录
2. `composer install`
3. `cp .env.example .env`  修改配置信息
4. `php artisan key:generate`  生成 APP_KEY
5. database/sql  找到 SQL 文件导入数据库
6. 配置 Apache / Nginx 站点，浏览访问

#### 使用说明
##### 如何录入源语言（中文）
1. 创建应用（可以后期创建）：可以将多个 Project 分配到一个应用下，因为项目迭代会出现多个 Project，为方便管理，增加应用管理。
2. 创建项目：点击 Project List，勾选需要翻译的语言，右上角添加项目
3. 回到 Project List，点击 “录入” 按钮，一个小键盘的图标，录入 key（程序用的） 和 源语言（中文）

##### 如何配置待翻译语言：
修改配置文件`config/languages.php`
```
return [
    /* 英语 */
    'en'    => 'English',
    /* 韩语 */
    'ko'    => 'Korean',
    /* 法语 */
    'fr'    => 'French',
];
```
##### 原文录入完成之后，如何邀请 translator 帮忙翻译：
1. 首先帮 translator 创建好账号，并发送给他。
2. 点击查看 Project，在语言管理页面，点击红色的小手图标邀请按钮，将其账号勾选提交。
![](http://yusure.cn/usr/uploads/2018/07/1033760907.png)
![](http://yusure.cn/usr/uploads/2018/07/1158591791.png)
3. 在邀请的图标后面是锁定功能，锁定之后，translator 不能修改译文，在 translator 完成翻译之后，该语言的译文自动锁定，如果需要修改，管理员可以随时解锁。
4. 最后面是给 translator 发送邮件提醒，邮箱是帮 translator 创建账号时添加的，发信配置在 .env 文件。

##### 如何配置对照语言：
例如翻译英文需要参考中文，翻译法语需要英文作为参考，那么就需要修改这个配置文件
`config/translator.php`

##### 如何导出译文：
  当译文都 ready 的时候，需要导出译文，导出译文有两种方式：第一种基于语言去导出，第二种针对整个应用（可以合并多个 Project）可以导出压缩包。  
目前可以导出三种格式 Android xml、iOS strings、RN js。  

本项目在公司内部运行半年有余，经过很多细节优化，为 Android、iOS 工程师提供了便利，现在将其开源出来，为开源事业添砖加瓦！  
本项目为开源项目，允许把它用于任何地方，不受任何约束，欢迎 star、 fork 项目。
* GitHub 托管地址：https://github.com/yusureabc/TranslationManagement
* Gitee  托管地址：https://gitee.com/yusure/TranslationManagement