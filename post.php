<?php
include('./header.php');
include('./lib.php');
//判断用户是否登录
if(($user = isLogin())==false){
	header('location:index.php');
	exit;
}
//接收post的数据
$status = P('status');
//如果为空，退出，提示
if(!$status){
	error('请填写内容！');
}
//连接redis操作
$r = connredis();
$postid = $r->incr('global:postid');
/*
$r->set('post:postid:'.$postid.':time', time());
$r->set('post:postid:'.$postid.':userid', $user['userid']);
$r->set('post:postid:'.$postid.':content', $status);
 */
$r->hmset('post:postid:'.$postid, array('userid'=>$user['userid'], 'time'=>time(), 
	'content'=>$status));
//新建一个列表，只维护自己10条数据
//多余的数据存到列表'global:store'中
$r->lpush('mypost:userid:'.$user['userid'], $postid);
if($r->llen('mypost:userid:'.$user['userid'])>10){
	$r->rpoplpush('mypost:userid:'.$user['userid'], 'global:store');
}

//获取粉丝列表
$fans = $r->smembers('follower:'.$user['userid']);
//将自己加到数组当中
$fans[] = $user['userid'];

foreach($fans as $fansid){
	$r->lpush('receivepost:'.$fansid, $postid);
}
header('location:home.php');
exit;
?>
