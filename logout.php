<?php
session_start();
session_unset(); // 清除所有 Session 變數
session_destroy(); // 銷毀會話

// 使用 header 正確重導至 index.php
header("Location: index.php");
exit;
?>
