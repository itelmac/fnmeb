
<?php
session_start();
if (!$_SESSION['adminid']) {
  header('Location:login');
}
include('../database/dbconfig.php');
$adminid = $_SESSION['adminid'];
$mysqli = $db_conn->query("SELECT * FROM admin WHERE adminid = '$adminid'");
$data = $mysqli->fetch_assoc();
$unique_id1 = $data['unique_id'];
if (isset($_GET['acctid'])) {
    $acctid = $_GET['acctid'];
    $mysqliot = $db_conn->query("SELECT * FROM accts WHERE acctid = '$acctid'");
    if($mysqliot) {
        $lio = $mysqliot->fetch_assoc();
        $name = $lio['fname'].' '.$lio['lname'];
    }
} else {
    $acctid = "";
    $name = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Live Chat</title>
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

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          
          
            <!-- Main row -->
            <div class="row p-5">
                
                    <?php 
                    if (isset($_GET['user_chat_id'])) {
                      $userid = $_GET['user_chat_id'];
                      $output = '
                        <div class="col-md-3">
                            <div class="card card-warning card-outline direct-chat direct-chat-primary">
                              <div class="card-header">
                                <h3 class="card-title">Online Customers</h3>

                                <div class="card-tools">
                                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                  </button>
                                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                  </button>
                                </div>
                              </div>
                              <!-- /.card-header -->
                              <div class="card-body p-0">
                                <ul id="direct-chat-messages-list" class="nav nav-pills flex-column">
                                </ul>
                              </div>
                              <!-- /.card-body -->
                              <div class="card-footer">
                                
                              </div>
                              <!-- /.card-footer-->
                              
                            </div>
                            
                        </div>

                        <div class="col-md-9">
                          <!-- DIRECT CHAT PRIMARY -->
                          <div class="card card-primary card-outline direct-chat direct-chat-primary">
                            <div class="card-header">
                              <h3 class="card-title">Direct Chat</h3>

                              <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                  <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                  <i class="fas fa-times"></i>
                                </button>
                              </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                              <div id="direct-chat-messages" class="direct-chat-messages">
                                
                              </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                              <form class="live-chat-form" id="live-chat-form">
                                <div class="input-group">
                                  <input type="hidden" name="send_msg">
                                  <input type="hidden" class="name" name="name" value="Julia">
                                  <input class="unique_id" type="hidden" name="unique_id" value="'.$unique_id1.'">
                                  <input class="incoming_id" type="hidden" name="incoming_id" value="'.$userid.'">
                                  
                                  <textarea class="form-control" id="new-message" name="message" placeholder="Type Message ..." rows="2"autocomplete="off"></textarea>
                                  
                                  <div class="input-group d-flex justify-content-end py-2">
                                      <button type="submit" id="new-message-btn" class="btn btn-primary rounded-0 border-0"><i class="fab fa-telegram-plane"></i></button>
                                  </div>
                                </div>
                              </form>
                            </div>
                            <!-- /.card-footer-->
                          </div>
                          <!--/.direct-chat -->
                        </div>
                      ';
                        
                    }
                    else {
                      $output = '
                        <div class="col-md-3">
                          <div class="card card-warning card-outline direct-chat direct-chat-primary">
                            <div class="card-header">
                              <h3 class="card-title">Online Customers</h3>

                              <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                  <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                  <i class="fas fa-times"></i>
                                </button>
                              </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                              <ul id="direct-chat-messages-list" class="nav nav-pills flex-column">
                              </ul>
                            </div>
                            <!-- /.card-body -->
                            
                          </div>
                          
                        </div>

                        <div class="col-md-9">
                          <!-- DIRECT CHAT PRIMARY -->
                          <div class="card card-primary card-outline direct-chat direct-chat-primary">
                            <div class="card-header">
                              <h3 class="card-title">Direct Chat</h3>

                              <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                  <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                  <i class="fas fa-times"></i>
                                </button>
                              </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                              <div class="direct-chat-messages">
                                
                              </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                              <form class="live-chat-form" id="live-chat-form">
                                <div class="input-group">
                                  <input type="hidden" name="send_msg">
                                  <input type="hidden" class="name" name="name" value="Julia">
                                  <textarea class="form-control" id="new-message" name="message" placeholder="Type Message ..." rows="2"autocomplete="off"></textarea>
                                  
                                  <div class="input-group d-flex justify-content-end py-2">
                                      <button type="submit" id="new-message-btn" class="btn btn-primary rounded-0 border-0"><i class="fab fa-telegram-plane"></i></button>
                                  </div>
                                </div>
                              </form>
                            </div>
                            <!-- /.card-footer-->
                            <div id="loading1" class="overlay dark"><i class="fas fa-4x fa-spinner fa-spin"></i></div>
                          </div>
                          <!--/.direct-chat -->
                        </div>
                      ';                      
                    }
                    echo $output;
                    ?> 
                <!-- /.col -->
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
  <script src="dist/js/adminlte.js"></script>
    <!-- Page specific script -->
    <script>
        $(function () {
            //Add text editor
            $('#compose-textarea').summernote()
        });

        $(document).ready(function() {
            document.body.innerHTML = number.toLocaleString();
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

        $(document).on('click','#inbox-msg-btn', function(e) {
            e.preventDefault();
            var inbox_msg = $('.acctid').val();
            var acctid = $('.acctid').val();
            var subject = $('.inbox-msg-subject').val();
            var message = $('.inbox-msg').val();
            $.ajax({
                url: 'inbox.php',
                type: 'POST',
                data: {inbox_msg:inbox_msg, acctid:acctid, subject:subject, message:message},
                 dataType: 'json',
                encode: true,
                beforeSend: function() {
                    $('#inbox-msg-btn').html('<i class="fa fa-spinner fa-spin"></i> Send')
                },
                success: function(response) {
                    if(response.status == '1') {
                        $('.inbox-msg-form')[0].reset();
                        $('#inbox-msg-btn').html('<i class="fas fa-check-circle"></i> Sent');
                    }
                }
            });
        });

        setInterval(function(){
            
        }, 200);

        get_users();
        get_msgs();
        get_chat_list();
        get_request();
        get_request_list();
        scrollToBottom();

        function get_users() {
            $.ajax({
                url: 'get-users.php',
                type: 'POST',
                success: function(data) {
                    $('#get-users').html(data);
                }
            });
        }

        function get_msgs() {
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

        function get_chat_list() {
            $.ajax({
                url: 'get-chat-list.php',
                type: 'POST',
                success: function(data) {
                    $('#direct-chat-messages-list').html(data);
                }
            });
        }

        function get_request() {
            var request_id = $('#request_id').val();
            $.ajax({
                url: 'get-request.php',
                type: 'POST',
                data: {request_id:request_id},
                success: function(data) {
                    $('#direct-request-messages').html(data);
                }
            });
        }

        function get_request_list() {
            var unique_id = $('.unique_id').val();
            var incoming_id = $('.incoming_id').val();
            $.ajax({
                url: 'get-request-list.php',
                type: 'POST',
                success: function(data) {
                    $('#direct-request-messages-list').html(data);
                }
            });
        }

        function scrollToBottom(){
            $('#direct-chat-messages').stop().animate({
                scrollTop: $('#direct-chat-messages')[0].scrollHeight
            }, 800);
        }

    </script>

</body>
</html>