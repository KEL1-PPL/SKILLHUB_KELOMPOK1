<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'kursus' ? 'active' : '' }} text-decoration-none"
        href="">
        <i class="bi bi-person fs-4"></i>
        <h3 class="fs-4 mt-1">Kursus Saya</h3>
    </a>
</li>

<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'whislist' ? 'active' : '' }} text-decoration-none"
        href="">
        <i class="bi bi-book fs-4"></i>
        <h3 class="fs-4 mt-1">Whislist Saya</h3>
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
<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'rating' ? 'active' : '' }} text-decoration-none"
        href="{{ route('ratings.index') }}">
        <i class="bi bi-star fs-4"></i>
        <h3 class="fs-4 mt-1">Rating</h3>
    </a>
</li>
