<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
<BR>
  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
    <!-- Display Logo Image if available, otherwise fallback to default logo -->
    <img class="rounded-circle" 
         src="{{ auth()->user()->logo_image ? asset('images/logo/' . auth()->user()->logo_image) : 'path_to_default_logo_image' }}" 
         alt="Logo" 
         style="width: 90px; height: 90px; object-fit: cover; border-radius: 50%;">
  </a>
<BR>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  <li class="nav-item">
    <a class="nav-link" href="{{ route('dashboard') }}">
      <i class="fas fa-fw fa-solid fa-house-user"></i>
      <span>Tableau de bord</span>
    </a>
  </li>

  
  <li class="nav-item">
    <a class="nav-link" href="/patients">
      <i class="fas fa-fw fa-solid fa-users"></i>
      <span>Gestion des patients</span>
    </a>
  </li>


  <li class="nav-item">
    <a class="nav-link" href="">
      <i class="fas fa-fw fa-solid fa-mortar-pestle"></i>
      <span>Gestion des produits</span>
    </a>
  </li>



  <!-- Nav Item - Profile -->
  <li class="nav-item">
    <a class="nav-link" href="/profile">
      <i class="fas fa-fw fa-solid fa-user"></i>
      <span>Profile</span>
    </a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>
