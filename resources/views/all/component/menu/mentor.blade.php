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

<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'Kursus' ? 'active' : '' }} text-decoration-none"
        href="{{ route('features.course.index') }}">
        <i class="bi bi-person fs-4"></i>
        <h3 class="fs-4 mt-1">Manajement Kursus</h3>
    </a>
</li>

<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'laporan' ? 'active' : '' }} text-decoration-none"
        href="">
        <i class="bi bi-book fs-4"></i>
        <h3 class="fs-4 mt-1">Manajement Laporan</h3>
    </a>
</li>

<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'exam' ? 'active' : '' }} text-decoration-none"
        href="">
        <i class="bi bi-person fs-4"></i>
        <h3 class="fs-4 mt-1">Manajement Kuis & Ujian</h3>
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
    <a class="sidebar-link {{ $title == 'sertifikat' ? 'active' : '' }} text-decoration-none"
        href="">
        <i class="bi bi-person fs-4"></i>
        <h3 class="fs-4 mt-1">Manajement Sertifikat</h3>
    </a>
</li>
<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'rating' ? 'active' : '' }} text-decoration-none"
        href="">
        <i class="bi bi-person fs-4"></i>
        <h3 class="fs-4 mt-1">Rating & Review</h3>
    </a>
</li>

