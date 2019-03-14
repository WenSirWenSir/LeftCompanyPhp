<?php

	//该模块用来获取一个用户的所有资料信息
	function get_userval($phone_md5,$token){
		$con = getmysqlcon();//获取连接数据库的句柄
		mysqli_select_db($con,"LEFT_USERPAGE")or die(insertErrinto("设置用户的数据库错误",4401));
		$list = mysqli_query($con,"select * from USER_VALUESPAGE where USER_PHONE_MD5 = '$phone_md5' and USER_TOKEN = '$token'")or die(insertErrinto("获取用户的数据失败",4400));
		$user_list = mysqli_fetch_array($list)or die(insertErrinto("初始化用户的数据数组失败",3399));
		$json = array();
		$json['status'] = "0";//获取成功
		$json['name'] = $user_list['USER_NAME'];
		$json['phone'] = $user_list['USER_PHONE'];
		$json['error_number'] = $user_list['USER_EROR_NUMBER'];
		$json['token'] = $user_list['USER_TOKEN'];
		$json['sex'] = $user_list['USER_SEX'];
		$json['birthdate'] = $user_list['USER_BIRTHDATE'];
		$json['cardid'] = $user_list['USER_CARDID'];
		$json['cardname'] = $user_list['USER_CARDNAME'];
		$json['cardstart'] = $user_list['USER_CARDSTART'];
		$json['cardend'] = $user_list['USER_CARDEND'];
		$json['leftcompany_id'] = $user_list['USER_LEFTCOMPANY_ID'];
		$json['login_verification'] = $user_list['USER_LOGIN_VERIFICATION'];
		$json['reg_time'] = $user_list['USER_REG_TIME'];
		$json['headimg'] = $user_list['USER_HEADIMG'];
		$json['about'] = $user_list['USER_ABOUT'];
		$json['signature'] = $user_list['USER_SIGNATURE'];
		$json['last_incoord'] = $user_list['USER_LAST_INCOORD'];
		$json['education'] = $user_list['USER_EDUCATION'];
		$json['interest'] = $user_list['USER_INTEREST'];
		$json['unit'] = $user_list['USER_UNIT'];
		$json['isblack_list'] = $user_list['USER_ISBLACK_LIST'];
		$json['phone_md5'] = $user_list['USER_PHONE_MD5'];
		echo json_encode($json);
		mysqli_close($con)or die(insertErrinto("关闭数据库失败",3398));//关闭数据库
	}	
	//该模块用来获取用户的默认收件地址
	function get_userdefault_addr($phone_md5,$token){
		$con = getmysqlcon();
		mysqli_select_db($con,"LEFT_USERPAGE")or die(insertErrinto("设置用户的数据库错误",3397));
		mysqli_query($con,"set names utf8")or die(insertErrinto("获取用户的默认收件地址设置编码错误",3394));
		//初始化数据库表
		mysqli_query($con,"create table if not exists USER_DEFAULT_ADDR (USER_NAME tinytext,USER_SEX int(1),USER_TEL varchar(11),USER_ADDR text,USER_COORD tinytext,USER_PHONE_MD5 tinytext)engine = innodb charset utf8")or die(insertErrinto("获取默认地址中,初始化数据库表失败",3398));
		$list = mysqli_query($con,"select * from USER_DEFAULT_ADDR where USER_PHONE_MD5 = '$phone_md5'")or die(insertErrinto("获取用户默认的收件地址发生错误",3396));
		if(mysqli_num_rows($list) < 1){
			//没有数据信息
			$json = array();
			$json['status'] = "1";
			echo json_encode($json);
		}
		else{
			//有数据信息
			$user_list = mysqli_fetch_array($list)or die(insertErrinto("获取用户的地址数据初始化数组失败",3395));
			$json = array();
			$json['status'] = "0";
			$json['name']  = $user_list['USER_NAME'];//收件名称
			$json['addr'] = $user_list['USER_ADDR'];//收件地址
			$json['sex'] = $user_list['USER_SEX'];//收件人的性别
			$json['tel'] = $user_list['USER_TEL'];//收件人的电话
			$json['coord'] = $user_list['USER_COORD'];//收件人的定位坐标
			echo json_encode($json);
		}
	}
?>

