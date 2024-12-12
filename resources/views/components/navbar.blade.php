<style>
  .full-width-dropdown {
    position: absolute !important; /* Allow dropdown to break out of parent */
    left: 0;
    right: 0;
    width: 90vw; /* Full viewport width */
    margin: 5px; /* Remove default margins */
    padding: 5px; /* Add spacing around content */
    border-radius: 10; /* Remove rounded corners for a flat design */
    z-index: 1050; /* Ensure it's above other content */
}

.full-width-dropdown .dropdown-item {
    padding: 10px; /* Add spacing to dropdown items */
}
@media (min-width: 768px) and (max-width: 1024px) {
    #sidebarDropdown {
        display: block !important; /* Ensure it overrides other styles */
    }
}
</style>
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

  <!-- Sidebar Toggle (Topbar) -->

    <button
      class="btn btn-link d-md-none rounded-circle mr-3"
      id="sidebarDropdown"
      role="button"
      data-toggle="dropdown"
      aria-haspopup="true"
      aria-expanded="false">
      <i class="fa fa-bars"></i>
    </button>

    <!-- Dropdown menu -->
    <div
      class="dropdown-menu full-width-dropdown shadow animated--grow-in"
      aria-labelledby="sidebarDropdown">
      <a class="dropdown-item" href="{{ route('dashboard') }}">
        <i class="fas fa-house-user fa-sm fa-fw mr-2 text-gray-400"></i>
        Tableau de bord
      </a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="/patients">
        <i class="fas fa-users fa-sm fa-fw mr-2 text-gray-400"></i>
        Gestion des patients
      </a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="/products">
        <i class="fas fa-mortar-pestle fa-sm fa-fw mr-2 text-gray-400"></i>
        Gestion des Produits
      </a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="/soins">
        <i class="fas fa-hand-holding-heart fa-sm fa-fw mr-2 text-gray-400"></i>
        Gestion des soins
      </a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="/invoices">
        <i class="fas fa-file-invoice fa-sm fa-fw mr-2 text-gray-400"></i>
        Facture
      </a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="/visites">
        <i class="fas fa-handshake fa-sm fa-fw mr-2 text-gray-400"></i>
        Visite du jour
      </a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="/rdvs">
        <i class="fas fa-calendar fa-sm fa-fw mr-2 text-gray-400"></i>
        Agenda
      </a>

    </div>
  </li>

  <!-- Topbar Navbar -->
  <ul class="navbar-nav ml-auto">

    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
    <li class="nav-item dropdown no-arrow d-sm-none">

      <!-- Dropdown - Messages -->
      <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
        <form class="form-inline mr-auto w-100 navbar-search">
          <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
              <button class="btn btn-primary" type="button">
                <i class="fas fa-search fa-sm"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </li>

    <!-- Nav Item - Alerts -->

    <div class="topbar-divider d-none d-sm-block"></div>

    <!-- Nav Item - User Information -->
    <li class="nav-item dropdown no-arrow">
    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="mr-2 d-none d-lg-inline text-gray-600 small">
            {{ auth()->user()->name }}
            <br>
            <small>{{ auth()->user()->level }}</small>
        </span>
        <!-- Display Profile Image if available, otherwise fallback to default -->
        <img class="img-profile rounded-circle" src="{{ auth()->user()->profile_image ? asset('images/profile/' . auth()->user()->profile_image) : 'https://startbootstrap.github.io/startbootstrap-sb-admin-2/img/undraw_profile.svg' }}" alt="Profile Image">
    </a>


      <!-- Dropdown - User Information -->
      <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="/profile">
          <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
          Profile
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ route('logout') }}">
          <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
          Logout
        </a>
      </div>
    </li>

  </ul>

</nav>
