<?php
	//資料庫主機設定
	$db_host = "localhost";
	$db_username = "root";
	$db_password = "1234";
	$c_db_name = "3cshop";
	//連線資料庫
	$c_db_link = @new mysqli($db_host, $db_username, $db_password, $c_db_name);
	//錯誤處理
	if ($c_db_link->connect_error != "") {
		echo "資料庫連結失敗！";
	}else{
		//設定字元集與編碼
		$c_db_link->query("SET NAMES 'utf8'");
	}
?>