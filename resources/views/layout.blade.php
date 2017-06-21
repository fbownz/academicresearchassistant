<!DOCTYPE html>
<html>
<head>
  <!--   Hello there...
  @fbownz
  Peace...
  -->


  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{$page_title}} | {{$site_title}}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="/css/bootstrap/css/bootstrap.min.css">
  @if (strpos($_SERVER['REQUEST_URI'], "users") !== false || strpos($_SERVER['REQUEST_URI'], "mailbox") !== false || strpos($_SERVER['REQUEST_URI'], "u_bids") !== false || strpos($_SERVER['REQUEST_URI'], "earnings") !== false || strpos($_SERVER['REQUEST_URI'], "find_work") !== false)
  <!-- datattables css -->
  <link rel="stylesheet" href="/css/plugins/datatables/dataTables.bootstrap.css">
  @endif
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="/css/AdminLTE.min.css">
  <link rel="stylesheet" href="/css/skins/skin-green.min.css">

      @if (strpos($_SERVER['REQUEST_URI'], "new") !== false || strpos($_SERVER['REQUEST_URI'], "orders") !== false)
        <!--<link rel="stylesheet" type="text/css" href="/css/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">-->
        <link rel="stylesheet" href="/css/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
        @elseif(strpos($_SERVER['REQUEST_URI'], "readmail") !== false || strpos($_SERVER['REQUEST_URI'], "compose") !== false )
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

  <!--[if lt IE 8]>
			<div class="outdated-browser-tip">You are using an <strong>outdated</strong> browser. Please <a href="http://www.microsoft.com/download/internet-explorer.aspx">upgrade your browser</a> to improve your experience.</div>
	<![endif]-->
</head>

<?
use App\User;
?>

