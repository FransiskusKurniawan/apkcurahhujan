<?php

use App\Http\Controllers\AuthWebController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('onboarding.index');
});

Route::get('/login', [AuthWebController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthWebController::class, 'login']);
Route::get('/register', [AuthWebController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthWebController::class, 'register']);
Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/monitoring/rainfall', [App\Http\Controllers\MonitoringController::class, 'rainfall'])->name('monitoring.rainfall');
    Route::get('/monitoring/rainfall/history', [App\Http\Controllers\MonitoringController::class, 'getHistory'])->name('monitoring.rainfall.history');
    Route::get('/monitoring/temperature', [App\Http\Controllers\MonitoringController::class, 'temperature'])->name('monitoring.temperature');
    Route::get('/monitoring/temperature/history', [App\Http\Controllers\MonitoringController::class, 'getTemperatureHistory'])->name('monitoring.temperature.history');
    Route::get('/monitoring/humidity', [App\Http\Controllers\MonitoringController::class, 'humidity'])->name('monitoring.humidity');
    Route::get('/monitoring/humidity/history', [App\Http\Controllers\MonitoringController::class, 'getHumidityHistory'])->name('monitoring.humidity.history');
    Route::get('/monitoring/water-level', [App\Http\Controllers\MonitoringController::class, 'waterLevel'])->name('monitoring.water_level');
    Route::get('/monitoring/water-level/history', [App\Http\Controllers\MonitoringController::class, 'getWaterLevelHistory'])->name('monitoring.water_level.history');
    Route::get('/monitoring/lux', [App\Http\Controllers\MonitoringController::class, 'lux'])->name('monitoring.lux');
    Route::get('/monitoring/lux/history', [App\Http\Controllers\MonitoringController::class, 'getLuxHistory'])->name('monitoring.lux.history');
    Route::get('/monitoring/solar-panel', [App\Http\Controllers\MonitoringController::class, 'solarPanel'])->name('monitoring.solar_panel');
    Route::get('/monitoring/solar-panel/history', [App\Http\Controllers\MonitoringController::class, 'getSolarPanelHistory'])->name('monitoring.solar_panel.history');
    Route::get('/monitoring/battery', [App\Http\Controllers\MonitoringController::class, 'battery'])->name('monitoring.battery');
    Route::get('/monitoring/battery/history', [App\Http\Controllers\MonitoringController::class, 'getBatteryHistory'])->name('monitoring.battery.history');
    Route::get('/setting', function () {
        return view('auth.setting');
    })->name('setting');
    Route::get('/setting/password', [AuthWebController::class, 'showChangePassword'])->name('change-password');
    Route::post('/setting/password', [AuthWebController::class, 'updatePassword'])->name('update-password');
    Route::get('/setting/username', [AuthWebController::class, 'showChangeUsername'])->name('change-username');
    Route::post('/setting/username', [AuthWebController::class, 'updateUsername'])->name('update-username');
    Route::post('/setting/photo', [AuthWebController::class, 'uploadProfilePhoto'])->name('upload-photo');
});
