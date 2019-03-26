<?php
	//查询用户的信息  
	include_once('../Conn.php');
	include_once('./get_userval.php');
	include_once('./update_userval.php');
	include_once('./insert_userval.php');
	//检查一个用户的名称和token是否正确
	$phone_md5 = trim($_GET['user_md5']);
	$token = trim($_GET['user_token']);
	$var = -1;
	$action = -1;
	$xml_obj;
	if($phone_md5 == ""){
		$xml_data = file_get_contents("php://input");
		$xml_obj = simplexml_load_string($xml_data,'SimpleXMLElement',LIBXML_NOCDATA);
		$phone_md5 = $xml_obj->user_md5;
		$token = $xml_obj->user_token;
		$action = $xml_obj->action;
		$var = check_user($phone_md5,$token);
	}
	else{
		//GET的方式
		$var = check_user($phone_md5,$token);
		$action = trim($_GET['action']);//获取命令码
	}
	switch($var){
		case 1:
			//token 过期
			$json = array();
			$json['status'] = 1;	
			echo json_encode($json);
			break;
		case 2:
			//没有用户的信息
			$json = array();
			$json['status'] = 2;	
			echo json_encode($json);
			break;
		case 0:
			//验证成功
			//判断验证码  要干嘛
			switch($action){
				case "get_userval":
					get_userval($phone_md5,$token);
					break;
				case "get_default_addr":
					//获取默认地址
					get_default_addr($phone_md5,$token);
					break;
				case "update_user_sex":
					//更新用户性别
					$sex = trim($_GET['update_sex']);
					update_user_sex($phone_md5,$token,$sex);
					break;
				case "get_userdefault_addr":
					//获取用户的默认地址
					get_userdefault_addr($phone_md5,$token);
					break;
				case "insert_user_addr":
					//插入一个用户的收件地址 获取xml数据信息
					$name = $xml_obj->addr_name;
					$tel = trim($xml_obj->tel);
					$addr = trim($xml_obj->addr);
					$physics_addr = trim($xml_obj->physics_addr);
					$addr_in = trim($xml_obj->addr_in);
					$user_sex = trim($xml_obj->user_sex);
					$user_year = trim($xml_obj->user_year);//年龄
					$_default = trim($xml_obj->default);//是否默认
					insert_user_addr($name,$tel,$addr,$physics_addr,$addr_in,$user_sex,$user_year,$default,$phone_md5);
					break;
				case "get_alladdr":
					//获取用户的所有的收件地址
					get_alladdr($phone_md5);
					break;
				default:
					break;
			}
			break;
	}

	function check_user($phone_md5,$token){
		$con = getmysqlcon();
		mysqli_select_db($con,"LEFT_USERPAGE")or die(insertErrinto("设置用户的数据表失败",3321));
		//获取用户的数据信息
		$list = mysqli_query($con,"select * from USER_VALUESPAGE where USER_PHONE_MD5 = '$phone_md5'")or die(insertErrinto("获取用户的数据失败",3320));		
		$a = mysqli_fetch_array($list);
		if($a['USER_TOKEN'] == ""){
			//没有获取到数据信息
			//token过期
			return 2;//没有用户的信息
		}
		else{
			$alist = mysqli_query($con,"select * from USER_VALUESPAGE where USER_PHONE_MD5 = '$phone_md5'")or die(insertErrinto("获取用户的数据失败",3320));		
			$user_list = mysqli_fetch_array($alist)or die(insertErrinto("初始化用户的数据数组失败",3319));
			if($user_list['USER_TOKEN'] == trim($token) && $user_list['USER_TOKEN'] != ""){
				//token 一样 
				return 0;//检查成功
			}
			else{
				//token过期
				return 1;//过期
			}
			
		}
	}