<body class="hold-transition skin-green sidebar-mini">
<!-- wrapper -->
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

      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- inbox menu -->
            <li class="dropdown messages-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-envelope-o"></i>
                @if($messages_no)
                <span class="label label-info">{{$messages_no}}</span>
                @endif
              </a>
              <ul class="dropdown-menu">
                <li class="header">You have {{$messages_no}} new messages</li>
                @if($messages_no)
                <li>
                  <!-- inner menu: contains the actual data -->
                  <ul class="menu">
                    @foreach($list_messages as $list_message)
                    <? $sender= User::find($list_message->sender_id) ?>
                    <li><!-- start message -->
                      <a href="/readmail/{{$list_message->id}}#m{{$list_message->id}}">
                        <div class="pull-left">
                          <img src="{{$sender->prof_pic_url}}" class="img-circle" alt="Sender Image">
                        </div>
                        <h4>
                          @if($sender->ni_admin)
                            @if ($list_message->department == 'Support')
                               Support
                            @elseif ($list_message->department == 'Quality Assurance')
                                Quality Assurance
                            @elseif ($list_message->department == 'Billing')
                                Billing
                            @endif
                          @else
                          {{$sender->first_name}}
                          @endif
                          <small><i class="fa fa-clock-o"></i>{{$list_message->created_at->format('M j, H:i A')}}</small>
                        </h4>
                        <p>{{$list_message->subject}}</p>
                      </a>
                    </li>
                    <!-- end message -->
                    @endforeach
                  </ul>
                </li>
                <li class="footer"><a href="/mailbox">See All Messages</a></li>
                @endif
              </ul>
            </li>

            <!-- We start order messages notifications here -->
            <li class="dropdown notifications-menu">
              <!-- Menu toggle button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-comments-o"></i>
                <span class="label label-primary">@if($order_msg_no){{$order_msg_no}}@endif</span>
              </a>
              <!-- Notifications Menu -->

              <ul class="dropdown-menu">
                <li class="header">You have {{$order_msg_no}} new Order messages</li>
                <li>
                  <!-- Inner Menu: contains the notifications -->
                  @if($order_msg_no)
                  <ul class="menu">
                    @foreach($list_order_message as $notification)

                    <!-- start notification -->
                    <li>
                      <a href="/orders/{{$notification->order_id}}/notifications/{{$notification->id}}#order-message">
                        @if(count($notification->order)>0)
                          <?
                            $icon = "fa-comments";
                          ?>
                        @else
                          <?
                            $icon = "fa-warning";
                          ?>
                        @endif
                        <i class="fa {{$icon}} text-aqua"></i>

                       @if($notification->type == 'order_message')
                         @if(count($notification->order) > 0)
                          New message on #{{$notification->order->order_no}}
                         @else
                          No order found
                         @endif

                         <!-- start of Admin Notification Messages -->
                       @elseif($notification->type == 'admin_order_message')
                         @if(count($notification->order) > 0)
                          New message on #{{$notification->order->order_no}}
                         @else
                          No order found
                         @endif
                         <!-- end of Admin Notification Messages -->
                       @endif
                      </a>
                    </li>
                    <!-- end notification -->
                    @endforeach
                  </ul>
                  @endif
                </li>
                <!-- <li class="footer"><a href="#">View all</a></li> -->
              </ul>
            </li>

            <!-- Start of normal notifications -->
            <li class="dropdown notifications-menu">
              <!-- Menu toggle button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bell-o" aria-hidden="true"></i>
                <span class="label label-warning">@if($notifications_no){{$notifications_no}}@endif</span>
              </a>
              <!-- Notifications Menu -->

              <ul class="dropdown-menu">
                <li class="header">You have {{$notifications_no}} notifications</li>
                <li>
                  <!-- Inner Menu: contains the notifications -->
                  @if($notifications_no)
                  <ul class="menu">
                    @foreach($list_bid_accepted as $notification)

                    <li><!-- start notification -->
                      <a href="/orders/{{$notification->order_id}}/notifications/{{$notification->id}}#@if($notification->type == 'admin_order_message')order-message @elseif($notification->type == 'order_message')order-message @endif">
                        @if($notification->type == 'order_bid_accepted')
                        <? $icon = "fa-thumbs-up" ?>
                        @elseif($notification->type == 'order_message')
                        <? $icon = "fa-comments" ?>

                        <!-- Start of Admin icons -->
                        @elseif($notification->type == 'admin_order_bidPlaced')
                        <? $icon = "fa-hourglass-half" ?>
                        @elseif($notification->type == 'admin_order_message')
                        <? $icon = "fa-commenting-o" ?>
                        <!-- end of Admin notification icons -->

                        @endif
                        <i class="fa {{$icon}} text-aqua"></i>

                       @if($notification->type == 'order_bid_accepted')
                         @if(count($notification->order) > 0)
                         Congrats your Bid for order #{{$notification->order->order_no}} was accepted !
                         @else
                         No order found was it deleted?
                         @endif
                       @elseif($notification->type == 'order_message')
                         @if(count($notification->order) > 0)
                         You have a new message on order #{{$notification->order->order_no}} !
                         @else
                         No order found was it deleted ?
                         @endif

                         <!-- start of Admin Notification Messages -->
                       @elseif($notification->type == 'admin_order_message')
                         @if(count($notification->order) > 0)
                         You have a new message on order #{{$notification->order->order_no}} !
                         @else
                         No order found was it deleted ?
                         @endif
                       @elseif($notification->type == 'admin_order_bidPlaced')
                         @if(count($notification->order) > 0)
                         {{$notification->user->first_name}} placed a new bid on Order #{{$notification->order->order_no}} !
                         @else
                         No order found was it deleted ?
                         @endif
                         <!-- start of Admin Notification Messages -->
                       @endif
                      </a>
                    </li>
                    <!-- end notification -->
                    @endforeach
                  </ul>
                  @endif
                </li>
                <!-- <li class="footer"><a href="#">View all</a></li> -->
              </ul>
            </li>

            <!-- Tasks Menu -->
            <li class="dropdown tasks-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-flag-o"></i>
                <span class="label label-danger">@if($number_tasks > 0){{$number_tasks}}@endif</span>
              </a>

              <ul class="dropdown-menu">
                <li class="header">You have {{$number_tasks}} new Tasks</li>
                <li>
                   @if($number_tasks > 0)
                  <!-- Inner menu: contains the tasks -->
                  <ul class="menu">
                    @if($list_tasks->count() >0)
                    @foreach($list_tasks as $list_task)

                    <li><!-- Task item -->
                      <a href="/orders/{{$list_task->order_id}}/notifications/{{$list_task->id}}">
                        <!-- Task title and progress text -->
                        <h3>
                        <!--
                          We find the order that the notification belongs to. then we calculate the total number of notifications the order has
                          From there we find  the total number of that type of notification, then we divide by total notifications and we find the
                          percentage
                        -->
                        <?

                          $order_task = $list_task->order;
                          if ($order_task) {
                            $percentage_no = $list_task->order->notifications->where('status',0)->where('type', $list_task->type)->count() / $list_task->order->notifications->where('status',0)->count() * 100;
                          }
                        ?>
                          
                          <small class="pull-right">{{round($percentage_no)}}%</small>

                          @if($list_task->type == 'order_revision')
                          New Revision request on order #{{$list_task->order->order_no}}
                          @endif
                          @if($list_task->type == 'order_late')
                          Late Order #{{$list_task->order->order_no}}
                          @endif


                          <!-- Start of Admin late order notifications -->

                          @if($list_task->type == 'admin_order_late')
                          Order #{{$list_task->order->order_no}} is Late
                          @endif


                        </h3>
                       
                        <!-- The progress bar -->
                        <div class="progress xs">
                          <!-- Change the css width attribute to simulate progress -->
                          <div class="progress-bar progress-bar-aqua" style="width: {{round($percentage_no)}}%" role="progressbar" aria-valuenow="{{round($percentage_no)}}" aria-valuemin="0" aria-valuemax="100">
                            <span class="sr-only">{{round($percentage_no)}}% Complete</span>
                          </div>
                        </div>
                      </a>
                    </li>
                    @endforeach
                    @endif
                  </ul>
                  @endif
                </li>
                <li class="footer">
                  <!-- <a href="#">View all tasks</a> -->
                </li>
              </ul>

            </li>
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                <img src="{{Auth::user()->prof_pic_url}}" class="user-image" alt="{{Auth::user()->first_name}} Image">
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs">{{Auth::user()->first_name }}</span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                  <img src="{{Auth::user()->prof_pic_url}}" class="img-circle" alt="{{Auth::user()->first_name}}">

                  <p>
                    {{Auth::user()->first_name}} <br>
                    <small>Member since {{Auth::user()->created_at->format('F, Y')}}</small>
                  </p>
                </li>
                <!-- Menu Body -->
                <li class="user-body">
                  <div class="row">
                    <div class="col-xs-4 text-center">
                      <a href="/earnings">Earnings</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="/orders">Orders</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="/mailbox">Inbox</a>
                    </div>
                  </div>
                  <!-- /.row -->
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="/user/profile" class="btn btn-default btn-flat">Profile</a>
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
          <img src="{{Auth::user()->prof_pic_url}}" class="img-circle" alt="{{Auth::user()->first_name}} Avi">
        </div>
        <div class="pull-left info">
          <p>{{Auth::user()->first_name}}</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
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
              @if (strpos($_SERVER['REQUEST_URI'], "find_work") !== false)
              active
              @endif">
              <a href="/find_work"><i class="fa fa-circle-o"></i>Find Work</a></li>
              <li class=class="
              @if (strpos($_SERVER['REQUEST_URI'], "orders") !== false)
              active
              @endif">
                <a href="/orders/"><i class="fa fa-circle"></i>All Orders</a>
              </li>
              @if(Auth::user()->ni_admin)
              <li class="
              @if (strpos($_SERVER['REQUEST_URI'], "new_order") !== false)
              active
              @endif">
              <a href="/new_order"><i class="fa fa-plus-circle"></i>Add new Order</a></li>
              <li class="
              @if (strpos($_SERVER['REQUEST_URI'], "order_bids") !== false)
              active
              @endif">
              <a href="/order_bids "><i class="fa fa-circle-o"></i>View All Bids</a></li>
              @endif
              <li class="
              @if (strpos($_SERVER['REQUEST_URI'], "u_bids") !== false)
              active
              @endif
              @if (Auth::user()->ni_admin)
              hidden
              @endif">
              <a href="/u_bids "><i class="fa fa-circle-o"></i>Bids</a></li>

            </ul>
        </li>
        <li class="
        @if ($page_title == 'Earnings')
        active
        @endif
        "><a href="/earnings"><i class="fa fa-money"></i> <span>My Earnings</span></a></li>
        <li class="
        @if (Auth::user()->ni_admin != '1')
        hidden
        @endif
        @if ($page_title == 'Users')
        active
        @endif
        "><a href="/users"><i class="fa fa-users"></i> <span>Users</span></a></li>
        <li class="treeview
        @if ($page_title =="Mailbox" || $page_title =="Read Mail" || $page_title =="Sentbox" || $page_title =="Compose")
         active
        @endif
        ">
          <a http="#">
            <i class="fa fa-envelope"></i>
            <span>Mailbox</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <?
          $unread_messages = Auth::user()->messages->where('unread' ,1)->count();
          ?>
          <ul class="treeview-menu">
            <li class="
            @if ($page_title =="Mailbox")
            active
            @elseif($page_title=="Read Mail")
            active
            @endif">
            <a href="/mailbox">Inbox <span class="label label-primary pull-right">{{$messages_no}}</span></a>
            </li>
           <li @if ($page_title == 'Compose')
           class="active"
           @endif
           ><a href="/compose">Compose</a></li>
            <li @if ($page_title == 'Compose')
           class="active"
           @endif
           ><a href="/sentbox">sent</a></li>
          </ul>
        </li>
        <li @if ($page_title == 'User Profile')
           class="active"
           @endif
           ><a href="/user/profile"><i class="fa fa-gears"></i> <span>My Profile</span></a></li>
        <!-- <li><a href="#"><i class="fa fa-question"></i> <span>Help</span></a></li> -->
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

              @if(Auth::user()->ni_admin != 1 &&  $prof_comp_array['count'] > 0)

              <div class="alert alert-warning alert-dismissible">
                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                	<h4><i class="icon fa fa-info"></i>Kindly Complete your Profile</h4>

                	<ol>
                  <li class="label label-info" font-size=100%>You need to complete the steps below in order to bid for jobs</li>
                  @if($prof_comp_array['b_detail'] == 0)
                  <li  class="label label-info" font-size=100%><a href="/user/profile">Add your Bank Details</a></li>
                  @endif
                  @if($prof_comp_array['simu1'] == 0)
                  <li class="label label-info" font-size=100%><a href="/user/profile">Add a phone number to your account</a></li>
                  @endif
                  @if($prof_comp_array['shule_level'] == 0)
                  <li class="label label-info" font-size=100%><a href="/user/profile">Select your Academic Level</a></li>
                  @endif
                  @if($prof_comp_array['copy_ya_id'] == 0)
                  <li class="label label-info" font-size=100%><a href="/user/profile">Upload a scanned copy of your ID/Passport</a></li>
                  @endif
                  @if($prof_comp_array['cv'] == 0)
                  <li class="label label-info" font-size=100%><a href="/user/profile">Upload your CV/Resume</a></li>
                  @endif
                	</ol>

              </div>
              @endif

              <!-- This notification is to let guys know that the minimum amount to be paid to the bank is KSh5000 -->
              @if(Auth::user()->bids->count() > 0 && $page_title == "Earnings")
              <div class="alert alert-warning alert-dismissible">
                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                	<h4><i class="icon fa fa-info"></i>Earning Policy Update</h4>

                	<ol>
                  		<li class="label label-info" font-size=100%>Kindly note that the minimum earning amount that qualifies for a payment is $50 <a href="http://academicresearchassistants.com/terms#payment">Learn more</a></li>
                	</ol>

              </div>
              @endif

              @if(Session::has('error'))
          <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h4><i class="icon fa fa-info"></i>Error!</h4>
                 <? $error = Session('error'); ?>
                {{$error}}
              </div>
      @endif
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
@if (strpos($_SERVER['REQUEST_URI'], "mailbox") !== false || strpos($_SERVER['REQUEST_URI'], "users") !== false || strpos($_SERVER['REQUEST_URI'], "earnings") !== false || strpos($_SERVER['REQUEST_URI'], "find_work") !== false)

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

  @if (strpos($_SERVER['REQUEST_URI'], "orders") !== false )
    <script src="/css/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- Messages editor -->
    <script>
      $(function () {
        //Add text editor
        $("#compose-textarea").wysihtml5();
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
@elseif(strpos($_SERVER['REQUEST_URI'], "users") !== false)
    <script>
      $(document).ready( function () {
        $('#users_table').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "sort": true ,
          "info": true,
          "autoWidth": true,
        });
      } );
  </script>
