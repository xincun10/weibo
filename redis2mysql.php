<?php
include('./lib.php');
include('./header.php');
$r = connredis();
//sql语句
$sql = 'insert into post(postid, userid, username, time, content) values';
//每次最多写100条
$i = 0;
while($r->llen('global:store') && $i++<1000){
	//将列表中的数据弹出到mysql里面
	$postid = $r->rpop('global:store');
	$post = $r->hMGet('post:postid:'.$postid, array('userid', 'time', 'content'));
	$name = $r->get('user:userid:'.$post['userid'].':username');
	$sql .= "($postid,".$post['userid'].",'$name',".$post['time'].",'".$post['content']."')";
}
echo $sql;
//连接mysql
$conn = new mysqli('192.168.42.130', 'root', '123456', 'test');
//mysql_query('use test', $conn);
//mysql_query('set names utf8', $conn);
//mysql_query($sql, $conn);
if($conn->query($sql)==false){
	echo $conn->error;
	exit;
}
echo '操作完成！';
include('footer.php');
?>
