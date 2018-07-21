<?php
/*
 * 具体步骤：
 * 接收$_POST参数，判断用户名密码是否完全
 * 连接redis，查询用户名，判断是否存在
 * 写入redis
 * 登录操作
 */
include('./lib.php');
include('./header.php');
//如果用户已经登录，直接跳转到home.php
if(isLogin()){
	header('location:home.php');
	ob_end_flush();
	exit;
}

$username = P('username');
$password = P('password');
$password2 = P('password2'); 
if(!$username || !$password || !$password2){
	error("请输入完整注册信息！");
}
//判断密码是否一致
if($password != $password2){
	error("2次密码不一致！");
}
//连接redis
$r = connredis();
//查询用户名是否已被注册
if($r->get('user:username:'.$username.':userid')){
	error("用户名已被注册！");
}
//userid自增
$userid = $r->incr('global:userid');
//将用户加入数据库
$r->set('user:userid:'.$userid.':username', $username);
$r->set('user:userid:'.$userid.':password', $password);
$r->set('user:username:'.$username.':userid', $userid);

//维护一个链表，前50个最新的userid
$r->lpush('newuserlink', $userid);
$r->ltrim('newuserlink', 0, 49);

include('footer.php');
?>
