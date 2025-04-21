<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'kursus' ? 'active' : '' }} text-decoration-none"
        href="{{ route('features.course.index') }}">
        <i class="bi bi-person fs-4"></i>
        <h3 class="fs-4 mt-1">Manajement Kursus</h3>
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

