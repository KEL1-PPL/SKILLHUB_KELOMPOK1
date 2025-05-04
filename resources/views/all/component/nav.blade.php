<nav class="navbar navbar-expand-lg navbar-light bg-light shadow fixed-top">
    <a class="navbar-brand d-flex align-items-center" href="#">
        <div class="ms-3">
            <strong class="fs-4 text-dark">Skill <span class="text-primary">Hub</span></strong>
            <p class="fs-5 text-muted mb-0">Ayo! Cari pengalaman belajarmu</p>
        </div>
    </a>

    <!-- Navbar Toggler for mobile responsiveness -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar links (hidden on mobile and revealed on toggle) -->
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav flex-row ms-auto align-items-center">
            <li class="nav-item dropdown">
                <a class="nav-link btn btn-outline-dark rounded-pill d-flex align-items-center gap-2 nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="fw-semibold text-dark">Username: {{ auth()->user()->name }}</span>
                </a>

                <!-- Dropdown Menu -->
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item d-flex align-items-center gap-2">
                            <i class="bi bi-box-arrow-right fs-6"></i>
                            <span class="fs-6 mb-0">Logout</span>
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>

<!-- Bootstrap JS and Popper.js (Required for the dropdown functionality) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
