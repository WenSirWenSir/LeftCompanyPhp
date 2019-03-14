<?php
	//插入用户数据模块
	//用来插入一个默认的数据到默认收件地址里
	//用户名 性别 电话  地址 坐标 加密MD5电话 秘钥
	function insert_default_addr($people,$sex,$tel,$addr,$coord,$phone_md5){
		$con  = getmysqlcon();	
		//设置数据库
		mysqli_select_db($con,"LEFT_USERPAGE")or die(insertErrinto("插入用户默认地址选择数据库失败",3305));
		mysqli_query($con,"set names utf8")or die(insertErrinto("插入用户默认地址设置utf8失败",3303));
		//初始化数据库表
		mysqli_query($con,"create table if not exists USER_DEFAULT_ADDR (USER_NAME tinytext,USER_SEX int(1),USER_TEL varchar(11),USER_ADDR text,USER_COORD tinytext,USER_PHONE_MD5 tinytext)engine = innodb charset utf8")or die(insertErrinto("插入默认地址中,初始化数据库表失败",3303));
		$insert = "insert into USER_DEFAULT_ADDR values ('$people',$sex,'$tel','$addr','$coord','$phone_md5')";
		echo $insert;
		mysqli_query($con,$insert)or die(insertErrinto("插入用户默认收件地址失败",3304));
	}
?>
