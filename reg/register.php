<?php
	
	include '../Conn.php';		
	$action = trim($_GET['action']);	//获取action 用来判断执行哪件事务
	if($action == ""){
		$json = array();
		$json['status'] = '-1';
		echo json_encode($json);//-1代表失败
	}
	else{
			//你好全世界
		switch($action){
			case 0:
				//获取数据信息	
				$user = trim($_GET['user_md5']);//用户加密的MD5的字符
				$token = trim($_GET['user_token']);//用户的token
				$phone = trim($_GET['user_phone']);//用户没有加密的手机号码
				if($user != ""){
					//登录 判断是否注册 如果没有就先插入数据库中 之后发送验证吗 在这个阶段不输出用户的所有信息
					$con = getmysqlcon();
					//设置数据库
					mysqli_query($con,"create database if not exists LEFT_USERPAGE")or die(insertErrinto("初始化公司用户的数据库失败",4443));
					mysqli_select_db($con,"LEFT_USERPAGE")or die(insertErrinto("选择公司用户数据库失败",4442));
					$sql = "
						create table if not exists USER_VALUESPAGE(
							USER_NAME tinytext,
							USER_PHONE tinytext,
							USER_EROR_NUMBER int,
							USER_TOKEN text,
							USER_SEX int(1),
							USER_BIRTHDATE tinytext,
							USER_CARDID tinytext,
							USER_CARDNAME tinytext,
							USER_CARDSTART tinytext,
							USER_CARDEND tinytext,
							USER_LEFTCOMPANY_ID tinytext,
							USER_LOGIN_VERIFICATION tinytext,
							USER_REG_TIME datetime,
							USER_HEADIMG tinytext,
							USER_ABOUT text,
							USER_SIGNATURE text,
							USER_LAST_INCOORD text,
							USER_EDUCATION tinytext,
							USER_INTEREST tinytext,
							USER_UNIT tinytext,
							USER_ISBLACK_LIST tinytext,
							USER_PHONE_MD5 tinytext
							)engine = innodb charset utf8;
					";
					mysqli_query($con,$sql)or die(insertErrinto("初始化用户的数据表失败",4441));
					if($phone != ""){
						//初始化  获取一个验证码
						$code = "";
						for($i = 0;$i < 4;$i++){
							$code.= rand(0,9);
						}
						//初始化数据成功的话 就开始发送验证码程序
						//判断用户的
						//查询用户的所有信息
						$list = mysqli_query($con,"select * from USER_VALUESPAGE where USER_PHONE_MD5 = '$user'")or die(insterErrinto("获取用户的数据失败",4401));
						$user_list =mysqli_fetch_array($list);
						//判断是否为空
						if($user_list['USER_PHONE_MD5'] == ""){
							//用户第一次登录公司网络
							mysqli_query($con,"insert into USER_VALUESPAGE values(
								'',
								'$phone',
								0,
								'',
								0,
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								now(),
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'$user')")or die(insertErrinto("用户第一次注册,初始化失败",4441));	
							//插入用户的随机数验证码
							mysqli_query($con,"update USER_VALUESPAGE set USER_LOGIN_VERIFICATION = '$code' where USER_PHONE_MD5 = '$user'")or die(insertErrinto("插入随机验证码失败",4400));
							//发送短信验证
							$rJson = json_decode(sendVerficationCode($phone,$code),true);
							if($rJson['return_code'] == "00000" && $rJson['return_code'] != ""){
								$json = array();
								$json['status'] = '0';
								echo json_encode($json);//-1代表失败
							}
							else{
								$json = array();
								$json['status'] = '-1';
								echo json_encode($json);//-1代表失败
							}
						}

						else{
							//判断是否多次登录失败
							if($user_list['USER_ERROR_NUMBER'] >= 4){
								$json = array();
								$json['status'] = '-2';
								echo json_encode($json);//-2代表错误次数过多
									//已经超出 不能登录
							}
							else{
								//不是第一次登录的话 就直接修改验证吗
								mysqli_query($con,"update USER_VALUESPAGE set USER_LOGIN_VERIFICATION = '$code' where USER_PHONE_MD5 = '$user'")or die(insertErrinto("插入随机验证码失败",4439));
								$rJson = json_decode(sendVerficationCode($phone,$code),true);
								if($rJson['return_code'] == "00000" && $rJson['return_code'] != ""){
									$json = array();
									$json['status'] = '0';
									echo json_encode($json);//-1代表失败
								}
								else{
									$json = array();
									$json['status'] = '-1';
									echo json_encode($json);//-1代表失败
								}
			
							}
						}
					}
					else{
						$json = array();
						$json['status'] = '-1';
						echo json_encode($json);//-1代表失败
					}
				}
				else{
					$json = array();
					$json['status'] = '-1';
					echo json_encode($json);//-1代表失败
				}
				break;
			case 1:
				//检测用户输入的验证码是否正确
				$user = trim($_GET['user_md5']);	
				$code = trim($_GET['user_code']);
				//用md5加密的手机号码获取验证码
				$con = getmysqlcon();
				mysqli_select_db($con,"LEFT_USERPAGE")or die(insertErrinto("检测用户验证码在设置数据表失败",4438));
				$list = mysqli_query($con,"select * from USER_VALUESPAGE where USER_PHONE_MD5='$user'")or die(insertErrinto("检测用户验证码在获取验证码数据中错误",4437));
				$user_list = mysqli_fetch_array($list)or die(inertErrinto("检测用户验证码中数据转换出错",4436));
				//首先判断是否已经多次登录失败了
				if($user_list['USER_LOGIN_VERIFICATION']){
					if($user_list['USER_EROR_NUMBER'] < 4){
						//没有超过
						if($user_list['USER_LOGIN_VERIFICATION'] == $code){
							//验证码正确
							//清空错误登录次数
							mysqli_query($con,"update USER_VALUESPAGE set USER_EROR_NUMBER = 0 where USER_PHONE_MD5 = '$user'")or die(insertErrinto("重置错误次数失败",4434));
							$json = array();
							$json['status'] = '0';
							//覆盖手机验证吗的随机数据
							$vcode = date('h-i-s');
							$vcodetoken = md5($vcode);//create VerificationCode md5
							date_default_timezone_set('Asia/shanghai');
							//创建用户登录的TOKEN
							$vlogin = date('h-i-s');
							$vlogintoken = md5($vlogin);
							//Update token
							mysqli_query($con,"update USER_VALUESPAGE set USER_TOKEN = '$vlogintoken',USER_LOGIN_VERIFICATION = '$vcodetoken'")or die(insertErrinto("更新用户的TOKEN出错",4433));
							$json['token'] = $vlogintoken;
							echo json_encode($json);
						}
						else{
							//验证码不正确
							$new_error = (int)$user_list['USER_EROR_NUMBER'] + 1;
							mysqli_query($con, "update USER_VALUESPAGE set USER_EROR_NUMBER = $new_error where USER_PHONE_MD5='$user'")or die(insertErrinto("更新用户的错误次数失败",4435));
							$json = array();
							$json['status'] = '-3';
							echo json_encode($json);//-3代表验证码不正确
						}
					}
					else{
						//已经超过了
						$json = array();
						$json['status'] = '-2';
						echo json_encode($json);
					}
				}
				else{
					//没有数据 恶意攻击  就吧错误次数往上抬1
					$json = array();
					$json['status']  = "-1";
					echo json_encode($json);
					$new_error = $user_list['USER_EROR_NUMBER'] + 1;
					mysqli_query($con,"update USER_VALUESPAGE set USER_EROR_NUMBER = $new_error")or die(insertErrinto("更新用户的错误次数失败",4436));
				}
				break;
			default:
				break;
		}
	}
?>
