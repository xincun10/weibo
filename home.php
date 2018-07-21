<?php 
include('./lib.php');
include('./header.php');
//判断用户是否登录
if(($user = isLogin()) == false){
	header('location:index.php');
	exit;
}
$r = connredis();
//获取关注者和粉丝的数量
$follower = $r->sCard('follower:'.$user['userid']);
$following = $r->sCard('following:'.$user['userid']);
//获取微博
$r->ltrim('receivepost:'.$user['userid'], 0, 49);
//排序，获取微博id列表
$newpost = $r->sort('receivepost:'.$user['userid'], array('sort'=>'desc'));
//$timepost = $r->sort('receivepost:'.$user['userid'], array('sort'=>'desc', 'get'=>'post:postid:*:time'));
//$userpost = $r->sort('receivepost:'.$user['userid'], array('sort'=>'desc', 'get'=>'post:postid:*:userid'));
?>
<div id="navbar">
<a href="index.php">主页</a>
| <a href="timeline.php">热点</a>
| <a href="logout.php">退出</a>
</div>
</div>
<div id="postform">
<form method="POST" action="post.php">
<?php echo $user['username']; ?>, 有啥感想?
<br>
<table>
<tr><td><textarea cols="70" rows="3" name="status"></textarea></td></tr>
<tr><td align="right"><input type="submit" name="doit" value="Update"></td></tr>
</table>
</form>
<div id="homeinfobox">
<?php echo $follower; ?> 粉丝<br>
<?php echo $following; ?> 关注<br>
</div>
</div>
<?php
foreach($newpost as $postid){
	//获取每一条微博信息
	$weibo = $r->hMGet('post:postid:'.$postid, array('userid', 'time', 'content'));
	$uname = $r->get('user:userid:'.$weibo['userid'].':username');
?>	
<div class="post">
<a class="username" href="profile.php?u=<?php echo $uname; ?>"><?php echo $uname; ?></a>
<?php echo $weibo['content'];?>
<br>
<i>
<?php echo round((time()-$weibo['time'])/60).'分钟前，通过web发布。'; ?>
</i>
</div>
<?php
}
?>
<?php include('./footer.php'); ?>
