<div class="nav-container">
    <nav class="navbar">
    <ul>
        <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
        <li><a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">Profile</a></li>
        <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </ul>
</nav>
</div>
