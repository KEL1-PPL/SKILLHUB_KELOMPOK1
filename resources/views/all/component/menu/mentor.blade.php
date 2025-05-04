<li class="sidebar-item">
    <a href="{{ route('features.course.index') }}" class="sidebar-link {{ $title == 'kursus' ? 'active' : '' }}">
        <i class="bi bi-book"></i>
        <span class="sidebar-link-text">Manajemen Kursus</span>
    </a>
</li>

<li class="sidebar-item">
    <a href="#" class="sidebar-link {{ $title == 'laporan' ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text"></i>
        <span class="sidebar-link-text">Manajemen Laporan</span>
    </a>
</li>

<li class="sidebar-item">
    <a href="#" class="sidebar-link {{ $title == 'exam' ? 'active' : '' }}">
        <i class="bi bi-pencil-square"></i>
        <span class="sidebar-link-text">Manajemen Kuis & Ujian</span>
    </a>
</li>

<li class="sidebar-item">
    <a href="#" class="sidebar-link {{ $title == 'diskusi' ? 'active' : '' }}">
        <i class="bi bi-chat-dots"></i>
        <span class="sidebar-link-text">Manajemen Forum Diskusi</span>
    </a>
</li>

<li class="sidebar-item">
    <a href="#" class="sidebar-link {{ $title == 'sertifikat' ? 'active' : '' }}">
        <i class="bi bi-award"></i>
        <span class="sidebar-link-text">Manajemen Sertifikat</span>
    </a>
</li>

<li class="sidebar-item">
    <a href="#" class="sidebar-link {{ $title == 'rating' ? 'active' : '' }}">
        <i class="bi bi-star"></i>
        <span class="sidebar-link-text">Rating & Review</span>
    </a>
</li>