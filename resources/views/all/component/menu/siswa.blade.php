<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'Kursus' ? 'active' : '' }} text-decoration-none"
        href="{{ route('features.course.index') }}">
        <i class="bi bi-person fs-4"></i>
        <h3 class="fs-4 mt-1">Kursus Saya</h3>
    </a>
</li>

<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'wishlist' ? 'active' : '' }} text-decoration-none"
        href="{{ route('wishlist.index') }}">
        <i class="bi bi-heart fs-4"></i>
        <h3 class="fs-4 mt-1">Wishlist Saya</h3>
    </a>
</li>

<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'kuis' ? 'active' : '' }} text-decoration-none"
        href="">
        <i class="bi bi-person fs-4"></i>
        <h3 class="fs-4 mt-1">Forum Kuis & Ujian</h3>
    </a>
</li>
<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'diskusi' ? 'active' : '' }} text-decoration-none"
        href="">
        <i class="bi bi-person fs-4"></i>
        <h3 class="fs-4 mt-1">Forum diskusi</h3>
    </a>
</li>
<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'laporan' ? 'active' : '' }} text-decoration-none"
        href="">
        <i class="bi bi-person fs-4"></i>
        <h3 class="fs-4 mt-1">Laporan Perkembangan</h3>
    </a>
</li>
<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'bayar' ? 'active' : '' }} text-decoration-none"
        href="">
        <i class="bi bi-person fs-4"></i>
        <h3 class="fs-4 mt-1">Payment kelas</h3>
    </a>
</li>