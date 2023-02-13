<?php
include_once 'database/dbconfig.php';
$unique_id = $_SESSION['unique_id'];
$mysqli = $db_conn->query("UPDATE admin SET login_status = 'offline' WHERE unique_id = '$unique_id'");
if ($mysqli) {
	unset($_SESSION['unique_id']);
	session_destroy();
	header('Location:login');
	exit(); 
}
?>