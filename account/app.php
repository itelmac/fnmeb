<?php 
session_start();
include('database/dbconfig.php');
if (!isset($_SESSION['unique_id'])) {
  header('Location:login');
  exit();
}
$unique_id = $_SESSION['unique_id'];
$mysqli = $db_conn->query("SELECT * FROM accts WHERE unique_id = '$unique_id'");
$data = $mysqli->fetch_assoc();
$acctid = $data['acctid'];
$email = $data['email'];
$visa = "visa";
$master = "master";
$vdr = "virtual";
$sqli_visa = $db_conn->query("SELECT * FROM cards WHERE acctid = '$acctid' AND type = '$visa' AND vdr = '$vdr' ORDER BY id DESC LIMIT 1");
if ($sqli_visa->num_rows>0) {
  $card_visa = $sqli_visa->fetch_assoc();
  $card_output1 = '
  <div class="cardcc w-100 h-100 mb-4" height="300px;">
    <div class="cardcc__front cardcc__part w-100" style="position:relative;max-width: 300px;background-color:#93310f;">
      <img class="cardcc__front-square cardcc__square" src="../icon/chip.png"> <strong style="color:white;">USD </strong>
      <img class="cardcc__front-logo cardcc__logo" src="../icon/visa.png">
      <p class="cardcc_numer"><strong>'.$card_visa['serial1'].' '.$card_visa['serial2'].' '.$card_visa['serial3'].' '.$card_visa['serial4'].'</strong></p>
      <div class="cardcc__space-75">
        <span class="cardcc__label">Card Holder</span>
        <p class="cardcc__info"><strong>'.$data['lname'].' '.$data['fname'].'</strong></p>
      </div>
      <div class="cardcc__space-25">
        <span class="cardcc__label">Expires</span>
        <p class="cardcc__info"><strong>'.$card_visa['exp'].'</strong></p>
      </div>
    </div>
  </div>
  ';
} else {
  $card_output1 = '';
}

$sqli_master = $db_conn->query("SELECT * FROM cards WHERE acctid = '$acctid' AND type = '$master' AND vdr = '$vdr' ORDER BY id DESC LIMIT 1");
if ($sqli_master->num_rows>0) {
  $card_master = $sqli_master->fetch_assoc();
  $card_output2 = '
  <div class="cardcc w-100 h-100 mb-4" height="300px;">
  <div class="cardcc__front cardcc__part w-100" style="position:relative;max-width: 300px;">
    <img class="cardcc__front-square cardcc__square" src="../icon/chip.png"> <strong style="color:white;">USD </strong>
    <img class="cardcc__front-logo cardcc__logo" style="width:100px;height:80px;padding:0px;margin-top:0px;" src="../icon/master.png">
    <p class="cardcc_numer"><strong>'.$card_master['serial1'].' '.$card_master['serial2'].' '.$card_master['serial3'].' '.$card_master['serial4'].'</strong></p>
    <div class="cardcc__space-75">
      <span class="cardcc__label">Card Holder</span>
      <p class="cardcc__info"><strong>'.$data['lname'].' '.$data['fname'].'</strong></p>
    </div>
    <div class="cardcc__space-25">
      <span class="cardcc__label">Expires</span>
      <p class="cardcc__info"><strong>'.$card_master['exp'].'</strong></p>
    </div>
  </div>
</div>
  ';
} else {
  $card_output2 = '';
}
$stmt = $db_conn->query("SELECT * FROM admin WHERE adminid = 'admin' ");
if ($stmt){
  $stmt_row = $stmt->fetch_assoc();
  $adminid = $stmt_row['unique_id'];
}

