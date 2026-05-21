<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SensorData;
use Carbon\Carbon;

$now = Carbon::now();
echo "Current Time: " . $now->toDateTimeString() . "\n";

$ranges = ['5m', '1h', '12h', '1d', '1w'];
foreach ($ranges as $range) {
    $startTime = match($range) {
        '5m' => $now->copy()->subMinutes(5),
        '1h' => $now->copy()->subHour(),
        '12h' => $now->copy()->subHours(12),
        '1d' => $now->copy()->subDay(),
        '1w' => $now->copy()->subWeek(),
    };
    
    $count = SensorData::where('created_at', '>=', $startTime)->count();
    echo "Range $range (since " . $startTime->toDateTimeString() . "): $count records\n";
}
