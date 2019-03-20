<?php
	//用户更新模块
	function update_user_sex($phone_md5,$token,$sex){
		$con = getmysqlcon();
		mysqli_select_db($con,"LEFT_USERPAGE")or die(insertErrinto("更新模块中,选择数据库失败",4401));
		//开始更新
		$err_json = array();
		$err_json['status'] = "1";//默认为失败
		mysqli_query($con,"update USER_VALUESPAGE set USER_SEX = $sex where USER_PHONE_MD5 = '$phone_md5' and USER_TOKEN = '$token'")or die(json_encode($err_json));
		$json =  array();
		$json['status'] = "0";//成功
		echo json_encode($json);
	}
	//更新用户的默认收件地址
	//用户名  性别  地址  坐标 电话
	function update_user_defaultaddr($phone_md5,$token,$people,$sex,$addr,$coord,$phone){

	}
