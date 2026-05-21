@extends('layouts.app')

@section('content')
<div class="space-y-6 animate__animated animate__fadeIn">
    
    <!-- Header Section -->
    <div class="flex flex-col gap-1">
        <h2 class="text-2xl font-extrabold tracking-tight text-slate-800">
            Welcome, {{ Auth::user()->name }} 
        </h2>
        <div class="flex items-center gap-2">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
            </span>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                System Live • <span id="last-updated-text">{{ $latestData ? $latestData->created_at->diffForHumans() : 'No data' }}</span>
            </p>
        </div>
    </div>

    @if(!$latestData)
    <!-- No Data State -->
    <div class="bg-white rounded-[2.5rem] p-12 text-center border border-slate-100 shadow-sm">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
            <i data-lucide="database-zap" class="w-10 h-10"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-700">No Sensor Data Yet</h3>
        <p class="text-slate-400 text-sm mt-1 max-w-xs mx-auto">Connecting sensors... data will appear here automatically once transmitted.</p>
    </div>
    @else

    <!-- Main Status Banner -->
    <a href="{{ route('monitoring.rainfall') }}" class="block relative overflow-hidden group hover:scale-[1.01] hover:shadow-xl hover:shadow-primary-600/20 active:scale-[0.99] transition-all duration-300">
        <div class="absolute inset-0 bg-primary-600 rounded-[2.5rem]"></div>
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
        
        <div class="relative p-8 text-white flex flex-col md:flex-row md:items-center justify-between gap-6 pb-12 md:pb-8">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-3xl flex items-center justify-center shadow-inner">
                    <i data-lucide="cloud-rain" class="w-10 h-10"></i>
                </div>
                <div>
                    <p class="text-primary-100 text-xs font-bold uppercase tracking-widest mb-1">Total Rainfall</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-5xl font-black" id="rainfall-value">{{ $latestData->rainfall }}</span>
                        <span class="text-primary-200 font-bold">mm</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-6 py-4 flex flex-col items-center flex-shrink-0">
                <p class="text-[10px] font-bold text-primary-100 uppercase tracking-widest mb-1">Status</p>
                @php
                    $isSafe = $latestData->status == 'Aman' || $latestData->rainfall < 100;
                @endphp
                <div class="flex items-center gap-2">
                    <span id="status-dot" class="w-3 h-3 rounded-full {{ $isSafe ? 'bg-green-400' : 'bg-red-400' }} shadow-[0_0_12px_rgba(74,222,128,0.5)]"></span>
                    <span class="text-xl font-extrabold uppercase tracking-tight" id="status-value">{{ $latestData->status }}</span>
                </div>
            </div>
        </div>

        <!-- Click Indicator Hint -->
        <div class="absolute bottom-4 right-8 bg-white/10 border border-white/10 backdrop-blur-md px-3 py-1 rounded-full text-white/85 flex items-center gap-1.5 opacity-80 group-hover:opacity-100 group-hover:bg-white group-hover:text-primary-600 transition-all duration-300 shadow-sm">
            <span class="text-[9px] font-bold uppercase tracking-widest">View Details</span>
            <i data-lucide="arrow-up-right" class="w-3 h-3"></i>
        </div>
    </a>

    <!-- Core Metrics Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Temp -->
        <a href="{{ route('monitoring.temperature') }}" class="relative block bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-lg hover:scale-[1.03] hover:-translate-y-1 active:scale-[0.97] hover:border-orange-300 hover:shadow-orange-100/40 transition-all duration-300 group">
            <!-- Clickable Corner Action Badge -->
            <div class="absolute top-4 right-4 w-7 h-7 rounded-full bg-slate-50 border border-slate-200/50 flex items-center justify-center text-slate-400 group-hover:bg-orange-500 group-hover:text-white group-hover:border-transparent group-hover:scale-110 shadow-sm transition-all duration-300">
                <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
            </div>
            <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="thermometer" class="w-5 h-5"></i>
            </div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Temperature</p>
            <div class="flex items-baseline gap-1 mt-1">
                <span class="text-2xl font-black text-slate-800" id="temperature-value">{{ $latestData->temperature }}</span>
                <span class="text-xs font-bold text-slate-500">°C</span>
            </div>
        </a>

        <!-- Humidity -->
        <a href="{{ route('monitoring.humidity') }}" class="relative block bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-lg hover:scale-[1.03] hover:-translate-y-1 active:scale-[0.97] hover:border-cyan-300 hover:shadow-cyan-100/40 transition-all duration-300 group">
            <!-- Clickable Corner Action Badge -->
            <div class="absolute top-4 right-4 w-7 h-7 rounded-full bg-slate-50 border border-slate-200/50 flex items-center justify-center text-slate-400 group-hover:bg-cyan-500 group-hover:text-white group-hover:border-transparent group-hover:scale-110 shadow-sm transition-all duration-300">
                <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
            </div>
            <div class="w-10 h-10 bg-cyan-50 text-cyan-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="droplets" class="w-5 h-5"></i>
            </div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Humidity</p>
            <div class="flex items-baseline gap-1 mt-1">
                <span class="text-2xl font-black text-slate-800" id="humidity-value">{{ $latestData->humidity }}</span>
                <span class="text-xs font-bold text-slate-500">%</span>
            </div>
        </a>

        <!-- Water Level -->
        <a href="{{ route('monitoring.water_level') }}" class="relative block bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-lg hover:scale-[1.03] hover:-translate-y-1 active:scale-[0.97] hover:border-cyan-300 hover:shadow-cyan-100/40 transition-all duration-300 group">
            <!-- Clickable Corner Action Badge -->
            <div class="absolute top-4 right-4 w-7 h-7 rounded-full bg-slate-50 border border-slate-200/50 flex items-center justify-center text-slate-400 group-hover:bg-cyan-500 group-hover:text-white group-hover:border-transparent group-hover:scale-110 shadow-sm transition-all duration-300">
                <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
            </div>
            <div class="w-10 h-10 bg-cyan-50 text-cyan-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="waves" class="w-5 h-5"></i>
            </div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Water Level</p>
            <div class="flex items-baseline gap-1 mt-1">
                <span class="text-2xl font-black text-slate-800" id="water-level-value">{{ $latestData->water_level }}</span>
                <span class="text-xs font-bold text-slate-500">cm</span>
            </div>
        </a>

        <!-- Lux -->
        <a href="{{ route('monitoring.lux') }}" class="relative block bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-lg hover:scale-[1.03] hover:-translate-y-1 active:scale-[0.97] hover:border-amber-300 hover:shadow-amber-100/40 transition-all duration-300 group">
            <!-- Clickable Corner Action Badge -->
            <div class="absolute top-4 right-4 w-7 h-7 rounded-full bg-slate-50 border border-slate-200/50 flex items-center justify-center text-slate-400 group-hover:bg-amber-500 group-hover:text-white group-hover:border-transparent group-hover:scale-110 shadow-sm transition-all duration-300">
                <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
            </div>
            <div class="w-10 h-10 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="sun" class="w-5 h-5"></i>
            </div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Light (Lux)</p>
            <div class="flex items-baseline gap-1 mt-1">
                <span class="text-2xl font-black text-slate-800" id="lux-value">{{ number_format($latestData->lux, 0) }}</span>
            </div>
        </a>
    </div>

    <!-- System Health Section -->
    <div class="flex flex-col gap-4">
        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest px-2">System Health & Power</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Solar Energy Card -->
            <a href="{{ route('monitoring.solar_panel') }}" class="relative bg-white pl-6 pr-16 py-5 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between hover:shadow-lg hover:scale-[1.02] hover:-translate-y-1 active:scale-[0.97] hover:border-emerald-300 hover:shadow-emerald-100/40 transition-all duration-300 group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i data-lucide="zap" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-slate-800 tracking-tight">Solar Panel</h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Energy Input</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="flex items-baseline justify-end gap-1">
                        <span class="text-xl font-black text-slate-800" id="voltage-panel-value">{{ $latestData->voltage_panel }}</span>
                        <span class="text-[10px] font-bold text-slate-400">V</span>
                    </div>
                    <p class="text-[10px] font-bold text-emerald-500 uppercase"><span id="current-panel-value">{{ $latestData->current_panel }}</span> A</p>
                </div>
                <!-- Interactive Action Badge -->
                <div class="absolute right-4 w-8 h-8 rounded-full bg-slate-50 border border-slate-200/50 flex items-center justify-center text-slate-400 group-hover:bg-emerald-500 group-hover:text-white group-hover:border-transparent group-hover:scale-110 shadow-sm transition-all duration-300">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </div>
            </a>

            <!-- Battery Card -->
            <a href="{{ route('monitoring.battery') }}" class="relative bg-white pl-6 pr-16 py-5 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between hover:shadow-lg hover:scale-[1.02] hover:-translate-y-1 active:scale-[0.97] hover:border-indigo-300 hover:shadow-indigo-100/40 transition-all duration-300 group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i data-lucide="battery" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-slate-800 tracking-tight">Battery</h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Storage Status</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="flex items-baseline justify-end gap-1">
                        <span class="text-xl font-black text-slate-800" id="voltage-battery-value">{{ $latestData->voltage_baterai }}</span>
                        <span class="text-[10px] font-bold text-slate-400">V</span>
                    </div>
                    <p class="text-[10px] font-bold text-indigo-500 uppercase"><span id="current-battery-value">{{ $latestData->current_baterai }}</span> A</p>
                </div>
                <!-- Interactive Action Badge -->
                <div class="absolute right-4 w-8 h-8 rounded-full bg-slate-50 border border-slate-200/50 flex items-center justify-center text-slate-400 group-hover:bg-indigo-500 group-hover:text-white group-hover:border-transparent group-hover:scale-110 shadow-sm transition-all duration-300">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </div>
            </a>
        </div>
    </div>

    <!-- Extra Info Banner -->
    <div class="bg-slate-900 rounded-[2rem] p-6 text-white overflow-hidden relative">
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Internal Clock</p>
                <div class="flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4 text-primary-400"></i>
                    <span class="text-lg font-bold tracking-tight" id="timertc-value">{{ $latestData->timertc }}</span>
                </div>
            </div>
            
            <div class="flex items-center justify-between md:justify-start gap-8 px-2 md:px-8 md:border-x border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/20 flex items-center justify-center text-indigo-400">
                        <i data-lucide="activity" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <p class="text-[8px] font-bold text-slate-400 uppercase leading-none">Jitter</p>
                        <p class="text-sm font-black tracking-tight"><span id="jitter-value">{{ $latestData->jitter ?? '0' }}</span><span class="text-[9px] text-slate-500 ml-1">ms</span></p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-cyan-500/20 flex items-center justify-center text-cyan-400">
                        <i data-lucide="wifi" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <p class="text-[8px] font-bold text-slate-400 uppercase leading-none">Delay</p>
                        <p class="text-sm font-black tracking-tight"><span id="delay-value">{{ $latestData->delay ?? '0' }}</span><span class="text-[9px] text-slate-500 ml-1">ms</span></p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3 w-full md:w-auto">
                <!-- Pump 1 -->
                <div class="bg-white/5 rounded-2xl px-4 py-2.5 flex items-center justify-between md:justify-start gap-4 border border-white/5">
                    <div id="pump-status-card" class="w-8 h-8 rounded-lg bg-{{ $latestData->status_pompa ? 'emerald' : 'slate' }}-500 flex items-center justify-center shadow-lg shadow-{{ $latestData->status_pompa ? 'emerald' : 'slate' }}-500/20">
                        <i id="pump-status-icon" data-lucide="cog" class="w-5 h-5 {{ $latestData->status_pompa ? 'animate-spin' : '' }}"></i>
                    </div>
                    <div>
                        <p class="text-[8px] font-bold text-slate-400 uppercase leading-none">Pump 1</p>
                        <p id="pump-status-text" class="text-xs font-black uppercase text-{{ $latestData->status_pompa ? 'emerald' : 'slate' }}-400">{{ $latestData->status_pompa ? 'Active' : 'Offline' }}</p>
                    </div>
                </div>

                <!-- Pump 2 -->
                <div class="bg-white/5 rounded-2xl px-4 py-2.5 flex items-center justify-between md:justify-start gap-4 border border-white/5">
                    <div id="pump-status-card-2" class="w-8 h-8 rounded-lg bg-{{ $latestData->status_pompa2 ? 'emerald' : 'slate' }}-500 flex items-center justify-center shadow-lg shadow-{{ $latestData->status_pompa2 ? 'emerald' : 'slate' }}-500/20">
                        <i id="pump-status-icon-2" data-lucide="cog" class="w-5 h-5 {{ $latestData->status_pompa2 ? 'animate-spin' : '' }}"></i>
                    </div>
                    <div>
                        <p class="text-[8px] font-bold text-slate-400 uppercase leading-none">Pump 2</p>
                        <p id="pump-status-text-2" class="text-xs font-black uppercase text-{{ $latestData->status_pompa2 ? 'emerald' : 'slate' }}-400">{{ $latestData->status_pompa2 ? 'Active' : 'Offline' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.Echo) {
            window.Echo.channel('sensor-data')
                .listen('.new-data', (e) => {
                    console.log('Real-time data received:', e.data);
                    
                    const data = e.data;
                    
                    // Update simple text values
                    const fields = {
                        'rainfall-value': data.rainfall,
                        'status-value': data.status,
                        'temperature-value': data.temperature,
                        'humidity-value': data.humidity,
                        'water-level-value': data.water_level,
                        'lux-value': Math.round(data.lux),
                        'voltage-panel-value': data.voltage_panel,
                        'current-panel-value': data.current_panel,
                        'voltage-baterai-value': data.voltage_baterai,
                        'current-baterai-value': data.current_baterai,
                        'timertc-value': data.timertc,
                        'jitter-value': data.jitter || 0,
                        'delay-value': data.delay || 0,
                        'last-updated-text': 'Just now'
                    };

                    for (const [id, value] of Object.entries(fields)) {
                        const el = document.getElementById(id);
                        if (el) {
                            // Subtle animation on update
                            el.classList.add('animate__animated', 'animate__flash');
                            setTimeout(() => el.classList.remove('animate__animated', 'animate__flash'), 1000);
                            el.innerText = value;
                        }
                    }

                    // Update Status Dot Color
                    const statusDot = document.getElementById('status-dot');
                    if (statusDot) {
                        const isSafe = data.status === 'Aman' || data.rainfall < 100;
                        statusDot.classList.toggle('bg-green-400', isSafe);
                        statusDot.classList.toggle('bg-red-400', !isSafe);
                    }

                    // Update Pump UI
                    const pumpCard = document.getElementById('pump-status-card');
                    const pumpIcon = document.getElementById('pump-status-icon');
                    const pumpText = document.getElementById('pump-status-text');
                    
                    if (pumpCard && pumpIcon && pumpText) {
                        const isActive = !!data.status_pompa;
                        
                        // Card classes
                        pumpCard.classList.toggle('bg-emerald-500', isActive);
                        pumpCard.classList.toggle('bg-slate-500', !isActive);
                        pumpCard.classList.toggle('shadow-emerald-500/20', isActive);
                        pumpCard.classList.toggle('shadow-slate-500/20', !isActive);
                        
                        // Icon animation
                        if (isActive) {
                            pumpIcon.classList.add('animate-spin');
                        } else {
                            pumpIcon.classList.remove('animate-spin');
                        }
                        
                        // Text and color
                        pumpText.innerText = isActive ? 'Active' : 'Offline';
                        pumpText.classList.toggle('text-emerald-400', isActive);
                        pumpText.classList.toggle('text-slate-400', !isActive);
                    }

                    // Update Pump 2 UI
                    const pumpCard2 = document.getElementById('pump-status-card-2');
                    const pumpIcon2 = document.getElementById('pump-status-icon-2');
                    const pumpText2 = document.getElementById('pump-status-text-2');
                    
                    if (pumpCard2 && pumpIcon2 && pumpText2) {
                        const isActive2 = !!data.status_pompa2;
                        
                        pumpCard2.classList.toggle('bg-emerald-500', isActive2);
                        pumpCard2.classList.toggle('bg-slate-500', !isActive2);
                        pumpCard2.classList.toggle('shadow-emerald-500/20', isActive2);
                        pumpCard2.classList.toggle('shadow-slate-500/20', !isActive2);
                        
                        if (isActive2) {
                            pumpIcon2.classList.add('animate-spin');
                        } else {
                            pumpIcon2.classList.remove('animate-spin');
                        }
                        
                        pumpText2.innerText = isActive2 ? 'Active' : 'Offline';
                        pumpText2.classList.toggle('text-emerald-400', isActive2);
                        pumpText2.classList.toggle('text-slate-400', !isActive2);
                    }
                });
        }
    });
</script>

<style>
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin {
        animation: spin 3s linear infinite;
    }
</style>
@endsection
