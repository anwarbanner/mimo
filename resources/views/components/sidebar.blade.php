<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion h-100" id="accordionSidebar">
    <br />
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center my-0" href="{{ route('dashboard') }}">
        <!-- Display Logo Image if available, otherwise fallback to default logo -->
        <img class="rounded-circle" src="{{ asset('images/logo/logo-acup.jpg') }}" alt="Logo"
            class="rounded-circle object-cover"
            style="width: 100px; height: 100px;"
            class="w-12 h-12 sm:w-16 sm:h-16 lg:w-24 lg:h-24">
    </a>
    
    <br />

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
        <a class="nav-link" href="/products">
            <i class="fas fa-fw fa-solid fa-mortar-pestle"></i>
            <span>Gestion des produits</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="/soins">
            <i class="fas fa-fw fa-solid fa-hand-holding-heart"></i>
            <span>Gestion des Soins</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/invoices">
            <i class="fas fa-fw fa-solid fa-file-invoice"></i>
            <span>factures</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/rdvs">
            <i class="fas fa-fw fa-solid fa-calendar"></i>
            <span>Agenda</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/visites">
            <i class="fas fa-fw fa-solid fa-handshake"></i>
            <span>Visites du jour</span>
        </a>
    </li>


    <!-- Nav Item - Profile -->
    <li class="nav-item">
        <a class="nav-link" href="/profile">
            <i class="fas fa-fw fa-solid fa-gear"></i>
            <span>parametre de compte</span>
        </a>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
