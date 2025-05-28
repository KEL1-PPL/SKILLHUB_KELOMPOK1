<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'subscription' ? 'active' : '' }} text-decoration-none"
        href="{{ route('admin.subscription-plans.index') }}">
        <i class="bi bi-person fs-4"></i>
        <h3 class="fs-4 mt-1">Manajement Langganan</h3>
    </a>
</li>

<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'Kursus' ? 'active' : '' }} text-decoration-none"
        href="{{ route('features.course.index') }}">
        <i class="bi bi-person fs-4"></i>
        <h3 class="fs-4 mt-1">Manajement Kursus</h3>
    </a>
</li>

<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'Data Wishlist' ? 'active' : '' }} text-decoration-none"
        href="{{ route('admin.wishlist.index') }}">
        <i class="bi bi-heart fs-4"></i>
        <h3 class="fs-4 mt-1">Wishlist User</h3>
    </a>
</li>

<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'Wishlist Analytics' ? 'active' : '' }} text-decoration-none"
        href="{{ route('admin.wishlist.dashboard') }}">
        <i class="bi bi-graph-up fs-4"></i>
        <h3 class="fs-4 mt-1">Wishlist Analytics</h3>
    </a>
</li>

<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'Manage Discounts' ? 'active' : '' }} text-decoration-none"
        href="{{ route('admin.discounts.index') }}">
        <i class="bi bi-tag fs-4"></i>
        <h3 class="fs-4 mt-1">Manage Discounts</h3>
    </a>
</li>

<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'kategori' ? 'active' : '' }} text-decoration-none"
        href="">
        <i class="bi bi-book fs-4"></i>
        <h3 class="fs-4 mt-1">Manajement Kategori</h3>
    </a>
</li>

<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'sertifikat' ? 'active' : '' }} text-decoration-none"
        href="">
        <i class="bi bi-person fs-4"></i>
        <h3 class="fs-4 mt-1">Manajement Sertifikat</h3>
    </a>
</li>
<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'diskusi' ? 'active' : '' }} text-decoration-none"
        href="">
        <i class="bi bi-person fs-4"></i>
        <h3 class="fs-4 mt-1">Manajement Forum diskusi</h3>
    </a>
</li>
<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'user' ? 'active' : '' }} text-decoration-none"
        href="">
        <i class="bi bi-person fs-4"></i>
        <h3 class="fs-4 mt-1">Manajement User</h3>
    </a>
</li>

