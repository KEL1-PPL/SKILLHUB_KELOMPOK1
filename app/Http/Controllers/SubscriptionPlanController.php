<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SubscriptionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscriptionPlans = SubscriptionPlan::latest()->paginate(10);
        $title = 'subscription'; 
        return view('features.subscription-plans.index', compact('subscriptionPlans', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'subscription';
        return view('features.subscription-plans.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_in_days' => ['required', 'integer', 'min:1'],
            'features' => ['required', 'array'],
            'features.*' => ['required', 'string'],
            'is_active' => ['boolean'],
        ], [
            'features.required' => 'Minimal satu fitur harus diisi',
            'features.*.required' => 'Fitur tidak boleh kosong',
            'duration_in_days.min' => 'Durasi minimal 1 hari',
            'price.min' => 'Harga tidak boleh negatif',
        ], [
            'name' => 'Nama paket',
            'description' => 'Deskripsi',
            'price' => 'Harga',
            'duration_in_days' => 'Durasi (hari)',
            'features' => 'Fitur',
            'features.*' => 'Fitur',
            'is_active' => 'Status aktif',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        $data['is_active'] = $request->has('is_active') ? true : false;
        
        if (isset($data['features'])) {
            $data['features'] = json_encode($data['features']);
        }

        SubscriptionPlan::create($data);

        return redirect()
            ->route('admin.subscription-plans.index')
            ->with('success', 'Paket berlangganan berhasil ditambahkan.');
    }
    
    /**
     * Display the specified resource.
     */
    public function show(SubscriptionPlan $subscriptionPlan)
    {
        return view('features.subscription-plans.show', compact('subscriptionPlan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        $title = 'subscription';
        return view('features.subscription-plans.edit', compact('subscriptionPlan', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_in_days' => ['required', 'integer', 'min:1'],
            'features' => ['required', 'array'],
            'features.*' => ['required', 'string'],
            'is_active' => ['boolean'],
        ], [
            'features.required' => 'Minimal satu fitur harus diisi',
            'features.*.required' => 'Fitur tidak boleh kosong',
            'duration_in_days.min' => 'Durasi minimal 1 hari',
            'price.min' => 'Harga tidak boleh negatif',
        ], [
            'name' => 'Nama paket',
            'description' => 'Deskripsi',
            'price' => 'Harga',
            'duration_in_days' => 'Durasi (hari)',
            'features' => 'Fitur',
            'features.*' => 'Fitur',
            'is_active' => 'Status aktif',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        $data['is_active'] = $request->has('is_active') ? true : false;
        
        if (isset($data['features'])) {
            $data['features'] = json_encode($data['features']);
        }

        $subscriptionPlan->update($data);

        return redirect()
            ->route('admin.subscription-plans.index')
            ->with('success', 'Paket berlangganan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->delete();
        
        return redirect()
            ->route('admin.subscription-plans.index')
            ->with('success', 'Paket langganan berhasil dihapus!');
    }

    // Menampilkan halaman checkout
    public function checkout($planId)
    {
        $subscriptionPlan = SubscriptionPlan::findOrFail($planId);
        $title = 'transaksi';
        return view('features.subscription-plans.checkout', compact('subscriptionPlan', 'title'));
    }

    public function getActivePlans()
    {
        $activePlans = SubscriptionPlan::active()->get();
        return response()->json($activePlans);
    }

    public function processPayment(Request $request, $planId)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => ['required', 'string', 'in:bank_transfer,e_wallet,credit_card'],
            'phone' => ['required', 'string', 'max:15'],
            'email' => ['required', 'email'],
        ], [
            'payment_method.required' => 'Metode pembayaran harus dipilih',
            'payment_method.in' => 'Metode pembayaran tidak valid',
            'phone.required' => 'Nomor telepon harus diisi',
            'phone.max' => 'Nomor telepon maksimal 15 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $subscriptionPlan = SubscriptionPlan::findOrFail($planId);
        $user = Auth::user();
        
        $invoiceNumber = 'INV-' . strtoupper(uniqid());
        $subtotal = $subscriptionPlan->price;
        $tax = $subtotal * 0.11; // PPN 11%
        $total = $subtotal + $tax;
        $startDate = now();
        $expiryDate = now()->addDays($subscriptionPlan->duration_in_days);
        
        UserSubscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $subscriptionPlan->id,
            'invoice_number' => $invoiceNumber,
            'payment_method' => $request->payment_method,
            'phone' => $request->phone,
            'email' => $request->email,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'status' => 'active',
            'start_date' => $startDate,
            'end_date' => $expiryDate,
        ]);
        
        $paymentData = [
            'invoice_number' => $invoiceNumber,
            'subscription_plan' => $subscriptionPlan,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => $request->payment_method,
            'phone' => $request->phone,
            'email' => $request->email,
            'user' => $user,
            'payment_date' => $startDate,
            'expiry_date' => $expiryDate,
        ];

        return view('features.subscription.invoice', compact('paymentData'))
            ->with('success', 'Pembayaran berhasil! Paket berlangganan Anda telah aktif.');
    }

    public function invoice()
    {
        $paymentData = session('payment_data');
        
        if (!$paymentData) {
            return redirect()->route('dashboard')->with('error', 'Data pembayaran tidak ditemukan.');
        }
        
        return view('features.subscription.invoice', compact('paymentData'));
    }

    public function mySubscriptions()
    {
        $user = Auth::user();
        $subscriptions = UserSubscription::where('user_id', $user->id)
            ->with('subscriptionPlan')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $title = 'transaksi';
        return view('features.subscription.my-subscriptions', compact('subscriptions', 'title'));
    }

    public function subscriptionDetail($id)
    {
        $subscription = UserSubscription::where('user_id', Auth::id())
            ->where('id', $id)
            ->with('subscriptionPlan')
            ->firstOrFail();
            
        $title = 'transaksi';
        return view('features.subscription.detail', compact('subscription', 'title'));
    }
}
