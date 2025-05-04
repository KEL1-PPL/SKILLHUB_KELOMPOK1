@extends('all.component.app')

@push('style')
<style>
    .notification-list {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .notification-item {
        background-color: var(--white);
        border-radius: 10px;
        box-shadow: var(--shadow-sm);
        padding: 1.25rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        border-left: 4px solid var(--primary-color);
    }
    
    .notification-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }
    
    .notification-item.read {
        opacity: 0.7;
        border-left-color: var(--border-color);
    }
    
    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .notification-title {
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--text-dark);
    }
    
    .notification-time {
        font-size: 0.85rem;
        color: var(--text-light);
    }
    
    .notification-message {
        color: var(--text-dark);
        margin-bottom: 1rem;
    }
    
    .notification-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .notification-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .badge-discount {
        background-color: rgba(46, 204, 113, 0.1);
        color: #2ecc71;
    }
    
    .badge-general {
        background-color: rgba(52, 152, 219, 0.1);
        color: #3498db;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        background-color: var(--white);
        border-radius: 10px;
        box-shadow: var(--shadow-sm);
    }
    
    .empty-icon {
        font-size: 3rem;
        color: var(--text-light);
        margin-bottom: 1rem;
    }
    
    .empty-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }
    
    .empty-description {
        color: var(--text-light);
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">{{ $title ?? 'My Notifications' }}</h1>
                
                @if($notifications->count() > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-check-all"></i> Mark All as Read
                    </button>
                </form>
                @endif
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="notification-list">
                @forelse($notifications as $notification)
                    <div class="notification-item {{ $notification->isRead() ? 'read' : '' }}">
                        <div class="notification-header">
                            <h3 class="notification-title">{{ $notification->title }}</h3>
                            <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <p class="notification-message">{{ $notification->message }}</p>
                        
                        <div class="notification-actions">
                            <div>
                                @if($notification->type == 'discount')
                                    <span class="notification-badge badge-discount">
                                        <i class="bi bi-tag"></i> Discount
                                    </span>
                                @else
                                    <span class="notification-badge badge-general">
                                        <i class="bi bi-bell"></i> General
                                    </span>
                                @endif
                            </div>
                            
                            @if(!$notification->isRead())
                                <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-check"></i> Mark as Read
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small">
                                    <i class="bi bi-check-all"></i> Read {{ $notification->read_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-bell-slash empty-icon"></i>
                        <h3 class="empty-title">No Notifications</h3>
                        <p class="empty-description">You don't have any notifications at the moment.</p>
                    </div>
                @endforelse
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
