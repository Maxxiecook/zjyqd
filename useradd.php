<?php
	include("./config/mysql.php");
	$sckey='';
	$qq='';
	@$sckey=$_POST['sckey'];
	@$qq=$_POST['qq'];
	if(isset($_POST['userName'])&&isset($_POST['userPwd'])){
		$userName=$_POST['userName'];
		$userPwd=$_POST['userPwd'];
		$checkName=mysqli_query($link,"select user_id from user where user_name='{$userName}'");
		if(mysqli_fetch_array($checkName)[0]){
			echo '1';
			return 0;
		}else{
			$info=mysqli_query($link,"insert into user(`user_name`,`user_pwd`,`user_qq`,`user_sckey`) values('{$userName}','{$userPwd}','{$qq}','{$sckey}')");
			if($checkName){
				echo 'yes';
				return 0;
			}else{
				echo 'no';
				return 0;
			}
		}
	}else{
		return 0;
	}
?>