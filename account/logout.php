<?php
session_start();
include_once 'database/dbconfig.php';
$unique_id = $_SESSION['unique_id'];
$mysqli = $db_conn->query("UPDATE accts SET login_status = 'offline' WHERE unique_id = '$unique_id'");
if ($mysqli) {
    $sqli = $db_conn->query("DELETE FROM conversations WHERE incoming_msg_id ='$unique_id' OR outgoing_msg_id = '$unique_id'");
    if ($sqli) {
        unset($_SESSION['unique_id']);
        session_destroy();
        header('Location:login');
        exit(); 
    } 
}
?>