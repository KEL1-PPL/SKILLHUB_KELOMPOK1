@extends('all.component.app')

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            <div class="row">
                <div class="col-12 mb-3">
                    <a href="{{ route('features.material.index', $course->id) }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left"></i> Kembali ke Daftar Materi
                    </a>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">{{ $material->title }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                {!! nl2br(e($material->content)) !!}
                            </div>
                            
                            @if(auth()->user()->role === 'siswa')
                                <form action="{{ route('features.material.toggle-completion', [$course->id, $material->id]) }}" 
                                      method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $isCompleted ? 'btn-success' : 'btn-outline-success' }}">
                                        <i class="ti ti-{{ $isCompleted ? 'check' : 'circle' }}"></i>
                                        {{ $isCompleted ? 'Tandai Belum Selesai' : 'Tandai Selesai' }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Progress Belajar</h4>
                        </div>
                        <div class="card-body">
                            @if(auth()->user()->role === 'siswa' && $progress !== null)
                                <div class="progress mb-3" style="height: 20px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: {{ $progress }}%;" 
                                         aria-valuenow="{{ $progress }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ $progress }}%
                                    </div>
                                </div>
                                <p class="mb-0">Anda telah menyelesaikan {{ $progress }}% dari total materi.</p>
                            @else
                                <p class="mb-0">Progress belajar hanya tersedia untuk siswa.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 