<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'rainfall',
        'temperature',
        'humidity',
        'timertc',
        'lux',
        'water_level',
        'current_panel',
        'voltage_panel',
        'current_baterai',
        'voltage_baterai',
        'status_pompa',
        'status_pompa2',
        'status',
        'jitter',
        'delay',
    ];
}
