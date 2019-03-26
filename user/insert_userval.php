<?php
	//插入用户数据模块
	//用来插入一个默认的数据到默认收件地址里
	//用户名 性别 电话  地址 坐标 
	function insert_user_addr($name,$tel,$addr,$physics_addr,$addr_in,$user_sex,$user_year,$default,$phone_md5){
		$err_json = array();
		$err_json['status'] = 1;	
		$con  = getmysqlcon();	
		//设置数据库
		mysqli_query($con,"create database if not exists LEFT_USERADDR")or die(insertErrinto("创建默认数据库失败",3306));
		mysqli_select_db($con,"LEFT_USERADDR")or die(insertErrinto("插入用户默认地址选择数据库失败",3305));
		mysqli_query($con,"set names utf8")or die(insertErrinto("插入用户默认地址设置utf8失败",3303));
		//初始化数据库表
		//mysql支持最大为29位的字符
		//phone_md5  要减去头部的5位数
		$phone_md5 = substr($phone_md5,5);
		mysqli_query($con,"create table if not exists $phone_md5 (
					USER_NAME tinytext,
					USER_TEL varchar(11),
					USER_ADDR text,
					PHYSICS_ADDR text,
					ADDR_IN text,
					USER_SEX int(1),
					USER_YEAR tinytext,
		DEFAULT_ADDR int(1))engine = innodb charset utf8")or die(insertErrinto("插入默认地址中,初始化数据库表失败",3302));
		$insert = "insert into $phone_md5 values ('$name','$tel','$addr','$physics_addr','$addr_in',$user_sex,'$user_year',0)";
		mysqli_query($con,$insert)or die(insertErrinto("插入用户默认收件地址失败",3304));
		$ok_json = array();
		$ok_json['status'] = 0;	
		echo json_encode($ok_json);
		
	}
