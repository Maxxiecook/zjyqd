<?php
/*
 *------------------------------------------------------
 * Title : 职教云自动签到PHP版 V3.2
 *------------------------------------------------------
 * Author : ruanmu (chengjifei@foxmaill.com)
 *------------------------------------------------------
 * Time : 2020-08-23
 *------------------------------------------------------
 * Tips : 本源码不得贩卖
 *------------------------------------------------------
 */


header('content-type:text/html;charset=UTF-8');
date_default_timezone_set("PRC");


/*
**获取GET参数 让用户自定义签到频率
*/


@$skey=$_POST['skey'];//qq推送
@$userName=$_POST['userName'];//账号
@$userPwd=$_POST['userPwd'];//密码


/*
**跟随更新协议头
*/
$emit=time()."000";
//echo date('Y-m-d H:i:s');
$equipmentModel="Xiaomi Redmi K30 Pro";
$equipmentApiVersion="10";
$equipmentAppVersion="2.8.41";
$device=getDevice($equipmentModel,$equipmentApiVersion,$equipmentAppVersion,$emit);
//header
$headers = array('Content-Type:'.'application/x-www-form-urlencoded','emit:'.$emit,'device:'.$device);
//print_r($header);


/*
**自动签到部分
*/
//login
$url="https://zjyapp.icve.com.cn/newMobileAPI/MobileLogin/newSignIn";
$data=array("clientId"=>"d902c875d5f34c0f93362139f5af0c4c","sourceType"=>"2","userPwd"=>$userPwd,"userName"=>$userName,"appVersion"=>$equipmentAppVersion,"equipmentAppVersion"=>$equipmentAppVersion,"equipmentApiVersion"=>$equipmentApiVersion,"equipmentModel"=>$equipmentModel);
//print_r($data);
$output=httppost($url,$headers,$data);
//print_r($output);;


if($output['code'] == "1"){
    $stuId = $output["userId"];
    $newtoken=$output['newToken'];
    $faceDate = date("Y-m-d");
    //echo $stuId;

//get jrkt
    $url2="https://zjyapp.icve.com.cn/newmobileapi/faceteach/getStuFaceTeachList";
//$data="stuId={$stuId}&faceDate={$faceDate}&newToken={$newtoken}";

    $data=array("stuId"=>$stuId,"faceDate"=>$faceDate,"newToken"=>$newtoken);
    $output=httppost($url2,$headers,$data);
//print_r($output);
    $todayClassInfo=$output["dataList"];

//
    $url3="https://zjyapp.icve.com.cn/newmobileapi/faceteach/newGetStuFaceActivityList";
    if(!empty($todayClassInfo)){
        foreach($todayClassInfo as $i){
            $data=array("activityId"=>$i['Id'],"stuId"=>$stuId,"classState"=>$i['state'],"openClassId"=>$i['openClassId'],"newToken"=>$newtoken);

            $output=httppost($url3,$headers,$data);
//print_r($output);
            $inClassInfo=$output["dataList"];

//
            $url4="https://zjyapp.icve.com.cn/newmobileapi/faceteach/isJoinActivities";
            if(count($inClassInfo) != "0"){
                foreach($inClassInfo as $n){
                    if ($n["DataType"] == "签到" and $n["State"] !== "3"){
                        $attendData = array("activityId"=>$i['Id'],"openClassId"=>$i['openClassId'],"stuId"=>$stuId,"typeId"=>$n['Id'],"type"=>"1","newToken"=>$newtoken);
                        $output=httppost($url4,$headers,$attendData);
                        //print_r($output);
                        $attendInfo=$output;


                        $url5="https://zjyapp.icve.com.cn/newmobileapi/faceteach/saveStuSign";
                        if($attendInfo["isAttend"] != "1"){
                            $signInData = array("signId"=>$n['Id'],"stuId"=>$stuId,"openClassId"=>$i['openClassId'],"sourceType"=>"3","checkInCode"=>$n['Gesture'],"activityId"=>$i['Id'],"newToken"=>$newtoken);
                            $output=httppost($url5,$headers,$signInData);
                            $time=date("Y-m-d H:i:s");
                            echo '账号:'.$userName.'的'.$i["courseName"]." ".$time." ".$output["msg"]."\r\n";
                            //$url6="https://push.xuthus.cc/send/{$skey}?c=账号:{$userName}的{$i['courseName']}已经签到\n\n"."本次签到时间:{$time}";
                            //$url6="https://push.xuthus.cc/group/{$skey}?c=账号:{$userName}的{$i['courseName']}已经签到\n\n"."本次签到时间:{$time}";
                            //$url18="https://push.xuthus.cc/group/eeb6d0ceed896a02cf58f279f0ca5425?c=帐号：{$userName}的{$i['courseName']}已经签到\n\n"."本次签到时间:{$time}";
                            
                            //$output=file_get_contents($urlserver);
                            //$emai=sendMail($email,"职教云签到结果","签到成功！");
                            // $server=sc_send("账号:{$userName}的{$i['courseName']}已经签到\n\n"."本次签到时间:{$time}","职教云签到结果",$skey);
                            
                            $test= '账号:'.$userName.'的'.$i["courseName"].$output["msg"];
							
                            $url8="http://api.qqpusher.yanxianjun.com/send_group_msg?token=xxxxx&group_id=123456&message={$test}&auto_escape=true";
                            // $url8="https://push.xuthus.cc/group/xxxxxx?c={$test}";
                            //$url6="https://sc.ftqq.com/{$skey}.send?text={$test}";

                            //$output=file_get_contents($url6);
                            //$url6="https://sc.ftqq.com/{$skey}.send?text=('账号:{$userName}今日无课程\n本次时间:{$time}')";
                            // $output=file_get_contents($url8);

                        }else{
                            // echo"账号:".$userName."的".$n['DateCreated'].$i["courseName"]."的签到已经签到!"."";
							echo "yes";
                        };
                    }else{
                        if($n["DataType"] == "签到"){
                            // echo  "状态:".$output['code']."账号:".$userName."的".$n['DateCreated'].$i["courseName"]."的签到已经签到!"."";							echo "yes";
							echo "yes";
                        };
                    };
                };
            };
        };
    }else{
        // echo "状态:".$output['code']."账号:".$userName."当前无签到任务"."";
		echo "yes";
        $time=date("Y-m-d\nH:i:s");
        //$url6="https://push.xuthus.cc/send/{$skey}?c=账号:{$userName}今日无课程\n本次时间:{$time}";
        //$url6="https://sc.ftqq.com/{$skey}.send?text=('账号:{$userName}当前无签到任务\n本次时间:{$time}')";
        //$output=file_get_contents($url6);



        //$emai=sendMail($email,"职教云签到结果","当前无签到任务！");
        //$server=sc_send("当前无签到任务","职教云签到结果",$skey);
    };
}else{
    // echo $output['msg'];
	echo "no";
}



