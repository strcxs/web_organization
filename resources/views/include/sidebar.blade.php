<aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/dashboard" class="brand-link">
      <img src="{{asset('storage/images/icon_himaif.png')}}" alt="HMIF Logo" class="brand-image img-circle elevation-3">
      <span class="brand-text font-weight-light">HMIF Nurtanio</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <a href="{{route('profile')}}"><img src="{{asset('storage/images/default/default-user-icon.jpg')}}" style="width: 33px; height: 33px; object-fit: cover; border-radius: 50%;" class="img-circle elevation-2" alt="User Image" id="user_image"></a> 
        </div>
        <div class="info">
          <a href="{{route('profile')}}" class="d-block"></a>
          <p class="c-block" style="font-size: 13px"></p>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-bullhorn"></i>
              <p>
                Announcement
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('announcement')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>all announcement</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="{{route('discuss')}}" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Discuss
                {{-- <i class="fas fa-angle-left right"></i> --}}
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Voting
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('voting')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>vote now!</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('voteManage')}}"class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>vote management</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-comments-dollar"></i>
              <p>
                Pengajuan
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/tables/jsgrid.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>beasiswa</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
    </div>
  </aside>