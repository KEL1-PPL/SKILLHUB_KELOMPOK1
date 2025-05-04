<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the user's notifications.
     */
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(10);
        
        return view('notifications.index', [
            'title' => 'My Notifications',
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
                                   ->where('user_id', auth()->id())
                                   ->firstOrFail();
        
        $notification->markAsRead();
        
        return redirect()->back()->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
                   ->whereNull('read_at')
                   ->update(['read_at' => now()]);
        
        return redirect()->back()->with('success', 'All notifications marked as read');
    }
}
