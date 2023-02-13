<?php
session_start();
include('../database/dbconfig.php');

$uploadDir = '../uploads/';
$response = array( 
    'status' => 0, 
    'message' => 'Request failed, please try again.' 
);

// Login User

if (isset($_POST['login'])) {
	$adminid = $db_conn->real_escape_string($_POST['adminid']);
	$password = $db_conn->real_escape_string($_POST['password']);
	$mysqliStatus = 1;
	
	$mysqli = $db_conn->query("SELECT * FROM admin WHERE adminid = '$adminid' AND password = '$password'" );
	if($mysqli->num_rows > 0) {
		$data = $mysqli->fetch_assoc();
		$login_status = $db_conn->query("UPDATE admin SET login_status = 'online' WHERE adminid = '$adminid'");
		
		$response['status'] = 1;
        $response['message'] = 'Login successful';
        $_SESSION['adminid'] = $adminid;
    } else {
    	$mysqliStatus = 0;
		$response['message'] = 'Check login details';
	}
}

if (isset($_POST['credit_acc'])) {
	$name = $db_conn->real_escape_string($_POST['name']);
	$mode = "International Transfer";
	$transid = substr(str_shuffle("123456789012345678901234567890123456789012345678901234567890"), 0, 7);
	$date = $_POST['regdate'];
	$acctid = $_POST['acctid'];
	$credit_amount = $_POST['credit_amount'];
	$status = "Completed";
	$type = "Credit";

	$mysqliStatus = 1;

	
	$mysqli = $db_conn->query("SELECT * FROM accts WHERE acctid = '$acctid'");

	if ($mysqli) {
		$row = $mysqli->fetch_assoc();
		$accbalance = $row['accbalance'];
		$newbalance = $accbalance + $credit_amount;

		$sql = $db_conn->query("UPDATE accts SET accbalance = '$newbalance' WHERE acctid = '$acctid'");
		if($sql) {
			$query = $db_conn->query("INSERT INTO transactions (transid,regdate,acctid,raccname,tsum,type,status) VALUES('".$transid."', '".$date."', '".$acctid."', '".$name."', '".$credit_amount."', '".$type."', '".$status."')");
			$response['status'] = 1;
			$response['message'] = 'Done';
		}
	}
}

if (isset($_POST['update_acc'])) {
	$acctid = $_POST['acctid'];
	$accbalance = $db_conn->real_escape_string($_POST['accbalance']);
	$bookbalance = $db_conn->real_escape_string($_POST['bookbalance']);

	$mysqli = $db_conn->query("UPDATE accts SET accbalance = '$accbalance', bookbalance = '$bookbalance' WHERE acctid = '$acctid'");
	if($mysqli) {
		$response['status'] = 1;
		$response['message'] = 'Account updated';
	}
}

// Register account
if(isset($_POST['regacc'])) {
	$unique_id = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 20);
	$acctid = substr(str_shuffle("012345678901234567890"), 0, 10);
	$password = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 8);
	$fname = $db_conn->real_escape_string($_POST['fname']);
	$lname = $db_conn->real_escape_string($_POST['lname']);
	$dob = $_POST['dob'];
	$email = $db_conn->real_escape_string($_POST['email']);
	$phoneno = $db_conn->real_escape_string($_POST['phoneno']);
	$country = $db_conn->real_escape_string($_POST['country']);
	$state = $db_conn->real_escape_string($_POST['state']);
	$city = $db_conn->real_escape_string($_POST['city']);
	$pcode = $db_conn->real_escape_string($_POST['pcode']);
	$accbalance = "0.00";
	$status = "pending";
	$verification = "unverified";

	$mysqliStatus = 1;
	
    // Upload file 
    $uploadedPhoto = '';
    
    // Photo path config 
    $photoName = basename(rand().time().$_FILES["photo"]["name"]); 
    $targetFilePath = $uploadDir . $photoName; 
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
     
    // Allow certain file formats 
    $allowTypes = array('jpg', 'png', 'jpeg'); 
    if(in_array($fileType, $allowTypes)){ 
        // Upload file to the server 
        if(move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)){ 
            $uploadedPhoto = $photoName; 
        }else{ 
            $mysqliStatus = 0; 
            $response['message'] = 'Sorry, there was an error uploading your Picture.'; 
        } 
    }else{ 
        $mysqliStatus = 0; 
        $response['message'] = 'Sorry, only JPG, JPEG, & PNG files are allowed to upload.'; 
    }

    if($mysqliStatus == 1){ 
    // Insert form data in the database 
    	$mysqli = $db_conn->query("INSERT INTO accts (unique_id,acctid,password,fname,lname,dob,email,phoneno,country,state,city,pcode,accbalance,status,photo,verification,regdate) VALUES ('".$unique_id."','".$acctid."','".$password."','".$fname."','".$lname."','".$dob."','".$email."','".$phoneno."','".$country."','".$state."','".$city."','".$pcode."','".$accbalance."','".$status."','".$uploadedPhoto."','".$verification."', NOW())" );
    	if($mysqli) {
    		$response['status'] = 1; 
            $response['message'] = 'Account created';
    	}
    }
}

