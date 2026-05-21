<?php

namespace Database\Seeders;

use App\Models\SensorData;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SensorDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        SensorData::truncate();

        $now = Carbon::now();
        $startDate = $now->copy()->subMonth();
        $data = [];

        // 1. Data history: Hourly for the first 29 days
        for ($i = 0; $i < 29 * 24; $i++) {
            $timestamp = $startDate->copy()->addHours($i);
            $this->addRecord($data, $timestamp);
            if (count($data) >= 100) {
                SensorData::insert($data);
                $data = [];
            }
        }

        // 2. Medium frequency: Every 5 minutes for 23 hours
        $lastDayStart = $now->copy()->subDay();
        for ($i = 0; $i < 23 * 12; $i++) {
            $timestamp = $lastDayStart->copy()->addMinutes($i * 5);
            $this->addRecord($data, $timestamp);
            if (count($data) >= 100) {
                SensorData::insert($data);
                $data = [];
            }
        }

        // 3. High frequency: Every 1 minute for the last 60 minutes
        // This specifically fixes the "5m" timeframe view
        $lastHourStart = $now->copy()->subHour();
        for ($i = 0; $i <= 60; $i++) {
            $timestamp = $lastHourStart->copy()->addMinutes($i);
            $this->addRecord($data, $timestamp);
            if (count($data) >= 100) {
                SensorData::insert($data);
                $data = [];
            }
        }

        if (count($data) > 0) {
            SensorData::insert($data);
        }
    }

    private function addRecord(&$data, $timestamp)
    {
        $hourOfDay = $timestamp->hour;
        $isRaining = rand(1, 10) > 7;
        $rainfall = $isRaining ? (rand(5, 100) / 10) : 0;
        
        $tmpBase = 26;
        $tmpVariation = sin(($hourOfDay - 6) * pi() / 12) * 5;
        $temperature = $tmpBase + $tmpVariation + (rand(-10, 10) / 10);
        
        $data[] = [
            'username' => 'FRANS_DEVICE_01',
            'rainfall' => round($rainfall, 2),
            'temperature' => round($temperature, 2),
            'humidity' => round(80 - ($tmpVariation * 2) + (rand(-50, 50) / 10), 2),
            'timertc' => $timestamp->toIso8601String(),
            'lux' => $hourOfDay > 6 && $hourOfDay < 18 ? rand(500, 2000) : rand(10, 50),
            'water_level' => rand(10, 100) / 10,
            'current_panel' => rand(0, 500) / 100,
            'voltage_panel' => rand(120, 180) / 10,
            'current_baterai' => rand(0, 300) / 100,
            'voltage_baterai' => rand(115, 135) / 10,
            'status_pompa' => rand(0, 1),
            'status_pompa2' => rand(0, 1),
            'status' => 'success',
            'jitter' => rand(5, 25),
            'delay' => rand(15, 60),
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];
    }
}
