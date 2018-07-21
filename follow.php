<?php
include('./header.php');
include('./lib.php');

if(($user = isLogin())==false){
	error('没有登录');
	exit;
}
$uid = G('uid');
$f = G('f');
/*
 * 判断uid是否合法
 * 判断uid是否是自己
 * 添加到两个集合，关注列表和被关注列表
 * 跳转到页面profile.php
 */
$r = connredis();
$u = $r->get('user:userid:'.$uid.':username');
if(!$u){
	error('非法用户');
	exit;
}
//添加关注
if($f==0){
	$r->sAdd('following:'.$user['userid'], $uid);
	$r->sAdd('follower:'.$uid, $user['userid']);
}
//取消关注
if($f==1){
	$r->srem('following:'.$user['userid'], $uid);
	$r->srem('follower:'.$uid, $user['userid']);
}
//跳转页面
header('location: profile.php?u='.$u);
include('./footer.php');
?>
