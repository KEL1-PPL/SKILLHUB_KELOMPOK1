<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EarningsController extends Controller
{
    public function index()
    {
        $earnings = [
            ['id' => 1, 'amount' => 120000, 'note' => 'First course sale'],
            ['id' => 2, 'amount' => 150000, 'note' => 'Second course sale'],
        ];
        return view('features.earnings.index', compact('earnings'));
    }
}
