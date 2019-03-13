<?php
	error_reporting(E_ALL);
	//create table in mysql ; 
	// this table is LeftProgram
	function createLeftProgramUserTable($tableName){
		mysqli_query();	
	}
	// if code error 
	// get this error to mysql talbes;
	function insertErrinto($msg,$code_num){
		//msg can't not be null
		if($msg != ""){
			$sql = "create database if not exists ";
			$file_name = getFilename();
			$con = getMysqlcon();
			mysqli_query($con,"set names utf8")or die("insertErrinto error1");
			mysqli_select_db($con,"LEFTPROGRAM_ERRORLOG")or die("insertErrinto error");
			$insert_sql = "insert into ERROR_LOG values('$file_name',now(),'$msg',$code_num)";
			mysqli_query($con,$insert_sql)or die("-1");
		}
		else{
			//do nothing
		}
	}
	function getFilename(){
		$php_se = substr($_SERVER['PHP_SELF'],strripos($_SERVER['PHP_SELF'],"/") + 1);
		return $php_se;
	}
	//获取连接句柄
	function getMysqlcon(){
		$con = mysqli_connect("127.0.0.1","root","capitalist12..@..")or die("-2");
		return $con;

	}
	function sendVerficationCode($phone,$code){
		if($phone == "" || $code == ""){
			
		}
		else{
			//电话和验证码都不为空
			$host = "http://mobai.market.alicloudapi.com";
			$path = "/mobai_sms";
			$method = "POST";
			$headers = array();
			$appcode = "ab8f8bf6bba84d2d954a14340fca964f";
			array_push($headers,"Authorization:APPCODE ".$appcode);
			$querys = "param=code%3A".$code."&phone=".strval($phone)."&templateId=TP1902158";
			$url = $host.$path."?".$querys;
			$curl = curl_init();
			curl_setopt($curl,CURLOPT_CUSTOMREQUEST,$method);
			curl_setopt($curl,CURLOPT_URL,$url);
			curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
			curl_setopt($curl,CURLOPT_FAILONERROR,false);
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
			if( 1 == strpos("$".$host,"https://")){
				curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
				curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
			}
			return curl_exec($curl);
		}
			
	}
?>

