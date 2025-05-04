<li class="sidebar-item">
    <a href="{{ route('features.course.index') }}" class="sidebar-link {{ $title == 'kursus' ? 'active' : '' }}">
        <i class="bi bi-book"></i>
        <span class="sidebar-link-text">Kursus Saya</span>
    </a>
</li>

<li class="sidebar-item">
    <a href="{{ route('wishlist.index') }}" class="sidebar-link {{ $title == 'wishlist' ? 'active' : '' }}">
        <i class="bi bi-heart"></i>
        <span class="sidebar-link-text">Wishlist Saya</span>
    </a>
</li>

<li class="sidebar-item">
    <a href="#" class="sidebar-link {{ $title == 'kuis' ? 'active' : '' }}">
        <i class="bi bi-pencil-square"></i>
        <span class="sidebar-link-text">Forum Kuis & Ujian</span>
    </a>
</li>

<li class="sidebar-item">
    <a href="#" class="sidebar-link {{ $title == 'diskusi' ? 'active' : '' }}">
        <i class="bi bi-chat-dots"></i>
        <span class="sidebar-link-text">Forum Diskusi</span>
    </a>
</li>

<li class="sidebar-item">
    <a href="#" class="sidebar-link {{ $title == 'laporan' ? 'active' : '' }}">
        <i class="bi bi-graph-up"></i>
        <span class="sidebar-link-text">Laporan Perkembangan</span>
    </a>
</li>

<li class="sidebar-item">
    <a href="#" class="sidebar-link {{ $title == 'bayar' ? 'active' : '' }}">
        <i class="bi bi-credit-card"></i>
        <span class="sidebar-link-text">Payment Kelas</span>
    </a>
</li>