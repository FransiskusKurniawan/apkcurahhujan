<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->float('jitter')->nullable()->after('status');       // Network jitter (ms)
            $table->float('delay')->nullable()->after('jitter');        // Network delay (ms)
            $table->boolean('status_pompa2')->nullable()->after('delay'); // Pump 2 status
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->dropColumn(['jitter', 'delay', 'status_pompa2']);
        });
    }
};
