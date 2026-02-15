<div class="nav-container">
    <a href="{{ route('dashboard') }}" class="nav-logo" aria-label="Glos Bike Vault">
        <img src="{{ asset('images/logo-sm-stacked.png') }}" alt="Glos Bike Vault logo">
    </a>
    <nav class="navbar">
        <ul>
            <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class='bx bx-home-alt'></i> Dashboard</a></li>
            <li><a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}"><i class='bx bx-user'></i> Profile</a></li>
            <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bx bx-arrow-out-right-circle-half"></i>Logout</a></li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </ul>
    </nav>
</div>
