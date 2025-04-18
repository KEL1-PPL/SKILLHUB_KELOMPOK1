<?php

use App\Http\Controllers\Api\ChartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/register-user',[ChartController::class,'registerUsers']);