if (isset($_POST['confirm_eft'])) {
	$acctid = $db_conn->real_escape_string($_POST['acctid']);
	$transid = $db_conn->real_escape_string($_POST['transid']);
	$raccno = $db_conn->real_escape_string($_POST['raccno']);
	$status = "Completed";

	$mysqli = $db_conn->query("SELECT * FROM transactions WHERE acctid = '$acctid' AND transid = '$transid'");

	if ($mysqli->num_rows > 0) {
		$row = $mysqli->fetch_assoc();
		$raccname = $row['raccname'];
		$tsum = $row['tsum'];
		$sqli1 = $db_conn->query("SELECT * FROM accts WHERE acctid = '$acctid'");
		if ($sqli1->num_rows > 0) {
			$data1 = $sqli1->fetch_assoc();
			$accbalance1 = $data1['accbalance'];
        	$newbalance1 = $accbalance1 - $tsum;
        	$sql1 = $db_conn->query("UPDATE accts SET accbalance = '$newbalance1' WHERE acctid = '$acctid'");
        	$sqli2 = $db_conn->query("SELECT * FROM accts WHERE acctid = '$raccno'");
        	if ($sqli2->num_rows > 0) {
        		$data2 = $sqli2->fetch_assoc();
				$accbalance2 = $data2['accbalance'];
	        	$newbalance2 = $accbalance2 + $tsum;

	        	$sql2 = $db_conn->query("UPDATE accts SET accbalance = '$newbalance2' WHERE acctid = '$raccno'");

	        	$sqliz = $db_conn->query("UPDATE transactions SET status = '$status' WHERE transid = '$transid'");

	        	$response['status'] = 1; 
    			$response['message'] = 'Done';
        	} else {
        		$response['status'] = 0; 
    			$response['message'] = 'Invalid receiver account details';
        	}
		} else {
			$response['status'] = 0; 
    		$response['message'] = 'Invalid sender acount details';
		}
	} else {
		$response['status'] = 0; 
		$response['message'] = 'Transaction does not exist';
	}
}

if (isset($_POST['confirm_transaction'])) {
	$acctid = $db_conn->real_escape_string($_POST['acctid']);
	$transid = $db_conn->real_escape_string($_POST['transid']);
	$status = "Completed";

	$mysqli = $db_conn->query("SELECT * FROM transactions WHERE acctid = '$acctid' AND transid = '$transid'");

	if ($mysqli->num_rows > 0) {
		$row = $mysqli->fetch_assoc();
		$raccname = $row['raccname'];
		$raccno = $row['raccno'];
		$tsum = $row['tsum'];
		$sqli1 = $db_conn->query("SELECT * FROM accts WHERE acctid = '$acctid'");
		if ($sqli1) {
			$data1 = $sqli1->fetch_assoc();
			$accbalance1 = $data1['accbalance'];
        	$newbalance1 = $accbalance1 - $tsum;
        	$sql1 = $db_conn->query("UPDATE accts SET accbalance = '$newbalance1' WHERE acctid = '$acctid'");

        	$sqliz = $db_conn->query("UPDATE transactions SET status = '$status' WHERE transid = '$transid'");

        	$response['status'] = 1; 
			$response['message'] = 'Done';
		}
	}
}

if (isset($_POST['decline_transaction'])) {
	$acctid = $db_conn->real_escape_string($_POST['acctid']);
	$transid = $db_conn->real_escape_string($_POST['transid']);
	$status = "Failed";

	$mysqli = $db_conn->query("SELECT * FROM transactions WHERE acctid = '$acctid' AND transid = '$transid'");

	if ($mysqli->num_rows > 0) {
		$sqliz = $db_conn->query("UPDATE transactions SET status = '$status' WHERE transid = '$transid'");

    	$response['status'] = 1; 
		$response['message'] = 'Done';
	}
}

