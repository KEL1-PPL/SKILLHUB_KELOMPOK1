<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Menampilkan halaman Home setelah login
    public function index()
    {
        return view('home');  
    }
}