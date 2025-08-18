<nav class="main-header navbar navbar-expand navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('kepsek.dashboard') }}" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link"></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <!-- <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li> -->
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <div class="container">
        <button
          id="toggleButton"
          type="button"
          class="btn d-flex align-items-center gap-2 px-3 py-2 rounded-pill shadow-sm"
          style="font-size: 0.9rem;"
        >
          <span id="toggleIcon" class="position-relative d-inline-block" style="width: 1em; height: 1em;">
            <i id="sunIcon" class="fas fa-sun text-secondary position-absolute transition-icon"></i>
            <i id="moonIcon" class="fas fa-moon text-light position-absolute transition-icon"></i>
          </span>
        </button>
      </div>
    </div>
    </ul>
</nav>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('kepsek.dashboard') }}" class="brand-link">
    <img src="{{ asset('images/LogoGuru.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">SIPENA</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- SidebarSearch Form -->
      <!-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ route('kepsek.dashboard') }}" class="nav-link {{ request()->routeIs('kepsek.dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-home"></i>
              <p>Home</p>
            </a>
          </li>
          <li class="nav-item">
              <a href="{{ route('kepsek.rekap') }}" class="nav-link {{ request()->routeIs('kepsek.rekap') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-clipboard-list"></i>
                  <p>Rekap Absensi</p>
              </a>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link" data-toggle="modal" data-target="#logoutModal" 
            class="nav-link">
              <i class="fas fa-door-open nav-icon"></i>
              <p>Logout</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>