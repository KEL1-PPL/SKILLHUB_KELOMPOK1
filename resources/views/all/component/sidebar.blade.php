<div class="sidebar-header">
    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
        <img src="{{ asset('images/logo.png') }}" alt="Skill Hub Logo" class="img-fluid" style="max-height: 40px;">
        <div class="ms-3">
            <h2 class="brand-text mb-0">Skill <span>Hub</span></h2>
        </div>
    </a>
</div>

<div class="sidebar-menu">
    <!-- Dashboard Menu Item -->
    <div class="menu-category">Menu Utama</div>
    <ul class="nav flex-column">
        <li class="sidebar-item">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ $title == 'Dashboard' ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span class="sidebar-link-text">Dashboard</span>
            </a>
        </li>
    </ul>

    <!-- Role-specific Menu Items -->
    <div class="menu-category mt-3">
        {{ auth()->user()->role == 'admin' ? 'Management' : 'Layanan ' . ucfirst(auth()->user()->role) }}
    </div>
    
    <ul class="nav flex-column">
        @if (auth()->user()->role == 'admin')
            @include('all.component.menu.admin')
        @elseif (auth()->user()->role == 'mentor')
            @include('all.component.menu.mentor')
        @else
            @include('all.component.menu.siswa')
        @endif
    </ul>
</div>