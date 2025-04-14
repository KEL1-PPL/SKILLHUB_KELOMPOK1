<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EarningsController;

Route::get('/mentor/dashboard/pendapatan', [EarningsController::class, 'index']);
