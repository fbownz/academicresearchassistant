<!DOCTYPE html>
<html>
<head>
<?
  $page_title = '404 Page Not Found';
  $site_title = 'Academic Research Assistants'
?>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>404 Page Not Found | Academic Research Assistants</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="/css/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  
  <!-- Theme style -->
  <link rel="stylesheet" href="/css/AdminLTE.min.css">
  <link rel="stylesheet" href="/css/skins/skin-green.min.css">

     

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="sidebar-collapse skin-green sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="http://academicresearchassistants.com/" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b>RA</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>A</b>RA</span>
    </a>

    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
      </div>
    </nav>
  </header>
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="margin-left: 0px">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        404! Page Not Found
        <small>Sorry but I can't seem to find what you are looking for</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/dashboard"><i class="fa fa-home"></i> Home</a></li>
        <li><i class="fa fa-flag-o"> </i>Error</li>
        <li class="active">{{$page_title}}</li>
      </ol>
    </section>
    <section class="content">
          <div class="error-page" style="width:733px">
            <div class="error-content" style="margin-left:200px">

                  <form action="/dashboard/q" method="get" class="search-form" role="q">
                    <div class="input-group">
                      <input type="text" name="q" class="form-control" placeholder="Sorry you couldn't find anyone. Enter their order number again...">
                          <div class="input-group-btn">
                            <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                            </button>
                          </div>
                    </div>
                  </form>
              <div class="box-body col-md-4">
                  <img class="img-responsive pad" src="/assets/public/imgs/alien.png">
              </div>
                <div class="box-body col-md-8">
                  <img class="img-responsive pad" src="/assets/public/imgs/aliencallout.png">
                </div>
              </div>
            <!-- /.error-content -->
            

            
          </div>
          <!-- /.error-page -->
    </section>
    </div>
<!-- ./wrapper -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      <strong>Version </strong>am also confused
    </div>
    <!-- Default to the left -->
    By your continued use of our website means you accept our <a href="/terms">Terms</a> and <a href="/fines-policy">Fines Policy</a><br>
    <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="/">{{$site_title}}</a>.</strong> All rights reserved.
  </footer>
  <!-- Main Footer -->
<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.1.4 -->
<script src="/css/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Moments required to run the datepicker plugin -->
@if (strpos($_SERVER['REQUEST_URI'], "new") !== false)
<script src="/css/plugins/moment/moment.js"></script>
@endif
@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false)
<script src="/css/plugins/moment/moment.js"></script>
@endif
<!-- Bootstrap 3.3.5 -->
<script src="/css/bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="/css/js/app.min.js"></script>
@if (strpos($_SERVER['REQUEST_URI'], "orders") !== false) 

<script src="/css/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/css/plugins/datatables/dataTables.bootstrap.min.js"></script>
@elseif (strpos($_SERVER['REQUEST_URI'], "mailbox") !== false)
<script src="/css/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/css/plugins/datatables/dataTables.bootstrap.min.js"></script>  
@endif
@if(strpos($_SERVER['REQUEST_URI'], "update_user") !== false)
<script src="/css/plugins/select2/select2.full.min.js"></script>
  <script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2(); 
  </script>
@endif
@if (strpos($_SERVER['REQUEST_URI'], "new") !== false) 
        <!--<script src="/css/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>-->
        <script src="/css/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
        <script>
  $(function () {

    //bootstrap WYSIHTML5 - text editor
    $(".textarea").wysihtml5();

    //$('#deadline_').datetimepicker();
  });
</script>
@endif
@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
        <!--<script src="/css/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>-->
        <script src="/css/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
        <script>
  $(function () {

    //bootstrap WYSIHTML5 - text editor
    $(".textarea").wysihtml5();

    //$('#deadline_').datetimepicker();
  });
</script>
@endif

@if(strpos($_SERVER['REQUEST_URI'], "update_user") !== false)
<script src="../../plugins/select2/select2.full.min.js"></script>
  <script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2(); 
  </script>
@endif

  @if (strpos($_SERVER['REQUEST_URI'], "orders") !== false) 
  <script>
  $(document).ready (function () {
    $('#all_orders_table').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": false,
      "autoWidth": false,
    });
  });
  $(document).ready (function () {
    $('#active_orders_table').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": false,
      "autoWidth": false,
    });
  });
  $(document).ready (function () {
    $('#u_bids').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": false,
      "autoWidth": false,
    });
  });
</script>
  @elseif (strpos($_SERVER['REQUEST_URI'], "mailbox") !== false)
  <script>
  $(document).ready( function () {
    $('#unread_table').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": true,
    });
    $('#mailbox_table').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": false,
          "info": true,
          "autoWidth": true,
    });
} );
</script>
@elseif(strpos($_SERVER['REQUEST_URI'], "readmail") !== false || strpos($_SERVER['REQUEST_URI'], "compose") !== false )
<script src="/css/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Page Script -->
<script>
  $(function () {
    //Add text editor
    $("#compose-textarea").wysihtml5();
  });
</script>
  @endif      

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>

    
 