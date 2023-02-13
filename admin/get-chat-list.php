<?php 
session_start();
if(isset($_SESSION['adminid'])) {
    include_once '../database/dbconfig.php';
    $output = "";
    $mysqli = $db_conn->query("SELECT DISTINCT unique_id FROM conversations WHERE source = 'user' ORDER BY msg_id DESC");
    if($mysqli->num_rows > 0) {
        while($row = $mysqli->fetch_assoc()) {
            $unique_id = $row['unique_id'];
            $sqlio = $db_conn->query("SELECT DISTINCT name FROM conversations WHERE unique_id = '$unique_id' AND source = 'user' ORDER BY msg_id DESC");
            $data = $sqlio->fetch_assoc();
            
            $output .= '
            <li class="nav-item active">
                <a id="'.$row['unique_id'].'" href="chat?user_chat_id='.$row['unique_id'].'" class="nav-link text-primary">'.$data['name'].' <span class="float-right text-success text-sm"><i class="fas fa-star"></i></span></a>
            </li>
            ';
        }
    }
    else {
        $output .= '
        <li class="nav-item active">
            <a class="nav-link">No messages</a>
        </li>
        ';
    }
}
echo $output;
?>