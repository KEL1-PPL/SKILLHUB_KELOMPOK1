<?php

namespace App\Http\Controllers;

use App\Models\LiveClass;
use App\Models\LiveClassRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class LiveClassController extends Controller
{
    /**
     * Display a listing of live classes.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Default show active classes
        $query = LiveClass::where('is_active', true)
                          ->where('scheduled_at', '>=', now());
        
        // Filter options
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'past':
                    $query = LiveClass::where('scheduled_at', '<', now());
                    break;
                case 'all':
                    $query = LiveClass::query();
                    break;
                case 'my-registrations':
                    $userId = Auth::id();
                    $query = LiveClass::whereHas('registrations', function($q) use ($userId) {
                        $q->where('student_id', $userId);
                    });
                    break;
            }
        }
        
        // If user is a mentor, show their created classes too
        if (Auth::user()->hasRole('mentor')) {
            if ($request->has('show_mine') && $request->show_mine) {
                $query = LiveClass::where('mentor_id', Auth::id());
            }
        }
        
        $liveClasses = $query->with('mentor')
                            ->orderBy('scheduled_at')
                            ->paginate(10);
        
        return view('live-classes.index', compact('liveClasses'));
    }
    
    /**
     * Show the form for creating a new live class.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Only mentors can create live classes
        if (!Auth::user()->hasRole('mentor')) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('live-classes.create');
    }
    
    /**
     * Store a newly created live class in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Only mentors can create live classes
        if (!Auth::user()->hasRole('mentor')) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'scheduled_at' => 'required|date|after:now',
            'platform' => 'required|string|max:255',
            'access_link' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);
        
        $liveClass = LiveClass::create([
            'mentor_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'scheduled_at' => $request->scheduled_at,
            'platform' => $request->platform,
            'access_link' => $request->access_link,
            'max_participants' => $request->max_participants,
            'is_active' => $request->has('is_active') ? $request->is_active : true,
        ]);
        
        return redirect()->route('live-classes.show', $liveClass)
                        ->with('success', 'Live class created successfully!');
    }
    
    /**
     * Display the specified live class.
     * 
     * @param \App\Models\LiveClass $liveClass
     * @return \Illuminate\View\View
     */
    public function show(LiveClass $liveClass)
    {
        // Check if class is active or user is the mentor
        if (!$liveClass->is_active && $liveClass->mentor_id !== Auth::id()) {
            abort(404);
        }
        
        $isRegistered = $liveClass->isUserRegistered(Auth::user());
        $hasAvailableSeats = $liveClass->hasAvailableSeats();
        
        // Load registrations if user is the mentor
        $registrations = null;
        if ($liveClass->mentor_id === Auth::id()) {
            $registrations = $liveClass->registrations()->with('student')->get();
        }
        
        return view('live-classes.show', compact('liveClass', 'isRegistered', 'hasAvailableSeats', 'registrations'));
    }
    
    /**
     * Show the form for editing the specified live class.
     * 
     * @param \App\Models\LiveClass $liveClass
     * @return \Illuminate\View\View
     */
    public function edit(LiveClass $liveClass)
    {
        // Only the mentor who created the class can edit it
        if ($liveClass->mentor_id !== Auth::id()) {
            abort(403, 'You are not authorized to edit this live class.');
        }
        
        return view('live-classes.edit', compact('liveClass'));
    }
    
    /**
     * Update the specified live class in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\LiveClass $liveClass
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, LiveClass $liveClass)
    {
        // Only the mentor who created the class can update it
        if ($liveClass->mentor_id !== Auth::id()) {
            abort(403, 'You are not authorized to update this live class.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'scheduled_at' => 'required|date',
            'platform' => 'required|string|max:255',
            'access_link' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);
        
        // Additional validation if the class is being rescheduled
        if ($liveClass->scheduled_at->format('Y-m-d H:i') !== Carbon::parse($request->scheduled_at)->format('Y-m-d H:i')) {
            if (!$liveClass->isUpcoming()) {
                return redirect()->back()
                                ->withErrors(['scheduled_at' => 'Cannot reschedule a past live class.'])
                                ->withInput();
            }
        }
        
        $liveClass->update([
            'title' => $request->title,
            'description' => $request->description,
            'scheduled_at' => $request->scheduled_at,
            'platform' => $request->platform,
            'access_link' => $request->access_link,
            'max_participants' => $request->max_participants,
            'is_active' => $request->has('is_active') ? $request->is_active : $liveClass->is_active,
        ]);
        
        return redirect()->route('live-classes.show', $liveClass)
                        ->with('success', 'Live class updated successfully!');
    }
    
    /**
     * Remove the specified live class from storage.
     * 
     * @param \App\Models\LiveClass $liveClass
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(LiveClass $liveClass)
    {
        // Only the mentor who created the class can delete it
        if ($liveClass->mentor_id !== Auth::id()) {
            abort(403, 'You are not authorized to delete this live class.');
        }
        
        // Check if the class has registrations
        if ($liveClass->registrations()->count() > 0) {
            // Only allow cancelling (deactivating) classes with registrations
            $liveClass->update(['is_active' => false]);
            return redirect()->route('live-classes.index')
                            ->with('success', 'Live class has been cancelled.');
        } else {
            // If no registrations, allow complete deletion
            $liveClass->delete();
            return redirect()->route('live-classes.index')
                            ->with('success', 'Live class deleted successfully!');
        }
    }
    
    /**
     * Register current user for a live class.
     * 
     * @param \App\Models\LiveClass $liveClass
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(LiveClass $liveClass)
    {
        // Check if class is active and upcoming
        if (!$liveClass->is_active || !$liveClass->isUpcoming()) {
            return redirect()->back()
                            ->with('error', 'Cannot register for inactive or past classes.');
        }
        
        // Check if user is already registered
        if ($liveClass->isUserRegistered(Auth::user())) {
            return redirect()->back()
                            ->with('info', 'You are already registered for this class.');
        }
        
        // Check if class has available seats
        if (!$liveClass->hasAvailableSeats()) {
            return redirect()->back()
                            ->with('error', 'This class is fully booked.');
        }
        
        // Create registration
        LiveClassRegistration::create([
            'live_class_id' => $liveClass->id,
            'student_id' => Auth::id(),
            'registered_at' => now(),
            'attended' => false,
        ]);
        
        return redirect()->back()
                        ->with('success', 'You have successfully registered for this live class!');
    }
    
    /**
     * Cancel user registration for a live class.
     * 
     * @param \App\Models\LiveClass $liveClass
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelRegistration(LiveClass $liveClass)
    {
        // Check if class is upcoming
        if (!$liveClass->isUpcoming()) {
            return redirect()->back()
                            ->with('error', 'Cannot cancel registration for past classes.');
        }
        
        // Check if user is registered
        $registration = $liveClass->registrations()
                              ->where('student_id', Auth::id())
                              ->first();
        
        if (!$registration) {
            return redirect()->back()
                            ->with('error', 'You are not registered for this class.');
        }
        
        // Delete registration
        $registration->delete();
        
        return redirect()->back()
                        ->with('success', 'Your registration has been cancelled.');
    }
    
    /**
     * Mark student attendance for a live class.
     * Only the mentor who created the class can mark attendance.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\LiveClass $liveClass
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAttendance(Request $request, LiveClass $liveClass)
    {
        // Only the mentor who created the class can mark attendance
        if ($liveClass->mentor_id !== Auth::id()) {
            abort(403, 'You are not authorized to mark attendance for this class.');
        }
        
        $request->validate([
            'registrations' => 'required|array',
            'registrations.*' => 'exists:live_class_registrations,id',
        ]);
        
        // Update attendance status
        foreach ($request->registrations as $registrationId => $attended) {
            $registration = LiveClassRegistration::findOrFail($registrationId);
            $registration->update(['attended' => (bool) $attended]);
        }
        
        return redirect()->back()
                        ->with('success', 'Attendance marked successfully!');
    }
}