<aside class="main-sidebar sidebar-light-primary">
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
          <a href="{{route('profile')}}"><img src="{{asset('storage/images/default/default-user-icon.jpg')}}" style="width: 46px; height: 46px; object-fit: cover; border-radius: 50%;" class="img-circle elevation-2" alt="User Image" id="user_image"></a> 
        </div>
        <div class="info">
          <a href="{{route('profile')}}" class="d-block"></a>
          <p class="c-block" style="font-size: 13px"></p>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item {{ request()->routeIs('announcement') ? 'bg-primary' : '' }}">
            <a href="{{route('announcement')}}" class="nav-link">
              <i class="nav-icon fas fa-bullhorn"></i>
              <p>
                Announcement
              </p>
            </a>
          </li>
          <li class="nav-item {{ request()->routeIs('discuss') ? 'bg-primary' : '' }}">
            <a href="{{route('discuss')}}" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Discuss
              </p>
            </a>
          </li>
          <li class="nav-item {{ request()->routeIs('voting') ? 'bg-primary' : '' }}">
            <a href="{{route('voting')}}" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Vote Now!
              </p>
            </a>
          </li>
          <li class="nav-item {{ request()->routeIs('cabinet') ? 'bg-primary' : '' }}" id="cabinet">
            <a href="{{route('cabinet')}}" class="nav-link">
              <i class="nav-icon fas fa-star"></i>
              <p>
                Cabinet
              </p>
            </a>
          </li>
          <li class="nav-item" id="admin" style="display: none">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-solid fa-briefcase"></i>
              <p>
                Manage
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('membersManage')}}" class="nav-link">
                  <i class="{{ request()->routeIs('membersManage') ? 'fas' : 'far' }} fa-circle nav-icon"></i>
                  <p>Member management</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('voteManage')}}" class="nav-link">
                  <i class="{{ request()->routeIs('voteManage') ? 'fas' : 'far' }} fa-circle nav-icon"></i>
                  <p>Vote management</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin')}}" class="nav-link">
                  <i class="{{ request()->routeIs('admin') ? 'fas' : 'far' }} fa-circle nav-icon"></i>
                  <p>Web management</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
    </div>
  </aside>
  <script>
    if (sessionStorage.getItem('session') == 1 || sessionStorage.getItem('session') != 2) {
        document.getElementById('cabinet').style.display = "block";
    } else {
        document.getElementById('cabinet').style.display = "none";
    }
    if (sessionStorage.getItem('session') == 1) {
        document.getElementById('admin').style.display = "block";
    }
  </script>