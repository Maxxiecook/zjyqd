职教云自动签到系统
以网站的形式让用户提交自己的职教云账号存入服务器，采用自动化任务的方式每隔几十秒执行一次签到程序，签到成功会以qq或者微信的方式进行推送

示例

[示例网站](http://zjy.feidanyl.top)


使用方法：

1.创建数据库，创建user表，表里创建user_id（varchar类型，自动增长）、user_name(varchar类型)、user_pwd(varchar类型)、user_qq(varchar类型)、user_sckey(varchar类型)
![](https://tu.yaohuo.me/imgs/2021/02/709d72da6dd4b04f.png)
2.将源码上传到网站的根目录
3.修改config目录下mysql.php文件的配置
4修改cs.php的第15行为自己网站访问的域名
5设置定时任务（宝塔界面可以进入计划任务设置）,每分钟访问一次域名下cs.php就可以了

![](https://tu.yaohuo.me/imgs/2021/02/951ac2f3bf755cb5.png)