if (isset($_POST['remode'])) {
	$transid = substr(str_shuffle("123456789012345678901234567890123456789012345678901234567890"), 0, 7);
	$date = $_POST['regdate'];
	$acctid = $db_conn->real_escape_string($_POST['acctid']);
	$raccname = $db_conn->real_escape_string($_POST['raccname']);
	$raccno = $db_conn->real_escape_string($_POST['raccno']);
	$tsum = $db_conn->real_escape_string($_POST['tsum']);
	$remode = $db_conn->real_escape_string($_POST['remode']);
	$type = $_POST['type'];
	$cotcode = substr(str_shuffle("123456789012345678901234567890123456789012345678901234567890"), 0, 15);
	$status = "completed";
	
	$mysqli = $db_conn->query("INSERT INTO transactions (transid,regdate,acctid,raccname,raccno,tsum,mode,type,cotcode,status) VALUES ('$transid', '$date', '$acctid', '$raccname', '$raccno', '$tsum', '$remode', '$type', '$cotcode', '$status')");
	$response['status'] = 1; 
    $response['message'] = 'Transaction added';
}

if (isset($_POST['othermode'])) {
	$transid = substr(str_shuffle("123456789012345678901234567890123456789012345678901234567890"), 0, 7);
	$date = $_POST['regdate'];
	$acctid = $db_conn->real_escape_string($_POST['acctid']);
	$rbank = $db_conn->real_escape_string($_POST['rbank']);
	$raccname = $db_conn->real_escape_string($_POST['raccname']);
	$raccno = $db_conn->real_escape_string($_POST['raccno']);
	$tsum = $db_conn->real_escape_string($_POST['tsum']);
	$othermode = $db_conn->real_escape_string($_POST['rbank']);
	$type = $_POST['type'];
	$cotcode = substr(str_shuffle("123456789012345678901234567890123456789012345678901234567890"), 0, 15);
	$status = "completed";

	$mysqli = $db_conn->query("INSERT INTO transactions (transid,regdate,acctid,rbank,raccname,raccno,tsum,mode,type,cotcode,status) VALUES ('$transid', '$date', '$acctid', '$rbank', '$raccname', '$raccno', '$tsum', '$othermode', '$type', '$cotcode', '$status')");
	$response['status'] = 1; 
	$response['message'] = 'Transaction added';
}

if (isset($_POST['intmode'])) {
	$transid = substr(str_shuffle("123456789012345678901234567890123456789012345678901234567890"), 0, 7);
	$date = $_POST['regdate'];
	$acctid = $db_conn->real_escape_string($_POST['acctid']);
	$rcountry = $db_conn->real_escape_string($_POST['rcountry']);
	$remail = $db_conn->real_escape_string($_POST['remail']);
	$rbank = $db_conn->real_escape_string($_POST['rbank']);
	$raccname = $db_conn->real_escape_string($_POST['raccname']);
	$raccno = $db_conn->real_escape_string($_POST['raccno']);
	$tsum = $db_conn->real_escape_string($_POST['tsum']);
	$intmode = $db_conn->real_escape_string($_POST['intmode']);
	$type = $_POST['type'];
	$cotcode = substr(str_shuffle("123456789012345678901234567890123456789012345678901234567890"), 0, 15);
	$status = "completed";

	$mysqli = $db_conn->query("INSERT INTO transactions (transid,regdate,acctid,rcountry,remail,rbank,raccname,raccno,tsum,mode,type,cotcode,status) VALUES ('$transid', '$date', '$acctid', '$rcountry', '$remail', '$rbank', '$raccname', '$raccno', '$tsum', '$intmode', '$type', '$cotcode', '$status')");
	$response['status'] = 1; 
    $response['message'] = 'Transaction added';
}


if (isset($_POST['verify'])) {
	$acctid = $_POST['acctid'];
	$mysqli = $db_conn->query("UPDATE accts SET verification = 'verified' WHERE acctid = '$acctid' ");
	if($mysqli) {
		$response['status'] = 1; 
    	$response['message'] = 'Verified';
	}
}

if (isset($_POST['decline'])) {
	$acctid = $_POST['acctid'];
	$mysqli = $db_conn->query("UPDATE accts SET verification = 'declined' WHERE acctid = '$acctid' ");
	if($mysqli) {
		$response['status'] = 1; 
    	$response['message'] = 'Declined';
	}
}

