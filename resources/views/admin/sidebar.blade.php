<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">

<li class="nav-item">
  <a class="nav-link {{ request()->routeIs('admin.dashboard') ? '' : 'collapsed' }}" href="{{ route('admin.dashboard') }}">
    <i class="bi bi-grid"></i>
    <span>Dashboard</span>
  </a>
</li>

  <li class="nav-item">
  <a class="nav-link {{ request()->routeIs('admin.datamaster.*') ? '' : 'collapsed' }}" 
     data-bs-target="#components-nav" 
     data-bs-toggle="collapse" 
     href="#">
    <i class="bi bi-menu-button-wide"></i><span>Data Master</span><i class="bi bi-chevron-down ms-auto"></i>
  </a>

  <ul id="components-nav" 
    class="nav-content collapse {{ request()->routeIs('admin.datamaster.*') || request()->routeIs('admin.kelas.*') || request()->routeIs('admin.siswa.*') || request()->routeIs('admin.mapel.*') ? 'show' : '' }}" 
    data-bs-parent="#sidebar-nav">

    <li>
      <a href="{{ route('admin.datamaster.users') }}" 
         class="{{ request()->routeIs('admin.datamaster.users') ? 'active' : '' }}">
        <i class="bi bi-circle"></i><span>Akun Pengguna</span>
      </a>
    </li>
    <li>
      <a href="{{ route('admin.datamaster.dataguru') }}" 
         class="{{ request()->routeIs('admin.datamaster.dataguru') ? 'active' : '' }}">
        <i class="bi bi-circle"></i><span>Data Guru</span>
      </a>
    </li>
    <li>
      <a href="{{ route('admin.kelas.index') }}" 
        class="{{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
        <i class="bi bi-circle"></i><span>Data Kelas</span>
      </a>
    </li>
    <li>
      <a href="{{ route('admin.siswa.index') }}" 
        class="{{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
        <i class="bi bi-circle"></i><span>Data Siswa</span>
      </a>
    </li>
    <li>
      <a href="{{ route('admin.mapel.index') }}" 
        class="{{ request()->routeIs('admin.mapel.*') ? 'active' : '' }}">
        <i class="bi bi-circle"></i><span>Data Mata Pelajaran</span>
      </a>
    </li>
  </ul>
  </li>
  </ul>

</aside>
<!-- End Sidebar-->