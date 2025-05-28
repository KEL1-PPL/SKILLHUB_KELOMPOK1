@extends('all.component.app')

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css">
<style>
    .analytics-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .analytics-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .card-stats {
        padding: 1.5rem;
        border-radius: 10px;
        background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
        color: white;
    }
    
    .stats-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    
    .stats-number {
        font-size: 2rem;
        font-weight: 700;
    }
    
    .stats-title {
        font-size: 1rem;
        opacity: 0.8;
    }
    
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    .table-recent td {
        vertical-align: middle;
    }
    
    .activity-time {
        font-size: 0.8rem;
        color: var(--text-light);
    }
    
    .course-image-small {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 5px;
    }
    
    .dashboard-header {
        margin-bottom: 2rem;
    }
    
    .dashboard-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .dashboard-subtitle {
        color: var(--text-light);
    }
    
    .chart-card {
        height: 100%;
    }
    
    .chart-card .card-header {
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .chart-card .card-header .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .top-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
        padding: 0.5rem;
        border-radius: 5px;
        transition: background-color 0.2s ease;
    }
    
    .top-item:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }
    
    .top-item-rank {
        font-weight: 700;
        font-size: 1.2rem;
        width: 30px;
        color: var(--primary-color);
    }
    
    .top-item-content {
        flex: 1;
    }
    
    .top-item-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .top-item-subtitle {
        font-size: 0.8rem;
        color: var(--text-light);
    }
    
    .top-item-value {
        font-weight: 700;
        color: var(--primary-color);
    }
</style>

<style>
    @media (min-width: 992px) {
        main {
            margin-left: 300px;
        }
    }
    .card-header {
        margin-top: 20px;
    }

    .dashboard-title {
        margin-top: 100px;
    }

    .dashboard-subtitle {
        margin-top: 10px;
    }
    /* .content-container {
        padding: 2rem;
        padding-top: 120px;
    } */
</style>
@endpush

@section('content')
<div class="dashboard-header">
    <h1 class="dashboard-title">📊 Wishlist Analytics Dashboard</h1>
    <p class="dashboard-subtitle">Comprehensive overview of wishlist trends and user preferences</p>
</div>

