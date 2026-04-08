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
        Schema::create('sensor_data', function (Blueprint $table) {
        $table->id();
        $table->string('username');

        $table->float('rainfall');
        $table->float('temperature');
        $table->float('humidity');
        $table->string('timertc');
        $table->float('lux');
        $table->float('water_level');

        $table->float('current_panel');
        $table->float('voltage_panel');
        $table->float('current_baterai');
        $table->float('voltage_baterai');

        $table->boolean('status_pompa');
        $table->string('status');

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_data');
    }
};
