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
  <title>Transaction History</title>
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

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <div class="card card-tabs shadow-none border-0">
              <div class="card-header p-0 pt-1 border-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">FNM Account</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Other Bank</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-messages-tab" data-toggle="pill" href="#custom-tabs-three-messages" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">Wire</a>
                  </li>
                </ul>
              </div>
              <div class="card-body pb-0">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                    <form id="transferFNMForm">
                      <input type="hidden" name="remode" value="FNM Account">
                      <div class="form-group">
                        <input type="number" class="form-control" name="acctid" placeholder="User Account Number">
                      </div>
                      <div class="form-group">
                        <select name="type" class="form-control" required>
                          <option value="debit">Debit</option>
                          <option value="credit">Credit</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <input type="text" class="form-control" name="raccname" placeholder="Name of Sender" required>
                      </div>
                      <div class="form-group">
                        <input type="number" class="form-control" name="raccno" placeholder="Account Number" required>
                      </div>
                      <div class="form-group">
                        <input type="number" class="form-control" name="tsum" placeholder="Amount ($):" required>
                      </div>
                      <div class="form-group">
                        <label class="form-control-label">Date of Transaction</label>
                        <input type="text" class="form-control" name="regdate" value="2021-08-21 06:58:19am" required>
                      </div>
                      
                      <div class="form-group">
                        <div id="error" class="py-2 text-danger"></div>
                        <button type="submit" class="btn btn-primary" id="retransferbtn"><i id="transferSpinProcess" class="fab fa-wpressr"></i> Submit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                      </div>     
                    </form>
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                    <form id="transferBankForm">
                      <input type="hidden" name="othermode">
                      <div class="form-group">
                        <input type="number" class="form-control" name="acctid" placeholder="User Account Number">
                      </div>
                      <div class="form-group">
                        <select name="type" class="form-control" required>
                          <option value="debit">Debit</option>
                          <option value="credit">Credit</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <select class="form-control" aria-label="Choose Bank" id="otherbankname" name="rbank">
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
                        <input type="text" class="form-control" name="raccname" placeholder="Name of Account" required>
                      </div>
                      <div class="form-group">
                        <input type="number" class="form-control" name="raccno" placeholder="Account Number" required>
                      </div>
                      <div class="form-group">
                        <input type="number" class="form-control" name="tsum" placeholder="Amount ($)" required>
                      </div>
                      <div class="form-group">
                        <label class="form-control-label">Date of Transaction</label>
                        <input type="text" class="form-control" name="regdate" value="2021-08-21 06:58:19am" required>
                      </div>
                      <div class="form-group">
                        <div id="error1" class="py-2 text-danger"></div>
                        <button type="submit" class="btn btn-primary" id="othertransferbtn"><i id="transferSpinProcess" class="fab fa-wpressr"></i> Submit</button>        
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                      </div>
                    </form>
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-three-messages" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab">
                    <form id="transferWireForm">
                      <input type="hidden" name="intmode" value="International Transfer">
                      <div class="form-group">
                        <input type="number" class="form-control" name="acctid" placeholder="User Account Number">
                      </div>
                      <div class="form-group">
                        <select name="type" class="form-control" required>
                          <option value="debit">Debit</option>
                          <option value="credit">Credit</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label class="form-control-label">Receiver Country:</label>
                        <select class="form-control" name="rcountry">
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
                        <input type="email" class="form-control" name="remail" placeholder="Recevier's Email" required>
                      </div>
                      <div class="form-group">
                        <input type="text" class="form-control" name="rbank" placeholder="Bank" required>
                      </div>
                      <div class="form-group">
                        <input type="text" class="form-control" name="raccname" placeholder="Name of Account" required>
                      </div>
                      <div class="form-group">
                        <input type="number" class="form-control" name="raccno" placeholder="Account Number" required>
                      </div>
                      <div class="form-group">
                        <input type="number" class="form-control" name="tsum" placeholder="Amount ($)" required>
                      </div>
                      <div class="form-group">
                        <label class="form-control-label">Date of Transaction</label>
                        <input type="text" class="form-control" name="regdate" value="2021-08-21 06:58:19am" required>
                      </div>
                      <div id="error2" class="py-2 text-danger"></div>
                      <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="inttransferbtn"><i id="transferSpinProcess" class="fab fa-wpressr"></i> Submit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <!-- /.card -->
            </div>
          </div>
        </div>
      </div>
    </div>

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

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">

          <div class="row mt-4">
            <div class="col-12 text-right">
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">
                Add Transaction
              </button>
            </div>
          </div>

  
          
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
              <div class="card rounded-0">
                <div class="card-body">
                  <?php  
                    $sql = $db_conn->query("SELECT * FROM transactions WHERE status = 'Pending' AND mode = 'EFT' ORDER BY id DESC");
                    if($sql->num_rows > 0) {
                          
                  echo '
                  <table id="example5" class="table update1 table-sm ">
                    <h5>
                      <span class="text-dark px-1">Pending Electronic Funds Transfer</span>
                      
                    </h5>
                    <thead class="white-text">
                      <tr>
                        <th>#</th>
                        <th>Account Number</th>
                        <th>Receiver</th>
                        <th>COT code</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody class="bg-white">';
                       while($row = $sql->fetch_assoc()) {
                      echo '<tr>';
                        echo '<td>'.$row['transid'].'</td>';
                        echo '<td>'.$row['acctid'].'</td>';
                        echo '<td>'.$row['raccno'].'</td>';
                        echo '<td>'.$row['cotcode'].'</td>';
                        echo '<td>'.$row['mode'].'</td>';
                        echo '<td>$'.number_format($row['tsum'], 2).'</td>';
                        echo '<td class="text-warning">'.$row['status'].'</td>';
                        echo '<td><a class="confirm_eft py-0 my-0 btn btn-info btn-sm" role="button" data-id="'.$row['transid'].'" data-user="'.$row['acctid'].'" data-type="'.$row['acctid'].'" data-raccno="'.$row['raccno'].'">Confirm</a></td>';
                        echo '<td><a class="decline1 py-0 my-0 btn btn-danger btn-sm" role="button" data-id="'.$row['transid'].'" data-user="'.$row['acctid'].'" data-type="'.$row['acctid'].'">Decline</a></td>';
                      echo '</tr>';
                       
                          }
                        
                    echo '</tbody>
                  </table>
                  ';
                  }
                ?>
                </div>
              </div>
                  
            </div>
          </div>
          <!-- /.row (main row) -->

          <!-- Main row -->
          <div class="row">
              
            <div class="col-12">
              <div class="card rounded-0">
                <div class="card-body">
                   <?php
                       $sql = $db_conn->query("SELECT * FROM transactions WHERE status = 'Pending' AND type = 'Debit' AND mode = 'IFT' OR status = 'Pending' AND type = 'Debit' AND mode = 'IBFT' ORDER BY id DESC");
                        if($sql->num_rows > 0) {
                          echo '
                  <table id="example1" class="table update2 table-sm">
                    <h5>
                      <span class="text-dark px-1">Pending Debits</span>
                      
                    </h5>
                    <thead class="white-text">
                      <tr>
                        <th>#</th>
                        <th>Account Number</th>
                        <th>Date</th>
                        <th>COT code</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody class="bg-white">';
                      
                          while($row = $sql->fetch_assoc()) {
                      echo '<tr>';
                        echo '<td>'.$row['transid'].'</td>';
                        echo '<td>'.$row['acctid'].'</td>';
                        echo '<td>'.$row['regdate'].'</td>';
                        echo '<td>'.$row['cotcode'].'</td>';
                        echo '<td>'.$row['mode'].'</td>';
                        echo '<td>'.number_format($row['tsum'], 2).'</td>';
                        echo '<td>'.$row['status'].'</td>';
                        echo '<td><a class="confirm py-0 my-0 btn btn-info btn-sm" role="button" data-id="'.$row['transid'].'" data-user="'.$row['acctid'].'" data-type="'.$row['acctid'].'">Confirm</a></td>';
                        echo '<td><a class="decline2 py-0 my-0 btn btn-danger btn-sm" role="button" data-id="'.$row['transid'].'" data-user="'.$row['acctid'].'" data-type="'.$row['acctid'].'">Decline</a></td>';
                       echo '</tr>';
                          }
                          echo '
                          </tbody>
                        </table>';
                        }
                        
                      ?>  
                          
                </div>
              </div>
                  
            </div>
          </div>
          <!-- /.row (main row) -->


          <!-- Main row -->
          <div class="row">
              
            <div class="col-12">
               <div class="card rounded-0">
                  <div class="card-body">
                    <?php
                     $sql = $db_conn->query("SELECT * FROM transactions WHERE status = 'completed' AND type = 'Debit' ORDER BY id DESC"); 
                      if($sql->num_rows > 0) {
                        
                          echo '
                    <table id="example2" class="table update3 table-sm">
                      <h5>
                        <span class="text-dark px-1">Completed Debits</span>
                        
                      </h5>
                      <thead class="white-text">
                        <tr>
                          <th>#</th>
                          <th>Account Number</th>
                          <th>Date</th>
                          <th>Type</th>
                          <th>Amount</th>
                          <th>Status</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody class="bg-white">';
                     
                      while($row = $sql->fetch_assoc()) { 
                        echo '<tr>';
                          echo '<td>'.$row['transid'].'</td>';
                          echo '<td>'.$row['acctid'].'</td>';
                          echo '<td>'.$row['regdate'].'</td>';
                          echo '<td>'.$row['mode'].'</td>';
                          echo '<td>$'.number_format($row['tsum'], 2).'</td>';
                          echo '<td>'.$row['status'].'</td>';
                         echo '<td><a class="delete_debit py-0 my-0 btn btn-danger btn-sm" role="button" data-user="'.$row['acctid'].'" data-id="'.$row['transid'].'" data-tsum="'.$row['tsum'].'" data-type="'.$row['type'].'">Delete</a></td>';
                        echo '</tr>';
                        
                            }
                            echo '
                        </tbody>
                          </table>';
                          }
                          
                        ?>  
                            
                  </div>
                </div> 
            </div>
          </div>
          <!-- /.row (main row) -->

          <!-- Main row -->
          <div class="row">
              
            <div class="col-12">
              <div class="card rounded-0">
                <div class="card-body">
                  <?php  
                    $sql = $db_conn->query("SELECT * FROM transactions WHERE status = 'Completed'  AND type = 'Credit' OR status = 'Failed' AND type = 'Credit' ORDER BY id DESC");
                        if($sql->num_rows > 0) {
                          echo '
                  <table id="example3" class="table update4 table-sm table-hover">
                    <h5>
                      <span class="text-dark px-1">Completed Credits</span>
                    </h5>
                    
                    <thead class="white-text">
                      <tr>
                        <th>#</th>
                        <th>Account Number</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody class="bg-white">';
                       
                          while($row = $sql->fetch_assoc()) {
                      echo '<tr>';
                        echo '<td>'.$row['transid'].'</td>';
                        echo '<td>'.$row['acctid'].'</td>';
                        echo '<td>'.$row['regdate'].'</td>';
                        echo '<td>'.$row['type'].'</td>';
                        echo '<td>$'.number_format($row['tsum'], 2).'</td>';
                        echo '<td>'.$row['status'].'</td>';
                        echo '<td><a class="delete_credit py-0 my-0 btn btn-danger btn-sm" role="button" data-user="'.$row['acctid'].'" data-id="'.$row['transid'].'" data-tsum="'.$row['tsum'].'" data-type="'.$row['type'].'">Delete</a></td>';
                      echo '</tr>';
                          }
                          echo '
                          </tbody>
                        </table>';
                        }
                        
                      ?>  
                          
                </div>
              </div>
                  
            </div>
          </div>
          <!-- /.row (main row) -->

        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!--Footer-->
    <footer class="main-footer text-center font-small primary-color-dark darken-2 mt-4 wow fadeIn">
      <!--Copyright-->
      <div class="footer-copyright py-3">
        <div class="pt-2">All Rights Reserved &copy; 2010-<?php echo date("Y"); ?></div>
      </div>
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
  <script src="..dist/js/adminlte.js"></script>
  

  <script>
    $(document).on('click', '.confirm_eft', function () {
      var acctid = $(this).attr('data-user');
      var transid = $(this).attr('data-id');
      var confirm_eft = $(this).attr('data-type');
      var raccno = $(this).attr('data-raccno');
      $.ajax({
        url: 'server.php',
        type: 'POST',
        data: {acctid:acctid,transid:transid,confirm_eft:confirm_eft,raccno:raccno},
        dataType: 'json',
        encode: true,
        success: function(response) {
          if (response.status == 1) {
            $('.update1').load(location.href + " .update1");
            $('.update3').load(location.href + " .update3");
          }
        }
      });
    });

    $(document).on('click', '.confirm', function () {
      var acctid = $(this).attr('data-user');
      var transid = $(this).attr('data-id');
      var confirm_transaction = $(this).attr('data-type');
      $.ajax({
        url: 'server.php',
        type: 'POST',
        data: {acctid:acctid,transid:transid,confirm_transaction:confirm_transaction},
        dataType: 'json',
        encode: true,
        success: function(response) {
          if (response.status == 1) {
            $('.update2').load(location.href + " .update2");
            $('.update3').load(location.href + " .update3");
            $('.update4').load(location.href + " .update4");
          }
        }
      });
    });

    $(document).on('click', '.decline1', function () {
      var acctid = $(this).attr('data-user');
      var transid = $(this).attr('data-id');
      var decline_transaction = $(this).attr('data-type');
      $.ajax({
        url: 'server.php',
        type: 'POST',
        data: {acctid:acctid,transid:transid,decline_transaction:decline_transaction},
        dataType: 'json',
        encode: true,
        success: function(response) {
          if (response.status == 1) {
            $('.update1').load(location.href + " .update1");
            $('.update4').load(location.href + " .update4");
          }
        }
      });
    });

    $(document).on('click', '.decline2', function () {
      var acctid = $(this).attr('data-user');
      var transid = $(this).attr('data-id');
      var decline_transaction = $(this).attr('data-type');
      $.ajax({
        url: 'server.php',
        type: 'POST',
        data: {acctid:acctid,transid:transid,decline_transaction:decline_transaction},
        dataType: 'json',
        encode: true,
        success: function(response) {
          if (response.status == 1) {
            $('.update2').load(location.href + " .update2");
            $('.update4').load(location.href + " .update4");
          }
        }
      });
    });

    $("#transferBankForm").on('submit', function(e){
      e.preventDefault();
      $.ajax({
        url: 'server.php',
        type: 'post',
        data: $(this).serialize(),
        dataType: 'json',
        encode: true,
        beforeSend: function() {
          $('#retransferbtn').html('<span class="spinner-border spinner-border-sm float-right" role="status" aria-hidden="true"></span> Processing..');
        },
        success: function(response) {
          if (response.status == 1) {
            window.location.href = "transactions";
          }
          else {
            $('#retransferbtn').html('Confirm');
            $('#error').html('The amount you have entered is invalid or too large! Try entering a smaller amount.');
          }
        }
      });
      
    });

    $("#transferFNMForm").on('submit', function(e){
      e.preventDefault();
      $.ajax({
        url: 'server.php',
        type: 'post',
        data: $(this).serialize(),
        dataType: 'json',
        encode: true,
        beforeSend: function() {
          $('#othertransferbtn').html('<span class="spinner-border spinner-border-sm float-right" role="status" aria-hidden="true"></span> Processing..');
        },
        success: function(response) {
          if (response.status == 1) {
            window.location.href = "transactions";
          }
          else {
            $('#othertransferbtn').html('Confirm');
            $('#error1').html('The amount you have entered is invalid or too large! Try entering a smaller amount.');
          }
        }
      });
      
    });

    $("#transferWireForm").on('submit', function(e){
      e.preventDefault();
      $.ajax({
        url: 'server.php',
        type: 'post',
        data: $(this).serialize(),
        dataType: 'json',
        encode: true,
        beforeSend: function() {
          $('#inttransferbtn').html('<span class="spinner-border spinner-border-sm float-right" role="status" aria-hidden="true"></span> Processing...');
        },
        success: function(response) {
          if (response.status == 1) {
            window.location.href = "transactions";
          }
          else {
            $('#inttransferbtn').html('Confirm');
            $('#error2').html('The amount you have entered is invalid or too large! Try entering a smaller amount.');
          }
        }
      });
      
    });

    $(document).on('click', '.delete_debit', function () {
      var acctid = $(this).attr('data-user');
      var transid = $(this).attr('data-id');
      var tsum = $(this).attr('data-tsum');
      var type = $(this).attr('data-type');
      var delete_debit = $(this).attr('data-type');
      $.ajax({
        url: 'server.php',
        type: 'POST',
        data: {acctid:acctid,transid:transid,type:type,delete_debit:delete_debit},
        dataType: 'json',
        encode: true,
        success: function(response) {
          if (response.status == 1) {
            $('.update3').load(location.href + " .update3");
          }
        }
      });
    });

    $(document).on('click', '.delete_credit', function () {
      var acctid = $(this).attr('data-user');
      var transid = $(this).attr('data-id');
      var tsum = $(this).attr('data-tsum');
      var type = $(this).attr('data-type');
      var delete_credit = $(this).attr('data-type');
      $.ajax({
        url: 'server.php',
        type: 'POST',
        data: {acctid:acctid,transid:transid,type:type,delete_credit:delete_credit},
        dataType: 'json',
        encode: true,
        success: function(response) {
          if (response.status == 1) {
            $('.update4').load(location.href + " .update4");
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

</body>
</html>