<!-- Summary Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card analytics-card">
            <div class="card-stats bg-primary">
                <div class="row">
                    <div class="col-4">
                        <div class="stats-icon">
                            <i class="bi bi-heart-fill"></i>
                        </div>
                    </div>
                    <div class="col-8">
                        <h3 class="stats-number">{{ number_format($totalWishlists) }}</h3>
                        <p class="stats-title">Total Wishlists</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card analytics-card">
            <div class="card-stats" style="background: linear-gradient(45deg, #2ecc71, #27ae60);">
                <div class="row">
                    <div class="col-4">
                        <div class="stats-icon">
                            <i class="bi bi-book-fill"></i>
                        </div>
                    </div>
                    <div class="col-8">
                        <h3 class="stats-number">{{ number_format($coursesWithWishlists) }}</h3>
                        <p class="stats-title">Wishlisted Courses</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card analytics-card">
            <div class="card-stats" style="background: linear-gradient(45deg, #f39c12, #e67e22);">
                <div class="row">
                    <div class="col-4">
                        <div class="stats-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                    <div class="col-8">
                        <h3 class="stats-number">{{ number_format($totalUsers) }}</h3>
                        <p class="stats-title">Total Users</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card analytics-card">
            <div class="card-stats" style="background: linear-gradient(45deg, #9b59b6, #8e44ad);">
                <div class="row">
                    <div class="col-4">
                        <div class="stats-icon">
                            <i class="bi bi-bar-chart-fill"></i>
                        </div>
                    </div>
                    <div class="col-8">
                        <h3 class="stats-number">{{ $totalCourses > 0 ? number_format(($coursesWithWishlists / $totalCourses) * 100, 1) : 0 }}%</h3>
                        <p class="stats-title">Course Coverage</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card chart-card">
            <div class="card-header">
                <span>Wishlist Trends</span>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary active" id="daily-btn">Daily</button>
                    <button class="btn btn-sm btn-outline-primary" id="monthly-btn">Monthly</button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="wishlistTrendsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card chart-card">
            <div class="card-header">
                <span>Top Wishlisted Courses</span>
                <a href="{{ route('admin.wishlist.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="top-courses-list">
                    @forelse($topCourses as $index => $course)
                        <div class="top-item">
                            <div class="top-item-rank">{{ $index + 1 }}</div>
                            <div class="top-item-content">
                                <div class="top-item-title">{{ $course->course->title ?? 'Unknown Course' }}</div>
                                <div class="top-item-subtitle">
                                    @if($course->course && $course->course->price)
                                        Rp {{ number_format($course->course->price, 0, ',', '.') }}
                                    @else
                                        Free
                                    @endif
                                </div>
                            </div>
                            <div class="top-item-value">{{ $course->total }}</div>
                        </div>
                    @empty
                        <p class="text-center py-3">No data available</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Second Row of Charts -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card chart-card">
            <div class="card-header">
                <span>Most Active Users</span>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="activeUsersChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <span>Recent Wishlist Activity</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-recent">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Course</th>
                                <th>Added</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivities as $activity)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <div class="fw-bold">{{ $activity->user->name ?? 'Unknown User' }}</div>
                                                <div class="small text-muted">{{ $activity->user->email ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($activity->course && $activity->course->image)
                                                <img src="{{ asset('image/dashboard_kursus/' . $activity->course->image) }}" 
                                                     class="course-image-small me-2" 
                                                     alt="{{ $activity->course->title ?? 'Course' }}">
                                            @endif
                                            <div>{{ $activity->course->title ?? 'Unknown Course' }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="activity-time">
                                            {{ $activity->created_at->diffForHumans() }}
                                            <div class="small text-muted">{{ $activity->created_at->format('M d, Y H:i') }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('features.course.show', $activity->course_id ?? 0) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No recent activity</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prepare data for charts
    const dailyLabels = @json($dailyWishlists->pluck('date'));
    const dailyData = @json($dailyWishlists->pluck('total'));
    
    const monthlyLabels = @json($monthlyWishlists->pluck('month'));
    const monthlyData = @json($monthlyWishlists->pluck('total'));
    
    const activeUserLabels = @json($activeUsers->map(function($user) {
        return $user->user ? $user->user->name : 'Unknown User';
    }));
    const activeUserData = @json($activeUsers->pluck('total'));
    
    // Set up color schemes
    const primaryColor = '#4e73df';
    const secondaryColor = '#1cc88a';
    const tertiaryColor = '#36b9cc';
    const quaternaryColor = '#f6c23e';
    
    // Create Wishlist Trends Chart (initially with daily data)
    const trendsCtx = document.getElementById('wishlistTrendsChart').getContext('2d');
    const trendsChart = new Chart(trendsCtx, {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Daily Wishlists',
                data: dailyData,
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: primaryColor,
                borderWidth: 2,
                pointBackgroundColor: primaryColor,
                pointBorderColor: '#fff',
                pointHoverRadius: 5,
                pointHoverBackgroundColor: primaryColor,
                pointHoverBorderColor: '#fff',
                pointHitRadius: 10,
                pointBorderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#000',
                    bodyColor: '#000',
                    borderColor: '#ddd',
                    borderWidth: 1,
                    padding: 10,
                    displayColors: false,
                    callbacks: {
                        title: function(tooltipItems) {
                            return 'Date: ' + tooltipItems[0].label;
                        },
                        label: function(context) {
                            return 'Wishlists: ' + context.raw;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
    
    // Toggle between daily and monthly data
    document.getElementById('daily-btn').addEventListener('click', function() {
        this.classList.add('active');
        document.getElementById('monthly-btn').classList.remove('active');
        
        trendsChart.data.labels = dailyLabels;
        trendsChart.data.datasets[0].data = dailyData;
        trendsChart.data.datasets[0].label = 'Daily Wishlists';
        trendsChart.update();
    });
    
    document.getElementById('monthly-btn').addEventListener('click', function() {
        this.classList.add('active');
        document.getElementById('daily-btn').classList.remove('active');
        
        trendsChart.data.labels = monthlyLabels;
        trendsChart.data.datasets[0].data = monthlyData;
        trendsChart.data.datasets[0].label = 'Monthly Wishlists';
        trendsChart.update();
    });
    
    // Create Active Users Chart
    const activeUsersCtx = document.getElementById('activeUsersChart').getContext('2d');
    new Chart(activeUsersCtx, {
        type: 'bar',
        data: {
            labels: activeUserLabels,
            datasets: [{
                label: 'Wishlists',
                data: activeUserData,
                backgroundColor: [
                    'rgba(78, 115, 223, 0.8)',
                    'rgba(28, 200, 138, 0.8)',
                    'rgba(54, 185, 204, 0.8)',
                    'rgba(246, 194, 62, 0.8)',
                    'rgba(231, 74, 59, 0.8)',
                    'rgba(133, 135, 150, 0.8)',
                    'rgba(105, 0, 132, 0.8)',
                    'rgba(0, 99, 132, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(255, 159, 64, 0.8)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#000',
                    bodyColor: '#000',
                    borderColor: '#ddd',
                    borderWidth: 1,
                    padding: 10,
                    displayColors: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
});
</script>
@endpush
