@extends('all.component.app')

@push('styles')
    <!-- Google Fonts: Figtree -->
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa;
        }

        .page-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 2rem 0;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .stats-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #007bff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 1rem;
        }

        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #007bff;
            margin-bottom: 0.5rem;
        }

        .stats-label {
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        .analysis-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            border: 1px solid #dee2e6;
        }

        .card-title {
            color: #343a40;
            font-weight: 700;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }

        .difficulty-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .difficulty-easy {
            background-color: #d4edda;
            color: #155724;
        }

        .difficulty-medium {
            background-color: #fff3cd;
            color: #856404;
        }

        .difficulty-hard {
            background-color: #f8d7da;
            color: #721c24;
        }

        .difficulty-very-hard {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .progress-custom {
            height: 8px;
            border-radius: 4px;
            background-color: #e9ecef;
        }

        .progress-bar-custom {
            border-radius: 4px;
            transition: width 0.6s ease;
        }

        .student-row {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s ease;
            border-radius: 6px;
            margin-bottom: 0.5rem;
        }

        .student-row:hover {
            background-color: #f8f9fa;
        }

        .trend-positive {
            color: #28a745;
        }

        .trend-negative {
            color: #dc3545;
        }

        .trend-neutral {
            color: #6c757d;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin: 1rem 0;
        }

        .btn-back {
            background-color: #6c757d;
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn-back:hover {
            background-color: #5a6268;
            color: white;
        }

        .score-distribution {
            display: flex;
            gap: 1rem;
            margin: 1rem 0;
            flex-wrap: wrap;
        }

        .score-range {
            flex: 1;
            min-width: 120px;
            text-align: center;
            padding: 1rem;
            border-radius: 6px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }

        .score-count {
            font-size: 1.5rem;
            font-weight: 700;
            color: #007bff;
            margin-bottom: 0.25rem;
        }

        .question-analysis-item {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid #007bff;
            border: 1px solid #dee2e6;
        }

        .accuracy-bar {
            width: 100%;
            height: 20px;
            background-color: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin: 0.5rem 0;
        }

        .accuracy-fill {
            height: 100%;
            background: linear-gradient(90deg, #dc3545, #ffc107, #28a745);
            transition: width 0.6s ease;
        }

        .tab-content {
            margin-top: 1rem;
        }

        .nav-tabs .nav-link {
            color: #007bff;
            font-weight: 600;
            border-radius: 6px 6px 0 0;
            border: 1px solid transparent;
        }

        .nav-tabs .nav-link.active {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }

        .nav-tabs .nav-link:hover {
            border-color: #e9ecef #e9ecef #dee2e6;
        }

        /* Progress badges similar to index.blade */
        .progress-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        /* Progress mini bars */
        .progress-mini {
            height: 8px;
            border-radius: 4px;
        }

        /* Table styling consistency */
        .table {
            border-radius: 6px;
            overflow: hidden;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-light {
            background-color: #f8f9fa;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Card styling consistency */
        .card {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-radius: 8px 8px 0 0;
            padding: 1rem 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Badge styling */
        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .page-header {
                padding: 1rem;
                text-align: center;
            }

            .score-distribution {
                flex-direction: column;
            }

            .score-range {
                min-width: auto;
            }

            .stats-number {
                font-size: 2rem;
            }
        }

        /* Animation classes */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .fade-in.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Empty state styling */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>
@endpush

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            <!-- Page Header -->
            <div class="page-header text-center">
                <div class="row align-items-center">
                    <div class="col-md-8 mx-auto text-center">
                        <h1 class="mb-2 text-center">
                            <i class="fas fa-chart-bar"></i> Analisis Hasil Quiz
                        </h1>
                        <h3 class="mb-1 text-center">{{ $quiz->title }}</h3>
                        <p class="mb-0 text-center">
                            <strong>Kursus:</strong> {{ $quiz->course->title }}
                            @if ($quiz->material)
                                | <strong>Materi:</strong> {{ $quiz->material->title }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            @if ($stats['total_attempts'] == 0)
                <div class="analysis-card">
                    <div class="empty-state">
                        <i class="fas fa-chart-line"></i>
                        <h4 class="mb-3">Belum Ada Data untuk Dianalisis</h4>
                        <p>Quiz ini belum pernah dikerjakan oleh siswa. Analisis akan tersedia setelah ada siswa yang
                            mengerjakan quiz.</p>
                        <a href="{{ route('mentor.quizzes.show', $quiz) }}" class="btn btn-primary">
                            Lihat Detail Quiz
                        </a>
                    </div>
                </div>
            @else
                <!-- Overall Statistics -->
                <div class="row mb-4 mt-5">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stats-card text-center fade-in">
                            <div class="stats-number">{{ $stats['total_attempts'] }}</div>
                            <div class="stats-label">Total Percobaan</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stats-card text-center fade-in">
                            <div class="stats-number">{{ $stats['unique_students'] }}</div>
                            <div class="stats-label">Siswa Unik</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stats-card text-center fade-in">
                            <div class="stats-number">{{ $stats['average_score'] }}%</div>
                            <div class="stats-label">Rata-rata Nilai</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stats-card text-center fade-in">
                            <div class="stats-number">{{ $stats['pass_rate'] }}%</div>
                            <div class="stats-label">Tingkat Kelulusan</div>
                        </div>
                    </div>
                </div>

                <!-- Additional Stats Row -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="stats-card text-center fade-in">
                            <div class="stats-number text-success">{{ $stats['highest_score'] }}%</div>
                            <div class="stats-label">Nilai Tertinggi</div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stats-card text-center fade-in">
                            <div class="stats-number text-warning">{{ $stats['lowest_score'] }}%</div>
                            <div class="stats-label">Nilai Terendah</div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stats-card text-center fade-in">
                            <div class="stats-number text-info">{{ $stats['average_duration'] }}</div>
                            <div class="stats-label">Rata-rata Waktu</div>
                        </div>
                    </div>
                </div>

                <!-- Analysis Tabs -->
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="analysisTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab"
                                    data-bs-target="#overview" type="button" role="tab">
                                    <i class="fas fa-chart-pie"></i> Ringkasan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="questions-tab" data-bs-toggle="tab" data-bs-target="#questions"
                                    type="button" role="tab">
                                    <i class="fas fa-question-circle"></i> Analisis Soal
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="students-tab" data-bs-toggle="tab" data-bs-target="#students"
                                    type="button" role="tab">
                                    <i class="fas fa-users"></i> Performa Siswa
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="analysisTabContent">
                            <!-- Overview Tab -->
                            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                                <div class="row">
                                    <div class="w-full md:w-1/2 ">
                                        <div class="bg-white rounded-lg shadow-md">
                                            <h5
                                                class="text-2xl font-bold text-gray-800 mb-4 border-b-2 border-gray-200 pb-3 flex items-center">
                                                <span class="text-2xl mr-3">📊</span>
                                                Distribusi Nilai
                                            </h5>

                                            <div class="space-y-2">
                                                <!-- Excellent: 90-100% -->
                                                <div
                                                    class="flex items-center justify-between bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border-l-8 border-green-500 hover:shadow-lg transition-all duration-200">
                                                    <div class="flex items-center space-x-4">
                                                        <div class="w-5 h-5 bg-green-500 rounded-full shadow-md"></div>
                                                        <div class="flex flex-col">
                                                            <span class="text-lg font-bold text-gray-800">90-100%</span>
                                                            <span
                                                                class="text-sm px-3 py-1 bg-green-100 text-green-700 rounded-full font-medium">Excellent</span>
                                                        </div>
                                                    </div>
                                                    <div class="text-4xl font-black text-green-600 min-w-[60px] text-right">
                                                        {{ $scoreRanges['90-100'] }}</div>
                                                </div>

                                                <!-- Good: 80-89% -->
                                                <div
                                                    class="flex items-center justify-between bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl border-l-8 border-blue-500 hover:shadow-lg transition-all duration-200">
                                                    <div class="flex items-center space-x-4">
                                                        <div class="w-5 h-5 bg-blue-500 rounded-full shadow-md"></div>
                                                        <div class="flex flex-col">
                                                            <span class="text-lg font-bold text-gray-800">80-89%</span>
                                                            <span
                                                                class="text-sm px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-medium">Good</span>
                                                        </div>
                                                    </div>
                                                    <div class="text-4xl font-black text-blue-600 min-w-[60px] text-right">
                                                        {{ $scoreRanges['80-89'] }}</div>
                                                </div>

                                                <!-- Average: 70-79% -->
                                                <div
                                                    class="flex items-center justify-between bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl border-l-8 border-yellow-500 hover:shadow-lg transition-all duration-200">
                                                    <div class="flex items-center space-x-4">
                                                        <div class="w-5 h-5 bg-yellow-500 rounded-full shadow-md"></div>
                                                        <div class="flex flex-col">
                                                            <span class="text-lg font-bold text-gray-800">70-79%</span>
                                                            <span
                                                                class="text-sm px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full font-medium">Average</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="text-4xl font-black text-yellow-600 min-w-[60px] text-right">
                                                        {{ $scoreRanges['70-79'] }}</div>
                                                </div>

                                                <!-- Below Average: 60-69% -->
                                                <div
                                                    class="flex items-center justify-between bg-gradient-to-r from-orange-50 to-red-50 rounded-xl border-l-8 border-orange-500 hover:shadow-lg transition-all duration-200">
                                                    <div class="flex items-center space-x-4">
                                                        <div class="w-5 h-5 bg-orange-500 rounded-full shadow-md"></div>
                                                        <div class="flex flex-col">
                                                            <span class="text-lg font-bold text-gray-800">60-69%</span>
                                                            <span
                                                                class="text-sm px-3 py-1 bg-orange-100 text-orange-700 rounded-full font-medium">Below
                                                                Avg</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="text-4xl font-black text-orange-600 min-w-[60px] text-right">
                                                        {{ $scoreRanges['60-69'] }}</div>
                                                </div>

                                                <!-- Poor: 0-59% -->
                                                <div
                                                    class="flex items-center justify-between bg-gradient-to-r from-red-50 to-pink-50 rounded-xl border-l-8 border-red-500 hover:shadow-lg transition-all duration-200">
                                                    <div class="flex items-center space-x-4">
                                                        <div class="w-5 h-5 bg-red-500 rounded-full shadow-md"></div>
                                                        <div class="flex flex-col">
                                                            <span class="text-lg font-bold text-gray-800">0-59%</span>
                                                            <span class="text-sm px-3 py-1 bg-red-100 text-red-700 rounded-full font-medium">Poor</span>
                                                        </div>
                                                    </div>
                                                    <div class="text-4xl font-black text-red-600 min-w-[60px] text-right">
                                                        {{ $scoreRanges['0-59'] }}</div>
                                                </div>
                                            </div>

                                            <!-- Summary info -->
                                            <div class="mt-4 pt-3 border-t border-gray-200">
                                                <p class="text-sm text-gray-600 text-center font-medium">
                                                    Total peserta berdasarkan rentang nilai quiz
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Questions Analysis Tab -->
                            <div class="tab-pane fade" id="questions" role="tabpanel">
                                <h5 class="card-title">Analisis Tingkat Kesulitan Soal</h5>
                                <p class="text-muted mb-4">Soal diurutkan berdasarkan tingkat kesulitan (tingkat akurasi
                                    terendah di atas)</p>

                                @foreach ($questionAnalysis as $index => $analysis)
                                    <div class="question-analysis-item">
                                        <div class="row align-items-center">
                                            <div class="col-md-1">
                                                <h6 class="mb-0">#{{ $index + 1 }}</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1">
                                                    <strong>{{ Str::limit($analysis['question']->question, 100) }}</strong>
                                                </p>
                                                <small class="text-muted">{{ ucfirst($analysis['question']->type) }} •
                                                    {{ $analysis['question']->points }} poin</small>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="accuracy-bar">
                                                    <div class="accuracy-fill"
                                                        style="width: {{ $analysis['accuracy_rate'] }}%"></div>
                                                </div>
                                                <small>{{ $analysis['accuracy_rate'] }}% akurasi
                                                    ({{ $analysis['correct_answers'] }}/{{ $analysis['total_answers'] }})
                                                </small>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <span
                                                    class="difficulty-badge difficulty-{{ strtolower(str_replace(' ', '-', $analysis['difficulty_level'])) }}">
                                                    {{ $analysis['difficulty_level'] }}
                                                </span>
                                            </div>
                                        </div>

                                        @if (!empty($analysis['common_wrong_answers']))
                                            <div class="mt-2">
                                                <small class="text-muted"><strong>Jawaban salah yang sering
                                                        dipilih:</strong></small>
                                                <ul class="mb-0 mt-1">
                                                    @foreach (array_slice($analysis['common_wrong_answers'], 0, 3, true) as $option => $count)
                                                        <li><small>{{ $option }} ({{ $count }} siswa)</small>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Students Performance Tab -->
                            <div class="tab-pane fade" id="students" role="tabpanel">
                                <h5 class="card-title">Performa Individual Siswa</h5>
                                <p class="text-muted mb-4">Diurutkan berdasarkan nilai terbaik</p>

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nama Siswa</th>
                                                <th class="text-center">Nilai Terbaik</th>
                                                <th class="text-center">Rata-rata</th>
                                                <th class="text-center">Percobaan</th>
                                                <th class="text-center">Tren</th>
                                                <th class="text-center">Terakhir</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($studentPerformance as $student)
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <h6 class="mb-1">{{ $student['user']->name }}</h6>
                                                            <small
                                                                class="text-muted">{{ $student['user']->email }}</small>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="stats-number" style="font-size: 1.5rem;">
                                                            {{ $student['best_score'] }}%</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div style="font-size: 1.2rem; font-weight: 600;">
                                                            {{ $student['average_score'] }}%</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div style="font-size: 1.2rem; font-weight: 600;">
                                                            {{ $student['completed_attempts'] }}</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge progress-badge
                                                            @if (str_contains($student['improvement_trend'], 'Meningkat')) bg-success
                                                            @elseif(str_contains($student['improvement_trend'], 'Menurun')) bg-danger
                                                            @else bg-secondary @endif
                                                        ">
                                                            {{ $student['improvement_trend'] }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <small
                                                            class="text-muted">{{ $student['last_attempt']->diffForHumans() }}</small>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recommendations Card -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-lightbulb text-warning"></i> Rekomendasi Kurikulum
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Berdasarkan Analisis Soal:</h6>
                                <ul>
                                    @if ($questionAnalysis->where('accuracy_rate', '<', 60)->count() > 0)
                                        <li>Terdapat {{ $questionAnalysis->where('accuracy_rate', '<', 60)->count() }} soal
                                            dengan tingkat kesulitan tinggi (akurasi < 60%). Pertimbangkan untuk menambah
                                                materi pembelajaran terkait.</li>
                                    @endif
                                    @if ($questionAnalysis->where('accuracy_rate', '>', 90)->count() > 0)
                                        <li>{{ $questionAnalysis->where('accuracy_rate', '>', 90)->count() }} soal terlalu
                                            mudah (akurasi > 90%). Pertimbangkan untuk meningkatkan kompleksitas.</li>
                                    @endif
                                    @if ($stats['pass_rate'] < 70)
                                        <li>Tingkat kelulusan rendah ({{ $stats['pass_rate'] }}%). Disarankan untuk
                                            meninjau ulang materi pembelajaran atau menurunkan passing score.</li>
                                    @endif
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Berdasarkan Performa Siswa:</h6>
                                <ul>
                                    @if ($studentPerformance->where('improvement_trend', 'like', '%Menurun%')->count() > 0)
                                        <li>{{ $studentPerformance->where('improvement_trend', 'like', '%Menurun%')->count() }}
                                            siswa menunjukkan tren penurunan. Berikan perhatian khusus dan bantuan tambahan.
                                        </li>
                                    @endif
                                    @if ($stats['average_score'] < $quiz->passing_score)
                                        <li>Rata-rata nilai ({{ $stats['average_score'] }}%) di bawah passing score
                                            ({{ $quiz->passing_score }}%). Pertimbangkan remedial atau review materi.</li>
                                    @endif
                                    <li>Fokuskan pembelajaran pada topik yang terkait dengan soal-soal berakurasi rendah
                                        untuk meningkatkan pemahaman siswa.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
                const fadeElements = document.querySelectorAll('.fade-in');
                fadeElements.forEach((el, index) => {
                    setTimeout(() => {
                        el.classList.add('show');
                    }, index * 100);
                });

                @if ($stats['total_attempts'] > 0)
                    const scoreCtx = document.getElementById('scoreDistributionChart').getContext('2d');
                    new Chart(scoreCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['90-100%', '80-89%', '70-79%', '60-69%', '0-59%'],
                            datasets: [{
                                data: [
                                    {{ $scoreRanges['90-100'] }},
                                    {{ $scoreRanges['80-89'] }},
                                    {{ $scoreRanges['70-79'] }},
                                    {{ $scoreRanges['60-69'] }},
                                    {{ $scoreRanges['0-59'] }}
                                ],
                                backgroundColor: [
                                    '#28a745',
                                    '#20c997',
                                    '#ffc107',
                                    '#fd7e14',
                                    '#dc3545'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });

                    const dailyCtx = document.getElementById('dailyTrendChart').getContext('2d');
                    new Chart(dailyCtx, {
                        type: 'line',
                        data: {
                            labels: [
                                @foreach ($timeAnalysis['daily'] as $day)
                                    '{{ date('d/m', strtotime($day['date'])) }}',
                                @endforeach
                            ],
                            datasets: [{
                                label: 'Jumlah Percobaan',
                                data: [
                                    @foreach ($timeAnalysis['daily'] as $day)
                                        {{ $day['attempts'] }},
                                    @endforeach
                                ],
                                borderColor: '#007bff',
                                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                    const hourlyCtx = document.getElementById('hourlyDistributionChart').getContext('2d');
                    new Chart(hourlyCtx, {
                        type: 'bar',
                        data: {
                            labels: [
                                @foreach ($timeAnalysis['hourly'] as $hour)
                                    '{{ $hour['hour'] }}',
                                @endforeach
                            ],
                            datasets: [{
                                label: 'Jumlah Percobaan',
                                data: [
                                    @foreach ($timeAnalysis['hourly'] as $hour)
                                        {{ $hour['attempts'] }},
                                    @endforeach
                                ],
                                backgroundColor: 'rgba(0, 123, 255, 0.8)'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
        @endif
    </script>
@endpush
