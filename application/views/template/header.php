<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <!-- Logo -->
    <a href="<?php echo base_url(); ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>Admn</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Admin</b>Panel</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="/assets/dist/img/image.gif" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $user->name; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="/assets/dist/img/image.gif" class="img-circle" alt="User Image">

                <p>
                  <?php echo $user->name; ?>
                  <small>Member since <?php echo $user->date_created; ?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <!-- <li class="user-body">
              </li> -->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo base_url('user/profile'); ?>" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo base_url('user/signout'); ?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="/assets/dist/img/image.gif" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $user->name; ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <!-- <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form> -->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <?php if($user->role_name === "admin") { ?>
        <li class="treeview <?php echo ($class == 'user') ? 'active' : ''; ?> ">
          <a href="#">
            <i class="fa fa-users" aria-hidden="true"></i> <span>Users</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url('user/show/'); ?>"><i class="fa fa-angle-right" aria-hidden="true"></i> View</a></li>
            <li><a href="<?php echo base_url('user/register/'); ?>"><i class="fa fa-angle-right" aria-hidden="true"></i> Add</a></li>
          </ul>
        </li>
        <?php } ?>
        <li class="treeview <?php echo ($class == 'portfolio') ? 'active' : ''; ?>">
          <a href="#">
            <i class="fa fa-folder-open" aria-hidden="true"></i> <span>My Portfolio</span>
            <span class="label label-primary pull-right">4</span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url('portfolio/show/'); ?>"><i class="fa fa-angle-right" aria-hidden="true"></i> View</a></li>
            <li><a href="<?php echo base_url('portfolio/register/'); ?>"><i class="fa fa-angle-right" aria-hidden="true"></i> Add</a></li>
          </ul>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-calculator" aria-hidden="true"></i> <span>Calculator</span>
            <small class="label pull-right bg-green">new</small>
          </a>
        </li>
        <li class="treeview <?php echo ($class == 'analytics') ? 'active' : ''; ?>">
          <a href="#">
            <i class="fa fa-line-chart" aria-hidden="true"></i> <span>Analytics</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> General</a></li>
            <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> Icons</a></li>
            <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> Buttons</a></li>
            <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> Sliders</a></li>
            <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> Timeline</a></li>
            <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> Modals</a></li>
          </ul>
        </li>
        <li class="treeview <?php echo ($class == 'forum') ? 'active' : ''; ?>">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Forums</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> Add</a></li>
            <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> Advanced Elements</a></li>
            <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> Editors</a></li>
          </ul>
        </li>
        <li class="treeview <?php echo ($class == 'service') ? 'active' : ''; ?>">
          <a href="#">
            <i class="fa fa-table"></i> <span>Services</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url('service/show/'); ?>"><i class="fa fa-angle-right" aria-hidden="true"></i> View</a></li>
            <li><a href="<?php echo base_url('service/register/'); ?>"><i class="fa fa-angle-right" aria-hidden="true"></i> Add</a></li>
          </ul>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-flag-o"></i> <span>Alerts</span>
            <small class="label pull-right bg-red">3</small>
          </a>
        </li>
        <li class="treeview <?php echo ($class == 'setting') ? 'active' : ''; ?>">
          <a href="#">
            <i class="fa fa-gears"></i> <span>Setting</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> Simple tables</a></li>
            <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> Data tables</a></li>
          </ul>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst($class); ?>
      </h1>
      <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Main row -->
      <div class="row">