if ($data['verification'] == 'verified') {
$verify_img = '<img src="img/kyc_icon.png" alt="KYC">';
$verify_info = 'Your account has been verified';
} else if ($data['verification'] == 'pending'){
$verify_img = '<img src="img/kyc_unverified.png" alt="KYC">';
$verify_info = 'Your verification request has been received. Kindly contact <a class="text-info" href="app?support">Support</a> for further assistance.';
} else if ($data['verification'] == 'declined'){
$verify_img = '<img src="img/kyc_unverified.png" alt="KYC">';
$verify_info = 'Your verification request has been declined. Kindly contact <a class="text-info" href="app?support">Support</a> for further information.';
} else {
$verify_img = '<img src="img/kyc_unverified.png" alt="KYC">';
$verify_info = '<button class="btn btn-secondary" data-toggle="modal" data-target="#verify_account">Verify Account</button>';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Account &middot; First National Merchant Bank</title>
  <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.png">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../dist/css/custom-css.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css">
  <style>
    .zoom:hover {
      -ms-transform: scale(1.05); /* IE 9 */
      -webkit-transform: scale(1.05); /* Safari 3-8 */
      transform: scale(1.05);
      transition: transform .2s;
    }
    label:not(.form-check-label):not(.custom-file-label) {
      font-weight: 400;
      white-space: nowrap;

    }
    .dataTables_length .form-control {
      width: auto;
    }
    div.dataTables_wrapper div.dataTables_filter input {
      margin-left: 0.5em;
      display: inline-block;
      width: auto;
    }

    .dt-buttons {
      margin-bottom: 5px;
    }
    .read-msg, .back-btn {
      cursor: pointer;
    }
    .nav-tabs .nav-link {
      border: none;
    }
    .profile-box .body .profile-info-box .profile-pic {
      width: 150px;
      height: 150px;
      border-radius: 100%;
      overflow: hidden;
      border: 5px solid #CCC;
      margin: auto;
      margin-top: -90px;
    }
    .profile-pic {
      position: relative;
      width: 125px;
      height: 125px;
      border-radius: 50%;
      overflow: hidden;
      background-color: #111;
    }

    .profile-pic:hover .profilepic__content {
      opacity: 1;
    }

    .profile-pic:hover .profilepic__image {
      opacity: .5;
    }

    .profilepic__image {
      object-fit: cover;
      opacity: 1;
      transition: opacity .2s ease-in-out;
    }

    .profilepic__content {
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: white;
      opacity: 0;
      transition: opacity .2s ease-in-out;
      cursor: pointer;
    }

    .profilepic__icon {
      color: white;
    }

    .fas {
      font-size: 20px;
    }

    .profilepic__text {
      text-transform: uppercase;
      font-size: 12px;
    }

    .profile-pic input[type="file"] {
      cursor: pointer;
      display: block;
      height: 100%;
      left: 0;
      opacity: 0 !important;
      position: absolute;
      top: 0;
      width: 100%;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini sidebar-collapse layout-fixed">

  <!-- Modal Authorisation -->
  <div class="modal fade" id="transAuth" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-body" style="padding: 0;">
          
          <div id="cotcode_ent" class="row justify-content-center px-2 pt-4 px-sm-5" style="display:block;">
            <div class="card rounded-0 px-4 border-0 shadow-none shadow-none w-100">
              <div class="card-body px-0" style="font-size: 16px;">
                <div class="text-primary border-bottom pb-2">
                  <i class="fa fa-rss"></i>
                  <span class="ml-5 font-weight-bold"> Online Transfer</span>
                </div>
                <div class="progress mb-4 mt-5" style="height: 1.8em; border-radius: 16px; background: #aeaeae">
                  <div class="progress-bar progress-bar-striped progress-bar-animated bg-info text-dark" role="progressbar" style="width: 41%; height: 100%; border-radius: 16px;" aria-valuenow="41" aria-valuemin="0" aria-valuemax="100">41% Completed</div>
                </div>
                <div class="py-3 px-4 justify-content-center" style="min-height: 350px;">
                  <div class="card-title w-100 py-2 text-center text-secondary mb-4">
                    Contact <a href="?support">support</a> or send an email to <a href="mailto:mail@fnmeb.com">mail@fnmeb.com</a> to get Authorisation Code.
                  </div>
                  
                  <div class="card-title w-100 text-danger text-center py-2 mb-5 mt-2 bg-danger bg-opacity-10" style="font-size: 12px;">Enter Authorisation Code</div>
                  <div id="getcotResponse"></div>
                </div>
              </div>
              
            </div>
          </div>
          <div id="cotcode_verify" class="row justify-content-center px-2 pt-4 px-sm-5" style="display:none;">
            <div class="card rounded-0 px-4 border-0 shadow-none">
              
              <div class="card-body px-0" style="font-size: 16px;">
                <div class="text-primary border-bottom pb-2">
                  <i class="fa fa-rss"></i>
                  <span class="ml-5 font-weight-bold"> Online Transfer</span>
                </div>
                <div class="progress my-4" style="height: 1.8em; border-radius: 16px; background: #aeaeae">
                  <div class="progress-bar progress-bar-striped progress-bar-animated bg-info text-dark" role="progressbar" style="width: 75%; height: 100%; border-radius: 16px;" aria-valuenow="41" aria-valuemin="0" aria-valuemax="100">75% Completed</div>
                </div>
                <div class="bg-235 py-3 px-4" style="min-height: 350px;">
                  <div class="card-title py-2 h4 text-center text-secondary">
                    KYC on Hold Status/CVLMF KYC/ Incomplete KYC
                  </div>
                  
                  <div class="card-text text-danger text-center py-2 mb-2 bg-danger bg-opacity-10" style="font-size: 12px;">Complete Your KYC verification</div>
                  <div class="card border-0 rounded-0">
                    <div class="card-body">
                      <div class="card-text text-center">
                        If your KYC status is “KYC On-Hold”/CVL MFKYC/Incomplete KYC Verified, you are requested to clear the deficiency(ies) or provide missing information/documents to the Intermediary with whom you have completed the KYC process. As of today, the KYC authorities do not allow ClearTax to make a new KYC where an earlier KYC process was initiated. To continue <a type="button" class="text-info" data-toggle="modal" data-target="#verify_account">click here</a> 
                      </div>
                      <div class="text-center mt-4">
                        <a class="text-primary closeopencotbtn" role="button" style="text-decoration: none;"><span class="font-weight-normal">—</span> Go Back <span class="font-weight-normal">—</span></a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
          </div>
          <div id="cotcode_success" class="row justify-content-center px-2 pt-4 px-sm-5" style="min-height: 540px; display:none;">
            <div class="card rounded-0 px-4 border-0 shadow-none">
              <div class="card-body px-0" style="font-size: 16px;">
                <div class="text-primary border-bottom pb-2">
                  <i class="fa fa-rss"></i>
                  <span class="ml-5 font-weight-bold"> Online Transfer</span>
                </div>
                <div class="progress mb-4 mt-5" style="height: 1.8em; border-radius: 16px; background: #aeaeae">
                  <div class="progress-bar progress-bar-striped progress-bar-animated bg-info text-dark" role="progressbar" style="width: 100%; height: 100%; border-radius: 16px;" aria-valuenow="41" aria-valuemin="0" aria-valuemax="100">100% Completed</div>
                </div>
              </div>
              <div class="card-body w-100 text-center text-success">
                <p style="font-size: 100px;"><i class="fa fa-thumbs-up"></i></p>
              </div>
              <div class="card-body">
                <div class="py-3 px-4">
                
                  <div class="text-center">
                    <a class="text-primary closeopencotbtn" role="button" style="text-decoration: none;">Back</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Card Modal-->
  <div class="modal fade" id="addOtherCardModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-size: 14px;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">+ Add New Card</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          
          <form id="addOtherCardForm" class="forms-sample col-12">
            <input type="hidden" name="acctid" value="<?php echo $acctid; ?>">
            <input type="hidden" name="addothercard">
            <div class="form-group">
              <label>Card Type:</label>
              <select class="custom-select form-control" name="type" required="">
                <option value="" disabled>Select Card Type</option>]
                <option value="visa">Visa Card</option>
                <option value="master">Master Card</option>
              </select>
            </div>
            <div class="form-group">
              <label>Card Serial Number:</label>
              <input class="form-control" type="text" name="serial" placeholder="e.g  5310 1092 2902 1922" required="">
            </div>
            <div class="form-group">
              <label>Card Expiry Date:</label>
              <input class="form-control" type="text" name="exp" placeholder="e.g  10/25" required="">
            </div>
            <div class="form-group">
              <label>CVV/Security Code:</label>
              <input class="form-control" type="text" name="cvv" placeholder="e.g 899" required="">
            </div>
            <div class="form-group">
              <button type="submit" class="theme btn btn-primary mr-2">+ Add Card</button>
            </div>
            <div class="form-group">
              <div id="error_response"></div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Card Modal-->
  <div class="modal fade" id="loanApplicationModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-size: 14px;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">+ Apply for a loan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <?php
          echo '
          <div class="card">

            <form id="loanApplicationForm" class="card shadow-none rounded-0">
              
              <div class="card-body">
                  
                  <input type="hidden" name="loans">
                  <input type="hidden" name="acctid" value="'.$acctid.'">
                  <input type="hidden" name="email" value="'.$email.'">
                  <div class="form-group w-100 ">
                    <div class="card-text text-muted text-capitalize text-center">Loan Amount: $<span class="text-primary" id="slider_value">1000</span></div>
                    <div class="form-row">
                      <div class="col-auto">$500</div>
                      <div class="col pt-1"><input type="range" name="amount" class="form-range slider w-100" min="500" value="1000" max="50000" step="500" id="myRange" onchange="show_value(this.value);" required></div>
                      <div class="col-auto">$50000</div>    
                    </div>
                  </div>

                  <div class="form-group w-100 py-4">
                    <div class="card-text text-muted text-capitalize text-center">Duration (Months): <span class="text-primary" id="slider_value1">6</span> Months</div>
                    <div class="form-row">
                      <div class="col-auto">3 Months</div>
                      <div class="col pt-1"><input type="range" name="duration" class="form-range slider w-100" min="3" value="12" max="36" step="1" id="myRange1" onchange="show_value1(this.value);" required></div>
                      <div class="col-auto">36 Months</div>    
                    </div>
                  </div>

                  <div class="form-group w-100 pt-4">
                    <div class="card-text text-muted text-uppercase text-center pb-2">Personal Information</span></div>
                    <div class="form-row pt-2">
                      <div class="col-12 col-sm-4 pt-4 pt-sm-0">
                          <label class="text-sm" for="floatingInput1">First name</label>
                          <input type="text" name="fname" class="form-control text-capitalize rounded-0 border-top-0 border-right-0 border-left-0" id="floatingInput1" required>
                      </div>
                      <div class="col-12 col-sm-4 pt-4 pt-sm-0">
                          <label class="text-sm" for="floatingInput1">Middle name (Optional)</label>
                          <input type="text" name="mname" class="form-control text-capitalize rounded-0 border-top-0 border-right-0 border-left-0" id="floatingInput1">
                      </div>
                      <div class="col-12 col-sm-4 pt-4 pt-sm-0">
                          <label class="text-sm" for="floatingInput3">Last name</label>
                          <input type="text" name="lname" class="form-control text-capitalize rounded-0 border-top-0 border-right-0 border-left-0" id="floatingInput3" required>
                      </div>    
                    </div>

                    <div class="form-row pt-4">
                      <div class="col-12 col-sm-4 pt-4 pt-sm-0">
                          <label class="text-sm" for="floatingInput1">Gender</label>
                          <select name="gender" class="form-control text-capitalize rounded-0 border-top-0 border-right-0 border-left-0" required>
                            <option value="">Please select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                          </select>
                      </div>
                      
                      <div class="col-12 col-sm-4 pt-4 pt-sm-0">
                          <label class="text-sm" for="floatingInput3">Date of Birth</label>
                          <input type="date" name="dob" class="form-control rounded-0 border-top-0 border-right-0 border-left-0" id="floatingInput3" required>
                      </div>
                      <div class="col-12 col-sm-4 pt-4 pt-sm-0">
                          <label class="text-sm" for="floatingInput3">Marital status</label>
                          <select name="marital" class="form-control text-capitalize rounded-0 border-top-0 border-right-0 border-left-0" required>
                            <option value="">Please select</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Other">Other</option>
                          </select>
                      </div>    
                    </div>
                  </div>

                  <div class="form-group w-100 pt-4">
                    <div class="card-text text-muted text-uppercase text-center pb-2">Contact Details</span></div>
                    <div class="form-row pt-2">
                      <div class="col-12 col-sm-4 pt-4 pt-sm-0">
                          <label class="text-sm" for="floatingInput1">Country</label>
                          <input type="text" name="country" class="form-control text-capitalize rounded-0 border-top-0 border-right-0 border-left-0" id="floatingInput1" required>
                      </div>
                      <div class="col-12 col-sm-4 pt-4 pt-sm-0">
                          <label class="text-sm" for="floatingInput1">State/County</label>
                          <input type="text" name="state" class="form-control text-capitalize rounded-0 border-top-0 border-right-0 border-left-0" id="floatingInput1" required>
                      </div>
                      <div class="col-12 col-sm-4 pt-4 pt-sm-0">
                          <label class="text-sm" for="floatingInput3">City</label>
                          <input type="text" name="city" class="form-control text-capitalize rounded-0 border-top-0 border-right-0 border-left-0" id="floatingInput3" required>
                      </div>    
                    </div>

                    <div class="form-row pt-4">
                      <div class="col-12 col-sm-4 pt-4 pt-sm-0">
                          <label class="text-sm" for="floatingInput3">Post code</label>
                          <input type="text" name="pcode" class="form-control text-capitalize rounded-0 border-top-0 border-right-0 border-left-0" id="floatingInput3" required>
                      </div>
                      <div class="col-12 col-sm-8 pt-4 pt-sm-0">
                          <label class="text-sm" for="floatingInput3">Address</label>
                          <input type="text" name="address" class="form-control text-capitalize rounded-0 border-top-0 border-right-0 border-left-0" id="floatingInput3" required>
                      </div>
                      
                    </div>

                    <div class="form-row pt-4">
                      <div class="col-12 col-sm-4 pt-4 pt-sm-0">
                          <label class="text-sm" for="floatingInput3">Number of dependents</label>
                          <input type="number" name="dependents" class="form-control text-capitalize rounded-0 border-top-0 border-right-0 border-left-0" id="floatingInput3" required>
                      </div>
                      <div class="col-12 col-sm-8 pt-4 pt-sm-0">
                        <label class="text-sm" for="floatingInput1">Please tell us your home owner status</label>
                        <select name="home_status" class="form-control text-capitalize rounded-0 border-top-0 border-right-0 border-left-0" required>
                          <option value="">Please select</option>
                          <option value="Home Owner">Home Owner</option>
                          <option value="Joint Owner">Joint Owner</option>
                          <option value="Rent">Rent</option>
                          <option value="Living With Family">Living With Family</option>
                          <option value="Tenant Furnished">Tenant Furnished</option>
                          <option value="Tenant Unfurnished">Tenant Unfurnished</option>
                          <option value="Council Tenant">Council Tenant</option>
                          <option value="Other">Other</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="form-group w-100 pt-4">
                    <div class="card-text text-muted text-uppercase text-center pb-2">Employment History</span></div>
                    <div class="form-row pt-2">
                      <div class="col-12 col-sm-4 pt-4 pt-sm-0">
                          <label class="text-sm" for="floatingInput1">What&#39s your current employment status?</label>
                          <select name="e_status" class="form-control text-capitalize rounded-0 border-top-0 border-right-0 border-left-0" required>
                            <option value="">Please select</option>
                            <option value="Full time">Full time</option>
                            <option value="Part time">Part time</option>
                            <option value="Self employed">Self employed</option>
                            <option value="Student">Student</option>
                            <option value="Homemaker">Homemaker</option>
                            <option value="Not currenlty employed">Not currenlty employed</option>
                            <option value="Disability benefits">Disability benefits</option>
                            <option value="Other benefits">Other benefit</option>
                          </select>
                      </div>
                      <div class="col-12 col-sm-4 pt-4 pt-sm-0">
                          <label class="text-sm" for="floatingInput1">How often do you get paid?</label>
                          <select name="pay_frequency" class="form-control text-capitalize rounded-0 border-top-0 border-right-0 border-left-0" required>
                            <option value="">Please select</option>
                            <option value="Specific Day of the Month">Specific Day of the Month</option>
                            <option value="Last Working Day of the Month">Last Working Day of the Month</option>
                            <option value="Last Monday of the Month">Last Monday of the Month</option>
                            <option value="Last Tuesday of the Month">Last Tuesday of the Month</option>
                            <option value="Last Wednesday of the Month">Last Wednesday of the Month</option>
                            <option value="Last Thursday of the Month">Last Thursday of the Month</option>
                            <option value="Last Friday of the Month">Last Friday of the Month</option>
                            <option value="Four Weekly">Four Weekly</option>
                            <option value="Twice Monthly">Twice Monthly</option>
                            <option value="Bi-Weekly">Bi-Weekly</option>
                            <option value="Weekly">Weekly</option>
                          </select>
                      </div>
                      <div class="col-12 col-sm-4 pt-4 pt-sm-0">
                          <label class="text-sm" for="floatingInput1">How are you paid?</label>
                          <select name="pay_type" class="form-control text-capitalize rounded-0 border-top-0 border-right-0 border-left-0" required>
                            <option value="">Please select</option>
                            <option value="Electronically">Electronically</option>
                            <option value="Cash">Cash</option>
                            <option value="Other">Other</option>
                          </select>
                      </div>
                    </div>

                    <div class="form-row pt-4">
                      <div class="col-12">
                          <div class="card-text">By submitting your application, you are confirming that you agree with our <span class="text-primary">Terms & Conditions</span> and that you have read our <span class="text-primary">Privacy Policy</span>. You also confirm that the details you have provided are accurate as they will be shared with our panel of lenders and brokers for the purpose of performing a soft credit search that will not affect your credit rating. It is important for you to understand that Fnmeb Ltd and its partners may need to contact you as an essential part of your application.</div>
                      </div>

                    </div>
                  </div>


                  <div class="form-group">
                      <div id="response4" class="card-text text-center"></div>
                  </div>

                  
              </div>
              <div class="card-footer text-center">
                  <button type="submit" id="loanApplicationBtn" class="btn btn-primary px-5">Apply Now</button>
                  <button type="button" class="btn btn-danger px-5" data-dismiss="modal">Cancel</button>
              </div>
            </form>
            <div id="loadet1" class="overlay dark" style="display: none;"><i class="fas fa-4x fa-spinner fa-spin"></i></div>
          </div>
          <!-- /.card -->
          ';
          ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal FNMEB Account-->
  <div class="modal fade" id="anontherFNMEBAccount" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-size: 14px;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">EFT TRANSFER</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body p-0">
          
          <div class="card m-0 rounded-0">
            <div class="card-body">
              <form>
                <input type="hidden" value="<?php echo $data['acctid']; ?>" id="acctid">
                <input type="hidden" id="remode" value="EFT">
                <div class="form-group">
                  <input type="number" class="form-control" id="reanumber" placeholder="Account Number" required>
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" id="refullname" placeholder="Name of Account Holder" readonly required>
                </div>
                <div class="form-group">
                  <input type="number" class="form-control" id="reamount" placeholder="Amount ($):" required>
                </div>
                <div id="error" class="py-2"></div>
                <button type="button" class="btn btn-primary" id="retransferbtn"><i class="fab fa-wpressr"></i> Confirm</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>     
              </form>
            </div>
            <div id="loadez1" class="overlay dark" style="display: none;"><i class="fas fa-4x fa-spinner fa-spin"></i></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Other Commercial Bank-->
  <div class="modal fade" id="otherCommercialBank" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-size: 14px;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">INTER-BANK FUNDS TRANSFER</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body p-0">
          <div class="card m-0 rounded-0">
            <div class="card-body">
              <form>
                <input type="hidden" value="<?php echo $data['acctid']; ?>" id="acctid1">
                <input type="hidden" id="ibftmode" value="IBFT">
                <div class="form-group">
                  <label>Select a Bank</label>
                  <select class="form-control select2" aria-label="Choose Bank" id="otherbankname" style="width: 100%;">
                    <option value="Aldermore Bank Plc, South Africa">Aldermore Bank Plc, South Africa</option>
                    <option value="Alliance Trust Savings Limited, Scotland">Alliance Trust Savings Limited, Scotland</option>
                    <option value="Arbuthnot Latham & Co Limited, UK">Arbuthnot Latham & Co Limited, UK</option>
                    <option value="Atom Bank Plc, UK">Atom Bank Plc, UK</option>
                    <option value="Banco Santander SA, Spain">Banco Santander SA, Spain</option>
                    <option value="Bank of Ireland Group, Ireland">Bank of Ireland Group, Ireland</option>
                    <option value="Bank of Scotland Plc, Scotland">Bank of Scotland Plc, Scotland</option>
                    <option value="Barclays Plc, UK">Barclays Plc, UK</option>
                    <option value="Bayerische Landesbank, Germany">Bayerische Landesbank, Germany</option>
                    <option value="BNP Paribas SA, France">BNP Paribas SA, France</option>
                    <option value="Crédit Agricole Group, France">Crédit Agricole Group, France</option>
                    <option value="Deutsche Bank AG, Germany">Deutsche Bank AG, Germany</option>
                    <option value="DZ Bank AG, Germany">DZ Bank AG, Germany</option>
                    <option value="DNB ASA, Norway">DNB ASA, Norway</option>
                    <option value="HSBC Holdings Plc, UK">HSBC Holdings Plc, UK</option>
                    <option value="ING Groep NV, Netherlands">ING Groep NV, Netherlands</option>
                    <option value="Intesa Sanpaolo SpA, Italy">Intesa Sanpaolo SpA, Italy</option>
                    <option value="KBC Group NV, Belgium">KBC Group NV, Belgium</option>
                    <option value="Kingdom Bank Ltd, UK">Kingdom Bank Ltd, UK</option>
                    <option value="Lloyds Banking Group, UK">Lloyds Banking Group, UK</option>
                    <option value="Morgan Stanley Bank International Limited, USA">Morgan Stanley Bank International Limited, USA</option>
                    <option value="Nordea Bank AB, Sweden">Nordea Bank AB, Sweden</option>
                    <option value="Société Générale SA, France">Société Générale SA, France</option>
                    <option value="Swedbank AB, Sweden">Swedbank AB, Sweden</option>
                    <option value="UBS Group AG, Switzerland">UBS Group AG, Switzerland</option>
                    <option value="UniCredit SpA, Italy">UniCredit SpA, Italy</option>
                    <option value="VTB Bank, Russia">VTB Bank, Russia</option>
                    <option value="Rabobank, Netherlands">Rabobank, Netherlands</option>
                    <option value="Royal Bank of Scotland Group, UK">Royal Bank of Scotland Group, UK</option>
                    <option value="Wesleyan Bank Limited, UK">Wesleyan Bank Limited, UK</option>
                    <option value="Wyelands Bank Plc, UK">Wyelands Bank Plc, UK</option>
                  </select>
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" id="otherrefullname" placeholder="Name of Account Holder" required>
                </div>
                <div class="form-group">
                  <input type="number" class="form-control" id="otherreanumber" placeholder="Account Number" required>
                </div>
                <div class="form-group">
                  <input type="number" class="form-control" id="otherreamount" placeholder="Amount ($)" required>
                </div>
                <div id="error1" class="py-2"></div>
                <button type="button" class="btn btn-primary" id="othertransferbtn"><i class="fab fa-wpressr"></i> Confirm</button>        
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              </form>
            </div>
            <div id="loadez2" class="overlay dark" style="display: none;"><i class="fas fa-4x fa-spinner fa-spin"></i></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal International Funds Transfer-->
  <div class="modal fade" id="internationalFundsTransfer" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-size: 14px;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">INTERNATIONAL FUNDS TRANSFER</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body p-0">
          <div class="card m-0 rounded-0">
            <div class="card-body">
              <form>
                <input type="hidden" value="<?php echo $data['acctid']; ?>" id="acctid2">
                <input type="hidden" id="intmode" value="IFT">
                <div class="form-group">
                  <label>Select Receiver Country</label>
                  <select class="form-control select2" id="intcountry" style="width: 100%;">
                    <option value="Afganistan">Afghanistan</option>
                    <option value="Albania">Albania</option>
                    <option value="Algeria">Algeria</option>
                    <option value="American Samoa">American Samoa</option>
                    <option value="Andorra">Andorra</option>
                    <option value="Angola">Angola</option>
                    <option value="Anguilla">Anguilla</option>
                    <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                    <option value="Argentina">Argentina</option>
                    <option value="Armenia">Armenia</option>
                    <option value="Aruba">Aruba</option>
                    <option value="Australia">Australia</option>
                    <option value="Austria">Austria</option>
                    <option value="Azerbaijan">Azerbaijan</option>
                    <option value="Bahamas">Bahamas</option>
                    <option value="Bahrain">Bahrain</option>
                    <option value="Bangladesh">Bangladesh</option>
                    <option value="Barbados">Barbados</option>
                    <option value="Belarus">Belarus</option>
                    <option value="Belgium">Belgium</option>
                    <option value="Belize">Belize</option>
                    <option value="Benin">Benin</option>
                    <option value="Bermuda">Bermuda</option>
                    <option value="Bhutan">Bhutan</option>
                    <option value="Bolivia">Bolivia</option>
                    <option value="Bonaire">Bonaire</option>
                    <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                    <option value="Botswana">Botswana</option>
                    <option value="Brazil">Brazil</option>
                    <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                    <option value="Brunei">Brunei</option>
                    <option value="Bulgaria">Bulgaria</option>
                    <option value="Burkina Faso">Burkina Faso</option>
                    <option value="Burundi">Burundi</option>
                    <option value="Cambodia">Cambodia</option>
                    <option value="Cameroon">Cameroon</option>
                    <option value="Canada">Canada</option>
                    <option value="Canary Islands">Canary Islands</option>
                    <option value="Cape Verde">Cape Verde</option>
                    <option value="Cayman Islands">Cayman Islands</option>
                    <option value="Central African Republic">Central African Republic</option>
                    <option value="Chad">Chad</option>
                    <option value="Channel Islands">Channel Islands</option>
                    <option value="Chile">Chile</option>
                    <option value="China">China</option>
                    <option value="Christmas Island">Christmas Island</option>
                    <option value="Cocos Island">Cocos Island</option>
                    <option value="Colombia">Colombia</option>
                    <option value="Comoros">Comoros</option>
                    <option value="Congo">Congo</option>
                    <option value="Cook Islands">Cook Islands</option>
                    <option value="Costa Rica">Costa Rica</option>
                    <option value="Cote DIvoire">Cote DIvoire</option>
                    <option value="Croatia">Croatia</option>
                    <option value="Cuba">Cuba</option>
                    <option value="Curaco">Curacao</option>
                    <option value="Cyprus">Cyprus</option>
                    <option value="Czech Republic">Czech Republic</option>
                    <option value="Denmark">Denmark</option>
                    <option value="Djibouti">Djibouti</option>
                    <option value="Dominica">Dominica</option>
                    <option value="Dominican Republic">Dominican Republic</option>
                    <option value="East Timor">East Timor</option>
                    <option value="Ecuador">Ecuador</option>
                    <option value="Egypt">Egypt</option>
                    <option value="El Salvador">El Salvador</option>
                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                    <option value="Eritrea">Eritrea</option>
                    <option value="Estonia">Estonia</option>
                    <option value="Ethiopia">Ethiopia</option>
                    <option value="Falkland Islands">Falkland Islands</option>
                    <option value="Faroe Islands">Faroe Islands</option>
                    <option value="Fiji">Fiji</option>
                    <option value="Finland">Finland</option>
                    <option value="France">France</option>
                    <option value="French Guiana">French Guiana</option>
                    <option value="French Polynesia">French Polynesia</option>
                    <option value="French Southern Ter">French Southern Ter</option>
                    <option value="Gabon">Gabon</option>
                    <option value="Gambia">Gambia</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Germany">Germany</option>
                    <option value="Ghana">Ghana</option>
                    <option value="Gibraltar">Gibraltar</option>
                    <option value="Great Britain">Great Britain</option>
                    <option value="Greece">Greece</option>
                    <option value="Greenland">Greenland</option>
                    <option value="Grenada">Grenada</option>
                    <option value="Guadeloupe">Guadeloupe</option>
                    <option value="Guam">Guam</option>
                    <option value="Guatemala">Guatemala</option>
                    <option value="Guinea">Guinea</option>
                    <option value="Guyana">Guyana</option>
                    <option value="Haiti">Haiti</option>
                    <option value="Hawaii">Hawaii</option>
                    <option value="Honduras">Honduras</option>
                    <option value="Hong Kong">Hong Kong</option>
                    <option value="Hungary">Hungary</option>
                    <option value="Iceland">Iceland</option>
                    <option value="Indonesia">Indonesia</option>
                    <option value="India">India</option>
                    <option value="Iran">Iran</option>
                    <option value="Iraq">Iraq</option>
                    <option value="Ireland">Ireland</option>
                    <option value="Isle of Man">Isle of Man</option>
                    <option value="Israel">Israel</option>
                    <option value="Italy">Italy</option>
                    <option value="Jamaica">Jamaica</option>
                    <option value="Japan">Japan</option>
                    <option value="Jordan">Jordan</option>
                    <option value="Kazakhstan">Kazakhstan</option>
                    <option value="Kenya">Kenya</option>
                    <option value="Kiribati">Kiribati</option>
                    <option value="Korea North">Korea North</option>
                    <option value="Korea Sout">Korea South</option>
                    <option value="Kuwait">Kuwait</option>
                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                    <option value="Laos">Laos</option>
                    <option value="Latvia">Latvia</option>
                    <option value="Lebanon">Lebanon</option>
                    <option value="Lesotho">Lesotho</option>
                    <option value="Liberia">Liberia</option>
                    <option value="Libya">Libya</option>
                    <option value="Liechtenstein">Liechtenstein</option>
                    <option value="Lithuania">Lithuania</option>
                    <option value="Luxembourg">Luxembourg</option>
                    <option value="Macau">Macau</option>
                    <option value="Macedonia">Macedonia</option>
                    <option value="Madagascar">Madagascar</option>
                    <option value="Malaysia">Malaysia</option>
                    <option value="Malawi">Malawi</option>
                    <option value="Maldives">Maldives</option>
                    <option value="Mali">Mali</option>
                    <option value="Malta">Malta</option>
                    <option value="Marshall Islands">Marshall Islands</option>
                    <option value="Martinique">Martinique</option>
                    <option value="Mauritania">Mauritania</option>
                    <option value="Mauritius">Mauritius</option>
                    <option value="Mayotte">Mayotte</option>
                    <option value="Mexico">Mexico</option>
                    <option value="Midway Islands">Midway Islands</option>
                    <option value="Moldova">Moldova</option>
                    <option value="Monaco">Monaco</option>
                    <option value="Mongolia">Mongolia</option>
                    <option value="Montserrat">Montserrat</option>
                    <option value="Morocco">Morocco</option>
                    <option value="Mozambique">Mozambique</option>
                    <option value="Myanmar">Myanmar</option>
                    <option value="Nambia">Nambia</option>
                    <option value="Nauru">Nauru</option>
                    <option value="Nepal">Nepal</option>
                    <option value="Netherland Antilles">Netherland Antilles</option>
                    <option value="Netherlands">Netherlands (Holland, Europe)</option>
                    <option value="Nevis">Nevis</option>
                    <option value="New Caledonia">New Caledonia</option>
                    <option value="New Zealand">New Zealand</option>
                    <option value="Nicaragua">Nicaragua</option>
                    <option value="Niger">Niger</option>
                    <option value="Nigeria">Nigeria</option>
                    <option value="Niue">Niue</option>
                    <option value="Norfolk Island">Norfolk Island</option>
                    <option value="Norway">Norway</option>
                    <option value="Oman">Oman</option>
                    <option value="Pakistan">Pakistan</option>
                    <option value="Palau Island">Palau Island</option>
                    <option value="Palestine">Palestine</option>
                    <option value="Panama">Panama</option>
                    <option value="Papua New Guinea">Papua New Guinea</option>
                    <option value="Paraguay">Paraguay</option>
                    <option value="Peru">Peru</option>
                    <option value="Phillipines">Philippines</option>
                    <option value="Pitcairn Island">Pitcairn Island</option>
                    <option value="Poland">Poland</option>
                    <option value="Portugal">Portugal</option>
                    <option value="Puerto Rico">Puerto Rico</option>
                    <option value="Qatar">Qatar</option>
                    <option value="Republic of Montenegro">Republic of Montenegro</option>
                    <option value="Republic of Serbia">Republic of Serbia</option>
                    <option value="Reunion">Reunion</option>
                    <option value="Romania">Romania</option>
                    <option value="Russia">Russia</option>
                    <option value="Rwanda">Rwanda</option>
                    <option value="St Barthelemy">St Barthelemy</option>
                    <option value="St Eustatius">St Eustatius</option>
                    <option value="St Helena">St Helena</option>
                    <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                    <option value="St Lucia">St Lucia</option>
                    <option value="St Maarten">St Maarten</option>
                    <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                    <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                    <option value="Saipan">Saipan</option>
                    <option value="Samoa">Samoa</option>
                    <option value="Samoa American">Samoa American</option>
                    <option value="San Marino">San Marino</option>
                    <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                    <option value="Saudi Arabia">Saudi Arabia</option>
                    <option value="Senegal">Senegal</option>
                    <option value="Seychelles">Seychelles</option>
                    <option value="Sierra Leone">Sierra Leone</option>
                    <option value="Singapore">Singapore</option>
                    <option value="Slovakia">Slovakia</option>
                    <option value="Slovenia">Slovenia</option>
                    <option value="Solomon Islands">Solomon Islands</option>
                    <option value="Somalia">Somalia</option>
                    <option value="South Africa">South Africa</option>
                    <option value="Spain">Spain</option>
                    <option value="Sri Lanka">Sri Lanka</option>
                    <option value="Sudan">Sudan</option>
                    <option value="Suriname">Suriname</option>
                    <option value="Swaziland">Swaziland</option>
                    <option value="Sweden">Sweden</option>
                    <option value="Switzerland">Switzerland</option>
                    <option value="Syria">Syria</option>
                    <option value="Tahiti">Tahiti</option>
                    <option value="Taiwan">Taiwan</option>
                    <option value="Tajikistan">Tajikistan</option>
                    <option value="Tanzania">Tanzania</option>
                    <option value="Thailand">Thailand</option>
                    <option value="Togo">Togo</option>
                    <option value="Tokelau">Tokelau</option>
                    <option value="Tonga">Tonga</option>
                    <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                    <option value="Tunisia">Tunisia</option>
                    <option value="Turkey">Turkey</option>
                    <option value="Turkmenistan">Turkmenistan</option>
                    <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                    <option value="Tuvalu">Tuvalu</option>
                    <option value="Uganda">Uganda</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="Ukraine">Ukraine</option>
                    <option value="United Arab Erimates">United Arab Emirates</option>
                    <option value="United States of America">United States of America</option>
                    <option value="Uraguay">Uruguay</option>
                    <option value="Uzbekistan">Uzbekistan</option>
                    <option value="Vanuatu">Vanuatu</option>
                    <option value="Vatican City State">Vatican City State</option>
                    <option value="Venezuela">Venezuela</option>
                    <option value="Vietnam">Vietnam</option>
                    <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                    <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                    <option value="Wake Island">Wake Island</option>
                    <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                    <option value="Yemen">Yemen</option>
                    <option value="Zaire">Zaire</option>
                    <option value="Zambia">Zambia</option>
                    <option value="Zimbabwe">Zimbabwe</option>
                  </select>
                </div>
                <div class="form-group">
                  <input type="email" class="form-control" id="intemail" placeholder="Recevier's Email" required>
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" id="intbank" placeholder="Name of Bank" required>
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" id="intname" placeholder="Name of Account Holder" required>
                </div>
                <div class="form-group">
                  <input type="number" class="form-control" id="intnumber" placeholder="Account Number" required>
                </div>
                <div class="form-group">
                  <input type="number" class="form-control" id="intamount" placeholder="Amount ($)" required>
                </div>
                <div id="error2" class="py-2"></div>
                <button type="button" class="btn btn-primary" id="inttransferbtn"><i class="fab fa-wpressr"></i> Confirm</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              </form>
            </div>
            <div id="loadez3" class="overlay dark" style="display: none;"><i class="fas fa-4x fa-spinner fa-spin"></i></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Verification-->
  <div class="modal fade" id="verify_account" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="userVerificationForm" enctype="multipart/form-data">
          <div class="modal-body">
          
            <input type="hidden" name="verify">
            <input type="hidden" name="acctid" value="<?php echo $acctid; ?>">

            <div class="card-text text-sm">Select Country of Residence <span class="text-danger">*</span></div>
            <p class="card-text text-muted" style="font-size: 14px;">Please ensure your country of residence matches your valid ID. Your priviledges could change based on the selection.</p>
            <div class="form-group">
              <select name="country" class="form-control select2" style="width: 100%;" reqiuired>
                <option value="<?php echo $data['country'] ?>" selected><?php echo $data['country'] ?></option>
                <option value="Afganistan">Afghanistan</option>
                <option value="Albania">Albania</option>
                <option value="Algeria">Algeria</option>
                <option value="American Samoa">American Samoa</option>
                <option value="Andorra">Andorra</option>
                <option value="Angola">Angola</option>
                <option value="Anguilla">Anguilla</option>
                <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                <option value="Argentina">Argentina</option>
                <option value="Armenia">Armenia</option>
                <option value="Aruba">Aruba</option>
                <option value="Australia">Australia</option>
                <option value="Austria">Austria</option>
                <option value="Azerbaijan">Azerbaijan</option>
                <option value="Bahamas">Bahamas</option>
                <option value="Bahrain">Bahrain</option>
                <option value="Bangladesh">Bangladesh</option>
                <option value="Barbados">Barbados</option>
                <option value="Belarus">Belarus</option>
                <option value="Belgium">Belgium</option>
                <option value="Belize">Belize</option>
                <option value="Benin">Benin</option>
                <option value="Bermuda">Bermuda</option>
                <option value="Bhutan">Bhutan</option>
                <option value="Bolivia">Bolivia</option>
                <option value="Bonaire">Bonaire</option>
                <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                <option value="Botswana">Botswana</option>
                <option value="Brazil">Brazil</option>
                <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                <option value="Brunei">Brunei</option>
                <option value="Bulgaria">Bulgaria</option>
                <option value="Burkina Faso">Burkina Faso</option>
                <option value="Burundi">Burundi</option>
                <option value="Cambodia">Cambodia</option>
                <option value="Cameroon">Cameroon</option>
                <option value="Canada">Canada</option>
                <option value="Canary Islands">Canary Islands</option>
                <option value="Cape Verde">Cape Verde</option>
                <option value="Cayman Islands">Cayman Islands</option>
                <option value="Central African Republic">Central African Republic</option>
                <option value="Chad">Chad</option>
                <option value="Channel Islands">Channel Islands</option>
                <option value="Chile">Chile</option>
                <option value="China">China</option>
                <option value="Christmas Island">Christmas Island</option>
                <option value="Cocos Island">Cocos Island</option>
                <option value="Colombia">Colombia</option>
                <option value="Comoros">Comoros</option>
                <option value="Congo">Congo</option>
                <option value="Cook Islands">Cook Islands</option>
                <option value="Costa Rica">Costa Rica</option>
                <option value="Cote DIvoire">Cote DIvoire</option>
                <option value="Croatia">Croatia</option>
                <option value="Cuba">Cuba</option>
                <option value="Curaco">Curacao</option>
                <option value="Cyprus">Cyprus</option>
                <option value="Czech Republic">Czech Republic</option>
                <option value="Denmark">Denmark</option>
                <option value="Djibouti">Djibouti</option>
                <option value="Dominica">Dominica</option>
                <option value="Dominican Republic">Dominican Republic</option>
                <option value="East Timor">East Timor</option>
                <option value="Ecuador">Ecuador</option>
                <option value="Egypt">Egypt</option>
                <option value="El Salvador">El Salvador</option>
                <option value="Equatorial Guinea">Equatorial Guinea</option>
                <option value="Eritrea">Eritrea</option>
                <option value="Estonia">Estonia</option>
                <option value="Ethiopia">Ethiopia</option>
                <option value="Falkland Islands">Falkland Islands</option>
                <option value="Faroe Islands">Faroe Islands</option>
                <option value="Fiji">Fiji</option>
                <option value="Finland">Finland</option>
                <option value="France">France</option>
                <option value="French Guiana">French Guiana</option>
                <option value="French Polynesia">French Polynesia</option>
                <option value="French Southern Ter">French Southern Ter</option>
                <option value="Gabon">Gabon</option>
                <option value="Gambia">Gambia</option>
                <option value="Georgia">Georgia</option>
                <option value="Germany">Germany</option>
                <option value="Ghana">Ghana</option>
                <option value="Gibraltar">Gibraltar</option>
                <option value="Great Britain">Great Britain</option>
                <option value="Greece">Greece</option>
                <option value="Greenland">Greenland</option>
                <option value="Grenada">Grenada</option>
                <option value="Guadeloupe">Guadeloupe</option>
                <option value="Guam">Guam</option>
                <option value="Guatemala">Guatemala</option>
                <option value="Guinea">Guinea</option>
                <option value="Guyana">Guyana</option>
                <option value="Haiti">Haiti</option>
                <option value="Hawaii">Hawaii</option>
                <option value="Honduras">Honduras</option>
                <option value="Hong Kong">Hong Kong</option>
                <option value="Hungary">Hungary</option>
                <option value="Iceland">Iceland</option>
                <option value="Indonesia">Indonesia</option>
                <option value="India">India</option>
                <option value="Iran">Iran</option>
                <option value="Iraq">Iraq</option>
                <option value="Ireland">Ireland</option>
                <option value="Isle of Man">Isle of Man</option>
                <option value="Israel">Israel</option>
                <option value="Italy">Italy</option>
                <option value="Jamaica">Jamaica</option>
                <option value="Japan">Japan</option>
                <option value="Jordan">Jordan</option>
                <option value="Kazakhstan">Kazakhstan</option>
                <option value="Kenya">Kenya</option>
                <option value="Kiribati">Kiribati</option>
                <option value="Korea North">Korea North</option>
                <option value="Korea Sout">Korea South</option>
                <option value="Kuwait">Kuwait</option>
                <option value="Kyrgyzstan">Kyrgyzstan</option>
                <option value="Laos">Laos</option>
                <option value="Latvia">Latvia</option>
                <option value="Lebanon">Lebanon</option>
                <option value="Lesotho">Lesotho</option>
                <option value="Liberia">Liberia</option>
                <option value="Libya">Libya</option>
                <option value="Liechtenstein">Liechtenstein</option>
                <option value="Lithuania">Lithuania</option>
                <option value="Luxembourg">Luxembourg</option>
                <option value="Macau">Macau</option>
                <option value="Macedonia">Macedonia</option>
                <option value="Madagascar">Madagascar</option>
                <option value="Malaysia">Malaysia</option>
                <option value="Malawi">Malawi</option>
                <option value="Maldives">Maldives</option>
                <option value="Mali">Mali</option>
                <option value="Malta">Malta</option>
                <option value="Marshall Islands">Marshall Islands</option>
                <option value="Martinique">Martinique</option>
                <option value="Mauritania">Mauritania</option>
                <option value="Mauritius">Mauritius</option>
                <option value="Mayotte">Mayotte</option>
                <option value="Mexico">Mexico</option>
                <option value="Midway Islands">Midway Islands</option>
                <option value="Moldova">Moldova</option>
                <option value="Monaco">Monaco</option>
                <option value="Mongolia">Mongolia</option>
                <option value="Montserrat">Montserrat</option>
                <option value="Morocco">Morocco</option>
                <option value="Mozambique">Mozambique</option>
                <option value="Myanmar">Myanmar</option>
                <option value="Nambia">Nambia</option>
                <option value="Nauru">Nauru</option>
                <option value="Nepal">Nepal</option>
                <option value="Netherland Antilles">Netherland Antilles</option>
                <option value="Netherlands">Netherlands (Holland, Europe)</option>
                <option value="Nevis">Nevis</option>
                <option value="New Caledonia">New Caledonia</option>
                <option value="New Zealand">New Zealand</option>
                <option value="Nicaragua">Nicaragua</option>
                <option value="Niger">Niger</option>
                <option value="Nigeria">Nigeria</option>
                <option value="Niue">Niue</option>
                <option value="Norfolk Island">Norfolk Island</option>
                <option value="Norway">Norway</option>
                <option value="Oman">Oman</option>
                <option value="Pakistan">Pakistan</option>
                <option value="Palau Island">Palau Island</option>
                <option value="Palestine">Palestine</option>
                <option value="Panama">Panama</option>
                <option value="Papua New Guinea">Papua New Guinea</option>
                <option value="Paraguay">Paraguay</option>
                <option value="Peru">Peru</option>
                <option value="Phillipines">Philippines</option>
                <option value="Pitcairn Island">Pitcairn Island</option>
                <option value="Poland">Poland</option>
                <option value="Portugal">Portugal</option>
                <option value="Puerto Rico">Puerto Rico</option>
                <option value="Qatar">Qatar</option>
                <option value="Republic of Montenegro">Republic of Montenegro</option>
                <option value="Republic of Serbia">Republic of Serbia</option>
                <option value="Reunion">Reunion</option>
                <option value="Romania">Romania</option>
                <option value="Russia">Russia</option>
                <option value="Rwanda">Rwanda</option>
                <option value="St Barthelemy">St Barthelemy</option>
                <option value="St Eustatius">St Eustatius</option>
                <option value="St Helena">St Helena</option>
                <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                <option value="St Lucia">St Lucia</option>
                <option value="St Maarten">St Maarten</option>
                <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                <option value="Saipan">Saipan</option>
                <option value="Samoa">Samoa</option>
                <option value="Samoa American">Samoa American</option>
                <option value="San Marino">San Marino</option>
                <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                <option value="Saudi Arabia">Saudi Arabia</option>
                <option value="Senegal">Senegal</option>
                <option value="Seychelles">Seychelles</option>
                <option value="Sierra Leone">Sierra Leone</option>
                <option value="Singapore">Singapore</option>
                <option value="Slovakia">Slovakia</option>
                <option value="Slovenia">Slovenia</option>
                <option value="Solomon Islands">Solomon Islands</option>
                <option value="Somalia">Somalia</option>
                <option value="South Africa">South Africa</option>
                <option value="Spain">Spain</option>
                <option value="Sri Lanka">Sri Lanka</option>
                <option value="Sudan">Sudan</option>
                <option value="Suriname">Suriname</option>
                <option value="Swaziland">Swaziland</option>
                <option value="Sweden">Sweden</option>
                <option value="Switzerland">Switzerland</option>
                <option value="Syria">Syria</option>
                <option value="Tahiti">Tahiti</option>
                <option value="Taiwan">Taiwan</option>
                <option value="Tajikistan">Tajikistan</option>
                <option value="Tanzania">Tanzania</option>
                <option value="Thailand">Thailand</option>
                <option value="Togo">Togo</option>
                <option value="Tokelau">Tokelau</option>
                <option value="Tonga">Tonga</option>
                <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                <option value="Tunisia">Tunisia</option>
                <option value="Turkey">Turkey</option>
                <option value="Turkmenistan">Turkmenistan</option>
                <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                <option value="Tuvalu">Tuvalu</option>
                <option value="Uganda">Uganda</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="Ukraine">Ukraine</option>
                <option value="United Arab Erimates">United Arab Emirates</option>
                <option value="United States of America">United States of America</option>
                <option value="Uraguay">Uruguay</option>
                <option value="Uzbekistan">Uzbekistan</option>
                <option value="Vanuatu">Vanuatu</option>
                <option value="Vatican City State">Vatican City State</option>
                <option value="Venezuela">Venezuela</option>
                <option value="Vietnam">Vietnam</option>
                <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                <option value="Wake Island">Wake Island</option>
                <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                <option value="Yemen">Yemen</option>
                <option value="Zaire">Zaire</option>
                <option value="Zambia">Zambia</option>
                <option value="Zimbabwe">Zimbabwe</option>
              </select>
            </div>
            
            <div class="text-muted card-text text-sm">Identity Information <span class="text-danger">*</span></div>
            <div class="form-group">
              <label class="mb-0" style="font-size: 14px;font-weight:normal;">Nationality</label>
              <select name="nationality" class="form-control select2" style="width: 100%;" reqiuired>
                <option value="<?php echo $data['country'] ?>" selected><?php echo $data['country'] ?></option>
                <option value="Afganistan">Afghanistan</option>
                <option value="Albania">Albania</option>
                <option value="Algeria">Algeria</option>
                <option value="American Samoa">American Samoa</option>
                <option value="Andorra">Andorra</option>
                <option value="Angola">Angola</option>
                <option value="Anguilla">Anguilla</option>
                <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                <option value="Argentina">Argentina</option>
                <option value="Armenia">Armenia</option>
                <option value="Aruba">Aruba</option>
                <option value="Australia">Australia</option>
                <option value="Austria">Austria</option>
                <option value="Azerbaijan">Azerbaijan</option>
                <option value="Bahamas">Bahamas</option>
                <option value="Bahrain">Bahrain</option>
                <option value="Bangladesh">Bangladesh</option>
                <option value="Barbados">Barbados</option>
                <option value="Belarus">Belarus</option>
                <option value="Belgium">Belgium</option>
                <option value="Belize">Belize</option>
                <option value="Benin">Benin</option>
                <option value="Bermuda">Bermuda</option>
                <option value="Bhutan">Bhutan</option>
                <option value="Bolivia">Bolivia</option>
                <option value="Bonaire">Bonaire</option>
                <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                <option value="Botswana">Botswana</option>
                <option value="Brazil">Brazil</option>
                <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                <option value="Brunei">Brunei</option>
                <option value="Bulgaria">Bulgaria</option>
                <option value="Burkina Faso">Burkina Faso</option>
                <option value="Burundi">Burundi</option>
                <option value="Cambodia">Cambodia</option>
                <option value="Cameroon">Cameroon</option>
                <option value="Canada">Canada</option>
                <option value="Canary Islands">Canary Islands</option>
                <option value="Cape Verde">Cape Verde</option>
                <option value="Cayman Islands">Cayman Islands</option>
                <option value="Central African Republic">Central African Republic</option>
                <option value="Chad">Chad</option>
                <option value="Channel Islands">Channel Islands</option>
                <option value="Chile">Chile</option>
                <option value="China">China</option>
                <option value="Christmas Island">Christmas Island</option>
                <option value="Cocos Island">Cocos Island</option>
                <option value="Colombia">Colombia</option>
                <option value="Comoros">Comoros</option>
                <option value="Congo">Congo</option>
                <option value="Cook Islands">Cook Islands</option>
                <option value="Costa Rica">Costa Rica</option>
                <option value="Cote DIvoire">Cote DIvoire</option>
                <option value="Croatia">Croatia</option>
                <option value="Cuba">Cuba</option>
                <option value="Curaco">Curacao</option>
                <option value="Cyprus">Cyprus</option>
                <option value="Czech Republic">Czech Republic</option>
                <option value="Denmark">Denmark</option>
                <option value="Djibouti">Djibouti</option>
                <option value="Dominica">Dominica</option>
                <option value="Dominican Republic">Dominican Republic</option>
                <option value="East Timor">East Timor</option>
                <option value="Ecuador">Ecuador</option>
                <option value="Egypt">Egypt</option>
                <option value="El Salvador">El Salvador</option>
                <option value="Equatorial Guinea">Equatorial Guinea</option>
                <option value="Eritrea">Eritrea</option>
                <option value="Estonia">Estonia</option>
                <option value="Ethiopia">Ethiopia</option>
                <option value="Falkland Islands">Falkland Islands</option>
                <option value="Faroe Islands">Faroe Islands</option>
                <option value="Fiji">Fiji</option>
                <option value="Finland">Finland</option>
                <option value="France">France</option>
                <option value="French Guiana">French Guiana</option>
                <option value="French Polynesia">French Polynesia</option>
                <option value="French Southern Ter">French Southern Ter</option>
                <option value="Gabon">Gabon</option>
                <option value="Gambia">Gambia</option>
                <option value="Georgia">Georgia</option>
                <option value="Germany">Germany</option>
                <option value="Ghana">Ghana</option>
                <option value="Gibraltar">Gibraltar</option>
                <option value="Great Britain">Great Britain</option>
                <option value="Greece">Greece</option>
                <option value="Greenland">Greenland</option>
                <option value="Grenada">Grenada</option>
                <option value="Guadeloupe">Guadeloupe</option>
                <option value="Guam">Guam</option>
                <option value="Guatemala">Guatemala</option>
                <option value="Guinea">Guinea</option>
                <option value="Guyana">Guyana</option>
                <option value="Haiti">Haiti</option>
                <option value="Hawaii">Hawaii</option>
                <option value="Honduras">Honduras</option>
                <option value="Hong Kong">Hong Kong</option>
                <option value="Hungary">Hungary</option>
                <option value="Iceland">Iceland</option>
                <option value="Indonesia">Indonesia</option>
                <option value="India">India</option>
                <option value="Iran">Iran</option>
                <option value="Iraq">Iraq</option>
                <option value="Ireland">Ireland</option>
                <option value="Isle of Man">Isle of Man</option>
                <option value="Israel">Israel</option>
                <option value="Italy">Italy</option>
                <option value="Jamaica">Jamaica</option>
                <option value="Japan">Japan</option>
                <option value="Jordan">Jordan</option>
                <option value="Kazakhstan">Kazakhstan</option>
                <option value="Kenya">Kenya</option>
                <option value="Kiribati">Kiribati</option>
                <option value="Korea North">Korea North</option>
                <option value="Korea Sout">Korea South</option>
                <option value="Kuwait">Kuwait</option>
                <option value="Kyrgyzstan">Kyrgyzstan</option>
                <option value="Laos">Laos</option>
                <option value="Latvia">Latvia</option>
                <option value="Lebanon">Lebanon</option>
                <option value="Lesotho">Lesotho</option>
                <option value="Liberia">Liberia</option>
                <option value="Libya">Libya</option>
                <option value="Liechtenstein">Liechtenstein</option>
                <option value="Lithuania">Lithuania</option>
                <option value="Luxembourg">Luxembourg</option>
                <option value="Macau">Macau</option>
                <option value="Macedonia">Macedonia</option>
                <option value="Madagascar">Madagascar</option>
                <option value="Malaysia">Malaysia</option>
                <option value="Malawi">Malawi</option>
                <option value="Maldives">Maldives</option>
                <option value="Mali">Mali</option>
                <option value="Malta">Malta</option>
                <option value="Marshall Islands">Marshall Islands</option>
                <option value="Martinique">Martinique</option>
                <option value="Mauritania">Mauritania</option>
                <option value="Mauritius">Mauritius</option>
                <option value="Mayotte">Mayotte</option>
                <option value="Mexico">Mexico</option>
                <option value="Midway Islands">Midway Islands</option>
                <option value="Moldova">Moldova</option>
                <option value="Monaco">Monaco</option>
                <option value="Mongolia">Mongolia</option>
                <option value="Montserrat">Montserrat</option>
                <option value="Morocco">Morocco</option>
                <option value="Mozambique">Mozambique</option>
                <option value="Myanmar">Myanmar</option>
                <option value="Nambia">Nambia</option>
                <option value="Nauru">Nauru</option>
                <option value="Nepal">Nepal</option>
                <option value="Netherland Antilles">Netherland Antilles</option>
                <option value="Netherlands">Netherlands (Holland, Europe)</option>
                <option value="Nevis">Nevis</option>
                <option value="New Caledonia">New Caledonia</option>
                <option value="New Zealand">New Zealand</option>
                <option value="Nicaragua">Nicaragua</option>
                <option value="Niger">Niger</option>
                <option value="Nigeria">Nigeria</option>
                <option value="Niue">Niue</option>
                <option value="Norfolk Island">Norfolk Island</option>
                <option value="Norway">Norway</option>
                <option value="Oman">Oman</option>
                <option value="Pakistan">Pakistan</option>
                <option value="Palau Island">Palau Island</option>
                <option value="Palestine">Palestine</option>
                <option value="Panama">Panama</option>
                <option value="Papua New Guinea">Papua New Guinea</option>
                <option value="Paraguay">Paraguay</option>
                <option value="Peru">Peru</option>
                <option value="Phillipines">Philippines</option>
                <option value="Pitcairn Island">Pitcairn Island</option>
                <option value="Poland">Poland</option>
                <option value="Portugal">Portugal</option>
                <option value="Puerto Rico">Puerto Rico</option>
                <option value="Qatar">Qatar</option>
                <option value="Republic of Montenegro">Republic of Montenegro</option>
                <option value="Republic of Serbia">Republic of Serbia</option>
                <option value="Reunion">Reunion</option>
                <option value="Romania">Romania</option>
                <option value="Russia">Russia</option>
                <option value="Rwanda">Rwanda</option>
                <option value="St Barthelemy">St Barthelemy</option>
                <option value="St Eustatius">St Eustatius</option>
                <option value="St Helena">St Helena</option>
                <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                <option value="St Lucia">St Lucia</option>
                <option value="St Maarten">St Maarten</option>
                <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                <option value="Saipan">Saipan</option>
                <option value="Samoa">Samoa</option>
                <option value="Samoa American">Samoa American</option>
                <option value="San Marino">San Marino</option>
                <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                <option value="Saudi Arabia">Saudi Arabia</option>
                <option value="Senegal">Senegal</option>
                <option value="Seychelles">Seychelles</option>
                <option value="Sierra Leone">Sierra Leone</option>
                <option value="Singapore">Singapore</option>
                <option value="Slovakia">Slovakia</option>
                <option value="Slovenia">Slovenia</option>
                <option value="Solomon Islands">Solomon Islands</option>
                <option value="Somalia">Somalia</option>
                <option value="South Africa">South Africa</option>
                <option value="Spain">Spain</option>
                <option value="Sri Lanka">Sri Lanka</option>
                <option value="Sudan">Sudan</option>
                <option value="Suriname">Suriname</option>
                <option value="Swaziland">Swaziland</option>
                <option value="Sweden">Sweden</option>
                <option value="Switzerland">Switzerland</option>
                <option value="Syria">Syria</option>
                <option value="Tahiti">Tahiti</option>
                <option value="Taiwan">Taiwan</option>
                <option value="Tajikistan">Tajikistan</option>
                <option value="Tanzania">Tanzania</option>
                <option value="Thailand">Thailand</option>
                <option value="Togo">Togo</option>
                <option value="Tokelau">Tokelau</option>
                <option value="Tonga">Tonga</option>
                <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                <option value="Tunisia">Tunisia</option>
                <option value="Turkey">Turkey</option>
                <option value="Turkmenistan">Turkmenistan</option>
                <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                <option value="Tuvalu">Tuvalu</option>
                <option value="Uganda">Uganda</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="Ukraine">Ukraine</option>
                <option value="United Arab Erimates">United Arab Emirates</option>
                <option value="United States of America">United States of America</option>
                <option value="Uraguay">Uruguay</option>
                <option value="Uzbekistan">Uzbekistan</option>
                <option value="Vanuatu">Vanuatu</option>
                <option value="Vatican City State">Vatican City State</option>
                <option value="Venezuela">Venezuela</option>
                <option value="Vietnam">Vietnam</option>
                <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                <option value="Wake Island">Wake Island</option>
                <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                <option value="Yemen">Yemen</option>
                <option value="Zaire">Zaire</option>
                <option value="Zambia">Zambia</option>
                <option value="Zimbabwe">Zimbabwe</option>
              </select>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-6">
                  <label class="mb-0" style="font-size: 14px;font-weight:normal;">First Name</label>
                  <input type="text" name="fname" class="form-control text-capitalize" value="<?php echo $data['fname'] ?>" reqiuired>
                </div>
                <div class="col-6">
                  <label class="mb-0" style="font-size: 14px;font-weight:normal;">Last Name</label>
                  <input type="text" name="lname" class="form-control text-capitalize" value="<?php echo $data['lname'] ?>" reqiuired>
                </div>
              </div>
            </div>

            <div class="form-group">

              <div class="row">
                <div class="col-6">
                  <label class="mb-0" style="font-size: 14px;font-weight:normal;">Middle Name</label>
                  <input type="text" name="mname" class="form-control text-capitalize" value="<?php echo $data['mname'] ?>" reqiuired>
                </div>
                <div class="col-6">
                  <label class="mb-0" style="font-size: 14px;font-weight:normal;">Date of Birth</label>
                  <input type="date" name="dob" class="form-control" value="<?php echo $data['dob'] ?>" required/>
                </div>
              </div>
            </div>

            <div class="text-muted card-text text-sm">Additional Information <span class="text-danger">*</span></div>

            <div class="form-group">

              <div class="row">
                <div class="col-12">
                  <label class="mb-0" style="font-size: 14px;font-weight:normal;">Street Address</label>
                  <input type="text" name="address" class="form-control text-capitalize" value="<?php echo $data['address'] ?>" reqiuired>
                </div>
              </div>
            </div>

            <div class="form-group">

              <div class="row">
                <div class="col-6">
                  <label class="mb-0" style="font-size: 14px;font-weight:normal;">Postal Code</label>
                  <input type="text" name="pcode" class="form-control text-capitalize" value="<?php echo $data['pcode'] ?>" reqiuired>
                </div>
                <div class="col-6">
                  <label class="mb-0" style="font-size: 14px;font-weight:normal;">City</label>
                  <input type="text" name="city" class="form-control text-capitalize" value="<?php echo $data['city'] ?>" reqiuired>
                </div>
              </div>
            </div>

            <div class="h6 pt-4">Upload Image of Passport</div>
            <p class="card-text text-muted" style="font-size: 14px;">Use a valid government issued ID</p>
                                            
            <div class="form-group mb-4">
              <label class="mb-0" style="font-size: 14px;font-weight:normal;">Country of Issue <span class="text-danger">*</span></label>
              <select name="issuer" class="form-control select2" style="width: 100%;" required>
                <option value="<?php echo $data['country'] ?>" selected><?php echo $data['country'] ?></option>
                <option value="Afganistan">Afghanistan</option>
                <option value="Afganistan">Afghanistan</option>
                <option value="Albania">Albania</option>
                <option value="Algeria">Algeria</option>
                <option value="American Samoa">American Samoa</option>
                <option value="Andorra">Andorra</option>
                <option value="Angola">Angola</option>
                <option value="Anguilla">Anguilla</option>
                <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                <option value="Argentina">Argentina</option>
                <option value="Armenia">Armenia</option>
                <option value="Aruba">Aruba</option>
                <option value="Australia">Australia</option>
                <option value="Austria">Austria</option>
                <option value="Azerbaijan">Azerbaijan</option>
                <option value="Bahamas">Bahamas</option>
                <option value="Bahrain">Bahrain</option>
                <option value="Bangladesh">Bangladesh</option>
                <option value="Barbados">Barbados</option>
                <option value="Belarus">Belarus</option>
                <option value="Belgium">Belgium</option>
                <option value="Belize">Belize</option>
                <option value="Benin">Benin</option>
                <option value="Bermuda">Bermuda</option>
                <option value="Bhutan">Bhutan</option>
                <option value="Bolivia">Bolivia</option>
                <option value="Bonaire">Bonaire</option>
                <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                <option value="Botswana">Botswana</option>
                <option value="Brazil">Brazil</option>
                <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                <option value="Brunei">Brunei</option>
                <option value="Bulgaria">Bulgaria</option>
                <option value="Burkina Faso">Burkina Faso</option>
                <option value="Burundi">Burundi</option>
                <option value="Cambodia">Cambodia</option>
                <option value="Cameroon">Cameroon</option>
                <option value="Canada">Canada</option>
                <option value="Canary Islands">Canary Islands</option>
                <option value="Cape Verde">Cape Verde</option>
                <option value="Cayman Islands">Cayman Islands</option>
                <option value="Central African Republic">Central African Republic</option>
                <option value="Chad">Chad</option>
                <option value="Channel Islands">Channel Islands</option>
                <option value="Chile">Chile</option>
                <option value="China">China</option>
                <option value="Christmas Island">Christmas Island</option>
                <option value="Cocos Island">Cocos Island</option>
                <option value="Colombia">Colombia</option>
                <option value="Comoros">Comoros</option>
                <option value="Congo">Congo</option>
                <option value="Cook Islands">Cook Islands</option>
                <option value="Costa Rica">Costa Rica</option>
                <option value="Cote DIvoire">Cote DIvoire</option>
                <option value="Croatia">Croatia</option>
                <option value="Cuba">Cuba</option>
                <option value="Curaco">Curacao</option>
                <option value="Cyprus">Cyprus</option>
                <option value="Czech Republic">Czech Republic</option>
                <option value="Denmark">Denmark</option>
                <option value="Djibouti">Djibouti</option>
                <option value="Dominica">Dominica</option>
                <option value="Dominican Republic">Dominican Republic</option>
                <option value="East Timor">East Timor</option>
                <option value="Ecuador">Ecuador</option>
                <option value="Egypt">Egypt</option>
                <option value="El Salvador">El Salvador</option>
                <option value="Equatorial Guinea">Equatorial Guinea</option>
                <option value="Eritrea">Eritrea</option>
                <option value="Estonia">Estonia</option>
                <option value="Ethiopia">Ethiopia</option>
                <option value="Falkland Islands">Falkland Islands</option>
                <option value="Faroe Islands">Faroe Islands</option>
                <option value="Fiji">Fiji</option>
                <option value="Finland">Finland</option>
                <option value="France">France</option>
                <option value="French Guiana">French Guiana</option>
                <option value="French Polynesia">French Polynesia</option>
                <option value="French Southern Ter">French Southern Ter</option>
                <option value="Gabon">Gabon</option>
                <option value="Gambia">Gambia</option>
                <option value="Georgia">Georgia</option>
                <option value="Germany">Germany</option>
                <option value="Ghana">Ghana</option>
                <option value="Gibraltar">Gibraltar</option>
                <option value="Great Britain">Great Britain</option>
                <option value="Greece">Greece</option>
                <option value="Greenland">Greenland</option>
                <option value="Grenada">Grenada</option>
                <option value="Guadeloupe">Guadeloupe</option>
                <option value="Guam">Guam</option>
                <option value="Guatemala">Guatemala</option>
                <option value="Guinea">Guinea</option>
                <option value="Guyana">Guyana</option>
                <option value="Haiti">Haiti</option>
                <option value="Hawaii">Hawaii</option>
                <option value="Honduras">Honduras</option>
                <option value="Hong Kong">Hong Kong</option>
                <option value="Hungary">Hungary</option>
                <option value="Iceland">Iceland</option>
                <option value="Indonesia">Indonesia</option>
                <option value="India">India</option>
                <option value="Iran">Iran</option>
                <option value="Iraq">Iraq</option>
                <option value="Ireland">Ireland</option>
                <option value="Isle of Man">Isle of Man</option>
                <option value="Israel">Israel</option>
                <option value="Italy">Italy</option>
                <option value="Jamaica">Jamaica</option>
                <option value="Japan">Japan</option>
                <option value="Jordan">Jordan</option>
                <option value="Kazakhstan">Kazakhstan</option>
                <option value="Kenya">Kenya</option>
                <option value="Kiribati">Kiribati</option>
                <option value="Korea North">Korea North</option>
                <option value="Korea Sout">Korea South</option>
                <option value="Kuwait">Kuwait</option>
                <option value="Kyrgyzstan">Kyrgyzstan</option>
                <option value="Laos">Laos</option>
                <option value="Latvia">Latvia</option>
                <option value="Lebanon">Lebanon</option>
                <option value="Lesotho">Lesotho</option>
                <option value="Liberia">Liberia</option>
                <option value="Libya">Libya</option>
                <option value="Liechtenstein">Liechtenstein</option>
                <option value="Lithuania">Lithuania</option>
                <option value="Luxembourg">Luxembourg</option>
                <option value="Macau">Macau</option>
                <option value="Macedonia">Macedonia</option>
                <option value="Madagascar">Madagascar</option>
                <option value="Malaysia">Malaysia</option>
                <option value="Malawi">Malawi</option>
                <option value="Maldives">Maldives</option>
                <option value="Mali">Mali</option>
                <option value="Malta">Malta</option>
                <option value="Marshall Islands">Marshall Islands</option>
                <option value="Martinique">Martinique</option>
                <option value="Mauritania">Mauritania</option>
                <option value="Mauritius">Mauritius</option>
                <option value="Mayotte">Mayotte</option>
                <option value="Mexico">Mexico</option>
                <option value="Midway Islands">Midway Islands</option>
                <option value="Moldova">Moldova</option>
                <option value="Monaco">Monaco</option>
                <option value="Mongolia">Mongolia</option>
                <option value="Montserrat">Montserrat</option>
                <option value="Morocco">Morocco</option>
                <option value="Mozambique">Mozambique</option>
                <option value="Myanmar">Myanmar</option>
                <option value="Nambia">Nambia</option>
                <option value="Nauru">Nauru</option>
                <option value="Nepal">Nepal</option>
                <option value="Netherland Antilles">Netherland Antilles</option>
                <option value="Netherlands">Netherlands (Holland, Europe)</option>
                <option value="Nevis">Nevis</option>
                <option value="New Caledonia">New Caledonia</option>
                <option value="New Zealand">New Zealand</option>
                <option value="Nicaragua">Nicaragua</option>
                <option value="Niger">Niger</option>
                <option value="Nigeria">Nigeria</option>
                <option value="Niue">Niue</option>
                <option value="Norfolk Island">Norfolk Island</option>
                <option value="Norway">Norway</option>
                <option value="Oman">Oman</option>
                <option value="Pakistan">Pakistan</option>
                <option value="Palau Island">Palau Island</option>
                <option value="Palestine">Palestine</option>
                <option value="Panama">Panama</option>
                <option value="Papua New Guinea">Papua New Guinea</option>
                <option value="Paraguay">Paraguay</option>
                <option value="Peru">Peru</option>
                <option value="Phillipines">Philippines</option>
                <option value="Pitcairn Island">Pitcairn Island</option>
                <option value="Poland">Poland</option>
                <option value="Portugal">Portugal</option>
                <option value="Puerto Rico">Puerto Rico</option>
                <option value="Qatar">Qatar</option>
                <option value="Republic of Montenegro">Republic of Montenegro</option>
                <option value="Republic of Serbia">Republic of Serbia</option>
                <option value="Reunion">Reunion</option>
                <option value="Romania">Romania</option>
                <option value="Russia">Russia</option>
                <option value="Rwanda">Rwanda</option>
                <option value="St Barthelemy">St Barthelemy</option>
                <option value="St Eustatius">St Eustatius</option>
                <option value="St Helena">St Helena</option>
                <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                <option value="St Lucia">St Lucia</option>
                <option value="St Maarten">St Maarten</option>
                <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                <option value="Saipan">Saipan</option>
                <option value="Samoa">Samoa</option>
                <option value="Samoa American">Samoa American</option>
                <option value="San Marino">San Marino</option>
                <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                <option value="Saudi Arabia">Saudi Arabia</option>
                <option value="Senegal">Senegal</option>
                <option value="Seychelles">Seychelles</option>
                <option value="Sierra Leone">Sierra Leone</option>
                <option value="Singapore">Singapore</option>
                <option value="Slovakia">Slovakia</option>
                <option value="Slovenia">Slovenia</option>
                <option value="Solomon Islands">Solomon Islands</option>
                <option value="Somalia">Somalia</option>
                <option value="South Africa">South Africa</option>
                <option value="Spain">Spain</option>
                <option value="Sri Lanka">Sri Lanka</option>
                <option value="Sudan">Sudan</option>
                <option value="Suriname">Suriname</option>
                <option value="Swaziland">Swaziland</option>
                <option value="Sweden">Sweden</option>
                <option value="Switzerland">Switzerland</option>
                <option value="Syria">Syria</option>
                <option value="Tahiti">Tahiti</option>
                <option value="Taiwan">Taiwan</option>
                <option value="Tajikistan">Tajikistan</option>
                <option value="Tanzania">Tanzania</option>
                <option value="Thailand">Thailand</option>
                <option value="Togo">Togo</option>
                <option value="Tokelau">Tokelau</option>
                <option value="Tonga">Tonga</option>
                <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                <option value="Tunisia">Tunisia</option>
                <option value="Turkey">Turkey</option>
                <option value="Turkmenistan">Turkmenistan</option>
                <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                <option value="Tuvalu">Tuvalu</option>
                <option value="Uganda">Uganda</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="Ukraine">Ukraine</option>
                <option value="United Arab Erimates">United Arab Emirates</option>
                <option value="United States of America">United States of America</option>
                <option value="Uraguay">Uruguay</option>
                <option value="Uzbekistan">Uzbekistan</option>
                <option value="Vanuatu">Vanuatu</option>
                <option value="Vatican City State">Vatican City State</option>
                <option value="Venezuela">Venezuela</option>
                <option value="Vietnam">Vietnam</option>
                <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                <option value="Wake Island">Wake Island</option>
                <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                <option value="Yemen">Yemen</option>
                <option value="Zaire">Zaire</option>
                <option value="Zambia">Zambia</option>
                <option value="Zimbabwe">Zimbabwe</option>
              </select>
            </div>

            <div class="form-group mb-2">
              <label class="form-control-label text-warning" for="exampleInputFile1" style="font-size: 14px;font-weight:normal;">Upload ID <span class="text-danger">*</span></label>
              <input type="file" name="passport" id="passport" class="form-control-file" id="exampleInputFile1" reqiuired>
              
            </div>


            <div class="form-group">
              <div id="response2" class="card-text"></div>
            </div>

          
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" id="userVerificationBtn" class="btn btn-primary">Submit</button>
          </div>
        </form>
        <div id="loadez1" class="overlay dark" style="display: none;"><i class="fas fa-4x fa-spinner fa-spin"></i></div>
      </div>
    </div>
  </div>

  <!-- Modal Profile-->
  <div class="modal fade" id="update_profile" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="updateProfileForm">
          <div class="modal-body">
          
            <input type="hidden" name="update_profile">
            <input type="hidden" name="acctid" value="<?php echo $acctid; ?>">

            <div class="card-text text-sm">Select Country of Residence <span class="text-danger">*</span></div>
            <p class="card-text text-muted" style="font-size: 14px;">Please ensure your country of residence matches your valid ID. Your priviledges could change based on the selection.</p>
            <div class="form-group">
              <select name="country" class="form-control select2" style="width: 100%;" reqiuired>
                <option value="<?php echo $data['country']; ?>" selected><?php echo $data['country']; ?></option>
                <option value="Afganistan">Afghanistan</option>
                <option value="Albania">Albania</option>
                <option value="Algeria">Algeria</option>
                <option value="American Samoa">American Samoa</option>
                <option value="Andorra">Andorra</option>
                <option value="Angola">Angola</option>
                <option value="Anguilla">Anguilla</option>
                <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                <option value="Argentina">Argentina</option>
                <option value="Armenia">Armenia</option>
                <option value="Aruba">Aruba</option>
                <option value="Australia">Australia</option>
                <option value="Austria">Austria</option>
                <option value="Azerbaijan">Azerbaijan</option>
                <option value="Bahamas">Bahamas</option>
                <option value="Bahrain">Bahrain</option>
                <option value="Bangladesh">Bangladesh</option>
                <option value="Barbados">Barbados</option>
                <option value="Belarus">Belarus</option>
                <option value="Belgium">Belgium</option>
                <option value="Belize">Belize</option>
                <option value="Benin">Benin</option>
                <option value="Bermuda">Bermuda</option>
                <option value="Bhutan">Bhutan</option>
                <option value="Bolivia">Bolivia</option>
                <option value="Bonaire">Bonaire</option>
                <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                <option value="Botswana">Botswana</option>
                <option value="Brazil">Brazil</option>
                <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                <option value="Brunei">Brunei</option>
                <option value="Bulgaria">Bulgaria</option>
                <option value="Burkina Faso">Burkina Faso</option>
                <option value="Burundi">Burundi</option>
                <option value="Cambodia">Cambodia</option>
                <option value="Cameroon">Cameroon</option>
                <option value="Canada">Canada</option>
                <option value="Canary Islands">Canary Islands</option>
                <option value="Cape Verde">Cape Verde</option>
                <option value="Cayman Islands">Cayman Islands</option>
                <option value="Central African Republic">Central African Republic</option>
                <option value="Chad">Chad</option>
                <option value="Channel Islands">Channel Islands</option>
                <option value="Chile">Chile</option>
                <option value="China">China</option>
                <option value="Christmas Island">Christmas Island</option>
                <option value="Cocos Island">Cocos Island</option>
                <option value="Colombia">Colombia</option>
                <option value="Comoros">Comoros</option>
                <option value="Congo">Congo</option>
                <option value="Cook Islands">Cook Islands</option>
                <option value="Costa Rica">Costa Rica</option>
                <option value="Cote DIvoire">Cote DIvoire</option>
                <option value="Croatia">Croatia</option>
                <option value="Cuba">Cuba</option>
                <option value="Curaco">Curacao</option>
                <option value="Cyprus">Cyprus</option>
                <option value="Czech Republic">Czech Republic</option>
                <option value="Denmark">Denmark</option>
                <option value="Djibouti">Djibouti</option>
                <option value="Dominica">Dominica</option>
                <option value="Dominican Republic">Dominican Republic</option>
                <option value="East Timor">East Timor</option>
                <option value="Ecuador">Ecuador</option>
                <option value="Egypt">Egypt</option>
                <option value="El Salvador">El Salvador</option>
                <option value="Equatorial Guinea">Equatorial Guinea</option>
                <option value="Eritrea">Eritrea</option>
                <option value="Estonia">Estonia</option>
                <option value="Ethiopia">Ethiopia</option>
                <option value="Falkland Islands">Falkland Islands</option>
                <option value="Faroe Islands">Faroe Islands</option>
                <option value="Fiji">Fiji</option>
                <option value="Finland">Finland</option>
                <option value="France">France</option>
                <option value="French Guiana">French Guiana</option>
                <option value="French Polynesia">French Polynesia</option>
                <option value="French Southern Ter">French Southern Ter</option>
                <option value="Gabon">Gabon</option>
                <option value="Gambia">Gambia</option>
                <option value="Georgia">Georgia</option>
                <option value="Germany">Germany</option>
                <option value="Ghana">Ghana</option>
                <option value="Gibraltar">Gibraltar</option>
                <option value="Great Britain">Great Britain</option>
                <option value="Greece">Greece</option>
                <option value="Greenland">Greenland</option>
                <option value="Grenada">Grenada</option>
                <option value="Guadeloupe">Guadeloupe</option>
                <option value="Guam">Guam</option>
                <option value="Guatemala">Guatemala</option>
                <option value="Guinea">Guinea</option>
                <option value="Guyana">Guyana</option>
                <option value="Haiti">Haiti</option>
                <option value="Hawaii">Hawaii</option>
                <option value="Honduras">Honduras</option>
                <option value="Hong Kong">Hong Kong</option>
                <option value="Hungary">Hungary</option>
                <option value="Iceland">Iceland</option>
                <option value="Indonesia">Indonesia</option>
                <option value="India">India</option>
                <option value="Iran">Iran</option>
                <option value="Iraq">Iraq</option>
                <option value="Ireland">Ireland</option>
                <option value="Isle of Man">Isle of Man</option>
                <option value="Israel">Israel</option>
                <option value="Italy">Italy</option>
                <option value="Jamaica">Jamaica</option>
                <option value="Japan">Japan</option>
                <option value="Jordan">Jordan</option>
                <option value="Kazakhstan">Kazakhstan</option>
                <option value="Kenya">Kenya</option>
                <option value="Kiribati">Kiribati</option>
                <option value="Korea North">Korea North</option>
                <option value="Korea Sout">Korea South</option>
                <option value="Kuwait">Kuwait</option>
                <option value="Kyrgyzstan">Kyrgyzstan</option>
                <option value="Laos">Laos</option>
                <option value="Latvia">Latvia</option>
                <option value="Lebanon">Lebanon</option>
                <option value="Lesotho">Lesotho</option>
                <option value="Liberia">Liberia</option>
                <option value="Libya">Libya</option>
                <option value="Liechtenstein">Liechtenstein</option>
                <option value="Lithuania">Lithuania</option>
                <option value="Luxembourg">Luxembourg</option>
                <option value="Macau">Macau</option>
                <option value="Macedonia">Macedonia</option>
                <option value="Madagascar">Madagascar</option>
                <option value="Malaysia">Malaysia</option>
                <option value="Malawi">Malawi</option>
                <option value="Maldives">Maldives</option>
                <option value="Mali">Mali</option>
                <option value="Malta">Malta</option>
                <option value="Marshall Islands">Marshall Islands</option>
                <option value="Martinique">Martinique</option>
                <option value="Mauritania">Mauritania</option>
                <option value="Mauritius">Mauritius</option>
                <option value="Mayotte">Mayotte</option>
                <option value="Mexico">Mexico</option>
                <option value="Midway Islands">Midway Islands</option>
                <option value="Moldova">Moldova</option>
                <option value="Monaco">Monaco</option>
                <option value="Mongolia">Mongolia</option>
                <option value="Montserrat">Montserrat</option>
                <option value="Morocco">Morocco</option>
                <option value="Mozambique">Mozambique</option>
                <option value="Myanmar">Myanmar</option>
                <option value="Nambia">Nambia</option>
                <option value="Nauru">Nauru</option>
                <option value="Nepal">Nepal</option>
                <option value="Netherland Antilles">Netherland Antilles</option>
                <option value="Netherlands">Netherlands (Holland, Europe)</option>
                <option value="Nevis">Nevis</option>
                <option value="New Caledonia">New Caledonia</option>
                <option value="New Zealand">New Zealand</option>
                <option value="Nicaragua">Nicaragua</option>
                <option value="Niger">Niger</option>
                <option value="Nigeria">Nigeria</option>
                <option value="Niue">Niue</option>
                <option value="Norfolk Island">Norfolk Island</option>
                <option value="Norway">Norway</option>
                <option value="Oman">Oman</option>
                <option value="Pakistan">Pakistan</option>
                <option value="Palau Island">Palau Island</option>
                <option value="Palestine">Palestine</option>
                <option value="Panama">Panama</option>
                <option value="Papua New Guinea">Papua New Guinea</option>
                <option value="Paraguay">Paraguay</option>
                <option value="Peru">Peru</option>
                <option value="Phillipines">Philippines</option>
                <option value="Pitcairn Island">Pitcairn Island</option>
                <option value="Poland">Poland</option>
                <option value="Portugal">Portugal</option>
                <option value="Puerto Rico">Puerto Rico</option>
                <option value="Qatar">Qatar</option>
                <option value="Republic of Montenegro">Republic of Montenegro</option>
                <option value="Republic of Serbia">Republic of Serbia</option>
                <option value="Reunion">Reunion</option>
                <option value="Romania">Romania</option>
                <option value="Russia">Russia</option>
                <option value="Rwanda">Rwanda</option>
                <option value="St Barthelemy">St Barthelemy</option>
                <option value="St Eustatius">St Eustatius</option>
                <option value="St Helena">St Helena</option>
                <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                <option value="St Lucia">St Lucia</option>
                <option value="St Maarten">St Maarten</option>
                <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                <option value="Saipan">Saipan</option>
                <option value="Samoa">Samoa</option>
                <option value="Samoa American">Samoa American</option>
                <option value="San Marino">San Marino</option>
                <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                <option value="Saudi Arabia">Saudi Arabia</option>
                <option value="Senegal">Senegal</option>
                <option value="Seychelles">Seychelles</option>
                <option value="Sierra Leone">Sierra Leone</option>
                <option value="Singapore">Singapore</option>
                <option value="Slovakia">Slovakia</option>
                <option value="Slovenia">Slovenia</option>
                <option value="Solomon Islands">Solomon Islands</option>
                <option value="Somalia">Somalia</option>
                <option value="South Africa">South Africa</option>
                <option value="Spain">Spain</option>
                <option value="Sri Lanka">Sri Lanka</option>
                <option value="Sudan">Sudan</option>
                <option value="Suriname">Suriname</option>
                <option value="Swaziland">Swaziland</option>
                <option value="Sweden">Sweden</option>
                <option value="Switzerland">Switzerland</option>
                <option value="Syria">Syria</option>
                <option value="Tahiti">Tahiti</option>
                <option value="Taiwan">Taiwan</option>
                <option value="Tajikistan">Tajikistan</option>
                <option value="Tanzania">Tanzania</option>
                <option value="Thailand">Thailand</option>
                <option value="Togo">Togo</option>
                <option value="Tokelau">Tokelau</option>
                <option value="Tonga">Tonga</option>
                <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                <option value="Tunisia">Tunisia</option>
                <option value="Turkey">Turkey</option>
                <option value="Turkmenistan">Turkmenistan</option>
                <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                <option value="Tuvalu">Tuvalu</option>
                <option value="Uganda">Uganda</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="Ukraine">Ukraine</option>
                <option value="United Arab Erimates">United Arab Emirates</option>
                <option value="United States of America">United States of America</option>
                <option value="Uraguay">Uruguay</option>
                <option value="Uzbekistan">Uzbekistan</option>
                <option value="Vanuatu">Vanuatu</option>
                <option value="Vatican City State">Vatican City State</option>
                <option value="Venezuela">Venezuela</option>
                <option value="Vietnam">Vietnam</option>
                <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                <option value="Wake Island">Wake Island</option>
                <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                <option value="Yemen">Yemen</option>
                <option value="Zaire">Zaire</option>
                <option value="Zambia">Zambia</option>
                <option value="Zimbabwe">Zimbabwe</option>
              </select>
            </div>
            
            <div class="text-muted card-text text-sm">Identity Information <span class="text-danger">*</span></div>
            <div class="form-group">
              <div class="row">
                <div class="col-6">
                  <label class="mb-0" style="font-size: 14px;font-weight:normal;">First Name</label>
                  <input type="text" name="fname" class="form-control text-capitalize" value="<?php echo $data['fname'] ?>" reqiuired>
                </div>
                <div class="col-6">
                  <label class="mb-0" style="font-size: 14px;font-weight:normal;">Last Name</label>
                  <input type="text" name="lname" class="form-control text-capitalize" value="<?php echo $data['lname'] ?>" reqiuired>
                </div>
              </div>
            </div>

            <div class="form-group">

              <div class="row">
                <div class="col-6">
                  <label class="mb-0" style="font-size: 14px;font-weight:normal;">Middle Name</label>
                  <input type="text" name="mname" class="form-control text-capitalize" value="<?php echo $data['mname'] ?>" reqiuired>
                </div>
                <div class="col-6">
                  <label class="mb-0" style="font-size: 14px;font-weight:normal;">Date of Birth</label>
                  <input type="date" name="dob" class="form-control" value="<?php echo $data['dob'] ?>" required/>
                </div>
              </div>
            </div>

            <div class="text-muted card-text text-sm">Additional Information <span class="text-danger">*</span></div>

            <div class="form-group">

              <div class="row">
                <div class="col-12">
                  <label class="mb-0" style="font-size: 14px;font-weight:normal;">Street Address</label>
                  <input type="text" name="address" class="form-control text-capitalize" value="<?php echo $data['address']; ?>" reqiuired>
                </div>
              </div>
            </div>

            <div class="form-group">

              <div class="row">
                <div class="col-6">
                  <label class="mb-0" style="font-size: 14px;font-weight:normal;">Postal Code</label>
                  <input type="text" name="pcode" class="form-control text-capitalize" value="<?php echo $data['pcode']; ?>" reqiuired>
                </div>
                <div class="col-6">
                  <label class="mb-0" style="font-size: 14px;font-weight:normal;">City</label>
                  <input type="text" name="city" class="form-control text-capitalize" value="<?php echo $data['city'] ?>" reqiuired>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div id="response3" class="card-text"></div>
            </div>

          
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" id="updateProfileBtn" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="wrapper">

    <!-- preloader start -->
    <div class="preloader">
        <div class="loader">
            <div class="ytp-spinner">
                <div class="ytp-spinner-container">
                    <div class="ytp-spinner-rotator">
                        <div class="ytp-spinner-left">
                            <div class="ytp-spinner-circle"></div>
                        </div>
                        <div class="ytp-spinner-right">
                            <div class="ytp-spinner-circle"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- preloader end -->

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-light fixed-top">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
     
      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
          <?php
          $sql = $db_conn->query("SELECT * FROM transactions WHERE acctid = '$acctid' AND status = 'Pending' ORDER BY id DESC");
          $num_msgs = $sql->num_rows;
          if($num_msgs>0){
            echo '<a class="nav-link text-danger" data-toggle="dropdown" href="#">Pending
            <i class="far fa-bell"></i>
            <span class="badge badge-danger navbar-badge" style="font-size:12px;">'.$num_msgs.'</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">'.$num_msgs.' Pending Transactions</span>';
          while($row = $sql->fetch_assoc()) {
            echo '<div class="dropdown-divider"></div>';
            echo '<a href="#" class="transid dropdown-item" data-user="'.$row['acctid'].'" data-type="getcot" data-id="'.$row['transid'].'" data-sum="'.$row['tsum'].'">
              <i class="fas fa-envelope mr-2"></i> '.$row['mode'].'
              <span class="float-right text-muted text-sm">'.number_format($row['tsum'], 2).'</span>
            </a>';
          }
          echo '</div>';
          }
          ?>
        </li>
        
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar main-sidebar-custom sidebar-light-primary">
      
      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-5">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
            <li class="nav-item">
              <a href="app" class="nav-link">
                <i class="fas fa-house-user nav-icon"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="?transfer" class="nav-link">
                <i class="fas fa-exchange-alt nav-icon"></i>
                <p>Transfer</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="?history" class="nav-link">
                <i class="fas fa-history nav-icon"></i>
                <p>History</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="?profile" class="nav-link">
                <i class="fas fa-user-circle nav-icon"></i>
                <p>Profile</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-cog nav-icon"></i>
                <p>
                  Settings
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="?settings" class="pl-5 nav-link">
                    <i class="fas fa-tachometer-alt nav-icon"></i>
                    <p>Account Settings</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="?cards" class="pl-5 nav-link">
                    <i class="fab fa-cc-visa nav-icon"></i>
                    <p>Virtual Cards</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="?inbox" class="nav-link">
                <i class="fas fa-envelope nav-icon"></i>
                <p>Inbox</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="?support" class="nav-link">
                <i class="fas fa-comments nav-icon"></i>
                <p>Support</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="?loans" class="nav-link">
                <i class="fas fa-landmark nav-icon"></i>
                <p>Loans</p>
              </a>
            </li>
            <li class="nav-item mt-2">
              <a role="button" class="nav-link"href="#" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-power-off nav-icon"></i>
                <p>Logout</p>
              </a>
            </li>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper pt-5">
      <!-- Main content -->
      <section class="content">

          

        <?php
        if (isset($_GET['profile'])) {
          echo '
          <!-- profile -->
          <div id="profile" class="container-fluid" style="display:block;">
            <div class="title-box">
              <div class="row pt-4">
                <div class="col-12 col-sm-6 col-xl-6">
                  <div class="page-title">Profile</div>
                </div>
                <div class="col-12 col-sm-6 col-xl-6 ">
                  <div class="breadcrumb">Dashboard <span><i class="fas fa-angle-right"></i></span>Customer Profile</div>
                </div>
             </div>
            </div>

            <div class="profile-box">
              <div class="header">
                <div class="overlay"></div>
                <div class="edit-btn"><a type="button" data-toggle="modal" data-target="#update_profile"><i class="fas fa-pencil-alt"></i></a></div>
                
              </div>
              <div class="body">
                <div class="row">
                  <div class="col-xl-3 col-lg-3 pr-0">
                    <div class="left-panel">
                      <div class="profile-info-box mb-4">
                        <div class="profile-pic">
                          <img id="wizardPicturePreview" src="uploads/'.$data['photo'].'" class="profilepic__image w-100">
                          <div class="profilepic__content">
                            <input type="file" id="file" class="" data-user="'.$acctid.'">
                            <span class="profilepic__icon"><i class="fas fa-camera"></i></span>
                            <span class="profilepic__text">Update</span>
                          </div>
                        </div>
                        <div class="name">'.$data['fname'].' '.$data['lname'].'</div>
                        <div class="text-success text-uppercase">'.$data['status'].'</div>

                      </div>
                      <div class="widget">
                        <ul class="list">
                          <li><i class="fas fa-phone-alt"></i> '.$data['phoneno'].'</li>
                          <li><i class="fas fa-envelope"></i> '.$data['email'].'</li>
                        </ul>
                      </div>

                    </div>
                  </div>
                  <div class="col-xl-6 col-lg-6">
                    <div class="timeline-part">
                      <div class="widget video border-0">
                        <div class="head">Contact Information</div>
                        <div class="video-box">
                          <div class="row">

                            <div class="col-sm-6">
                              <div class="title">Country</div>
                              <p>'.$data['country'].'</p>
                            </div>
                          
                            <div class="col-sm-6">
                              <div class="title">State</div>
                              <p>'.$data['state'].'</p>
                            </div>
                          
                            <div class="col-sm-6">
                              <div class="title">City</div>
                              <p>'.$data['city'].'</p>
                            </div>
                          
                            <div class="col-sm-6">
                              <div class="title">Postal Code</div>
                              <p>'.$data['pcode'].'</p>
                            </div>
                          
                            <div class="col-sm-6">
                              <div class="title">Address</div>
                              <p>'.$data['address'].'</p>
                            </div>

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-3 col-lg-3 pl-0">
                    <div class="right-panel">
                      <div class="widget video">
                        <div class="head">Bio Data</div>
                        <div class="video-box">
                          <div class="row">

                            <div class="col-8">
                              <div class="title">Marital Status</div>
                              <p>'.$data['marital'].'</p>
                            </div>
                          </div>
                        </div>
                        <div class="video-box">
                          <div class="row">

                            <div class="col-8">
                              <div class="title">Gender</div>
                              <p>'.$data['gender'].'</p>
                            </div>
                          </div>
                        </div>
                        <div class="video-box">
                          <div class="row">
                            <div class="col-8">
                              <div class="title">Employment/Job</div>
                              <p>'.$data['employment'].'</p>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          ';
        }

        elseif (isset($_GET['history'])) {
          echo '
          <!-- history -->
          <div id="history" class="container-fluid" style="display:block;">
            <div class="title-box">
              <div class="row pt-4">
                <div class="col-12 col-sm-6 col-xl-6">
                  <div class="page-title">Deposit History</div>
                </div>
                <div class="col-12 col-sm-6 col-xl-6 ">
                  <div class="breadcrumb">Account <span><i class="fas fa-angle-right"></i></span>History</div>
                </div>
             </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <!-- /.card-header -->
                  <div class="card-body">
                    <table id="example2" class="table table-sm" style="font-size:14px;">
                      <thead>
                        <tr>
                          <th scope="col">REF. NO.</th>
                          <th scope="col">TYPE</th>
                          <th scope="col">AMOUNT</th>
                          <th scope="col">RECEIVER</th>
                          <th scope="col">DESCRIPTION</th>
                          <th scope="col">DATE/TIME</th>
                          <th scope="col">STATUS</th>
                        </tr>
                      </thead>
                      <tbody>';
                      $sql = $db_conn->query("SELECT * FROM transactions WHERE acctid = '$acctid' OR raccno = '$acctid' ORDER BY id DESC");
                      if($sql->num_rows > 0) {
                        while($row = $sql->fetch_assoc()) {
                        echo '<tr>';
                          echo '<td>'.$row['transid'].'</td>';
                          echo '<td>'.$row['type'].'</td>';
                          echo '<td>'.number_format($row['tsum'], 2).'</td>';
                          echo '<td>'.$row['raccno'].'</td>';
                          echo '<td>'.$row['raccname'].'</td>';
                          echo '<td>'.$row['regdate'].'</td>';
                          echo '<td>'.$row['status'].'</td>';
                        echo '</tr>';
                        }
                      }
                      echo '</tbody>
                    </table>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
            </div>
          </div>
          ';
        }

        /*
        elseif (isset($_GET['deposit'])) {
          echo '
          <!-- deposit -->
          <div id="deposit" class="container-fluid" style="display:block;">
            <div class="title-box">
              <div class="row pt-4">
                <div class="col-12 col-sm-6 col-xl-6">
                  <div class="page-title">Cash Deposit</div>
                </div>
                <div class="col-12 col-sm-6 col-xl-6 ">
                  <div class="breadcrumb">Deposit <span><i class="fas fa-angle-right"></i></span>Cash</div>
                </div>
             </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <div class="card">
                  
                  <!-- /.card-header -->
                  <div class="card-body">
                    <p class="card-text text-danger">Use the below Account Information to make deposit. kindly inform the customer billing department by opening a Support ticket after payment stating clearing your deposit details for approval</p>
                    <hr>
                    <form id="cashDeposit">
                      <div class="form-group">
                        <label for="exampleInputPassword1">Deposited Amount in Bank</label>
                        <input type="text" class="form-control" name="amount" placeholder="e.g. USD1000" required="" id="exampleInputPassword1">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword1">Comment(s)</label>
                       <textarea class="form-control" name="msg" placeholder="Description " type="text" required="" spellcheck="false"></textarea>

                      </div>
                      <input type="hidden" class="form-control" name="sender_name" value="Digital ">
                      <input type="hidden" class="form-control" name="sender_name" value="Digital ">
                      <div class="form-group">
                        <button type="submit" name="ticket" class="theme btn btn-primary mr-2 border-0">Submit</button>
                        <button class="theme btn btn-secondary text-white">Cancel</button>
                      </div>
                    </form>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
              <div class="col-sm-6">
                <div class="card">
                  
                  <!-- /.card-header -->
                  <div class="card-body">
                    <p class="card-text font-weight-bold">Cash Deposit Details</p>
                    <hr>
                    <p> Bank Name: <span class="float-right font-weight-bold">Bank of America</span></p>
                    <p> Bank Account No: <span class="float-right font-weight-bold">0292938929292</span></p>
                    <p> Account Name: <span class="float-right font-weight-bold">Bankio-Demo Limited</span></p>
                    <p> Swift Code: <span class="float-right font-weight-bold">WHSY82992H</span></p>
                    <p> IBAN/Routing: <span class="float-right font-weight-bold">10109292992929-9292</span></p>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
            </div>
          </div>
          ';
        }*/

        elseif (isset($_GET['transfer'])) {
          echo '
          <!-- transfer -->
          <div id="transfer" class="container-fluid px-sm-5" style="display:block;">
            <div class="container">
              <div class="title-box">
                <div class="row pt-4">
                  <div class="col-12 col-sm-6 col-xl-6">
                    <div class="page-title">Select Money Transfer Method</div>
                  </div>
                  <div class="col-12 col-sm-6 col-xl-6 ">
                    <div class="breadcrumb">Account <span><i class="fas fa-angle-right"></i></span>Transfer</div>
                  </div>
               </div>
              </div>
              <div class="row">
                <div class="col-sm-6 col-md-4 px-sm-4">
                  <div class="card zoom">
                    <div class="card-body">
                      <center><img src="../assets/img/same-bank.png" height="100" width="100" title="EFT Funds Transfer"></center>
                      <div class="card-title border-bottom-with-margin w-100">EFT Transfer</div>
                      <p>EFT (Elecronic Funds Transfer) moves from one FNMEB account to another FNMEB account.</p>
                      <div id="example" class="text-center">
                        <button type="buttom" data-toggle="modal" data-target="#anontherFNMEBAccount" class="btn btn-success mb-3 mr-2"><i class="fas fa-money-bill" style="size:800px;"></i>  EFT Transfer</button>
                      </div>
                      
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 col-md-4 px-sm-4">
                  <div class="card zoom">
                    <div class="card-body">
                      <center><img src="../assets/img/ibft.png" height="100" width="100" title="Inter-Bank Funds Transfer"></center>
                      <div class="card-title border-bottom-with-margin w-100">IBFT Transfer</div>
                      <p>IBFT (Inter-Bank Funds Transfer) moves money from one bank to another in the same country.</p>
                      <div id="example" class="text-center">
                        <button type="button" data-toggle="modal" data-target="#otherCommercialBank" class="btn btn-danger mb-3 mr-2"><i class="fas fa-money-bill-alt" style="size:800px;"></i>  IBFT Transfer</button>
                      </div>
                      
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 col-md-4 px-sm-4">
                  <div class="card zoom">
                    <div class="card-body">
                      <center><img src="../assets/img/wire.png" height="100" width="100" title="National Electronic Funds Transfer"></center>
                      <div class="card-title border-bottom-with-margin w-100">IFT Transfer</div>
                      <p>IFT (International Funds Transfer) moves money from one country to another country.</p>
                      <div id="example" class="text-center">
                        <button type="button" data-toggle="modal" data-target="#internationalFundsTransfer" class="btn btn-info mb-3 mr-2"><i class="fas fa-money-bill" style="size:800px;"></i>  IFT Transfer</button>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
            
          </div>
          ';
        }

        elseif (isset($_GET['settings'])) {
          echo '
          <!-- transfer -->
          <div id="transfer" class="container-fluid px-sm-5" style="display:block;">
            <div class="title-box">
              <div class="row pt-4">
                <div class="col-12 col-sm-6 col-xl-6">
                  <div class="page-title">Account Settings</div>
                </div>
                <div class="col-12 col-sm-6 col-xl-6 ">
                  <div class="breadcrumb">Account <span><i class="fas fa-angle-right"></i></span>Settings</div>
                </div>
             </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="card card-info card-tabs">
                  <div class="card-header p-0 pt-2">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false"><i class="fas fa-lock"></i> Change Password</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false"><i class="fas fa-shield-alt"></i> KYC verification</a>
                      </li>
                    </ul>
                  </div>
                  <div class="card-body py-0">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                      <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                         <form class="row pt-4">
                          <div class="col-12">
                            <div class="form-group rounded my-2 border border-light" style="padding:1px;">
                              <input type="password" name="oldpassword" id="oldpassword" class="form-control" placeholder="Old Password" required style="font-size: 12px">
                            </div>
                            <div class="form-group rounded my-2 border border-light" style="padding:1px;">
                              <input type="password" name="password" id="password1" class="form-control" placeholder="New Password" required style="font-size: 12px">
                            </div>
                            <div class="form-group rounded mt-2 border border-light" style="padding:1px;">
                              <input type="password" id="password2" class="form-control" placeholder="Confirm Password" required style="font-size: 12px">
                            </div>
                            <div id="change_response" style="font-size:10px;"></div>
                            <div class="form-group">
                              <button type="button" id="changepasswordbtn" class="btn btn-secondary float-end" style="font-size: 12px">Update</button>
                            </div>
                          </div>
                            
                            
                        </form>
                      </div>
                      <div class="tab-pane fade show active" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">
                        <div class="card rounded-0 text-center border-0 shadow-none">
                          <div class="card-body">'.$verify_img.'</div>
                          <div class="card-footer">'.$verify_info.'</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.card -->
                </div>
              </div>
              <!-- /.
              <div class="col-sm-6">
                <div class="card card-info pb-3">
                  <div class="card-header">
                    <div class="card-title text-white font-weight-normal w-100">System Information <i class="fas fa-assistive-listening-systems float-right"></i></div>
                  </div>
                  <div class="card-body">
                    <div class="card-title text-info">System Information</div>
                    <div class="card-text pb-4">'.php_uname()."\n".'</div>
                    <div class="card-title text-info">Operating System</div>
                    <div class="card-text pb-4">'.PHP_OS."\n".'</div>
                    <div class="card-title text-info">IP Address</div>
                    <div class="card-text">'.getenv("REMOTE_ADDR").'</div>
                  </div>
                </div>
              </div>
              -->
            </div>
          </div> 
          ';
        }

        elseif (isset($_GET['support'])) {
          echo '
          <!-- live chat -->
          <div id="support" class="container-fluid" style="display:block;">
            <div class="title-box">
              <div class="row pt-4">
                <div class="col-12 col-sm-6 col-xl-6">
                  <div class="page-title">Open Support Ticket</div>
                </div>
                <div class="col-12 col-sm-6 col-xl-6 ">
                  <div class="breadcrumb">Support <span><i class="fas fa-angle-right"></i></span>Get Help</div>
                </div>
             </div>
            </div>
            <div class="row">
              <div class="col-12">
                
                <div class="card w-100" style="z-index:1000;position: relative;right:0;bottom:0; max-width: 700px;">
                  <div class="card-header bg-dark">
                    <h3 class="card-title text-white">Customer Support</h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
                    </div>
                  </div>

                  <div class="card-body px-0 pb-0">
                      <div id="direct-chat-messages" class="direct-chat-messages">
                          
                          
                      </div>
                      <form class="live-chat-form bg-light p-2" id="live-chat-form">
                        <div class="input-group">
                          <input type="hidden" name="send_msg">
                          <input type="hidden" class="name" name="name" value="'.$data['fname'].' '.$data['lname'].'">
                          <input class="unique_id" type="hidden" name="unique_id" value="'.$unique_id.'">
                          <input class="incoming_id" type="hidden" name="incoming_id" value="'.$adminid.'">
                          <textarea class="form-control input-field" id="new-message" name="message" placeholder="Type message here..." rows="2"autocomplete="off"></textarea>
                        </div>
                        <div class="input-group py-2">
                          <button type="submit" id="new-message-btn" class="btn btn-info rounded-0 border-0">Send <i class="fab fa-telegram-plane"></i></button>
                        </div>
                      </form>
                  </div>
                  
                </div>
                
              </div>
            </div>
          </div>
          ';
        }

        elseif (isset($_GET['cards'])) {
          echo '
          <!-- cardcc__square -->
          <div id="card" class="container-fluid" style="display:block;">
            <div class="title-box">
              <div class="row pt-4">
                <div class="col-12 col-sm-6 col-xl-6">
                  <div class="page-title">Credit Cards</div>
                </div>
                <div class="col-12 col-sm-6 col-xl-6 ">
                  <div class="breadcrumb">Virtual Cards <span><i class="fas fa-angle-right"></i></span>Master/Visa</div>
                </div>
             </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <h6 class="card-title w-100 mb-4">+ Add New Card</h6>
                    <div>
                        <button type="button" class="theme btn btn-primary mr-2" data-toggle="modal" data-target="#addOtherCardModal" >+ Add Other Bank Card</button>
                    </div>
                    <div class="card-title w-100 text-center text-waring">OR</div>
                    <form id="addCard">
                      <input type="hidden" name="acctid" value="'.$acctid.'">
                      <input type="hidden" name="addcard">
                      <div class="form-group">
                        <label>Choose Card Type: <span id="error"></span></label>
                        <select class="form-control" name="type">
                          <option value="">Select Card Type</option> 
                          <option value="visa">Visa Card</option> 
                          <option value="master">Master Card</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <button type="submit" class="theme btn btn-primary mr-2">+ Add Card</button>
                      </div>
                    </form>
                    <table id="example3" class="update table table-sm">
                      <thead>
                        <tr>
                          <th>CARD NO:</th>
                          <th>EXP</th>
                          <th>CVV</th>
                          <th>STATUS</th>
                          <th>TYPE</th>
                          <th>ACTION</th>
                        </tr>
                      </thead>
                      <tbody>';
                      $sqlio = $db_conn->query("SELECT * FROM cards WHERE acctid = '$acctid' ORDER BY id DESC");
                      if($sqlio->num_rows > 0) {
                        while($rowi = $sqlio->fetch_assoc()) {
                        echo '<tr>';
                        $vdr = $rowi['vdr'];
                        if ($vdr == 'virtual') {
                          echo '<td>'.$rowi['serial1'].' '.$rowi['serial2'].' '.$rowi['serial3'].' '.$rowi['serial4'].'</td>';
                        } else {
                          $str = $rowi['serial_all'];
                          $parts = chunk_split($str, 4);
                          echo '<td>'.$parts.'</td>';
                        }
                          echo '<td>'.$rowi['exp'].'</td>';
                          echo '<td>'.$rowi['cvv'].'</td>';
                          echo '<td class="btn btn-sm btn-success text-uppercase">'.$rowi['status'].'</td>';
                          echo '<td class="text-capitalize">'.$rowi['type'].' Card</td>';
                          echo '<td class="delete btn btn-sm btn-danger  text-uppercase" data-type="delete" data-id="'.$rowi['id'].'">Delete</td>';
                        echo '</tr>';
                        }
                      }
                      echo '</tbody>
                    </table>
                  </div>
                  <div id="loading1" class="overlay dark" style="display: none;"><i class="fas fa-4x fa-spinner fa-spin"></i></div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <h6 class="card-title w-100 pb-4">Bank Card Details</h6>
                    '.$card_output1.'
                    '.$card_output2.'
                  </div>
                  <div id="loading2" class="overlay dark" style="display: none;"><i class="fas fa-4x fa-spinner fa-spin"></i></div>
                </div>
              </div>
            </div>
          </div>
          ';
        }

        /*
        elseif (isset($_GET['verification'])) {
          echo '
          ';
        }*/

        elseif (isset($_GET['inbox'])) {
          echo '
          <!-- inbox -->
          <div id="inbox" class="container-fluid" style="display:block;">
            <div class="title-box">
              <div class="row pt-4">
                <div class="col-12 col-sm-6 col-xl-6">
                  <div class="page-title">Inbox</div>
                </div>
                <div class="col-12 col-sm-6 col-xl-6 ">
                  <div class="breadcrumb">Customer <span><i class="fas fa-angle-right"></i></span>Messages</div>
                </div>
             </div>
            </div>

            <div class="row">
              <div class="col-md-3">
                <a href="?support" class="btn btn-info btn-block mb-3">Open Ticket <span class="font-weight-bold">+</span></a>

                <div class="card card-info card-outline">
                  <div class="card-header">
                    <h3 class="card-title">Folders</h3>

                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body p-0">
                    <ul class="nav nav-pills flex-column">
                      
                    </ul>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>

              <div class="col-md-9">
                <div class="card card-primary card-outline">
                  <div class="card-header">
                    <h3 class="card-title">Inbox</h3>

                    <div class="card-tools">
                      <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="Search Mail">
                        <div class="input-group-append">
                          <div class="btn btn-primary">
                            <i class="fas fa-search"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.card-tools -->
                  </div>
                  <!-- /.card-header -->
                  <div id="mail-list" class="card-body p-0">
                    <div class="mailbox-controls">
                      <!-- Check all button -->
                      <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
                      </button>
                      <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm">
                          <i class="far fa-trash-alt"></i>
                        </button>
                        <button type="button" class="btn btn-default btn-sm">
                          <i class="fas fa-reply"></i>
                        </button>
                        <button type="button" class="btn btn-default btn-sm">
                          <i class="fas fa-share"></i>
                        </button>
                      </div>
                      <!-- /.btn-group -->
                      <button type="button" class="btn btn-default btn-sm">
                        <i class="fas fa-sync-alt"></i>
                      </button>
                      <div class="float-right">
                        <div class="btn-group">
                          <button type="button" class="btn btn-default btn-sm">
                            <i class="fas fa-chevron-left"></i>
                          </button>
                          <button type="button" class="btn btn-default btn-sm">
                            <i class="fas fa-chevron-right"></i>
                          </button>
                        </div>
                        <!-- /.btn-group -->
                      </div>
                      <!-- /.float-right -->
                    </div>
                    <div class="table-responsive mailbox-messages">
                      <table class="table table-hover">
                        <tbody>';
                        $sqliot = $db_conn->query("SELECT * FROM inbox WHERE acctid = '$acctid' ORDER BY msg_id DESC");
                        if($sqliot->num_rows > 0) {
                          while($rowit = $sqliot->fetch_assoc()) {
                            echo'<tr class="read-msg" data-id="'.$rowit['msg_id'].'">';
                          echo'<td>
                            <div class="icheck-primary">
                              <input type="checkbox" value="" id="check1">
                              <label for="check1"></label>
                            </div>
                          </td>';
                          echo'<td class="mailbox-subject"><b>'.$rowit['subject'].'</b>
                          </td>';
                          echo'<td><button class="btn btn-sm btn-warning text-right px-4 text-white rounded-pill read-msg" data-id="'.$rowit['msg_id'].'">Open</button></td>';
                        echo'</tr>';
                          }
                        }
                        

                        echo '</tbody>
                      </table>
                      <!-- /.table -->
                    </div>
                    <!-- /.mail-box-messages -->
                  </div>
                  <div id="read-mail" class="card-body p-0" style="display:none;">
                    <!-- /.mailbox-read-info -->
                    <div class="mailbox-controls with-border text-center">
                      <span class="back-to-list font-weight-bold back-btn text-warning">Go Back</span>
                    </div>
                    <!-- /.mailbox-controls -->

                    <div class="mailbox-read-info">
                      <h5 class="message-subject"></h5>
                      <h6>From: mail@fnmeb.com
                        <span class="mailbox-read-time float-right"></span></h6>
                    </div>
                    
                    <div class="mailbox-read-message">
                      
                    </div>
                    <!-- /.mailbox-read-message -->
                  </div>
                  <div id="loadex" class="overlay dark" style="display: none;"><i class="fas fa-4x fa-spinner fa-spin"></i></div>
                  <!-- /.card-body -->
                  <div class="card-footer p-0">
                    <div class="mailbox-controls">
                      <!-- Check all button -->
                      <button type="button" class="btn btn-default btn-sm checkbox-toggle">
                        <i class="far fa-square"></i>
                      </button>
                      <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm">
                          <i class="far fa-trash-alt"></i>
                        </button>
                        <button type="button" class="btn btn-default btn-sm">
                          <i class="fas fa-reply"></i>
                        </button>
                        <button type="button" class="btn btn-default btn-sm">
                          <i class="fas fa-share"></i>
                        </button>
                      </div>
                      <!-- /.btn-group -->
                      <button type="button" class="btn btn-default btn-sm">
                        <i class="fas fa-sync-alt"></i>
                      </button>
                      <div class="float-right">
                        <div class="btn-group">
                          <button type="button" class="btn btn-default btn-sm">
                            <i class="fas fa-chevron-left"></i>
                          </button>
                          <button type="button" class="btn btn-default btn-sm">
                            <i class="fas fa-chevron-right"></i>
                          </button>
                        </div>
                        <!-- /.btn-group -->
                      </div>
                      <!-- /.float-right -->
                    </div>
                  </div>
                </div>
                <!-- /.card -->
              </div>

            </div>
          </div>
          ';
        }

        elseif (isset($_GET['loans'])) {
          echo '
          <!-- dashboard -->
          <div id="dashboard" class="container" style="display:block;">
            <div class="title-box">
              <div class="row pt-4">
                <div class="col-12 col-sm-6 col-xl-6">
                  <div class="page-title">Loan Application</div>
                </div>
                <div class="col-12 col-sm-6 col-xl-6 ">
                  <div class="breadcrumb">Account <span><i class="fas fa-angle-right"></i></span>Loans</div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <!-- /.card-header -->
                  <div class="card-body">
                    <div class="card-text text-center text-muted py-4">
                        FNMEB is an ethical and responsible direct lender offering an alternative to other loan options so you can borrow with peace of mind!
                    </div>
                    <div class="card-text text-center h1 text-primary text-bold pb-4">
                        Apply for a direct loan online
                    </div>
                    <div class="card-text text-center text-muted">
                        <p>Our application form is simple and straightforward. Simply fill out your details below and we will let you know if we can offer you a loan. Once you’ve submitted a loan application, one of our staff will contact you so we can progress your application.</br>
                        FNMEB aim to be as transparent as possible which means we show you directly how we compare against doorstep and payday lenders.</p>
                    </div>
                    <div class="text-center">
                      <button type="buttom" data-toggle="modal" data-target="#loanApplicationModal" class="btn btn-primary text-uppercase my-4">Apply Now For A Loan</button>
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <!-- /.card-header -->
                  <div class="card-body">';
                      $sql = $db_conn->query("SELECT * FROM loans WHERE acctid = '$acctid' ORDER BY id DESC");
                      if($sql->num_rows > 0) {
                        echo '
                        <h5 class="text-left">Loan Application Requests</h5>
                        <table id="example5" class="table table-sm text-left" style="font-size:14px;">
                          <thead>
                            <tr>
                              <th scope="col">DATE/TIME</th>
                              <th scope="col">AMOUNT</th>
                              <th scope="col">DURATION</th>
                              <th scope="col">STATUS</th>
                            </tr>
                          </thead>
                          <tbody>
                        ';
                        while($row = $sql->fetch_assoc()) {
                        echo '<tr>';
                          echo '<td>'.$row['appdate'].'</td>';
                          echo '<td>'.number_format($row['amount'], 2).'</td>';
                          echo '<td>'.$row['duration'].' Months</td>';
                          echo '<td class="text-capitalize text-info">'.$row['status'].'</td>';
                        echo '</tr>';
                        }
                      }
                      else {
                        echo "<div class='pl-4 text-center w-100 text-info'>NO LOAN APPLICATIONS AT THE MOMENT</div>";
                      }
                      echo '</tbody>
                    </table>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
            </div>
                
          </div>
          ';
        }

        else {
          echo '
          <!-- dashboard -->
          <div id="dashboard" class="container-fluid" style="display:block;">

            <div class="row pt-4 pb-4">
              <div class="col-sm-6">
                <i class="fas fa-landmark text-info" style="font-size:36px"></i>
              </div>
              <div class="col-sm-6 text-right">
                <a class="badge badge-danger mb-2" href="?cards"><i class="fab fa-cc-visa"></i> + Card</a>
                <a class="badge badge-info mb-2" href="?transfer"><i class="fab fa-cc-amazon-pay"></i> + Transfer</a>
                <a class="badge badge-success mb-2" href="?support"><i class="fab fa-cc-discover"></i> + Ticket</a>
              </div>  
            </div>

            <div class="row">
              <div class="col-sm-5">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                  
                  <div class="col-12">
                    <!-- small box -->
                    <div class="small-box bg-info zoom py-2">
                      <div class="inner">
                        <h5 class="font-weight-bold">$'.number_format($data['accbalance'], 2).'</h5>
                        <p>Available Balance</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-chart-pie"></i>
                      </div>
                    </div>
                  </div>
                  <!-- ./col -->
                  <div class="col-12">
                    <!-- small box -->
                    <div class="small-box bg-primary zoom py-2">
                      <div class="inner">
                        <h5 class="font-weight-bold">$'.number_format($data['bookbalance'], 2).'</h5>
                        <p>Booking Balance</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-chart-pie"></i>
                      </div>
                    </div>
                  </div>               
                </div>
                <!-- /.row -->
              </div>
              <div class="col-sm-7">
                <div class="row">
                  <div class="col-12 main">
                    <div class="card bg-transparent">
                      <div class="card-body py-0">
                        <div class="card-title py-1">
                          <i class="fab fa-cc-visa"></i> Virtual Card Here<span class="badge badge-danger" style="font-size:14px;">Acc/Date: '.date('Y-m-d', strtotime($data['regdate'])).'</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="card">
                      <div class="cardcc w-100 zoom">
                        <div class="cardcc__front cardcc__part w-100 h-100" id="zoom">
                          <img class="cardcc__front-square cardcc__square" src="../icon/chip.png"> <strong style="color:white;">USD </strong>
                          <img class="cardcc__front-logo cardcc__logo" src="../icon/visa.png">
                          <p class="cardcc_numer"><strong>'.$card_visa['serial1'].' '.$card_visa['serial2'].' '.$card_visa['serial3'].' '.$card_visa['serial4'].'</strong></p>
                          <div class="cardcc__space-75">
                            <span class="cardcc__label">Card Holder</span>
                            <p class="cardcc__info"><strong>'.$data['lname'].' '.$data['fname'].' '.$data['mname'].'</strong></p>
                          </div>
                          <div class="cardcc__space-25">
                            <span class="cardcc__label">Expires</span>
                            <p class="cardcc__info"><strong>'.$card_visa['exp'].'</strong></p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="card">
                      <div class="cardcc w-100 zoom">
                        <div class="cardcc__back cardcc__part w-100 h-100" id="zoom">
                          <div class="cardcc__black-line"></div>
                          <div class="cardcc__back-content">
                            <div class="cardcc__secret">
                              <p class="cardcc__secret--last"><strong>'.$card_visa['cvv'].'</strong></p>
                            </div>
                            <img class="cardcc__back-logo cardcc__logo" src="../icon/visa.png">
                            
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <h6 class="text-danger text-right font-weight-bold">No Virtual? <a href="?cards">+Add Card</a></h6>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  
                  <!-- /.card-header -->
                  <div class="card-body">
                    <h3 class="card-title w-100 mb-2">RECENT TRANSACTIONS</h3>
                    <table id="example1" class="table table-sm" style="font-size:14px;">
                      <thead>
                        <tr>
                          <th scope="col">ID</th>
                          <th scope="col">Date</th>
                          <th scope="col">Type</th>
                          <th scope="col">Description</th>
                          <th scope="col">Amount</th>
                          <th scope="col">Status</th>
                        </tr>
                      </thead>
                      <tbody>';
                      $sql = $db_conn->query("SELECT * FROM transactions WHERE acctid = '$acctid' ORDER BY id DESC LIMIT 10");
                      if($sql->num_rows > 0) {
                        while($row = $sql->fetch_assoc()) {
                        echo '<tr>';
                          echo '<td>'.$row['transid'].'</td>';
                          echo '<td>'.$row['regdate'].'</td>';
                          echo '<td>'.$row['type'].'</td>';
                          echo '<td>'.$row['raccname'].'</td>';
                          echo '<td>'.number_format($row['tsum'], 2).'</td>';
                          echo '<td>'.$row['status'].'</td>';
                        echo '</tr>';
                        }
                      }
                      echo '</tbody>
                    </table>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
            </div>
                
          </div>
          ';
        }
        ?>

        
      </section>
      <!-- /.content -->

      <div class="modal fade" id="logoutModal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Ready to leave</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p>Select "Logout" below if you are ready to end your current session.&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <a href="logout" class="btn btn-primary">Logout</a>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->


      <a id="back-to-top" href="#" class="btn btn-info back-to-top" role="button" aria-label="Scroll to top">
        <i class="fas fa-chevron-up"></i>
      </a>
      

              
    </div>
    <!-- /.content-wrapper -->
    <!--Footer-->
    <footer class="main-footer text-center font-small info-color-light wow fadeIn">
      <!--Copyright-->
      <div class="footer-copyright"></div>
      <!--/.Copyright-->

    </footer>
    <!--/.Footer-->
    
  </div>
  <!-- ./wrapper -->  

  <!-- jQuery -->
  <script src="../plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Select2 -->
  <script src="../plugins/select2/js/select2.full.min.js"></script>
  <!-- DataTables  & Plugins -->
  <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="../plugins/jszip/jszip.min.js"></script>
  <script src="../plugins/pdfmake/pdfmake.min.js"></script>
  <script src="../plugins/pdfmake/vfs_fonts.js"></script>
  <script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
  <!-- Toastr -->
  <script src="../plugins/toastr/toastr.min.js"></script>
  <!-- Summernote -->
  <script src="../plugins/summernote/summernote-bs4.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../dist/js/adminlte.js"></script>
  
  <!-- Page specific script -->
  <script>

  $(document).ready(function() {
    $('#cotalert').show();
    // Prepare the preview for profile picture
    $("#file").change(function(){
        readURL(this);
    });
  });

  $(document).on('change', '#file', function(){   
    var name = document.getElementById("file").files[0].name;
    var form_data = new FormData();
    var ext = name.split('.').pop().toLowerCase();
    if(jQuery.inArray(ext, ['gif','png','jpg','jpeg']) == -1) 
    {
     alert("Invalid Image File");
    }
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById("file").files[0]);
    var f = document.getElementById("file").files[0];
    var fsize = f.size||f.fileSize;
    if(fsize > 2000000)
    {
     alert("Image File Size is very big");
    }
    else
    {
     form_data.append("acctid", $('#file').attr('data-user'));
     form_data.append("change_profile_pic", $('#file').attr('data-user'));
     form_data.append("file", document.getElementById('file').files[0]);
     $.ajax({
      url:"server.php",
      method:"POST",
      data: form_data,
      dataType: 'json',
      contentType: false,
      cache: false,
      processData: false,
    
      success:function(response)
      {
       if (response.status == 1) {
         $('#profile').load(location.href + " #profile");
       }
      }
     });
    }
   });

  function readURL(input) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();

          reader.onload = function (e) {
              $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
          }
          reader.readAsDataURL(input.files[0]);
      }
  }
    
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    $("#example1").DataTable({
      "responsive": true,
      "lengthChange": false, 
      "autoWidth": false,
      "paging": false,
      "searching": false,
      "ordering": true,
      "info": false,
      "order": [[ 1, "desc" ]],
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "buttons": ["copy", "pdf", "print"],
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "order": [[ 1, "desc" ]],
    }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    $('#example3').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": false,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "order": [[ 1, "desc" ]],
    }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');
    $('#example4').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": false,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "order": [[ 1, "desc" ]],
    }).buttons().container().appendTo('#example4_wrapper .col-md-6:eq(0)');
    $('#example5').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": false,
      "ordering": false,
      "info": false,
      "autoWidth": false,
      "responsive": true,
      "order": [[ 1, "desc" ]],
    }).buttons().container().appendTo('#example4_wrapper .col-md-6:eq(0)');
  });

  $(document).on('click', '.delete', function() {
    var card_id = $(this).attr('data-id');
    var delete_card = $(this).attr('data-id');
    $.ajax({
      url: 'server.php',
      type: 'POST',
      dataType: 'json',
      data: {card_id:card_id, delete_card:delete_card},
      beforeSend: function() {
        $('.update').css("opacity",".5");
        $('#loading2').show();
      },
      success: function (response) {
        if (response.status == '1') {
          $('#error3').html('Card Removed');
          setTimeout(function() {
            $('.update').css("opacity","");
          }, 2000);
          $('.update').load(location.href + " .update");
          setTimeout(function() {
            $('#loading2').hide();
          }, 2000);
        }
      }
    });
    
  });

  $(document).on('click', '.read-msg', function() {
    var msg_id = $(this).attr('data-id');
    $.ajax({
      url: 'server.php',
      type: 'POST',
      dataType: 'json',
      data: {msg_id:msg_id},
      beforeSend: function() {
        $('#loading').show();
        $('#mail-list').hide();
        $('#read-mail').show();
      },
      success: function (response) {
        if (response.status == '1') {
          $('.message-subject').html(''+response.subject+'');
          $('.mailbox-read-time').html(''+response.time+'');
          $('.mailbox-read-message').html(''+response.message+'');
        }
      }
    });
    
  });

  $(document).on('click', '.back-btn', function() {
    $('#read-mail').hide();
    $('#mail-list').show();
  });

  $(document).on('click', '.transid', function() {
    var acctid = $(this).attr('data-user');
    var transid = $(this).attr('data-id');
    var tsum = $(this).attr('data-sum');
    var getcot = $(this).attr('data-type');
    $.ajax({
      url: 'server.php',
      type: 'POST',
      data: {acctid:acctid, transid:transid, tsum:tsum, getcot:getcot},
      dataType: 'json',
      encode: true,
      beforeSend: function() {
        $('#editOverlay').show();
      },
      success: function (response) {
        $('#editOverlay').hide();
        $('#getcotResponse').html(''+response.message+'');
        $('#transAuth').modal('show');
      }
    });
    
  });

  $(document).on('click','#cotcodebtn', function(e) {
    e.preventDefault();
    if ($("#cotcode").val() == '') {
      alert('Fill all fields');
    } else {
      var acctid = $("#acctid").val();
      var transid = $("#transid").val();
      var tsum = $("#tsum").val();
      var cotcode = $("#cotcode").val();

      $.ajax({
        url: 'server.php',
        type: 'post',
        data: {acctid: acctid, transid: transid, tsum: tsum, cotcode: cotcode},
        dataType: 'json',
        encode: true,
        beforeSend: function() {
          var transid = $("#transid").val();
          $('#cotcodebtn').html('Processing...')
        },
        success: function(response) {
          if (response.status == '1') {
            $('#cotcode_ent').hide();
            $('#cotcode_success').show();
          }
          else if (response.status == '2') {
            $('#cotcode_ent').hide();
            $('#cotcode_verify').show();
          }
          else if (response.status == '0') {
            $('#error').show();
            $('#cotcodebtn').html('Confirm')
          }
        }
      });
      
    }
      
  });

  $(document).on('click', '.closeopencotbtn', function(e) {
    e.preventDefault();
    $('#transAuth').modal('hide');
  });


  $(document).on('click', '#calert', function() {
    $(this).text($(this).text() == 'Show' ? 'Hide' : 'Show');
    $('#pendingTransactions').toggle();
  });

  $(document).on('click', '#closealert', function() {
    $('#cotalert, #dalert').hide();
  });

  $(document).on('click', '#close-livechat-app', function() {
    var delete_msgs = 1;
    var unique_id = $('.unique_id').val();
    $.ajax({
      url: 'server.php',
      type: 'POST',
      data: {delete_msgs:delete_msgs,unique_id:unique_id},
      dataType: 'json',
      encode: true,
      success: function(response) {
        if(response.status == '1') {
          $('#live-chat-form')[0].reset();
          get_msgs();
          scrollToBottom();
        }
      }
    });
  });

  $(document).on('submit','#loanApplicationForm', function(e) {
    e.preventDefault();
    $.ajax({
      url: 'server.php',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      encode: true,
      beforeSend: function() {
        $('#loadet1').show();
        $('#loanApplicationBtn').html('Processing');
      },
      success: function(response) {
        if(response.status == '1') {
          $('#loanApplicationForm')[0].reset();
          $('#response4').html('<span class="text-success">Loan application sent.</span>');
          $('#loanApplicationBtn').html('Apply Now');
          $('#loadet1').hide();
        } else {
          $('#response4').html('<span class="text-danger">Error processing application. Try again.</span>');
          $('#loanApplicationBtn').html('Apply Now');
          $('#loadet1').hide();
        }
      }
    });
  });

  $(document).on('submit','#live-chat-form', function(e) {
    e.preventDefault();
    $.ajax({
      url: 'server.php',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      encode: true,
      success: function(response) {
        if(response.status == '1') {
          $('#live-chat-form')[0].reset();
          get_msgs();
          scrollToBottom();
        }
      }
    });
  });

  setInterval(function(){
    
  }, 200);

  function get_msgs() {
    var name = $('.name').val();
    var unique_id = $('.unique_id').val();
    var incoming_id = $('.incoming_id').val();
    $.ajax({
      url: 'get-chat.php',
      type: 'POST',
      data: {unique_id:unique_id, incoming_id:incoming_id},
       success: function(data) {
              $('#direct-chat-messages').html(data);
      }
    });
  }

  function scrollToBottom(){
    $('#direct-chat-messages').stop().animate({
      scrollTop: $('#direct-chat-messages')[0].scrollHeight
    }, 800);
  }

  $(document).on('change', '#reanumber', function() {
    var acctid = $("#reanumber").val();
    var get_acc = $("#reanumber").val();
    $.ajax({
      url: 'server.php',
      type: 'post',
      data: {acctid: acctid, get_acc: get_acc},
      dataType: 'json',
      encode: true,
      success: function(response) {
        if (response.status == 1) {
          $("#refullname").val(''+response.message+'');
        }
        else {
          $("#refullname").val('');
          $('#error').html(''+response.message+'').removeClass('text-success').addClass('text-danger');
        }
      }
    });
  });

  $(document).on('click','#retransferbtn', function(e) {
    e.preventDefault();
    var acctid = $("#acctid").val();
    var refullname = $("#refullname").val();
    var reanumber = $("#reanumber").val();
    var reamount = $("#reamount").val();
    var mode = $("#remode").val();
    $.ajax({
      url: 'server.php',
      type: 'post',
      data: {acctid: acctid, raccname: refullname, raccno: reanumber, tsum: reamount, remode: mode},
      dataType: 'json',
      encode: true,
      beforeSend: function() {
        $('#loadez1').show();
      },
      success: function(response) {
        if (response.status == 1) {
          $('#error').html(''+response.message+'').removeClass('text-danger').addClass('text-success');
          $('#loadez1').hide();
        }
        else {
          $('#error').html(''+response.message+'').removeClass('text-success').addClass('text-danger');
          $('#loadez1').hide();
        }
      }
    });
    
  });

  $(document).on('click','#othertransferbtn', function(e) {
    e.preventDefault();
    var acctid1 = $("#acctid1").val();
    var otherbankname = $("#otherbankname").val();
    var otherrefullname = $("#otherrefullname").val();
    var otherreanumber = $("#otherreanumber").val();
    var otherreamount = $("#otherreamount").val();
    var mode = $("#ibftmode").val();
    $.ajax({
      url: 'server.php',
      type: 'post',
      data: {acctid: acctid1, rbank: otherbankname, raccname:otherrefullname, raccno: otherreanumber, tsum: otherreamount, othermode: mode},
      dataType: 'json',
      encode: true,
      beforeSend: function() {
        $('#loadez2').show();
      },
      success: function(response) {
        if (response.status == 1) {
          $('#error1').html(''+response.message+'').removeClass('text-danger').addClass('text-success');
          $('#loadez2').hide();
        }
        else {
          $('#error1').html(''+response.message+'').removeClass('text-success').addClass('text-danger');
          $('#loadez2').hide();
        }
      }
    });
    
  });

  $(document).on('click','#inttransferbtn', function(e) {
    e.preventDefault();
    var acctid2 = $("#acctid2").val();
    var intcountry = $("#intcountry").val();
    var intemail = $("#intemail").val();
    var intbank = $("#intbank").val();
    var intname = $("#intname").val();
    var intnumber = $("#intnumber").val();
    var intamount = $("#intamount").val();
    var mode = $("#intmode").val();
    $.ajax({
      url: 'server.php',
      type: 'post',
      data: {acctid: acctid2, rcountry: intcountry, remail: intemail, rbank: intbank, raccname: intname, raccno: intnumber, tsum: intamount, intmode: mode},
      dataType: 'json',
      encode: true,
      beforeSend: function() {
        $('#loadez3').show();
      },
      success: function(response) {
        if (response.status == 1) {
          $('#error2').html(''+response.message+'').removeClass('text-danger').addClass('text-success');
          $('#loadez3').hide();
        }
        else {
          $('#error2').html(''+response.message+'').removeClass('text-success').addClass('text-danger');
          $('#loadez3').hide();
        }
      }
    });
    
  });

  $(document).on('submit', '#userVerificationForm', function(e){
    e.preventDefault();
    if ($("#passport")[0].files.length === 0) {
      alert('No file selected');
    } else {
      $.ajax({
        type: 'POST',
        url: 'server.php',
        data: new FormData(this),
        dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function(){
            $('#userVerificationBtn').html('Updating...<i class="fas fa-spinner fa-spin float-right"></i>');
            $('#userVerificationForm').css("opacity",".5");
        },
        success: function(response){
          $('#response2').html('');
          if(response.status == 1){
            $('#userVerificationForm')[0].reset();
            $('#response2').html('<p class="text-center rounded text-sm py-2 border border-success text-success">'+response.message+'</p>');
            $('#userVerificationForm').css("opacity","");
            $("#userVerificationBtn").html("Submit");
            $('#userProfile').load(location.href + " #userProfile");
            setTimeout(function() {
              $('#response2').html('');
              $('#verify_account').modal('hide');
            }, 5000);
          }
          else {
            $('#response2')('<p class="text-center rounded text-sm py-2 border border-danger text-danger">'+response.message+'</p>');
            $('#userVerificationForm').css("opacity","");
            $('#userVerificationBtn').html("Submit");
            setTimeout(function() {
              $('#response2').html('');
            }, 5000);
          }
        }
      });
    }
  });


  $(document).on('submit', '#updateProfileForm', function(e){
    e.preventDefault();
    $.ajax({
      type: 'POST',
      url: 'server.php',
      data: $(this).serialize(),
      dataType: 'json',
      encode: true,
      beforeSend: function(){
          $('#updateProfileBtn').html('Updating...<i class="fas fa-spinner fa-spin float-right"></i>');
          $('#updateProfileForm').css("opacity",".5");
      },
      success: function(response){
        if(response.status == 1){
          $('#updateProfileForm')[0].reset();
          $('#response3').html('<p class="text-center rounded text-sm py-2 border border-success text-success">'+response.message+'</p>');
          $('#updateProfileForm').css("opacity","");
          $("#updateProfileBtn").html("Submit");
          $('#profile').load(location.href + " #profile");
          setTimeout(function() {
            $('#response3').html('');
            $('#update_profile').modal('hide');
            $('#updateProfileForm').load(location.href + " #updateProfileForm");
          }, 5000);
          
        }
        else {
          $('#response3')('<p class="text-center rounded text-sm py-2 border border-danger text-danger">'+response.message+'</p>');
          $('#updateProfileForm').css("opacity","");
          $('#updateProfileBtn').html("Submit");
          setTimeout(function() {
            $('#response3').html('');
          }, 5000);
        }
      }
    });
  });

  $('#password2').keyup(function() {
    var password1 = $("#password1").val();
    var password2 = $("#password2").val();

    if (password1 !== password2) {
      $('#change_response').html('<p class="text-danger">Both passwords don\'t match!</p>');
    } 
    else {
      $('#change_response').html('<p class="text-success">Both passwords match!</p>');
      $(document).on('click','#changepasswordbtn', function(e) {
        e.preventDefault();
        var acctid = $("#acctid").val();
        var oldpassword = $("#oldpassword").val();
        var password1 = $("#password1").val();
        var password2 = $("#password2").val();
        var mode = $("#remode").val();
        $.ajax({
          url: 'server.php',
          type: 'post',
          data: {acctid: acctid, oldpassword: oldpassword, password: password1},
          dataType: 'json',
          encode: true,
          beforeSend: function() {
            $('#changepasswordbtn').html('<span class="spinner-border spinner-border-sm text-capitalize float-right" role="status" aria-hidden="true"></span> Processing...');
          },
          success: function(response) {
            if (response.status == 1) {
               $('#change_response').html('<p class="text-success">'+response.message+'</p>');
               $('#changepasswordbtn').html('Update');
               setTimeout(function() {
                $('#change_response').html('');
              }, 5000);
            }
            else {
              $('#changepasswordbtn').html('Update');
              $('#change_response').html('<p class="text-danger">'+response.message+'</p>');
              $('#changepasswordbtn').html('Update');
              setTimeout(function() {
                $('#change_response').html('');
              }, 5000);
            }
          }
        });
        
      });
    }
  });

  $('#addCard').on('submit', function(e){
    e.preventDefault();
    $.ajax({
      url: 'server.php',
      type: 'post',
      dataType: 'json',
      data: $(this).serialize(),
      encode: true,
      beforeSend: function() {
        $('#addCard').css("opacity",".5");
        $('#loading1').show();
      },
      success: function(response) {
        if (response.status == '1') {
          $('#loading1').hide();
          $('#error').html('Card Added');
          setTimeout(function() {
            $('#addCard').css("opacity","");
            $('#loading2').show();
          }, 2000);
          $('.update').load(location.href + " .update");
          setTimeout(function() {
            $('#loading2').hide();
          }, 2000);
        }
      }
    });
  });

  $('#addOtherCardForm').on('submit', function(e){
    e.preventDefault();
    $.ajax({
      url: 'server.php',
      type: 'post',
      dataType: 'json',
      data: $(this).serialize(),
      encode: true,
      beforeSend: function() {
        $('#addCard').css("opacity",".5");
      },
      success: function(response) {
        if (response.status == '1') {
          $('#error_response').html('Card Added');
          setTimeout(function() {
            $('#addOtherCardForm').modal('hide');
            $('#loading2').show();
          }, 2000);
          $('.update').load(location.href + " .update");
          setTimeout(function() {
            $('#loading2').hide();
          }, 2000);
        }
      }
    });
  });

  scrollToBottom();
  get_msgs();
  function show_value(x)
  {
  document.getElementById("slider_value").innerHTML=x;
  }

  function show_value1(x)
  {
  document.getElementById("slider_value1").innerHTML=x;
  }
  </script>
</body>
</html>