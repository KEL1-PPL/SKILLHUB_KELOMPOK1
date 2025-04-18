<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function registerUsers()
    {
        $data = User::getUserRegistrationsPerMonth();
        if (!$data) {
            return response()->json([
                'data' => null,
                'success' => false,
            ]);
        }

        return response()->json([
            'months' => array_values($data->keys()->toArray()),
            'siswa' => array_values($data->pluck('siswa')->toArray()),
            'mentor' => array_values($data->pluck('mentor')->toArray())
        ]);
    }
}
