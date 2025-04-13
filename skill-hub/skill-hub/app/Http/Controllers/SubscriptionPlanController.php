<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscriptionPlans = SubscriptionPlan::latest()->paginate(10);
        return view('subscription-plans.index', compact('subscriptionPlans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('subscription-plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubscriptionPlanRequest $request)
    {
        $data = $request->validated();
        
        if (isset($data['features'])) {
            $data['features'] = json_encode($data['features']);
        }

        SubscriptionPlan::create($data);

        return redirect()
            ->route('subscription-plans.index')
            ->with('success', 'Paket berlangganan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        return view('subscription-plans.edit', compact('subscriptionPlan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubscriptionPlanRequest $request, SubscriptionPlan $subscriptionPlan)
    {
        $data = $request->validated();
        
        if (isset($data['features'])) {
            $data['features'] = json_encode($data['features']);
        }

        $subscriptionPlan->update($data);

        return redirect()
            ->route('subscription-plans.index')
            ->with('success', 'Paket berlangganan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->delete();

        return redirect()
            ->route('subscription-plans.index')
            ->with('success', 'Paket berlangganan berhasil dihapus.');
    }
}
