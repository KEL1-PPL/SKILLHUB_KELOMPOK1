<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Validator;

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

        // Handle checkbox is_active
        $data['is_active'] = $request->has('is_active') ? true : false;
        
        // Convert features from array to JSON
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
        return view('admin.subscription-plans.show');
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

        // Handle checkbox is_active
        $data['is_active'] = $request->has('is_active') ? true : false;
        
        // Convert features from array to JSON
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
}
