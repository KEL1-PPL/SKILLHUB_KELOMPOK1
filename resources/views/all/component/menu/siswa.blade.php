<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'Kursus' ? 'active' : '' }} text-decoration-none"
        href="{{ route('features.course.index') }}">
        <i class="bi bi-person fs-4"></i>
        <h3 class="fs-4 mt-1">Kursus Saya</h3>
    </a>
</li>

<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'live' ? 'active' : '' }} text-decoration-none"
        href="{{ route('live-class-student.index') }}">
        <i class="bi bi-broadcast fs-4"></i>
        <h3 class="fs-4 mt-1">Live Class</h3>
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
    <a class="sidebar-link {{ $title == 'transaksi' ? 'active' : '' }} text-decoration-none"
        href="{{ route('subscription.my-subscriptions') }}">
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
<li class="sidebar-item">
    <a class="sidebar-link {{ $title == 'sertifikat' ? 'active' : '' }} text-decoration-none"
        href="{{ route('my.certificates') }}">
        <i class="bi bi-file-earmark-pdf fs-4"></i>
        <h3 class="fs-4 mt-1">Sertifikat</h3>
    </a>
</li>