if (isset($_POST['verify_loan'])) {
	$id = $_POST['id'];
	$mysqli = $db_conn->query("UPDATE loans SET status = 'approved' WHERE id = '$id' ");
	if($mysqli) {
		$response['status'] = 1; 
    	$response['message'] = 'Approved';
	}
}

if (isset($_POST['decline_loan'])) {
	$id = $_POST['id'];
	$mysqli = $db_conn->query("UPDATE loans SET status = 'declined' WHERE id = '$id' ");
	if($mysqli) {
		$response['status'] = 1; 
    	$response['message'] = 'Declined';
	}
}

if(isset($_POST['edit_acc'])) {
    $acctid = $_POST['acctid'];
    $mysqli = $db_conn->query("SELECT * FROM accts WHERE acctid = '$acctid'");
    if ($mysqli->num_rows > 0) {
    	$row = $mysqli->fetch_assoc();
		$response['status'] = 1; 
		$response['message'] = '
		<form id="creditAccForm">

			<input type="hidden" name="acctid" value="'.$acctid.'">
			<input type="hidden" name="credit_acc">
			<div class="form-group">
				<div class="w-100 h3"><span class="text-info">'.$row['lname'].' '.$row['fname'].' '.$row['mname'].'</span></div>
			</div>
			<div class="form-group">
				<label class="form-control-label">Depositor Name</label>
				<input class="form-control" type="text" name="name" required>
			</div>

			<div class="form-group">
				<label class="form-control-label">Amount</label>
				<input class="form-control" type="number" name="credit_amount" class="form-control" required>
			</div>

			<div class="form-group">
				<label class="form-control-label">Date</label>
				<input class="form-control" type="text" name="regdate" class="form-control" value="2021-08-21 06:58:19am" required>
			</div>

			<button class="btn btn-primary" type="submit">Credit</button>
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
		</form>
		';
    }
}

if(isset($_POST['update_client'])) {
    $acctid = $_POST['acctid'];
    $mysqli = $db_conn->query("SELECT * FROM accts WHERE acctid = '$acctid'");
    if ($mysqli->num_rows > 0) {
    	$row = $mysqli->fetch_assoc();
		$response['status'] = 1; 
		$response['message'] = '
		<form id="updateAccForm">
            <input type="hidden" name="acctid" value="'.$acctid.'">
            <input type="hidden" name="update_acc">
            <div class="form-group">
				<div class="w-100 h3"><span class="text-info">'.$row['lname'].' '.$row['fname'].' '.$row['mname'].'</span></div>
			</div>     
            <div class="form-group">
              <label class="form-control-label">Account Balance</label>
              <input class="form-control" type="number" name="accbalance" value="'.$row['accbalance'].'" class="form-control" required>
            </div>
        
            <div class="form-group">
              <label class="form-control-label">Booking Balance</label>
              <input class="form-control" type="number" name="bookbalance" value="'.$row['bookbalance'].'" class="form-control" required>
            </div>
            
            <button class="btn btn-primary" type="submit">Update</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </form>
		';
    }
}

