<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{$page_title}} | {{$site_title}}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="/css/bootstrap/css/bootstrap.min.css">
  @if (strpos($_SERVER['REQUEST_URI'], "orders") !== false ||strpos($_SERVER['REQUEST_URI'], "mailbox") !== false )
  <!-- datattables css -->
  <link rel="stylesheet" href="/css/plugins/datatables/datatables.bootstrap.css">
  @endif
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  
  <!-- Theme style -->
  <link rel="stylesheet" href="/css/AdminLTE.min.css">
  <link rel="stylesheet" href="/css/skins/skin-green.min.css">

      @if (strpos($_SERVER['REQUEST_URI'], "new") !== false)
        <!--<link rel="stylesheet" type="text/css" href="/css/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">-->
        <link rel="stylesheet" href="/css/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
        @elseif(strpos($_SERVER['REQUEST_URI'], "readmail") !== false)
        <link rel="stylesheet" href="/css/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
        @endif
        @if (strpos($_SERVER['REQUEST_URI'], "edit") !== false)
        <link rel="stylesheet" type="text/css" href="/css/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
        <link rel="stylesheet" href="/css/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
        @endif

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b>RA</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>A</b>RA</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!--I removed the Messages Menu-->

          <!-- Notifications Menu -->
          <li class="dropdown notifications-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">{{$notifications_no}}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have {{$notifications_no}} notifications</li>
              <li>
                <!-- Inner Menu: contains the notifications -->
                <ul class="menu">
                  <li><!-- start notification -->
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> {{$list_notifications}}
                    </a>
                  </li>
                  <!-- end notification -->
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>
          <!-- Tasks Menu -->
          <li class="dropdown tasks-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">{{$number_tasks}}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">{{$number_tasks}} Tasks</li>
              <li>
                <!-- Inner menu: contains the tasks -->
                <ul class="menu">
                  <li><!-- Task item -->
                    <a href="#">
                      <!-- Task title and progress text -->
                      <h3>
                        {{$list_tasks}}
                        <small class="pull-right">{{$number_tasks}}%</small>
                      </h3>
                      <!-- The progress bar -->
                      <div class="progress xs">
                        <!-- Change the css width attribute to simulate progress -->
                        <div class="progress-bar progress-bar-aqua" style="width: {{$number_tasks}}%" role="progressbar" aria-valuenow="{{$number_tasks}}" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">{{$number_tasks}}% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="{{Auth::user()->prof_pic}}" class="user-image" alt="{{Auth::user()->first_name}} Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">{{Auth::user()->first_name }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="{{Auth::user()->prof_pic}}" class="img-circle" alt="{{Auth::user()->first_name}}">

                <p>
                  {{Auth::user()->first_name}} - {{$user_description}}
                  <small>Member since {{$join_date_text}}</small>
                </p>
              </li>
              <!-- Menu Body -->
              <!-- <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div> -->
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="/logout" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{Auth::user()->prof_pic}}" class="img-circle" alt="{{Auth::user()->first_name}} Avi">
        </div>
        <div class="pull-left info">
          <p>{{Auth::user()->first_name}}</p>
          <!-- Status -->
          <!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a> -->
        </div>
      </div>

      <!-- search form (Optional) -->
      <form action="/dashboard/q" method="get" class="sidebar-form" role="q">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search Enter Order ID...">
              <span class="input-group-btn">
                <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">MENU NAVIGATION</li>
        <!-- Optionally, you can add icons to the links -->
        <li class=" 
              @if ($page_title == 'Dashboard')
              active
              @endif">
          <a href="/dashboard">
            <i class="fa fa-dashboard"></i> 
            <span>Dashboard</span>
          </a>
          </li>
        <li class=" treeview
            @if (strpos($_SERVER['REQUEST_URI'], "order") !== false) 
            active
            @endif">
            <a href="#">
              <i class="fa fa-tasks"></i>
              <span>Orders</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li class="
              @if (strpos($_SERVER['REQUEST_URI'], "orders") !== false) 
              active
              @endif">
              <a href="/orders"><i class="fa fa-circle-o"></i>All Orders</a></li>
              @if(Auth::user()->ni_admin)
              <li class="
              @if (strpos($_SERVER['REQUEST_URI'], "new_order") !== false) 
              active
              @endif">
              <a href="/new_order"><i class="fa fa-circle-o"></i>Add new Order</a></li>
              <li class="
              @if (strpos($_SERVER['REQUEST_URI'], "order_bids") !== false) 
              active
              @endif">
              <a href="/order_bids "><i class="fa fa-circle-o"></i>View All Bids</a></li>
              @endif
            </ul>
        </li>
        <li class="
        @if ($page_title == 'Earnings')
        active
        @endif
        "><a href="/earnings"><i class="fa fa-money"></i> <span>My Earnings</span></a></li>
        <li class="treeview 
        @if ($page_title =="Mailbox" || $page_title =="Read Mail" || $page_title =="Sentbox")
         active
        @endif
        ">
          <a http="#">
            <i class="fa fa-envelope"></i> 
            <span>Mailbox</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <?
          $unread_messages = Auth::user()->messages->where('unread' ,'1')->count();
          ?>
          <ul class="treeview-menu">
            <li class="
            @if ($page_title =="Mailbox")
            active
            @elseif($page_title=="Read Mail")
            active
            @endif">
            <a href="/mailbox">Inbox <span class="label label-primary pull-right">{{$unread_messages}}</span></a>
            </li>
            <!-- <li><a href="/compose">Compose</a></li> -->
            <!-- <li><a href="/read-mail">Read</a></li> -->
          </ul>
        </li>
        <li><a href="#"><i class="fa fa-gears"></i> <span>Settings</span></a></li>
        <li><a href="#"><i class="fa fa-question"></i> <span>Help</span></a></li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{$page_title}}
        <small>{{$page_description}}</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/dashboard"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">{{$page_title}}</li>
      </ol>
    </section>

    @yield('body')

</div>
<!-- ./wrapper -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      <strong>Version </strong>{{$version_no}}
    </div>
    <!-- Default to the left -->
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

  @if (strpos($_SERVER['REQUEST_URI'], "orders") !== false) 
  <script>
  $(document).ready (function () {
    $('#all_orders_table').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
    });
  });
</script>
  @elseif (strpos($_SERVER['REQUEST_URI'], "mailbox") !== false)
  <script>
  $(document).ready( function () {
    $('#mailbox_table').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
    });
} );
</script>
@elseif(strpos($_SERVER['REQUEST_URI'], "readmail") !== false)
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
