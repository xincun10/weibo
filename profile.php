<?php 
include('./header.php');
include('./lib.php');
//判断用户是否登录
if(($user = isLogin()) == false){
	header('location: index.php');
	exit;
}
//获取userid
$u = G('u');
$r = connredis();
$uid = $r->get('user:username:'.$u.':userid');
if(!$uid){
	error('非法用户');
	exit;
}
//判断是否是uid的粉丝
$isfocus = $r->sismember('following:'.$user['userid'], $uid);
//url中显示
$isf = $isfocus ? 1 : 0;
//链接显示
$isfv = $isfocus ? '取消关注' : '关注ta';
?>
<div id="navbar">
<a href="index.php">主页</a>
| <a href="timeline.php">热点</a>
| <a href="logout.php">退出</a>
</div>
</div>
<h2 class="username"><?php echo $u; ?></h2>
<a href="follow.php?uid=<?php echo $uid; ?>&f=<?php echo $isf; ?>" class="button"><?php echo $isfv; ?></a>

<div class="post">
<a class="username" href="profile.php?u=test">test</a> 
world<br>
<i>11 分钟前 通过 web发布</i>
</div>

<div class="post">
<a class="username" href="profile.php?u=test">test</a>
hello<br>
<i>22 分钟前 通过 web发布</i>
</div>
<?php
include('./footer.php');
?>
