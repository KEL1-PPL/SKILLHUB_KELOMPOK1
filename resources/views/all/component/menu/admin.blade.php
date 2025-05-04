<li class="sidebar-item">
    <a href="{{ route('admin.wishlist.index') }}" class="sidebar-link {{ $title == 'Data Wishlist' ? 'active' : '' }}">
        <i class="bi bi-heart"></i>
        <span class="sidebar-link-text">Wishlist User</span>
    </a>
</li>

<li class="sidebar-item">
    <a href="{{ route('admin.wishlist.dashboard') }}" class="sidebar-link {{ $title == 'Wishlist Analytics' ? 'active' : '' }}">
        <i class="bi bi-graph-up"></i>
        <span class="sidebar-link-text">Wishlist Analytics</span>
    </a>
</li>

<li class="sidebar-item">
    <a href="{{ route('admin.discounts.index') }}" class="sidebar-link {{ $title == 'Manage Discounts' ? 'active' : '' }}">
        <i class="bi bi-tag"></i>
        <span class="sidebar-link-text">Manage Discounts</span>
    </a>
</li>

<li class="sidebar-item">
    <a href="#" class="sidebar-link {{ $title == 'kategori' ? 'active' : '' }}">
        <i class="bi bi-grid"></i>
        <span class="sidebar-link-text">Manajemen Kategori</span>
    </a>
</li>

<li class="sidebar-item">
    <a href="#" class="sidebar-link {{ $title == 'sertifikat' ? 'active' : '' }}">
        <i class="bi bi-award"></i>
        <span class="sidebar-link-text">Manajemen Sertifikat</span>
    </a>
</li>

<li class="sidebar-item">
    <a href="#" class="sidebar-link {{ $title == 'diskusi' ? 'active' : '' }}">
        <i class="bi bi-chat-dots"></i>
        <span class="sidebar-link-text">Manajemen Forum Diskusi</span>
    </a>
</li>

<li class="sidebar-item">
    <a href="#" class="sidebar-link {{ $title == 'user' ? 'active' : '' }}">
        <i class="bi bi-people"></i>
        <span class="sidebar-link-text">Manajemen User</span>
    </a>
</li>