/*
**核心函数 请勿更改
*/






function sc_send(  $text , $desp, $skey  )
{
    $postdata = http_build_query(
        array(
            'text' => $text,
            'desp' => $desp
        )
    );

    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );
    $context  = stream_context_create($opts);

    return $result = file_get_contents('https://sc.ftqq.com/'.$skey.'.send', false, $context);

}

function httppost($url,$headers,$data){
    $curl=curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);

    curl_setopt($curl, CURLOPT_POST, 1);

    curl_setopt($curl, CURLOPT_POSTFIELDS,http_build_query($data));

    $output = curl_exec($curl);

    curl_close($curl);
    $output=json_decode($output,true);
    return $output;
    //print_r($output);
}

function curl_get($url,$headers,$data,$cookie){
    $curl=curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
    curl_setopt($curl,CURLOPT_COOKIE,$cookie);
    //curl_setopt($curl, CURLOPT_POST, 1);

    //curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    $output = curl_exec($curl);

    curl_close($curl);
    $output=json_decode($output,true);
    return $output;
    //print_r($output);
}

function curl_post($url,$headers,$data,$cookie){
    $curl=curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
    curl_setopt($curl,CURLOPT_COOKIE,$cookie);
    curl_setopt($curl, CURLOPT_POST, 1);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    $output = curl_exec($curl);

    curl_close($curl);
    $output=json_decode($output,true);
    return $output;
    //print_r($output);
}






function post_curl($url, $params=[], $headers=[]){
    $httpInfo = array();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    curl_setopt( $ch , CURLOPT_POST , true );
    curl_setopt( $ch , CURLOPT_POSTFIELDS , http_build_query($params));
    curl_setopt( $ch , CURLOPT_URL , $url );
    $response = curl_exec( $ch );
    if ($response === FALSE) {
        return false;

    }
    curl_close( $ch );
    return $response;

}
//
function object_array($array) {
    if(is_object($array)) {
        $array = (array)$array;
    } if(is_array($array)) {
        foreach($array as $key=>$value) {
            $array[$key] = object_array($value);
        }
    }
    return $array;
}

function getDevice($model,$vsersionAndroid,$versionName,$timeStamp){
    $tmp=md5($model);
    //echo $tmp."";
    $tmp1=$tmp.$vsersionAndroid;
    //echo $tmp."";
    $tmp=md5($tmp1);
    //echo $tmp."";
    $tmp1=$tmp.$versionName;
    //echo $tmp."";
    $tmp=md5($tmp1);
    //echo $tmp."";
    $tmp1=$tmp.$timeStamp;
    //echo $tmp."";
    return md5($tmp1);
}


/*发送邮件方法
 *@param $to：接收者 $title：标题 $content：邮件内容
 *@return bool true:发送成功 false:发送失败
 */
function sendMail($to, $title, $content)
{
    //
    require './PHPMailer/PHPMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer;
    //使用smtp鉴权方式发送邮件
    $mail->isSMTP();
    //smtp需要鉴权 这个必须是true
    $mail->SMTPAuth = true;
    // qq 邮箱的 smtp服务器地址，这里当然也可以写其他的 smtp服务器地址
    $mail->Host = 'smtp.qq.com';
    //smtp登录的账号 这里填入字符串格式的qq号即可
    $mail->Username = '';
    // 这个就是之前得到的授权码，一共16位
    $mail->Password = '';
    $mail->setFrom('', '');
    // $to 为收件人的邮箱地址，如果想一次性发送向多个邮箱地址，则只需要将下面这个方法多次调用即可
    $mail->addAddress($to);
    // 该邮件的主题
    $mail->Subject = $title;
    // 该邮件的正文内容
    $mail->Body = $content;

    // 使用 send() 方法发送邮件
    if (!$mail->send()) {
        return '发送失败: ' . $mail->ErrorInfo;
    } else {
        return "发送成功";
    }
}





