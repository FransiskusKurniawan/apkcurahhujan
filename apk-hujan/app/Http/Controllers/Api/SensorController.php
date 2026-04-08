<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorData;
use App\Events\SensorDataStored;

class SensorController extends Controller
{
    // Ambil semua data
    public function index()
    {
        return SensorData::latest()->get();
    }

    // Simpan data
    public function store(Request $request)
    {
        $data = SensorData::create($request->all());
        
        // Dispatch event for real-time update
        event(new SensorDataStored($data));

        return response()->json($data);
    }
}