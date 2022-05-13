<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('home')}}" class="brand-link">
      <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"  class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light ">ĐIỂM DANH</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      

      

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

           <li class="nav-item">
            <a href="{{ route('attendance.index') }}" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Điểm danh
                
              </p>
            </a>
          </li>

           <li class="nav-item">
            <a href="{{ route('teacher.index') }}" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Quản lý giảng viên
              </p>
            </a>
          </li>

           <li class="nav-item">
            <a href="{{ route('student.index') }}" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Quản lý sinh viên
              </p>
            </a>
          </li>

           <li class="nav-item">
            <a href="{{ route('subject.index') }}" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Quản lý môn học
                
              </p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="{{ route('user.index') }}" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Quản lý người dùng
                
              </p>
            </a>
          </li>
          
        </ul>
      </nav>
    </div>
  </aside>