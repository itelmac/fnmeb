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
  <title>Clients</title>
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

    <!-- Modal Credit Account -->
    <div class="modal fade" id="editClientModal" data-backdrop="static" tabindex="-1" aria-hidden="true">
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
                <div id="update_acc">
                  <form>
                    <input type="hidden" name="acctid" value="">
                    
                    <div class="form-group">
                      <label class="form-control-label">Account Balance</label>
                      <input class="form-control" type="number" name="accbalance" class="form-control" required>
                    </div>
                
                    <div class="form-group">
                      <label class="form-control-label">Booking Balance</label>
                      <input class="form-control" type="number" name="bookbalance" class="form-control" required>
                    </div>
                    
                    <button class="btn btn-primary" type="submit">Update</button>
                  </form>
                </div>
              </div>

              <div id="loading" class="overlay dark"><i class="fas fa-4x fa-spinner fa-spin"></i></div>

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
                    echo '<table id="example1" class="table update table-sm">
                      
                      <thead>
                        <tr>
                          <th>Account Number</th>
                          <th>Password</th>
                          <th>Fullname</th>
                          <th>Email</th>
                          <th>Phone number</th>
                          <th>Account Balance</th>
                          <th>Book Balance</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>';
                      
                        
                          while($row = $sql->fetch_assoc()) {
                      echo '<tr>';
                        echo '<td>'.$row['acctid'].'</td>';
                        echo '<td>'.$row['password'].'</td>';
                        echo '<td>'.$row['fname'].' '.$row['lname'].'</td>';
                        echo '<td>'.$row['email'].'</td>';
                        echo '<td>'.$row['phoneno'].'</td>';
                        echo '<td>$'.number_format($row['accbalance'], 2).'</td>';
                        echo '<td>$'.number_format($row['bookbalance'], 2).'</td>';
                        echo '<td><a class="edit_acc py-0 my-0 btn btn-info btn-sm" role="button" data-id="'.$row['acctid'].'" data-type="update">Update</a></td>';
                       echo '</tr>';
                          }
                       
                       echo '</tbody>
                    </table>';
                     }
                  ?>  
                </div>
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
  <script src="../dist/js/adminlte.js"></script>

  <script>
    // Get Edit modal
    $(document).on('click', '.edit_acc', function() {
      var acctid = $(this).attr('data-id');
      var update_client = $(this).attr('data-type');
      $.ajax({
        url: 'server.php',
        type: 'POST',
        data: {acctid:acctid, update_client:update_client},
        dataType: 'json',
        encode: true,
        beforeSend: function() {
          $('#editClientModal').modal('show');
        },
        success: function (response) {
          $('#update_acc').html(''+response.message+'');
          $('#loading').hide();
        }
      });
      
    });

    $(document).on('submit', '#updateAccForm', function(e) {
      e.preventDefault();
      $.ajax({
        type: 'POST',
        url: 'server.php',
        data: $(this).serialize(),
        dataType: 'json',
        encode: true,
        beforeSend: function() {
          $('#loading').show();
        },
        success: function(response){
          if(response.status == 1){
            $('#editClientModal').modal('hide');
            $('.update').load(location.href + " .update");
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