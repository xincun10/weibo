<?php
include('./header.php');
include('./lib.php');

//如果用户已经登录，直接跳转到home.php
if(isLogin()){
	header('location:home.php');
	ob_end_flush();
	exit;
}

//接收表单数据
$username = P('username');
$password = P('password');
//连接redis
$r = connredis();
$userid = $r->get('user:username:'.$username.':userid');
//合法性验证
if($r->get('user:userid:'.$userid.':password')!=$password){
	error('用户名和密码不匹配！登录失败');
}
//设置cookie,登录成功
//ob_start();//打开输出缓冲区
setcookie('username', $username);
setcookie('userid', $userid);
//跳转到个人中心
header('location:home.php');
ob_end_flush();//输出全部内容到浏览器
?>
