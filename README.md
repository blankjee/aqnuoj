## 安庆师范大学在线测评系统 - AQNUOJ

### 介绍

AQNUOJ 是基于 PHP 的高校在线评测系统。项目于2019年7月1日启动，旨在进一步完善学校信息化建设，激发学生学习程序设计语言的兴趣，锻炼大学生程序设计水平，提高大学生的综合素质，丰富校园文化气氛，拓展学生的课外活动，也为有更好的程序设计线上环境。

项目基于 Hustoj 进行二次开发，感谢 [zhblue](https://github.com/zhblue) 对本 OJ 的支持。

### 架构

AQNUOJ 由 WEB 服务和评测 Core 模块组合而成，两部分可以独立运行。可通过数据库方式进行连接或HTTP方式连接，支持分布式测评。WEB 服务采用 PHP 语言编写，数据库采用 MySQL，前端框架采用 Bootstrap，Core 端采用 C++语言编写。

### 安装

#### 注意事项

不要相信百度的老字号教程（那些是几年前的老黄历）会导致没有判断力，没有显示，不容易升级等等。

特别是不要安装 Apache，如果已经安装，请先禁用或卸载，以免80端口冲突。

不要使用 LNMP / LAMP / Cpanel /其他面板程序提供的 Mysql, Nginx, Apache, PHP 环境。安装脚本已经包含所有必需环境的安装。

#### 安装依赖性

需要先安装HUSTOJ（访问[https://github.com/zhblue/hustoj)以获取更多信息）

#### 正式安装

 1 下载最新版本的AQNUOJ Web软件包并将其另存为/home/judge/src/web.tar.gz

 2 解压缩web.tar.gz
```bash
cd /home/judge/src
mv ./web ./web-old
tar -zxvf web.tar.gz
```
 3. 配置数据库。有关详细信息，请参见原始web（web-old）中的db_info.inc.php
 4. 删除原始的jol数据库并导入下载的sql文件
### 界面

1. 主页

   ![]([https://github.com/HUANGoJIE/aqnuoj/blob/dev/gitimg/%E4%B8%BB%E9%A1%B5.png](https://github.com/HUANGoJIE/aqnuoj/blob/dev/gitimg/主页.png))

2. 公告

   ![]([https://github.com/HUANGoJIE/aqnuoj/blob/dev/gitimg/%E5%85%AC%E5%91%8A.png](https://github.com/HUANGoJIE/aqnuoj/blob/dev/gitimg/公告.png))

3. 状态

   ![]([https://github.com/HUANGoJIE/aqnuoj/blob/dev/gitimg/%E7%8A%B6%E6%80%81.png](https://github.com/HUANGoJIE/aqnuoj/blob/dev/gitimg/状态.png))

4. 后台-公告页管理

   ![]([https://github.com/HUANGoJIE/aqnuoj/blob/dev/gitimg/%E5%90%8E%E5%8F%B0-%E5%85%AC%E5%91%8A%E9%A1%B5.png](https://github.com/HUANGoJIE/aqnuoj/blob/dev/gitimg/后台-公告页.png))

5. 后台-问题管理

   ![]([https://github.com/HUANGoJIE/aqnuoj/blob/dev/gitimg/%E5%90%8E%E5%8F%B0-%E9%97%AE%E9%A2%98%E7%AE%A1%E7%90%86.png](https://github.com/HUANGoJIE/aqnuoj/blob/dev/gitimg/后台-问题管理.png))

### 浏览器支持

Chrome、Firefox、IE (IE9以上)或其他支持 bootstrap 3的浏览器。

### 许可

GNU General Public License v3.0

### 最后

如果您觉得这个项目还不错，就 star 一下吧 ：)