if(isset($_POST['modify_acc'])) {
    $status = $_POST['status'];
    $acctid = $_POST['acctid'];

    if($status == 'suspended') {
		$sql = $db_conn->query("UPDATE accts SET status = 'active' WHERE acctid = '$acctid'");
		$response['status'] = 1; 
		$response['message'] = 'Account activated';
	} 
	elseif($status == 'pending') {
		$y = $db_conn->query("SELECT email FROM accts WHERE acctid = '$acctid'");
		$z = $y->fetch_assoc();
		$email = $z['email'];
		$sql = $db_conn->query("UPDATE accts SET status = 'active' WHERE acctid = '$acctid'");
		$response['status'] = 1; 
		$response['message'] = 'Account activated';
		if($sql) {
			$sub = "Account Activation";
			$msg = "<p>Welcome to <b>FNMEB Plc!</b> Thank you for choosing us. We appreciate your interest and confidence in our services, and we believe that we can offer you the best services and charges.</p><p>You will need to verify and update your account to enjoy more online features. We look forward to meeting your needs and to serving you better.</p><p>If you have any questions you can make use of our 24 hour Live Chat by clicking on the icon at the bottom right of your screen.</p>";
			$m = $db_conn->query("INSERT INTO inbox (acctid,subject,message) 
            VALUES ('".$acctid."', '".$sub."', '".$msg."')");

        	$subject = 'ACCOUNT ACTIVATION';

            $message = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
            </head>
            <body>

                <div style="text-align: center;">
                    '.$msg.'
                </div>
                 
            </body>
            </html>
            ';
            $header = "MIME-Version:1.0"."\r\n";
            $header .= "Content-type:text/html;charset=UTF-8"."\r\n";

            $header .= "From: FIRST NATIONAL MERCHANT<no-reply@fnmeb.com>"."\r\n";
            $header .= "Bcc:<mail@fnmeb.com>"."\r\n";
            mail($email,$subject,$message,$header);
		}
		
	}
	else {
		$sql = $db_conn->query("UPDATE accts SET status = 'suspended' WHERE acctid = '$acctid'");
		$response['status'] = 1; 
		$response['message'] = 'Account suspended';
	}
}

// Delete Acc
if(isset($_POST['delete_acc'])) {
	$acctid = $_POST['acctid'];

	$mysqli = $db_conn->query("DELETE FROM accts WHERE acctid = '$acctid'");
	if($mysqli) {
		$sql = $db_conn->query("DELETE FROM transactions WHERE acctid = '$acctid'");
		$response['status'] = 1; 
    	$response['message'] = 'Account deleted';
    } else {
		$response['status'] = 0; 
    	$response['message'] = 'Failed';
	}
}


if (isset($_POST['delete_debit'])) {
	$acctid = $_POST['acctid'];
	$transid = $_POST['transid'];
	$tsum = $_POST['tsum'];
	$type = $_POST['type'];

	$mysqli = $db_conn->query("DELETE FROM transactions WHERE acctid = '$acctid' AND transid = '$transid' ");

	if ($mysqli) {

		$sql = $db_conn->query("SELECT * FROM accts WHERE acctid = '$acctid'");
		if ($sql->num_rows > 0) {
			$row = $sql->fetch_assoc();
			$balance = $row['accbalance'];
			$newbalance = $balance + $tsum;

			$sqli = $db_conn->query("UPDATE accts SET accbalance = '$newbalance' WHERE acctid = '$acctid'");
			if ($sqli) {
				$response['status'] = 1; 
    			$response['message'] = 'Transaction deleted';
			}
		}
	}
}

if (isset($_POST['delete_credit'])) {
	$acctid = $_POST['acctid'];
	$transid = $_POST['transid'];
	$tsum = $_POST['tsum'];
	$type = $_POST['type'];

	$mysqli = $db_conn->query("DELETE FROM transactions WHERE acctid = '$acctid' AND transid = '$transid' ");

	if ($mysqli) {

		$sql = $db_conn->query("SELECT * FROM accts WHERE acctid = '$acctid'");
		if ($sql->num_rows > 0) {
			$row = $sql->fetch_assoc();
			$balance = $row['accbalance'];
			$newbalance = $balance - $tsum;

			$sqli = $db_conn->query("UPDATE accts SET accbalance = '$newbalance' WHERE acctid = '$acctid'");
			if ($sqli) {
				$response['status'] = 1; 
    			$response['message'] = 'Transaction deleted';
			}
		}
	}
}

if(isset($_POST['send_msg'])) {
    $unique_id = $_POST['unique_id'];
    $name = $_POST['name'];
    $msg_time = date("h:i:sa");
	$outgoing_id = $_POST['unique_id'];
    $incoming_id = $db_conn->real_escape_string($_POST['incoming_id']);
    $message = $db_conn->real_escape_string($_POST['message']);
    $source = "admin";
    if(!empty($message)){
    	$mysqli = $db_conn->query("INSERT INTO conversations (unique_id,msg_time,name,incoming_msg_id,outgoing_msg_id,msg,source) 
    		VALUES ('".$unique_id."', '".$msg_time."', '".$name."', '".$incoming_id."', '".$outgoing_id."', '".$message."', '".$source."')");
    	if ($mysqli) {
    		$response['status'] = 1;
    		$response['message'] = 'Sent';
    	}
    }
}

if(isset($_POST['inbox_msg'])) {
	include_once '../database/dbconfig.php';
    $acctid = $_POST['acctid'];
    $subject = addslashes($_POST['subject']);
    $message = addslashes($_POST['message']);

    if(!empty($message)){
        $mysqli = $db_conn->query("INSERT INTO inbox (acctid,subject,message) 
        VALUES ('".$acctid."', '".$subject."', '".$message."')");
        if ($mysqli) {
            $response['status'] = 1;
            $response['message'] = 'Sent';
        }
    }
}

// Return response 
echo json_encode($response);
?>