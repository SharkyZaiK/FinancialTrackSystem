<?php
	$db_host = 'localhost';
	$db_user = 'root'; // 預設為 root
	$db_pass = '123456789';     // 預設密碼為空
	$db_name = 'dbproject';

	$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
	if ($conn->connect_error) {
		die("資料庫連線失敗: " . $conn->connect_error);
	}
?>