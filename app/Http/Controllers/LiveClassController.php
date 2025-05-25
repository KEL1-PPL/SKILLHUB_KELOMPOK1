<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LiveClass;

class LiveClassController extends Controller
{
    public function index()
    {
        return view('features.live-class.index', ['title' => 'live']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'datetime' => 'required|date',
            'platform' => 'required|string',
            'link' => 'required|url',
        ]);

        LiveClass::create($request->all());

        return redirect()->route('live-class.index')->with('success', 'Live class berhasil dibuat!');
    }
}
