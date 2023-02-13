<?php
session_start();
if (!$_SESSION['adminid']) {
  header('Location:login');
}
include('../database/dbconfig.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Accounts</title>
  <link rel="shortcut icon" href="img/favicon.png" />
  <link rel="icon" href="img/favicon.png" type="image/x-icon">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../dist/css/custom-css.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css">
</head>
<body id="results" class="hold-transition sidebar-mini layout-fixed">
    
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        
      </ul>
     
      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">        
        <!-- Live Chat Messages Dropdown Menu -->
        <li class="nav-item dropdown">
          <?php 
            include_once '../database/dbconfig.php';
            $sqli = $db_conn->query("SELECT * FROM conversations WHERE source = 'user' ORDER BY msg_id DESC");
            $msg_count = $sqli->num_rows;
          ?>
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-comments"></i>
            <span id="msg-count" class="badge badge-danger navbar-badge"><?php echo $msg_count; ?></span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <?php
            $sqli = $db_conn->query("SELECT DISTINCT unique_id FROM conversations WHERE source = 'user' ORDER BY msg_id DESC LIMIT 5");
            if($sqli->num_rows > 0) {
              while($show_msgs = $sqli->fetch_assoc()) {
                $unique_id = $show_msgs['unique_id'];
                $sqlio = $db_conn->query("SELECT DISTINCT name FROM conversations WHERE unique_id = '$unique_id' AND source = 'user' ORDER BY msg_id DESC");
                $stmt = $sqlio->fetch_assoc();     
            ?>
            <a href="chat?user_chat_id=<?php echo $show_msgs['unique_id']; ?>" class="dropdown-item">
              <!-- Message Start -->
              <div class="media">
                <div class="media-body">
                  <h3 class="dropdown-item-title font-weight-bold">
                    <?php echo $stmt['name']; ?>
                    <span class="float-right text-sm text-success"><i class="fas fa-star"></i></span>
                  </h3>
                  <p class="text-sm text-success"><i class="fas fa-dot-circle mr-1"></i>Online</p>
                </div>
              </div>
              <!-- Message End -->
            </a>
            <?php
              }
            }
            else {
              echo '<a class="dropdown-item dropdown-footer">No messages</a>';
            }
            ?>
            <div class="dropdown-divider"></div>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      
      <!-- Sidebar -->
      <div class="sidebar">
        
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
            <li class="nav-item mt-4">
              <a href="home" class="nav-link">
                <i class="fas fa-tachometer-alt nav-icon"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="clients" class="nav-link">
                <i class="fas fa-user nav-icon"></i>
                <p>Clients</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="accounts" class="nav-link">
                <i class="fas fa-users nav-icon"></i>
                <p>Accounts</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="transactions" class="nav-link">
                <i class="far fa-list-alt nav-icon"></i>
                <p>Transactions</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="chat" class="nav-link">
                <i class="fas fa-comments nav-icon"></i>
                <p>Live chat</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="messages" class="nav-link">
                <i class="fas fa-comment nav-icon"></i>
                <p>Messages</p>
              </a>
            </li>
            
            <li class="nav-item">
              <a href="logout" class="nav-link">
                <i class="nav-icon fa fa-power-off"></i>
                <p>
                  Sign out
                </p>
              </a>
            </li>
            
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Modal Credit Account -->
    <div class="modal fade" id="creditAccModal" data-backdrop="static" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Credit User Account</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body p-0">
            
            <div class="card mb-0">
              <div class="card-body">
                <div id="credit_acc">
                  <form>
                    <input type="hidden" name="acctid" value="">
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
                  </form>
                </div>
              </div>

              <div id="loading" class="overlay dark"><i class="fas fa-4x fa-spinner fa-spin"></i></div>

            </div>
            
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="registerUser" enctype="multipart/form-data">
            <div class="modal-body">
              <div class="row">
                  <div class="col-12 form-group">
                      <label class="form-control-label">Upload Photo</label>
                      <input type="file" name="photo" class="form-control text-field-box" data-msg="JPG, JPEG, PNG, GIF" required/>
                  </div>
                
              </div>
              <div class="row">
                  <div class="col-12 form-group">
                      <label class="form-control-label">First Name</label>
                      <input type="text" name="fname" value="" class="form-control text-field-box"  placeholder="First name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" required/>
                      <div class="validation"></div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-12 form-group">
                      <label class="form-control-label">Last Name</label>
                      <input  type="text" name="lname" value="" class="form-control text-field-box" placeholder="Last name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" required/>
                      <div class="validation"></div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-12 form-group">
                      <label for="dob" class="form-control-label">Date of Birth</label>
                      <input  type="date" name="dob" value="" class="form-control text-field-box" required/>
                      <div class="validation"></div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-12 form-group">
                      <label class="form-control-label">Email</label>
                      <input type="email" placeholder="Email" name="email" value="" class="form-control text-field-box"   data-rule="minlen:4" data-msg="Please enter at least 4 chars" required/>
                      <div class="validation"></div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-12 form-group">
                      <label class="form-control-label">Phone Number</label>
                      <input type="tel" placeholder="Phone Number" name="phoneno" value="" class="form-control text-field-box"   data-rule="minlen:4" data-msg="Please enter at least 4 chars" required/>
                      <div class="validation"></div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-12 form-group">
                      <label class="form-control-label">Country</label>
                      <select name="country" class="form-control text-field-box" required/>
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
                      <div class="validation"></div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-12 form-group">
                      <label class="form-control-label">State</label>
                      <input type="text" placeholder="State" name="state" value="" class="form-control text-field-box"   data-rule="minlen:4" data-msg="Please enter at least 4 chars" required/>
                      <div class="validation"></div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-12 form-group">
                      <label class="form-control-label">City</label>
                      <input type="text" placeholder="City" name="city" value="" class="form-control text-field-box"   data-rule="minlen:4" data-msg="Please enter at least 4 chars" required/>
                      <div class="validation"></div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-12 form-group">
                      <label class="form-control-label">Postal Code</label>
                      <input type="text" placeholder="Postal Code" name="pcode" value="" class="form-control text-field-box"   data-rule="minlen:4" data-msg="Please enter at least 4 chars" required/>
                      <div class="validation"></div>
                  </div>
              </div>
              <input type="hidden" name="regacc">
            </div>
            <div class="modal-footer">
              <div class="row">
                  <div id="err" class="col-12 form-group">
                      <div class="statusMsg"></div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-12">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
              </div>
            </div>
          </form>
          
        </div>
      </div>
    </div>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">

          <!-- Small boxes (Stat box) -->
          <div class="row mt-4">
            <div class="col-lg-4 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <?php 
                    
                    $sql = $db_conn->query("SELECT * FROM transactions WHERE status = 'Pending' ORDER BY id");
                    $row = $sql->num_rows;
                    echo '<h3>'.$row.'</h3>';
                  ?>

                  <p>Pending Transactions</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="transactions" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <?php 
                    
                    $mysqli = $db_conn->query("SELECT * FROM transactions WHERE status = 'Completed' ORDER BY id");
                    $data = $mysqli->num_rows;
                    echo '<h3>'.$data.'</h3>';
                  ?>

                  <p>Completed Transactions</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="transactions" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <?php 
                    
                    $query = $db_conn->query("SELECT * FROM accts ORDER BY id");
                    $query_num = $query->num_rows;
                    echo '<h3>'.$query_num.'</h3>';
                  ?>

                  <p>User Registrations</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="accounts" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            
          </div>
          <!-- /.row -->
          <!-- Main row -->
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <?php
                  $sql = $db_conn->query("SELECT * FROM accts ORDER BY id DESC");
                 
                  if($sql->num_rows > 0) {
                    echo '
                    <table id="example1" class="table update table-sm">
                      <h5>
                        <span class="text-dark px-1">User Accounts</span>
                      </h5>
                      <thead class="white-text">
                        <tr>
                          <th>Registration</th>
                          <th>Account Number</th>
                          <th>Fullname</th>
                          <th>Email</th>
                          <th>Account Balance</th>
                          <th>Status</th>
                          <th></th>
                          <th></th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody class="bg-white">';
                      while($row = $sql->fetch_assoc()) {
                        $status = $row['status'];
                        $result = $row['status'];
                           
                        echo '<tr>';
                           echo '<td>'.$row['regdate'].'</td>';
                          echo '<td>'.$row['acctid'].'</td>';
                          echo '<td>'.$row['fname'].' '.$row['lname'].'</td>';
                          echo '<td>'.$row['email'].'</td>';
                          echo '<td>$'.number_format($row['accbalance'], 2).'</td>';
                         echo '<td>';
                            
                            if ($status == 'active') {
                              echo '<span class="text-success">'.$row['status'].'</span>';
                            } elseif ($status == 'suspended') {
                              echo '<span class="text-danger">'.$row['status'].'</span>';
                            }
                            
                          echo '</td>';
                          echo '<td><a class="edit_acc py-0 my-0 btn btn-info btn-sm" role="button" data-id="'.$row['acctid'].'" data-type="credit">Credit</a></td>';
                              
                          if ($result == 'active') {
                            echo '<td><a class="modify_acc py-0 my-0 btn btn-secondary btn-sm" role="button" data-id="'.$row['acctid'].'" data-type="status" data-status="'.$result.'">Suspend</a></td>';
                          } 
                          else {
                            echo '<td><a class="modify_acc py-0 my-0 btn btn-primary btn-sm" role="button" data-id="'.$row['acctid'].'" data-type="status" data-status="'.$result.'">Activate</a></td>';
                          }
                          echo '<td><a class="delete_acc py-0 my-0 btn btn-danger btn-sm" role="button" data-id="'.$row['acctid'].'" data-type="delete">Delete</a></td>';
                        echo '</tr>';
                      }
                      echo '
                        </tbody>
                      </table>';
                    }
                  ?>
                </div>
                <div id="overlay" style="display:none;" class="overlay dark"><i class="fas fa-4x fa-spinner fa-spin"></i></div>
              </div>
            </div>
          </div>
          <!-- /.row (main row) -->
        </div>
        <!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    
    <!--Footer-->
    <footer class="main-footer text-center font-small primary-color-dark darken-2 mt-4 wow fadeIn">
      <!--Copyright-->
      <div class="pt-2">All Rights Reserved &copy; 2010-<?php echo date("Y"); ?></div>
      <!--/.Copyright-->

    </footer>
    <!--/.Footer-->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="../plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
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
  <!-- AdminLTE App -->
  <script src="../dist/js/adminlte.js"></script>

  <script>
    $(document).on('submit','#creditAccForm', function(e) {
      e.preventDefault();
      $.ajax({
        type: 'POST',
        url: 'server.php',
        data: $(this).serialize(),
        dataType: 'json',
        encode: true,
        beforeSend: function() {
          $('#loading').show();
          $('#overlay').show();
        },
        success: function(response){
          if(response.status == 1) {
            $('#creditAccModal').modal('hide');
            $('.update').load(location.href + " .update");
            setTimeout(function() {
              $('#overlay').hide();
            }, 3000);
          }
        }
      });
    });

    // Get credit modal
    $(document).on('click', '.edit_acc', function() {
      var acctid = $(this).attr('data-id');
      var edit_acc = $(this).attr('data-type');
      $.ajax({
        url: 'server.php',
        type: 'POST',
        data: {acctid:acctid, edit_acc:edit_acc},
        dataType: 'json',
        encode: true,
        beforeSend: function() {
          $('#creditAccModal').modal('show');
        },
        success: function (response) {
          if(response.status == 1) {
            $('#credit_acc').html(''+response.message+'');
            $('#loading').hide();
          }
        }
      });
      
    });

    // Get credit modal
    $(document).on('click', '.modify_acc', function(e) {
      e.preventDefault();
      var acctid = $(this).attr('data-id');
      var modify_acc = $(this).attr('data-type');
      var status = $(this).attr('data-status');
      $.ajax({
        url: 'server.php',
        type: 'POST',
        data: {acctid:acctid, modify_acc:modify_acc, status:status},
        dataType: 'json',
        encode: true,
        beforeSend: function() {
          $('#overlay').show();
        },
        success: function (response) {
          if(response.status == 1) {
            $('.update').load(location.href + " .update");
            setTimeout(function() {
              $('#overlay').hide();
            }, 2000);
          }
          
        }
      });
      
    });

    // Get credit modal
    $(document).on('click', '.delete_acc', function(e) {
      e.preventDefault();
      var acctid = $(this).attr('data-id');
      var delete_acc = $(this).attr('data-type');
      $.ajax({
        url: 'server.php',
        type: 'POST',
        data: {acctid:acctid, delete_acc:delete_acc},
        dataType: 'json',
        encode: true,
        beforeSend: function() {
          $('#overlay').show();
        },
        success: function (response) {
          if(response.status == 1) {
            $('.update').load(location.href + " .update");
            setTimeout(function() {
              $('#overlay').hide();
            }, 2000);
          }
            
        }
      });
      
    });

    // Registeration Code 
    $(document).ready(function(){
        // Submit form data via Ajax
        $("#registerUser").on('submit', function(e){
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'server.php',
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function(){
                    $('#btnregister').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
                    $('#registerUser').css("opacity",".5");
                },
                success: function(response){
                    $('.statusMsg').html('');
                    if(response.status == 1){
                        $('#registerUser')[0].reset();
                        $('.statusMsg').html('<p class="alert alert-success">'+response.message+'</p>');
                        $("#btnregister").html("Send Request");
                        $('#update').load(location.href + " #update");
                        setTimeout(function() {
                            $('.statusMsg').html('');
                            $('#staticBackdrop').modal('hide');
                        }, 2000);
                    }else{
                        $('.statusMsg').html('<p class="alert alert-danger">'+response.message+'</p>');
                        $("#btnregister").html("Send Request");
                    }
                    $('#registerUser').css("opacity","");
                }
            });
        });
        
        // File type validation
        var match = ['image/jpeg', 'image/png', 'image/jpg'];
        $("#mc4wp_photo").change(function() {
            for(i=0;i<this.files.length;i++){
                var file = this.files[i];
                var fileType = file.type;
                
                if(!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]) || (fileType == match[4]) || (fileType == match[5]))){
                    alert('Sorry, only JPG, JPEG, & PNG files are allowed to upload.');
                    $("#mc4wp_photo").val('');
                    return false;
                }
            }
        });
    });


    $(function () {
      $("#example1").DataTable({
        "paging": true,
        "responsive": true, 
        "lengthChange": true, 
        "autoWidth": true, 
        "searching": true, 
        "ordering": false,
        "order": true,
        "info": true
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $('#example2').DataTable({
        "paging": true,
        "responsive": true, 
        "lengthChange": true, 
        "autoWidth": true, 
        "searching": true, 
        "ordering": false,
        "order": true,
        "info": true
      }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
      $("#example3").DataTable({
        "paging": true,
        "responsive": true, 
        "lengthChange": true, 
        "autoWidth": true, 
        "searching": true, 
        "ordering": false,
        "order": true,
        "info": true
      }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');
      $('#example4').DataTable({
        "paging": true,
        "responsive": true, 
        "lengthChange": true, 
        "autoWidth": true, 
        "searching": true, 
        "ordering": false,
        "order": true,
        "info": true
      }).buttons().container().appendTo('#example4_wrapper .col-md-6:eq(0)');
      $('#example5').DataTable({
        "paging": true,
        "responsive": true, 
        "lengthChange": true, 
        "autoWidth": true, 
        "searching": true, 
        "ordering": false,
        "order": true,
        "info": true
      }).buttons().container().appendTo('#example5_wrapper .col-md-6:eq(0)');
    });
  </script>
  <!--End of Tawk.to Script-->
</body>
</html>