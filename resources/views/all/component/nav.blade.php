<nav class="navbar navbar-expand-lg shadow fixed-top" style="background:#fff">
    <a class="navbar-brand d-flex align-items-center" href="#">
        <div class="ms-3">
            <strong class="fs-3">Skill <span class="base-color-text">Hub</span></strong>
            <p class="fs-1">Ayo ! cari pengalaman belajarmu</p>
        </div>
    </a>

    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
            <!-- subscription button only for 'siswa' role -->
            @if(auth()->check() && auth()->user()->role == 'siswa')
                <li class="nav-item me-3">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#subscriptionModal">
                        <i class="bi bi-star"></i> Tingkatkan Paket
                    </button>
                </li>
            @endif


            <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover bg-dark text-white" href="#" id="drop2"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Username : {{ auth()->user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                    <div class="message-body">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="d-flex align-items-center gap-2 dropdown-item">
                                <i class="bi bi-box-arrow-right fs-6"></i>
                                <p class="mb-0 fs-3">Logout</p>
                            </button>
                        </form>
                    </div>
                </div>
            </li>
            <li>
                <div class="notification-indicator"></div>
                <a href="{{ route('notifications.index') }}" class="btn btn-link text-dark">
                    <i class="bi bi-bell fs-5"></i>
                    @php
                        $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                                                                 ->whereNull('read_at')
                                                                 ->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="notification-badge">{{ $unreadCount }}</span>
                    @endif
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- Bootstrap JS and Popper.js (Required for the dropdown functionality) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
