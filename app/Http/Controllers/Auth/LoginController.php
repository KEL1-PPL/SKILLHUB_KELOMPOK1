<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Logic untuk menampilkan halaman login
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Logic untuk login
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Check for unread notifications
            $unreadNotificationsCount = Notification::where('user_id', Auth::id())
                                                  ->whereNull('read_at')
                                                  ->count();
            
            if ($unreadNotificationsCount > 0) {
                // Store notification count in session to display after redirect
                session()->flash('notification_alert', [
                    'count' => $unreadNotificationsCount,
                    'message' => "You have {$unreadNotificationsCount} unread notifications!"
                ]);
            }
            
            // Redirect setelah login sukses
            return redirect()->intended('/dashboard');
        }

        // Jika login gagal
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
