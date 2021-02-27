
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device,user-scalable=no,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="./css/index.css"/>
		<title>自动签到</title>
	</head>
	<body>
		<div class="header">
			<?php
			//ip是否来自共享互联网
			if (!empty($_SERVER['HTTP_CLIENT_IP']))
			{
			    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
			}
			//ip是否来自代理
			elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			{
			    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			//ip是否来自远程地址
			else
			{
			    $ip_address = $_SERVER['REMOTE_ADDR'];
			}
			echo "当前IP：".$ip_address;
			?>
		</div>
		
		<div class="main">
			<div class="title">
				职教云自动签到
			</div>
			<div class="num">
				当前有效在线人数：100人
			</div>
			<div class="menu">
				<a href="http://pan.feidanyl.top:5244">非凡网盘</a>
				<a href="http://blog.feidanyl.top">非凡博客</a>
				<a href="./zz.html">赞助我们</a>
				<a href="#">其他功能</a>
			</div>
			<hr style="color: #3CB878;" >
			<form action="check.php" method="post">
				<div id="info1" class="info1">
					<h2>提示</h3>
					<span>当前您选择的是QQ方式进行推送签到情况，为避免提交失败，请务必添加QQ推送号：<a href="tencent://AddContact/?fromId=45&fromSubId=1&subcmd=all&uin=376414420&website=www.oicqzone.com">376414420</a>,之后再进行下一步操作，当您自动签到成功的时候会通过此QQ发送消息给您！</span>
					<button onclick="closeinfo1()" type="button">我知道了</button>
				</div>
				<div class="info2" id="info2">
					<h2>提示</h3>
					<span>当前您选择的是微信方式进行推送签到情况，为避免提交失败，请前往<a href="http://sc.ftqq.com/3.version">Server酱官网</a>按照要求关注公众号，将SCKEY填入待会提交时候的对话框，而后当签到成功会通过微信公众号提醒您！</span>
					<button onclick="closeinfo2()" type="button">我知道了</button>
				</div>
				<div class="a" id="a">账号</div><input id="zh" onFocus="zhtextin()" onblur="zhtextout()" type="text" name="userName" id="" value="" />
				<div class="b" id="b">密码</div><input id="pwd" onFocus="pwdtextin()" onblur="pwdtextout()" type="text" name="userPwd" id="" value="" />
				<div class="advise">
					推送方式：<span onclick="openinfo1();checkstatus(1)" id="qq">QQ</span><span onclick="openinfo2(),checkstatus(2)" id="wechat">微信</span>
					<input class="c" type="text" name="sckey" id="sckey" value="" placeholder="SCKEY"/>
					<input class="d" type="text" name="qq" id="qqh" value="" placeholder="QQ号"/>
				</div>
				<button id="sub" onclick="checksub()" type="button">点击提交</button>
				<script type="text/javascript">
					var a=document.getElementById('a');
					var b=document.getElementById('b');
					var zh=document.getElementById('zh');
					var pwd=document.getElementById('pwd');
					var info1=document.getElementById('info1');
					var info2=document.getElementById('info2');
					var qq=document.getElementById('qq');
					var wechat=document.getElementById('wechat');
					var sckey=document.getElementById('sckey');
					var qqh=document.getElementById('qqh');
					var qqstatus=false;
					var wechatstatus=false;
					function zhtextin(){
						a.style="top:-10px;font-size:12px;color:blue"
						zh.style="border-bottom:1px solid blue";
					}
					function zhtextout(){
						if(zh.value==''){
							a.style="top:0px;font-size:20px;color:#ff0000";
							zh.style="border-bottom:1px solid #ff0000";
						}
					}
					function pwdtextin(){
						b.style="top:40px;font-size:12px;color:blue";
						pwd.style="border-bottom:1px solid blue";
					}
					function pwdtextout(){
						if(pwd.value==''){
							b.style="top:60px;font-size:20px;color:#ff0000"
							pwd.style="border-bottom:1px solid #ff0000";
						}
					}
					function openinfo1(){
						if(!qqstatus){
							info1.style="display:block;top:0px";
						}
					}
					function closeinfo1(){
						info1.style="top:-999px;display:none";
					}
					function openinfo2(){
						if(!wechatstatus){
							info2.style="display:block;top:0px";
						}
					}
					function closeinfo2(){
						info2.style="top:-999px;display:none";
					}
					function checkstatus(i){
						if(i==1){
							if(qqstatus){
								qqstatus=!qqstatus;
								qq.style="background-color:#f7f7f7;color:#666;";
								qqh.style="display:none";
								qqh.value='';
							}else{
								qq.style="background-color:#00aaff;color:#fff;";
								qqstatus=!qqstatus;
								wechatstatus=false;
								wechat.style="background-color:#f7f7f7;color:#666;";
								qqh.style="display:block";
								sckey.style="display:none";
								sckey.value='';
							}
						}
						if(i==2){
							if(wechatstatus){
								wechatstatus=!wechatstatus;
								wechat.style="background-color:#f7f7f7;color:#666;"
								sckey.style="display:none";
								sckey.value='';
								
							}else{
								wechat.style="background-color:#00aaff;color:#fff;";
								wechatstatus=!wechatstatus;
								qqstatus=false;
								qq.style="background-color:#f7f7f7;color:#666;";
								sckey.style="display:block";
								qqh.style="display:none";
								qqh.value='';
							}
						}
					}
					
					function checksub(){
						if(zh.value==''||pwd.value==''){
							alert("账号密码不能为空");
							return 0;
						}else{
							if(wechatstatus&&sckey.value==''){
								alert("SCKEY不能为空，或者取消微信推送选项！");
								return 0;
							}else if(qqstatus&&qqh.value==''){
								alert("QQ号不能为空，或者取消QQ推送选项！");
								return 0;
							}
						}
						/* ajax请求 */
						var row="userName="+zh.value+"&"+"userPwd="+pwd.value+"&"+"sckey="+sckey.value+"&"+"qq="+qqh.value;
						function aaa(a,b){
							if(a=='yes'){
								// alert("登录成功");
								Ajax.post("useradd.php",row,bbb);
							}else{
								alert("提交失败，可能是账号密码错误");
							}
						}
						function bbb(a,b){
							if(a=='yes'){
								alert("提交成功");
							}else if(a=='1'){
								alert("已经添加过，无需重复添加！");
							}else{
								alert("提交失败");
							}
						}
						var Ajax={
						  get: function(url, fn) {
						    // XMLHttpRequest对象用于在后台与服务器交换数据   
						    var xhr = new XMLHttpRequest();            
						    xhr.open('GET', url, true);
						    xhr.onreadystatechange = function() {
						      // readyState == 4说明请求已完成
						      if (xhr.readyState == 4 && xhr.status == 200 || xhr.status == 304) { 
						        // 从服务器获得数据 
						        fn.call(this, xhr.responseText);  
						      }
						    };
						    xhr.send();
						  },
						  // datat应为'a=a1&b=b1'这种字符串格式，在jq里如果data为对象会自动将对象转成这种字符串格式
						  post: function (url, data, fn) {
						    var xhr = new XMLHttpRequest();
						    xhr.open("POST", url, true);
						    // 添加http头，发送信息至服务器时内容编码类型
						    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");  
						    xhr.onreadystatechange = function() {
						      if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 304)) {
						        fn.call(this, xhr.responseText);
						      }
						    };
						    xhr.send(data);
						  }
						}
						Ajax.post("./check.php",row,aaa);
						/*********/
					}
				</script>
			</form>
		</div>
		<div class="footer">
			开源项目<a href="https://github.com/feifanyl/zjyqd">zjyqd</a>
		</div>
	</body>
</html>
