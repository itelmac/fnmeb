<?php 
session_start();
include('database/dbconfig.php');

$uploadDir = 'uploads/';
$response = array( 
    'status' => 0, 
    'message' => 'Request failed, please try again.',
    'time' => '',
    'subject' => ''
);

// Register account
if(isset($_POST['regacc'])) {
    $unique_id = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 20);
    $acctid = substr(str_shuffle("012345678901234567890"), 0, 10);
    $password = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 8);
    $fname = $db_conn->real_escape_string($_POST['fname']);
    $lname = $db_conn->real_escape_string($_POST['lname']);
    $mname = $db_conn->real_escape_string($_POST['mname']);
    $marital = $db_conn->real_escape_string($_POST['marital']);
    $gender = $db_conn->real_escape_string($_POST['gender']);
    $employment = $db_conn->real_escape_string($_POST['employment']);
    $dob = $_POST['dob'];
    $email = $db_conn->real_escape_string($_POST['email']);
    $phoneno = $db_conn->real_escape_string($_POST['phoneno']);
    $country = $db_conn->real_escape_string($_POST['country']);
    $state = $db_conn->real_escape_string($_POST['state']);
    $city = $db_conn->real_escape_string($_POST['city']);
    $pcode = $db_conn->real_escape_string($_POST['pcode']);
    $address = $db_conn->real_escape_string($_POST['address']);
    $accbalance = "0.00";
    $bookbalance = "0.00";
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
        $mysqli = $db_conn->query("INSERT INTO accts (unique_id,acctid,password,fname,lname,mname,marital,gender,employment,dob,email,phoneno,country,state,city,pcode,address,accbalance,bookbalance,status,photo,verification,regdate) VALUES ('".$unique_id."','".$acctid."','".$password."','".$fname."','".$lname."','".$mname."','".$marital."','".$gender."','".$employment."','".$dob."','".$email."','".$phoneno."','".$country."','".$state."','".$city."','".$pcode."','".$address."','".$accbalance."','".$bookbalance."','".$status."','".$uploadedPhoto."','".$verification."', NOW())" );
        if($mysqli) {
            $response['status'] = 1; 
            $response['message'] = 'Request Sent!';
        }
    }
}

// Login account
if(isset($_POST['loginacc'])) {
    $acctid = $db_conn->real_escape_string($_POST['acctid']);
    $password = $db_conn->real_escape_string($_POST['password']);

    $mysqli = $db_conn->query("SELECT * FROM accts WHERE acctid = '$acctid' AND password = '$password'" );
    if($mysqli->num_rows > 0) {
        $data = $mysqli->fetch_assoc();
        $login_status = $db_conn->query("UPDATE accts SET login_status = 'online' WHERE acctid = '$acctid'");

        if ($login_status) {
            $db_conn->query("UPDATE accts SET login_status = 'online' WHERE acctid = '$acctid'");
            $_SESSION['unique_id'] = $data['unique_id'];
            $response['status'] = 1; 
            $response['message'] = 'Login Successful';
        }
        
    } else {
        $response['status'] = 0; 
        $response['message'] = 'Incorrect account login details';
    }
}

