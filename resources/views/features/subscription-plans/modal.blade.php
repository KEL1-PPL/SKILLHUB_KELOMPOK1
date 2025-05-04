<!-- Subscription Plans Modal -->
<div class="modal fade" id="subscriptionModal" tabindex="-1" aria-labelledby="subscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subscriptionModalLabel">Pilih Paket Berlangganan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <p class="text-muted">Tingkatkan akses Anda dengan berlangganan paket premium</p>
                </div>
                
                <div class="row">
                    <!-- Starter Plan -->
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">SkillHub Starter</h5>
                                <div class="my-3">
                                    <span class="h3">Rp50.000</span>
                                    <span class="text-muted">/bulan</span>
                                </div>
                                <p class="card-text text-muted">Ideal untuk pemula yang ingin mulai belajar pemrograman dengan akses dasar.</p>
                                
                                <ul class="list-unstyled mt-3">
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span>Akses ke semua kursus</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span>Sertifikat penyelesaian</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span>Forum diskusi komunitas</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <a href="{{ route('subscription.checkout', ['plan' => 1]) }}" 
                                    class="btn btn-outline-dark w-100">
                                    Pilih Paket Starter
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pro Plan -->
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 shadow border border-primary position-relative">
                            <div class="position-absolute badge bg-primary" style="top:-10px;left:50%;transform:translateX(-50%)">
                                Popular
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">SkillHub Pro</h5>
                                <div class="my-3">
                                    <span class="h3">Rp100.000</span>
                                    <span class="text-muted">/bulan</span>
                                </div>
                                <p class="card-text text-muted">Untuk siswa yang ingin pembelajaran lebih intensif dan proyek nyata.</p>
                                
                                <ul class="list-unstyled mt-3">
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span>Akses ke semua kursus</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span>Live Class dan Proyek Portofolio</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span>Sertifikat penyelesaian</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span>Forum komunitas + sesi tanya jawab</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <a href="{{ route('subscription.checkout', ['plan' => 2]) }}" 
                                    class="btn btn-primary w-100">
                                    Pilih Paket Pro
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Elite Plan -->
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">SkillHub Elite</h5>
                                <div class="my-3">
                                    <span class="h3">Rp900.000</span>
                                    <span class="text-muted">/tahun</span>
                                </div>
                                <p class="card-text text-muted">Langganan tahunan eksklusif dengan semua fitur premium dan keuntungan tambahan.</p>
                                
                                <ul class="list-unstyled mt-3">
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span>Akses semua kursus & fitur premium</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span>Proyek portofolio eksklusif</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span>Webinar bulanan & sesi mentoring</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span>Sertifikat penyelesaian premium</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span>Diskon bootcamp & event khusus</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <a href="{{ route('subscription.checkout', ['plan' => 3]) }}" 
                                    class="btn btn-outline-dark w-100">
                                    Pilih Paket Elite
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>