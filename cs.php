<?php

$connect=mysqli_connect("localhost","数据库账号","密码");
mysqli_select_db($connect,"数据库名");
mysqli_query($connect,"SET NAMES UTF-8");
$result = mysqli_query($connect,"SELECT * FROM user");
$row=mysqli_fetch_all($result);
foreach ($row as $index){
    $post_data = array(
    'userName' => $index[1],
    'userPwd' => $index[2],
	'qq' => $index[3],
	'sckey'=> $index[4]
);
echo send_post('http://域名/zjy.php', $post_data)."<br/>";
// echo send_post('http://zjy.feidanyl.top/zjy.php', $post_data)."<br/>";
// echo $index[1];
// var_dump($row);
}

/**
 * 发送post请求
 * @param string $url 请求地址
 * @param array $post_data post键值对数据
 * @return string
 */
function send_post($url, $post_data) {
    $postdata = http_build_query($post_data);
    $options = array(
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-type:application/x-www-form-urlencoded',
        'content' => $postdata,
        'timeout' => 15 * 60 // 超时时间（单位:s）
    )
  );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
}
//测试

mysqli_close($connect);

?>