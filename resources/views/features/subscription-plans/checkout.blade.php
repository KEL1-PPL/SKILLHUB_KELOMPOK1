@extends('all.component.app')

@section('content')
<div class="container-fluid" style="padding-top: 120px;">
    <div class="row mb-3">
        <div class="col-lg-10 offset-lg-2 col-md-12 px-4">
            <h1 class="h4 fw-bold">Checkout Paket Berlangganan</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 offset-lg-2 col-md-12 px-4">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="m-0 font-weight-bold">Detail Paket</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>{{ $subscriptionPlan->name }}</h3>
                            <p class="text-muted">{{ $subscriptionPlan->description }}</p>
                            
                            <div class="mb-3">
                                <h5>Harga</h5>
                                <p class="h3 text-primary">{{ $subscriptionPlan->formatted_price }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <h5>Durasi</h5>
                                <p>{{ $subscriptionPlan->formatted_duration }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <h5>Fitur yang Didapatkan</h5>
                                <ul class="list-group list-group-flush">
                                    @foreach(json_decode($subscriptionPlan->features) as $feature)
                                        <li class="list-group-item border-0 px-0">
                                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="m-0">Metode Pembayaran</h5>
                                </div>
                                <div class="card-body">
                                    <form action="#" method="POST">
                                        @csrf
                                        <input type="hidden" name="subscription_plan_id" value="{{ $subscriptionPlan->id }}">
                                        
                                        <div class="mb-3">
                                            <label for="payment_method" class="form-label">Pilih Metode Pembayaran</label>
                                            <select class="form-select" id="payment_method" name="payment_method" required>
                                                <option value="" selected disabled>-- Pilih Metode Pembayaran --</option>
                                                <option value="bank_transfer">Transfer Bank</option>
                                                <option value="credit_card">Kartu Kredit</option>
                                                <option value="e_wallet">E-Wallet</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3" id="bank_details" style="display: none;">
                                            <label class="form-label">Pilih Bank</label>
                                            <div class="d-flex flex-wrap gap-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="bank" id="bank_bca" value="bca">
                                                    <label class="form-check-label" for="bank_bca">BCA</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="bank" id="bank_bni" value="bni">
                                                    <label class="form-check-label" for="bank_bni">BNI</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="bank" id="bank_mandiri" value="mandiri">
                                                    <label class="form-check-label" for="bank_mandiri">Mandiri</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3" id="ewallet_details" style="display: none;">
                                            <label class="form-label">Pilih E-Wallet</label>
                                            <div class="d-flex flex-wrap gap-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ewallet" id="ewallet_gopay" value="gopay">
                                                    <label class="form-check-label" for="ewallet_gopay">GoPay</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ewallet" id="ewallet_ovo" value="ovo">
                                                    <label class="form-check-label" for="ewallet_ovo">OVO</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ewallet" id="ewallet_dana" value="dana">
                                                    <label class="form-check-label" for="ewallet_dana">DANA</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="coupon" class="form-label">Kode Voucher (opsional)</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="coupon" name="coupon" placeholder="Masukkan kode voucher">
                                                <button class="btn btn-outline-secondary" type="button">Terapkan</button>
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        
                                        <div class="d-flex justify-content-between mb-3">
                                            <span>Subtotal</span>
                                            <span>{{ $subscriptionPlan->formatted_price }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-3">
                                            <span>Diskon</span>
                                            <span id="discount">Rp 0</span>
                                        </div>
                                        <div class="d-flex justify-content-between fw-bold">
                                            <span>Total</span>
                                            <span id="total">{{ $subscriptionPlan->formatted_price }}</span>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-primary w-100">Bayar Sekarang</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('payment_method').addEventListener('change', function() {
        const bankDetails = document.getElementById('bank_details');
        const ewalletDetails = document.getElementById('ewallet_details');
        
        if (this.value === 'bank_transfer') {
            bankDetails.style.display = 'block';
            ewalletDetails.style.display = 'none';
        } else if (this.value === 'e_wallet') {
            bankDetails.style.display = 'none';
            ewalletDetails.style.display = 'block';
        } else {
            bankDetails.style.display = 'none';
            ewalletDetails.style.display = 'none';
        }
    });
</script>
@endpush
@endsection