<!-- Sidebar scroll-->

<style>
    .sidebar-item.active {
        background-color: #6084fc;
    }

    .sidebar-item.active .hide-menu {
        color: #fdfdfd;
    }

    .sidebar-item.active .sidebar-link span i {
        color: #fdfdfd;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
</style>

<div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
        <a href="" class="text-nowrap logo-img">
            <img src="" width="180" alt="" />
        </a>
        <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
        </div>
    </div>

    <nav class="sidebar-nav scroll-sidebar no-scrollbar" data-simplebar="">
        <ul id="sidebarnav">
            <li class="nav-small-cap">
                <h3 class="text-muted fs-4">Menu</h3>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link {{ $title == 'Dashboard' ? 'active' : '' }} text-decoration-none"
                    href="{{route('dashboard')}}">
                    <i class="ti ti-layout-dashboard fs-6"></i>
                    <h3 class="fs-4 mt-1">Dashboard</h3>
                </a>
            </li>
        </ul>

        <ul id="sidebarnav">
            <li class="nav-small-cap">
                <h3 class="text-muted fs-4">
                    {{ auth()->user()->role == 'admin' ? 'Management' : 'Layanan' . ucfirst(auth()->user()->role) }}</h3>
            </li>

            @if (auth()->user()->role == 'admin')
                @include('admin.menu')
            @elseif (auth()->user()->role == 'mentor')
                @include('mentor.menu')
            @else
                @include('siswa.menu')
            @endif

        </ul>

    </nav>

</div>
