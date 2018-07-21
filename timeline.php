<?php
include('./lib.php');
include('./header.php');
//判断用户是否登录
if(!isLogin()){
	header('location:index.php');
	exit;
}

$r = connredis();
//对用户链表进行排序
//将sort的结果（userid）替换*并得到结果
$newuserlist = array();
$newuserlist = $r->sort('newuserlink', array('sort'=>'desc', 'get'=>'user:userid:*:username'));

?>
<div id="navbar">
<a href="index.php">主页</a>
| <a href="timeline.php">热点</a>
| <a href="logout.php">退出</a>
</div>
</div>
<h2>热点</h2>
<i>最新注册用户(redis中的sort用法)</i><br>
<div>
<?php
//循环
foreach($newuserlist as $v){
?>
	<a class="username" href="profile.php?u=<?php echo $v; ?>"><?php echo $v; ?></a>
<?php
}
?>
</div>
<br><i>最新的50条微博!</i><br>
<div class="post">
<a class="username" href="profile.php?u=test">test</a>
world<br>
<i>22 分钟前 通过 web发布</i>
</div>

<div class="post">
<a class="username" href="profile.php?u=test">test</a>
hello<br>
<i>22 分钟前 通过 web发布</i>
</div>

<?php include('footer.php'); ?>