// Forgot Password
if  (isset($_POST['forgot_password'])) {

    $acctid = $db_conn->real_escape_string($_POST['acctid']);

    $mysqli = $db_conn->query("SELECT * FROM accts WHERE acctid = '$acctid'");

    if($mysqli->num_rows > 0) {
        $row = $mysqli->fetch_assoc();
        $email = $row['email'];
        $name = $row['lname'].' '.$row['fname'];
        $token = substr(str_shuffle("012345678901234567890"), 0, 6);

        $sqli = $db_conn->query("UPDATE accts SET token = '$token', token_expire = DATE_ADD(NOW(), INTERVAL 20 MINUTE) WHERE acctid = '$acctid' ");

        
        $subject = 'SECURITY ALERT';
        $message = '
        <p style="color:blue;">Password Reset Request</p>

        <p style="text-transform:capitalize;">Dear '.$name.'</p>
        
        <p>Password reset code: <span style="color: blue; font-weight:bold;">'.$token.'</span></p>

        <p>If you don&#39;t recognise this activity, please contact our customer support immediately at: <a href="mailto:mail@fnmeb.com"></a></p>
        <p>Please keep your login details secure at all times and do not disclose by phone, email or on suspicious websites. Neither FNMEB nor its staff will request your Password or Token details at any point in time.</p>
        <p style="color:red;font-style: italic;">YOUR TOKEN IS A KEY TO YOUR ACCOUNT, DON&#39;T SHARE YOUR TOKEN OR TOKEN CODE WITH ANYONE.</p>
        <p>Best regards,</p>
        <p>The FNMEB Team</p>
        <p>This is an automated message, please do not reply</p>';

        $header = "MIME-Version:1.0"."\r\n";
        $header .= "Content-type:text/html;charset=UTF-8"."\r\n";

        $header .= "From: FNMEB<no-reply@fnmeb.com>"."\r\n";
        $header .= "Bcc:<mail@fnmeb.com>"."\r\n";
        
        if (mail($email,$subject,$message,$header)) {
            $response['status'] = 1; 
            $response['message'] = 'A code was sent to the email linked to this account.';
        } else {
            $response['status'] = 0; 
            $response['message'] = 'Email not sent';
        }
    } else {
        $response['status'] = 0; 
        $response['message'] = 'Invalid Account Number. Check the Account Number again or contact <a href="mailto:mail@fnmeb.com">support</a>';
    }

}

//Reset password
if  (isset($_POST['recover_password'])) {
    $token = $db_conn->real_escape_string($_POST['token']);
    $acctid = $db_conn->real_escape_string($_POST['acctid']);

    if (empty($token)) {
        $response['status'] = 0; 
        $response['message'] = 'Invalid token';

    } else {
        $mysqli = $db_conn->query("SELECT * FROM accts WHERE acctid = '$acctid' AND token='$token' AND token_expire > NOW()");

        if ($mysqli->num_rows > 0) {
            $response['status'] = 1; 
            $response['message'] = 'Valid Token.';
        }
        else {
            $response['status'] = 0; 
            $response['message'] = 'Request failed, please try again.';
        }
    }

}

//Reset password
if  (isset($_POST['reset_password'])) {
    $token = $db_conn->real_escape_string($_POST['token']);
    $acctid = $db_conn->real_escape_string($_POST['acctid']);
    $password = $db_conn->real_escape_string($_POST['password']);
   
    $mysqli = $db_conn->query("SELECT * FROM accts WHERE acctid = '$acctid'");

    if ($mysqli->num_rows > 0) {
        $db_conn->query("UPDATE accts SET token='', token_expire = '', password = '$password' WHERE acctid='$acctid'");
        $response['status'] = 1; 
        $response['message'] = 'Your password was changed successfully.';
    }
    else {
        $response['status'] = 0; 
        $response['message'] = 'Request failed, please try again.';
    }

}

if(isset($_POST['change_profile_pic'])) {
    $acctid = $_POST['acctid'];
    $mysqliStatus = 1;
    
    // Upload file 
    $uploadedPhoto = '';
    
    // Photo path config 
    $photoName = basename(rand().time().$_FILES["file"]["name"]); 
    $targetFilePath = $uploadDir . $photoName; 
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
     
    // Allow certain file formats 
    $allowTypes = array('jpg', 'png', 'jpeg'); 
    if(in_array($fileType, $allowTypes)){ 
        // Upload file to the server 
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){ 
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
        $mysqli = $db_conn->query("UPDATE accts SET photo = '$uploadedPhoto' WHERE acctid = '$acctid'");
        if($mysqli) {
            $response['status'] = 1; 
            $response['message'] = 'Profile updated';
        }
    }
}

