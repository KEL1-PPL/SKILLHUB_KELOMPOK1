<div class="sidebar">
    <div class="sidebar-header">
        <h3>SKILLHUB</h3>
    </div>

    <div class="sidebar-menu">
        <h5 class="menu-header">MENU</h5>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>

        <h5 class="menu-header mt-4">LAYANAN MENTOR</h5>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('mentor.income-report') }}" class="nav-link {{ Request::is('mentor/income-report') ? 'active' : '' }}">
                    <i class="bi bi-cash-stack"></i>
                    <span>Laporan Pendapatan</span>
                </a>
            </li>
            <!-- Menu lainnya -->
        </ul>
    </div>
</div>
<div class="sidebar-wrapper">
    <div class="menu">
        <div class="menu-header">
            <h2>MENU</h2>
        </div>

        <div class="menu-item">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <div class="menu-header">
            <h2>LAYANAN MENTOR</h2>
        </div>

        <!-- Tambahkan link Laporan Pendapatan di sini -->
        <div class="menu-item">
            <a href="{{ route('mentor.income-report') }}" class="menu-link">
                <i class="bi bi-cash-stack"></i>
                <span>Laporan Pendapatan</span>
            </a>
        </div>

        <!-- Menu lainnya tetap sama -->
        <div class="menu-item">
            <a href="#" class="menu-link">
                <i class="bi bi-book-fill"></i>
                <span>Manajemen Kursus</span>
            </a>
        </div>

        <div class="menu-item">
            <a href="#" class="menu-link">
                <i class="bi bi-file-text-fill"></i>
                <span>Manajemen Laporan</span>
            </a>
        </div>

        <div class="menu-item">
            <a href="#" class="menu-link">
                <i class="bi bi-question-circle-fill"></i>
                <span>Manajemen Kuis & Ujian</span>
            </a>
        </div>

        <div class="menu-item">
            <a href="#" class="menu-link">
                <i class="bi bi-chat-dots-fill"></i>
                <span>Manajemen Forum diskusi</span>
            </a>
        </div>

        <div class="menu-item">
            <a href="#" class="menu-link">
                <i class="bi bi-award-fill"></i>
                <span>Manajemen Sertifikat</span>
            </a>
        </div>

        <div class="menu-item">
            <a href="#" class="menu-link">
                <i class="bi bi-star-fill"></i>
                <span>Rating & Review</span>
            </a>
        </div>
    </div>
</div>
<nav>
    <div class="sidebar-header">
        <h3>SKILLHUB</h3>
    </div>

    <div class="sidebar-menu">
        <a href="{{ route('mentor.dashboard') }}" class="menu-item">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        
        <a href="{{ route('mentor.income-report') }}" class="menu-item">
            <i class="fas fa-money-bill"></i>
            <span>Laporan Pendapatan</span>
        </a>

        <a href="{{ route('mentor.analytics') }}" class="menu-item">
            <i class="fas fa-chart-bar"></i>
            <span>Analitik Kemajuan Siswa</span>
        </a>

        <a href="{{ route('mentor.course-management') }}" class="menu-item">
            <i class="fas fa-book"></i>
            <span>Manajemen Kursus</span>
        </a>
    </div>
</nav>