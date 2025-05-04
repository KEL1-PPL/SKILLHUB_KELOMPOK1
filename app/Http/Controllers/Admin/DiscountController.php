<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Discount;
use App\Models\Notification;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Skip any authorization checks for this controller
     */
    public function __construct()
    {
        // Skip any authorization checks for this controller
    }
    
    /**
     * Display a listing of the discounts.
     */
    public function index()
    {
        $discounts = Discount::with('course')->latest()->get();
        
        return view('all.admin.discounts.index', [
            'title' => 'Manage Discounts',
            'discounts' => $discounts
        ]);
    }

    /**
     * Show the form for creating a new discount.
     */
    public function create()
    {
        $courses = Course::all();
        
        return view('all.admin.discounts.create', [
            'title' => 'Create Discount',
            'courses' => $courses
        ]);
    }

    /**
     * Store a newly created discount in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string|max:255',
        ]);

        $discount = Discount::create($request->all());
        
        // Get the course details
        $course = Course::findOrFail($request->course_id);
        
        // Create notifications for users who have this course in their wishlist
        $this->createNotificationsForWishlistedUsers($course, $discount);
        
        return redirect()->route('admin.discounts.index')
            ->with('success', 'Discount created successfully!');
    }

    /**
     * Show the form for editing the specified discount.
     */
    public function edit(Discount $discount)
    {
        $courses = Course::all();
        
        return view('all.admin.discounts.edit', [
            'title' => 'Edit Discount',
            'discount' => $discount,
            'courses' => $courses
        ]);
    }

    /**
     * Update the specified discount in storage.
     */
    public function update(Request $request, Discount $discount)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string|max:255',
        ]);

        $discount->update($request->all());
        
        return redirect()->route('admin.discounts.index')
            ->with('success', 'Discount updated successfully!');
    }

    /**
     * Remove the specified discount from storage.
     */
    public function destroy(Discount $discount)
    {
        $discount->delete();
        
        return redirect()->route('admin.discounts.index')
            ->with('success', 'Discount deleted successfully!');
    }
    
    /**
     * Create notifications for users who have the course in their wishlist.
     */
    private function createNotificationsForWishlistedUsers($course, $discount)
    {
        // Get all users who have this course in their wishlist
        $wishlists = Wishlist::where('course_id', $course->id)->get();
        
        foreach ($wishlists as $wishlist) {
            // Create a notification for each user
            Notification::create([
                'user_id' => $wishlist->user_id,
                'title' => 'New Discount Available!',
                'message' => "A {$discount->percentage}% discount is now available for '{$course->title}' course that's in your wishlist!",
                'type' => 'discount',
                'data' => [
                    'course_id' => $course->id,
                    'discount_id' => $discount->id,
                    'percentage' => $discount->percentage,
                ]
            ]);
        }
        
        // Also notify all users about the discount (optional)
        $users = User::where('role', 'siswa')->get();
        
        foreach ($users as $user) {
            // Skip users who already got a notification through wishlist
            if (!$wishlists->contains('user_id', $user->id)) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'New Course Discount!',
                    'message' => "A {$discount->percentage}% discount is now available for '{$course->title}' course!",
                    'type' => 'general_discount',
                    'data' => [
                        'course_id' => $course->id,
                        'discount_id' => $discount->id,
                        'percentage' => $discount->percentage,
                    ]
                ]);
            }
        }
    }
}
