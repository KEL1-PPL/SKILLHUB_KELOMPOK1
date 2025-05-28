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
                
                @php
                    $activePlans = \App\Models\SubscriptionPlan::active()->get();
                    $totalPlans = count($activePlans);
                    $popularIndex = min(1, $totalPlans - 1);
                    $newThreshold = now()->subDays(7);
                @endphp
                
                @if($totalPlans > 0)
                    <!-- Tombol navigasi -->
                    @if($totalPlans > 1)
                        <div class="d-flex justify-content-between mb-3">
                            <button class="btn btn-sm btn-outline-secondary me-2" id="prevPackage">
                                <i class="bi bi-chevron-left"></i> Sebelumnya
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" id="nextPackage">
                                Selanjutnya <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    @endif
                    
                    <!-- Container carousel horizontal -->
                    <div class="subscription-container">
                        <div class="subscription-slider" id="planSlider">
                            @foreach($activePlans as $index => $plan)
                                <div class="plan-card">
                                    <div class="card h-100 shadow-sm {{ $index == $popularIndex ? 'border border-primary' : '' }}">
                                        @if($index == $popularIndex)
                                            <div class="badge-label popular-badge">Popular</div>
                                        @endif
                                        @if(isset($plan->created_at) && $plan->created_at->gt($newThreshold))
                                            <div class="badge-label new-badge">New</div>
                                        @endif
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $plan->name }}</h5>
                                            <div class="my-3">
                                                <span class="h3">{{ $plan->formatted_price }}</span>
                                                <span class="text-muted">/{{ $plan->duration_in_days >= 365 ? 'tahun' : 'bulan' }}</span>
                                            </div>
                                            <p class="card-text text-muted">{{ $plan->description }}</p>
                                            
                                            <ul class="list-unstyled mt-3">
                                                @foreach(json_decode($plan->features) as $feature)
                                                    <li class="mb-2">
                                                        <i class="bi bi-check-circle text-success me-2"></i>
                                                        <span>{{ $feature }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="card-footer bg-transparent border-top-0">
                                            <a href="{{ route('subscription.checkout', ['plan' => $plan->id]) }}" 
                                                class="btn {{ $index == $popularIndex ? 'btn-primary' : 'btn-outline-dark' }} w-100">
                                                Pilih Paket
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Indikator posisi (dots) -->
                    @if($totalPlans > 1)
                        <div class="dots-container text-center mt-3">
                            @for($i = 0; $i < $totalPlans; $i++)
                                <button class="dot-indicator {{ $i == 0 ? 'active' : '' }}" data-index="{{ $i }}"></button>
                            @endfor
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <p>Belum ada paket berlangganan yang tersedia</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .subscription-container {
        position: relative;
        overflow: hidden;
        margin: 0 -15px;
        padding: 0 15px;
    }
    
    .subscription-slider {
        display: flex;
        overflow-x: auto;
        scroll-behavior: smooth;
        gap: 15px;
        padding: 10px 0;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    
    .subscription-slider::-webkit-scrollbar {
        display: none;
    }
    
    .plan-card {
        flex: 0 0 auto;
        width: calc(100% - 10px);
        transition: transform 0.3s ease;
    }
    
    .badge-label {
        position: absolute;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
        z-index: 2;
        color: white;
    }
    
    .popular-badge {
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #0d6efd;
    }
    
    .new-badge {
        top: -10px;
        right: 10px;
        background-color: #28a745;
    }
    
    .plan-card .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .dots-container {
        display: flex;
        justify-content: center;
        gap: 8px;
    }
    
    .dot-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #dee2e6;
        border: none;
        padding: 0;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .dot-indicator.active {
        background-color: #0d6efd;
        transform: scale(1.2);
    }
    
    @media (min-width: 576px) {
        .plan-card {
            width: calc(85% - 15px);
        }
    }
    
    @media (min-width: 768px) {
        .plan-card {
            width: calc(50% - 15px);
        }
    }
    
    @media (min-width: 992px) {
        .plan-card {
            width: calc(33.33% - 15px);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        initPlanSlider();
        
        document.getElementById('subscriptionModal').addEventListener('shown.bs.modal', function() {
            initPlanSlider();
        });

        window.addEventListener('resize', function() {
            setTimeout(initPlanSlider, 250);
        });
    });

    function initPlanSlider() {
        const slider = document.getElementById('planSlider');
        if (!slider) return;
        
        const cards = slider.querySelectorAll('.plan-card');
        const totalCards = cards.length;
        if (totalCards <= 1) return; 
        
        const prevBtn = document.getElementById('prevPackage');
        const nextBtn = document.getElementById('nextPackage');
        const dots = document.querySelectorAll('.dot-indicator');
        
        let currentIndex = 0;
        
        function getVisibleItems() {
            if (window.innerWidth >= 992) return 3;
            if (window.innerWidth >= 768) return 2;
            if (window.innerWidth >= 576) return 1;
            return 1;
        }
        
        function getItemWidth() {
            if (cards.length === 0) return 0;
            const card = cards[0];
            return card.offsetWidth + 15; 
        }
        
        function scrollToIndex(index) {
            const maxIndex = Math.max(0, totalCards - getVisibleItems());
            index = Math.max(0, Math.min(index, maxIndex));
            
            currentIndex = index;
            const scrollAmount = index * getItemWidth();
            
            slider.scrollTo({
                left: scrollAmount,
                behavior: 'smooth'
            });
            
            updateUI();
        }
        
        function updateUI() {
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === currentIndex);
            });
            
            if (prevBtn) {
                prevBtn.disabled = currentIndex <= 0;
            }
            if (nextBtn) {
                nextBtn.disabled = currentIndex >= totalCards - getVisibleItems();
            }
        }
        
        if (prevBtn) {
            prevBtn.addEventListener('click', () => scrollToIndex(currentIndex - 1));
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', () => scrollToIndex(currentIndex + 1));
        }
        
        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => scrollToIndex(i));
        });
        
        let touchStartX = 0;
        let touchEndX = 0;
        
        slider.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        slider.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            
            const threshold = 75;
            if (touchStartX - touchEndX > threshold) {
                scrollToIndex(currentIndex + 1);
            } else if (touchEndX - touchStartX > threshold) {
                scrollToIndex(currentIndex - 1);
            }
        });
        
        slider.addEventListener('scroll', function() {
            const scrollPos = slider.scrollLeft;
            const itemWidth = getItemWidth();
            
            if (itemWidth > 0) {
                const newIndex = Math.round(scrollPos / itemWidth);
                if (newIndex !== currentIndex) {
                    currentIndex = newIndex;
                    updateUI();
                }
            }
        });
        
        updateUI();
        
        setTimeout(() => {
            const newCard = document.querySelector('.new-badge');
            if (newCard) {
                const newIndex = Array.from(cards).findIndex(card => 
                    card.querySelector('.new-badge') !== null
                );
                
                if (newIndex !== -1) {
                    scrollToIndex(Math.max(0, newIndex));
                } else {
                    scrollToIndex(0);
                }
            } else {
                scrollToIndex(0);
            }
        }, 100);
    }
</script>