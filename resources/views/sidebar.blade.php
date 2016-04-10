 <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{$avatar_img_url}}" class="img-circle" alt="{{$username}} Avi">
        </div>
        <div class="pull-left info">
          <p>{{$username}}</p>
          <!-- Status -->
          <!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a> -->
        </div>
      </div>

      <!-- search form (Optional) -->
      <form action="../dashboard/q" method="get" class="sidebar-form" role="q">
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
              <li class="
              @if (strpos($_SERVER['REQUEST_URI'], "new_order") !== false) 
              active
              @endif"><a href="/new_order"><i class="fa fa-circle-o"></i>Add new Order</a></li>
            </ul>
        </li>
        <li class="
        @if ($page_title == 'Earnings')
        active
        @endif
        "><a href="#"><i class="fa fa-money"></i> <span>My Earnings</span></a></li>
        <li><a href="#"><i class="fa fa-gears"></i> <span>Settings</span></a></li>
        <li><a href="#"><i class="fa fa-question"></i> <span>Help</span></a></li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>