if(isset($_POST['send_msg'])) {
    include_once 'database/dbconfig.php';
    $unique_id = $_POST['unique_id'];
    $name = $_POST['name'];
    $msg_time = date("h:i:sa");
    $outgoing_id = $_POST['unique_id'];
    $incoming_id = $db_conn->real_escape_string($_POST['incoming_id']);
    $message = $db_conn->real_escape_string($_POST['message']);
    $source = "user";
    if(!empty($message)){
        $mysqli = $db_conn->query("INSERT INTO conversations (unique_id,msg_time,name,incoming_msg_id,outgoing_msg_id,msg,source) 
            VALUES ('".$unique_id."', '".$msg_time."', '".$name."', '".$incoming_id."', '".$outgoing_id."', '".$message."', '".$source."')");
        if ($mysqli) {
            $response['status'] = 1;
            $response['message'] = 'Sent';
        }
    }
}

// Send message
if(isset($_POST['send_us_message'])) {
    $fname = $db_conn->real_escape_string($_POST['fname']);
    $lname = $db_conn->real_escape_string($_POST['lname']);
    $email = $db_conn->real_escape_string($_POST['email']);
    $phoneno = $db_conn->real_escape_string($_POST['phoneno']);
    $subject = $db_conn->real_escape_string($_POST['subject']);
    $message = $db_conn->real_escape_string($_POST['message']);
    

    // Insert form data in the database 
    $mysqli = $db_conn->query("INSERT INTO messages (fname,lname,email,phoneno,subject,message) VALUES ('".$fname."', '".$lname."', '".$email."', '".$phoneno."', '".$subject."', '".$message."')");
    if($mysqli) {
        $response['status'] = 1; 
        $response['message'] = 'Message Sent!';
    }
}

// Apply for loan
if(isset($_POST['loans'])) {
    $acctid = $_POST['acctid'];
    $email = $_POST['email'];
    $amount = $_POST['amount'];
    $duration = $_POST['duration'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $marital = $_POST['marital'];
    $country = $_POST['country'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $pcode = $_POST['pcode'];
    $address = $_POST['address'];
    $dependents = $_POST['dependents'];
    $home_status = $_POST['home_status'];
    $e_status = $_POST['e_status'];
    $pay_frequency = $_POST['pay_frequency'];
    $pay_type = $_POST['pay_type'];
    $status = "pending";

    // Insert form data in the database 
    $mysqli = $db_conn->query("INSERT INTO loans (acctid,email,amount,duration,fname,mname,lname,gender,dob,marital,country,state,city,pcode,address,dependents,home_status,e_status,pay_frequency,pay_type,status,appdate) VALUES ('".$acctid."', '".$email."', '".$amount."', '".$duration."', '".$fname."', '".$mname."', '".$lname."', '".$gender."', '".$dob."', '".$marital."', '".$country."', '".$state."', '".$city."', '".$pcode."', '".$address."', '".$dependents."', '".$home_status."', '".$e_status."', '".$pay_frequency."', '".$pay_type."', '".$status."', NOW())");
    if($mysqli) {
        $response['status'] = 1; 
        $response['message'] = 'Application sent';
    }
}

if (isset($_POST['remode'])) {
    $transid = substr(str_shuffle("123456789012345678901234567890123456789012345678901234567890"), 0, 7);
    $date = date("Y-m-d h:i:sa");
    $acctid = $db_conn->real_escape_string($_POST['acctid']);
    $raccno = $db_conn->real_escape_string($_POST['raccno']);
    $tsum = $db_conn->real_escape_string($_POST['tsum']);
    $remode = $db_conn->real_escape_string($_POST['remode']);
    $type = "Debit";
    $cotcode = substr(str_shuffle("123456789012345678901234567890123456789012345678901234567890"), 0, 15);
    $status = "pending";

    if ($raccno == $acctid) {
        $response['status'] = 0;
        $response['message'] = 'Enter a different account number';
    } else {
        $ver = $db_conn->query("SELECT * FROM accts WHERE acctid = '$raccno'");
        if ($ver->num_rows > 0) {
            $data_ver = $ver->fetch_assoc();
            $raccname = $data_ver['lname'].' '.$data_ver['fname'];

            $sql = $db_conn->query("SELECT * FROM accts WHERE acctid = '$acctid'");
            $row = $sql->fetch_assoc();
            $accbalance = $row['accbalance'];

            if($tsum > $accbalance) {
                $response['status'] = 0;
                $response['message'] = 'Insufficient Balance';
            } else {
                $mysqli = $db_conn->query("INSERT INTO transactions (transid,regdate,acctid,raccname,raccno,tsum,mode,type,cotcode,status) VALUES ('$transid', '$date', '$acctid', '$raccname', '$raccno', '$tsum', '$remode', '$type', '$cotcode', '$status')");
                $response['status'] = 1; 
                $response['message'] = 'Transaction completed';
            }
        } else {
            $response['status'] = 0;
            $response['message'] = 'Invalid Account';
        }
    }
}

if (isset($_POST['othermode'])) {
    $transid = substr(str_shuffle("123456789012345678901234567890123456789012345678901234567890"), 0, 7);
    $date = date("Y-m-d h:i:sa");
    $acctid = $db_conn->real_escape_string($_POST['acctid']);
    $rbank = $db_conn->real_escape_string($_POST['rbank']);
    $raccname = $db_conn->real_escape_string($_POST['raccname']);
    $raccno = $db_conn->real_escape_string($_POST['raccno']);
    $tsum = $db_conn->real_escape_string($_POST['tsum']);
    $othermode = $db_conn->real_escape_string($_POST['othermode']);
    $type = "Debit";
    $cotcode = substr(str_shuffle("123456789012345678901234567890123456789012345678901234567890"), 0, 15);
    $status = "pending";

    $sql = $db_conn->query("SELECT * FROM accts WHERE acctid = '$acctid'");
    $row = $sql->fetch_assoc();

    $accbalance = $row['accbalance'];

    if($tsum > $accbalance) {
        $response['status'] = 0;
        $response['message'] = 'Insufficient Balance';
    } else {

        $mysqli = $db_conn->query("INSERT INTO transactions (transid,regdate,acctid,rbank,raccname,raccno,tsum,mode,type,cotcode,status) VALUES ('$transid', '$date', '$acctid', '$rbank', '$raccname', '$raccno', '$tsum', '$othermode', '$type', '$cotcode', '$status')");
        $response['status'] = 1; 
        $response['message'] = 'Transaction completed';
    }
}

if (isset($_POST['intmode'])) {
    $transid = substr(str_shuffle("123456789012345678901234567890123456789012345678901234567890"), 0, 7);
    $date = date("Y-m-d h:i:sa");
    $acctid = $db_conn->real_escape_string($_POST['acctid']);
    $rcountry = $db_conn->real_escape_string($_POST['rcountry']);
    $remail = $db_conn->real_escape_string($_POST['remail']);
    $rbank = $db_conn->real_escape_string($_POST['rbank']);
    $raccname = $db_conn->real_escape_string($_POST['raccname']);
    $raccno = $db_conn->real_escape_string($_POST['raccno']);
    $tsum = $db_conn->real_escape_string($_POST['tsum']);
    $intmode = $db_conn->real_escape_string($_POST['intmode']);
    $type = "Debit";
    $cotcode = substr(str_shuffle("123456789012345678901234567890123456789012345678901234567890"), 0, 15);
    $status = "pending";

    $sql = $db_conn->query("SELECT * FROM accts WHERE acctid = '$acctid'");
    $row = $sql->fetch_assoc();

    $accbalance = $row['accbalance'];

    if($tsum > $accbalance) {
        $response['status'] = 0;
        $response['message'] = 'Insufficient Balance';
    } else {

        $mysqli = $db_conn->query("INSERT INTO transactions (transid,regdate,acctid,rcountry,remail,rbank,raccname,raccno,tsum,mode,type,cotcode,status) VALUES ('$transid', '$date', '$acctid', '$rcountry', '$remail', '$rbank', '$raccname', '$raccno', '$tsum', '$intmode', '$type', '$cotcode', '$status')");
        $response['status'] = 1; 
        $response['message'] = 'Transaction completed';
    }
}

if(isset($_POST['getcot'])) {
    $acctid = $_POST['acctid'];
    $transid = $_POST['transid'];
    $tsum = $_POST['tsum'];
    $output = "";

    $mysqli = $db_conn->query("SELECT * FROM transactions WHERE transid = '$transid' AND acctid = '$acctid'");
    if ($mysqli->num_rows > 0) {
      $response['status'] = 1; 
      $response['message'] = '
        <form>
          <div class="mx-sm-5 py-2">
            <input type="hidden" value="'.$acctid.'" id="acctid">
            <input type="hidden" name="transid" id="transid" value="'.$transid.'">
            <input type="hidden" name="tsum" id="tsum" value="'.$tsum.'">
            <div class="form-group my-3 mx-1 px-md-5 mx-md-5 text-center">
                <input type="text" class="form-control col pb-1" id="cotcode" style="max-width: ;" required>
            </div>
            <div id="error" class="text-center text-danger py-2" style="display: none;">Invalid COT Code Contact Issuer</div>
            <div class="form-group text-center py-3">
              <button type="button" id="cotcodebtn" class="btn-info btn" style="min-width:150px;">Confirm</button>
              <a class="text-light btn btn-secondary closeopencotbtn" role="button" style="min-width:150px;">Close</a>
            </div>
          </div>
        </form>
      ';
    }
}

if (isset($_POST['cotcode'])) {
    $acctid = $_POST['acctid'];
    $transid = $_POST['transid'];
    $cotcode = $db_conn->real_escape_string($_POST['cotcode']);
    $tsum = $_POST['tsum'];
    $status = "Completed";

    $mysqli = $db_conn->query("SELECT * FROM accts WHERE acctid = '$acctid'");

    if ($mysqli) {
        $row = $mysqli->fetch_assoc();
        $verification = $row['verification'];
        $accbalance = $row['accbalance'];
        $newbalance = $accbalance - $tsum;

        if ($verification == 'verified') {
           $sql = $db_conn->query("UPDATE accts SET accbalance = '$newbalance' WHERE acctid = '$acctid'");
            if ($sql) {
                $query = $db_conn->query("SELECT * FROM transactions WHERE transid = '$transid' AND cotcode = '$cotcode'");
                if($query->num_rows > 0) {
                    $sqli = $db_conn->query("UPDATE transactions SET status = '$status' WHERE transid = '$transid' AND cotcode = '$cotcode'");
                    $response['status'] = 1; 
                    $response['message'] = 'Transaction completed';
                } else {
                    $response['status'] = 0;
                    $response['message'] = 'COT code does not exist';
                }
            } 
        } else {
            $response['status'] = 2;
            $response['message'] = 'Account not verified';
        }
                
    }
}

if(isset($_POST['oldpassword'])) {
    $acctid = $db_conn->real_escape_string($_POST['acctid']);
    $oldpassword = $db_conn->real_escape_string($_POST['oldpassword']);
    $password = $db_conn->real_escape_string($_POST['password']);

    $mysqli = $db_conn->query("SELECT * FROM accts WHERE acctid = '$acctid'");
    $row = $mysqli->fetch_assoc();

    $opassword = $row['password'];

    if ($opassword !== $oldpassword) {
        $response['status'] = 0; 
        $response['message'] = 'Incorrect old password';
    } else {
        $mysqli = $db_conn->query("UPDATE accts SET password ='$password' WHERE acctid = '$acctid'");
        $response['status'] = 1; 
        $response['message'] = 'Password changed';
    }
}

if (isset($_POST['verify'])) {
    $acctid = $_POST['acctid'];
    $country = $_POST['country'];
    $nationality = $_POST['nationality'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $mname = $_POST['mname'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $pcode = $_POST['pcode'];
    $city = $_POST['city'];
    $issuer = $_POST['issuer'];
    $verification = "pending";
    
    $mysqliStatus = 1;
    
    // Upload file 
    $uploadedPhoto = '';
    
    // Photo path config 
    $photoName = basename(rand().time().$_FILES["passport"]["name"]); 
    $targetFilePath = $uploadDir . $photoName; 
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
     
    // Allow certain file formats 
    $allowTypes = array('jpg', 'png', 'jpeg');
    if(in_array($fileType, $allowTypes)){ 
        // Upload file to the server 
        if(move_uploaded_file($_FILES["passport"]["tmp_name"], $targetFilePath)){ 
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
        $mysqli = $db_conn->query("UPDATE accts SET country = '$country', nationality = '$nationality', fname = '$fname', lname = '$lname', mname = '$mname', dob = '$dob', address = '$address', pcode = '$pcode', city = '$city', issuer = '$issuer', passport = '$uploadedPhoto', verification = '$verification'  WHERE acctid ='$acctid'");
         
        if($mysqli){ 
            $response['status'] = 1; 
            $response['message'] = 'Request submitted successfully!';
        } 
    }
}

if (isset($_POST['update_profile'])) {
    $acctid = $_POST['acctid'];
    $country = $_POST['country'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $mname = $_POST['mname'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $pcode = $_POST['pcode'];
    $city = $_POST['city'];
    
    // Insert form data in the database
    $mysqli = $db_conn->query("UPDATE accts SET country = '$country', fname = '$fname', lname = '$lname', mname = '$mname', dob = '$dob', address = '$address', pcode = '$pcode', city = '$city'  WHERE acctid ='$acctid'");
         
    if($mysqli){ 
        $response['status'] = 1; 
        $response['message'] = 'Profile Updated!';
    } 
}

if (isset($_POST['delete_msgs'])) {
    $unique_id = $_POST['unique_id'];
    $mysqli = $db_conn->query("DELETE FROM conversations WHERE incoming_msg_id = '$unique_id' OR outgoing_msg_id = '$unique_id'");
}

if (isset($_POST['addcard'])) {
    $acctid = $db_conn->real_escape_string($_POST['acctid']);
    $type = $db_conn->real_escape_string($_POST['type']);
    if ($type == 'visa') {
        $serial1 = "46".''.substr(str_shuffle("1234567890123456789"), 0, 2);
    } else {
        $serial1 = "56".''.substr(str_shuffle("1234567890123456789"), 0, 2);
    }
    $serial2 = substr(str_shuffle("1234567890123456789"), 0, 4);
    $serial3 = substr(str_shuffle("1234567890123456789"), 0, 4);
    $serial4 = substr(str_shuffle("1234567890123456789"), 0, 4);
    $exp = "09/26";
    $cvv = substr(str_shuffle("1234567890123456789"), 0, 3);
    $vdr = "virtual";
    $status = "active";

    $mysqli = $db_conn->query("INSERT INTO cards (acctid,type,serial1,serial2,serial3,serial4,exp,cvv,vdr,status) VALUES ('$acctid', '$type', '$serial1', '$serial2', '$serial3', '$serial4', '$exp', '$cvv', '$vdr', '$status')");
    if ($mysqli) {
        $response['status'] = 1; 
        $response['message'] = 'Card Added';
    }
}

if (isset($_POST['addothercard'])) {
    $acctid = $db_conn->real_escape_string($_POST['acctid']);
    $type = $db_conn->real_escape_string($_POST['type']);
    $serial = $db_conn->real_escape_string($_POST['serial']);
    $exp = $db_conn->real_escape_string($_POST['exp']);
    $cvv = $db_conn->real_escape_string($_POST['cvv']);
    $vdr = "other";
    $status = "active";

    $mysqli = $db_conn->query("INSERT INTO cards (acctid,type,serial_all,exp,cvv,vdr,status) VALUES ('$acctid', '$type', '$serial', '$exp', '$cvv', '$vdr', '$status')");
    if ($mysqli) {
        $response['status'] = 1; 
        $response['message'] = 'Card Added';
    }
}

if (isset($_POST['delete_card'])) {
    $card_id = $_POST['card_id'];
    $mysqli = $db_conn->query("DELETE FROM cards WHERE id = '$card_id'");
    if ($mysqli) {
        $response['status'] = 1; 
        $response['message'] = 'Deleted';
    }
}

if (isset($_POST['msg_id'])) {
    $msg_id = $_POST['msg_id'];
    $mysqli = $db_conn->query("SELECT * FROM inbox WHERE msg_id = '$msg_id'");
    if ($mysqli) {
        $data = $mysqli->fetch_assoc();
        $subject = $data['subject'];
        $time = $data['msg_time'];
        $message = $data['message'];
        $response['status'] = 1;
        $response['subject'] = $subject;
        $response['time'] = $time;
        $response['message'] = $message;
    }
}

if (isset($_POST['get_acc'])) {
    $acctid = $_POST['acctid'];
    $mysqli = $db_conn->query("SELECT * FROM accts WHERE acctid = '$acctid'");
    if ($mysqli->num_rows > 0) {
        $row = $mysqli->fetch_assoc();
        $name = $row['lname'].' '.$row['fname'];
        $response['status'] = 1; 
        $response['message'] = $name;
    } else {
        $response['status'] = 0; 
        $response['message'] = 'Invalid Account';
    }
}

// Return response 
echo json_encode($response);
?>