@elseif(strpos($_SERVER['REQUEST_URI'], "find_work") !== false)
    <script>
      $(document).ready( function () {
        $('#availabe_orders').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "sort": true ,
          "autoWidth": true,
        });
      } );
  </script>
@elseif(strpos($_SERVER['REQUEST_URI'],"earnings") !== false)
    <script>
      $(document).ready( function () {
        $('#active_writers_table').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "sort": true ,
          "info": true,
        });
      } );
    </script>
    <script>
      $(document).ready( function () {
        $('#pe_earnings_table').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "sort": true ,
          "info": true,
        });
      } );
    </script>
    <script>
      $(document).ready( function () {
        $('#pa_earnings_table').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "sort": true ,
          "info": true,
        });
      } );
    </script>
@elseif(strpos($_SERVER['REQUEST_URI'], "readmail") !== false || strpos($_SERVER['REQUEST_URI'], "compose") !== false)
<script src="/css/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Messages editor -->
<script>
  $(function () {
    //Add text editor
    $("#compose-textarea").wysihtml5();
  });
</script>
  @endif
  <!-- GA script -->
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-89615848-1', 'auto');
    ga('send', 'pageview');

  </script>
  <!-- Heap Analytics -->
  
    <script type="text/javascript">
      window.heap=window.heap||[],heap.load=function(e,t){window.heap.appid=e,window.heap.config=t=t||{};var r=t.forceSSL||"https:"===document.location.protocol,a=document.createElement("script");a.type="text/javascript",a.async=!0,a.src=(r?"https:":"http:")+"//cdn.heapanalytics.com/js/heap-"+e+".js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(a,n);for(var o=function(e){return function(){heap.push([e].concat(Array.prototype.slice.call(arguments,0)))}},p=["addEventProperties","addUserProperties","clearEventProperties","identify","removeEventProperty","setEventProperties","track","unsetEventProperty"],c=0;c<p.length;c++)heap[p[c]]=o(p[c])};
        heap.load("2580214040");
  </script>
</body>
</html>
