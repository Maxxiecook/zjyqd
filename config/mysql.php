<?php
	 $link=@mysqli_connect('localhost','数据库账号','密码','',3306);
	    if(mysqli_connect_errno()){//判断数据库是否连接成功
	        exit(mysqli_connect_error());//输出错误并退出
	    }
	     mysqli_set_charset($link, 'utf8');//设置默认字符编码
	    mysqli_select_db($link, '数据库名');//选择特定的数据库
?>