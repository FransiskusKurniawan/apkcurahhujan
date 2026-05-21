<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Fetch the latest sensor data for the authenticated user
        // If your app is multi-user, you might want to filter by username:
        // $latestData = SensorData::where('username', Auth::user()->username)->latest()->first();
        
        // For now, let's just get the absolute latest data to ensure something shows up
        $latestData = SensorData::latest()->first();

        // Optionally get recent history for a trend (last 10 records)
        $history = SensorData::latest()->take(10)->get();

        return view('dashboard.index', compact('latestData', 'history'));
    }
}
