@extends('layouts.app')

@section('content')
<div class="space-y-6 animate__animated animate__fadeIn">
    
    <!-- Top Bar -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-400 hover:text-emerald-600 border border-slate-200 transition-all shadow-sm">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h2 class="text-xl font-extrabold tracking-tight text-slate-800">Solar Panel Analysis</h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Energy Performance Sync</p>
            </div>
        </div>
        
        <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-slate-200">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span class="text-xs font-bold text-slate-600 uppercase tracking-tight">Live Tracking</span>
        </div>
    </div>

    <!-- Main Chart Card -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden flex flex-col transition-all">
        <!-- Header -->
        <div class="p-6 md:p-8 border-b border-slate-50">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <!-- Left: Title & Icon -->
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                        <i data-lucide="zap" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-lg md:text-xl font-black text-slate-800 tracking-tight leading-tight">Solar Power Output</h3>
                            <span class="px-2 py-0.5 bg-green-100 text-[10px] font-bold text-green-600 rounded-lg uppercase tracking-wider">LIVE</span>
                        </div>
                        <p class="text-xs font-medium text-slate-400">Voltage and Current generation metrics</p>
                    </div>
                </div>

                <!-- Right: Current Values -->
                <div class="flex items-center justify-between lg:justify-end gap-4 md:gap-8 bg-slate-50/50 lg:bg-transparent p-4 md:p-6 lg:p-0 rounded-[1.5rem] lg:rounded-none">
                    <div class="text-right lg:border-r border-slate-200 lg:pr-8">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Voltage</p>
                        <div class="flex items-baseline justify-end gap-1.5">
                            <span class="text-2xl md:text-4xl font-black text-slate-900 tabular-nums tracking-tighter" id="current-voltage-large">{{ number_format($latest->voltage_panel ?? 0, 1) }}</span>
                            <span class="text-xs md:text-sm font-bold text-slate-400">V</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Current</p>
                        <div class="flex items-baseline justify-end gap-1.5">
                            <span class="text-2xl md:text-4xl font-black text-slate-900 tabular-nums tracking-tighter" id="current-current-large">{{ number_format($latest->current_panel ?? 0, 2) }}</span>
                            <span class="text-xs md:text-sm font-bold text-slate-400">A</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeframe & Controls Bar -->
        <div class="px-6 py-3 bg-slate-50/30 border-b border-slate-50 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-1 p-1 bg-white rounded-xl border border-slate-100 shadow-sm overflow-x-auto no-scrollbar w-full md:w-auto">
                <button class="timeframe-btn flex-1 md:flex-none px-4 py-1.5 text-[11px] font-bold rounded-lg transition-all text-slate-400 hover:text-slate-600" data-range="5m">5m</button>
                <button class="timeframe-btn flex-1 md:flex-none px-4 py-1.5 text-[11px] font-bold rounded-lg transition-all text-slate-400 hover:text-slate-600" data-range="1h">1h</button>
                <button class="timeframe-btn flex-1 md:flex-none px-4 py-1.5 text-[11px] font-bold rounded-lg transition-all text-slate-400 hover:text-slate-600" data-range="12h">12h</button>
                <button class="timeframe-btn flex-1 md:flex-none px-4 py-1.5 text-[11px] font-bold rounded-lg transition-all active-timeframe bg-emerald-50 text-emerald-600 shadow-sm" data-range="1d">1D</button>
                <button class="timeframe-btn flex-1 md:flex-none px-4 py-1.5 text-[11px] font-bold rounded-lg transition-all text-slate-400 hover:text-slate-600" data-range="1w">1W</button>
                <button class="timeframe-btn flex-1 md:flex-none px-4 py-1.5 text-[11px] font-bold rounded-lg transition-all text-slate-400 hover:text-slate-600" data-range="1m">1M</button>
            </div>
            
            <div class="flex items-center justify-center gap-6 w-full md:w-auto px-2">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]"></div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Voltage (V)</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.4)]"></div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Current (A)</span>
                </div>
            </div>
        </div>

        <!-- Chart Area -->
        <div class="relative p-2 md:p-4 bg-slate-50/50">
            <div id="solar-chart" class="w-full h-[400px]"></div>
            
            @if($history->isEmpty())
                <div id="chart-placeholder" class="absolute inset-0 flex flex-col items-center justify-center bg-white/50 backdrop-blur-sm z-10">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-4">
                        <i data-lucide="database-zap" class="w-8 h-8"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-500">Waiting for incoming energy data...</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Period Selector -->
    <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 flex flex-col md:flex-row items-center justify-between gap-6 transition-all">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="calendar" class="w-6 h-6"></i>
            </div>
            <div>
                <h4 class="text-base font-black text-slate-800 tracking-tight">Custom Period</h4>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Select Date Range</p>
            </div>
        </div>
        
        <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
            <div class="flex flex-col gap-1 w-full md:w-44">
                <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Start Date</label>
                <input type="date" id="start-date" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2 text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all">
            </div>
            <div class="flex flex-col gap-1 w-full md:w-44">
                <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">End Date</label>
                <input type="date" id="end-date" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2 text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all">
            </div>
            <button id="apply-filter" class="w-full md:w-auto mt-4 bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-2.5 rounded-xl text-xs font-bold transition-all shadow-lg shadow-emerald-500/20 active:scale-95 flex items-center justify-center gap-2">
                <i data-lucide="filter" class="w-4 h-4"></i>
                Apply Filter
            </button>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white p-4 md:p-6 rounded-[1.5rem] md:rounded-[2.5rem] border border-slate-100 shadow-sm hover:border-emerald-200 transition-colors">
            <div class="flex items-center gap-3 mb-3 md:mb-4">
                <div class="w-7 h-7 md:w-8 md:h-8 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="zap" class="w-3.5 h-3.5 md:w-4 md:h-4"></i>
                </div>
                <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Max Volt</p>
            </div>
            <div class="flex items-baseline gap-1">
                <span class="text-xl md:text-3xl font-black text-slate-800" id="stat-max-v">{{ number_format($globalStats['max_voltage'], 1) }}</span>
                <span class="text-[10px] md:text-xs font-bold text-slate-400">V</span>
            </div>
        </div>

        <div class="bg-white p-4 md:p-6 rounded-[1.5rem] md:rounded-[2.5rem] border border-slate-100 shadow-sm hover:border-amber-200 transition-colors">
            <div class="flex items-center gap-3 mb-3 md:mb-4">
                <div class="w-7 h-7 md:w-8 md:h-8 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="activity" class="w-3.5 h-3.5 md:w-4 md:h-4"></i>
                </div>
                <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Max Current</p>
            </div>
            <div class="flex items-baseline gap-1">
                <span class="text-xl md:text-3xl font-black text-slate-800" id="stat-max-a">{{ number_format($globalStats['max_current'], 2) }}</span>
                <span class="text-[10px] md:text-xs font-bold text-slate-400">A</span>
            </div>
        </div>

        <div class="bg-white p-4 md:p-6 rounded-[1.5rem] md:rounded-[2.5rem] border border-slate-100 shadow-sm hover:border-emerald-200 transition-colors">
            <div class="flex items-center gap-3 mb-3 md:mb-4">
                <div class="w-7 h-7 md:w-8 md:h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="bar-chart-3" class="w-3.5 h-3.5 md:w-4 md:h-4"></i>
                </div>
                <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Avg Volt</p>
            </div>
            <div class="flex items-baseline gap-1">
                <span class="text-xl md:text-3xl font-black text-slate-800" id="stat-avg-v">{{ number_format($globalStats['avg_voltage'], 1) }}</span>
                <span class="text-[10px] md:text-xs font-bold text-slate-400">V</span>
            </div>
        </div>

        <div class="bg-white p-4 md:p-6 rounded-[1.5rem] md:rounded-[2.5rem] border border-slate-100 shadow-sm hover:border-emerald-200 transition-colors">
            <div class="flex items-center gap-3 mb-3 md:mb-4">
                <div class="w-7 h-7 md:w-8 md:h-8 bg-green-50 text-green-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="clock" class="w-3.5 h-3.5 md:w-4 md:h-4"></i>
                </div>
                <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Samples</p>
            </div>
            <div class="flex items-baseline gap-1">
                <span class="text-xl md:text-3xl font-black text-slate-800" id="stat-count">{{ $globalStats['total'] }}</span>
                <span class="text-[10px] md:text-xs font-bold text-slate-400">pts</span>
            </div>
        </div>
    </div>

</div>

<!-- ApexCharts scripts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Initial Data Preparation
        const initialHistory = @json($history);
        
        const parseHistoryData = (data, field) => {
            if (!data) return [];
            const items = Array.isArray(data) ? data : Object.values(data);
            const seen = new Set();
            return items
                .map(item => {
                    const timeSource = item.timertc || item.created_at;
                    if (!timeSource) return null;
                    
                    let date;
                    if (typeof timeSource === 'string' && !timeSource.includes('T') && !timeSource.includes('Z') && !timeSource.includes('+')) {
                        date = new Date(timeSource.replace(' ', 'T'));
                    } else {
                        date = new Date(timeSource);
                    }
                    
                    const timestamp = date.getTime();
                    const val = item[field] !== undefined && item[field] !== null ? parseFloat(item[field]) : null;

                    return {
                        x: timestamp,
                        y: Number.isFinite(val) ? val : null
                    };
                })
                .filter(item => {
                    if (!item || item.x === null || Number.isNaN(item.x)) return false;
                    if (seen.has(item.x)) return false;
                    seen.add(item.x);
                    return true;
                })
                .sort((a, b) => a.x - b.x);
        };

        let voltageData = parseHistoryData(initialHistory, 'voltage_panel');
        let currentData = parseHistoryData(initialHistory, 'current_panel');

        // Chart Configuration
        const options = {
            series: [
                { name: 'Voltage', data: voltageData },
                { name: 'Current', data: currentData }
            ],
            colors: ['#10b981', '#f59e0b'],
            chart: {
                type: 'line',
                height: 400,
                toolbar: { 
                    show: true,
                    autoSelected: 'pan',
                    tools: {
                        download: false,
                        selection: false,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true
                    }
                },
                zoom: { 
                    enabled: true,
                    type: 'x',
                    autoScaleYaxis: true
                },
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    dynamicAnimation: { enabled: true, speed: 350 }
                }
            },
            stroke: {
                curve: 'smooth',
                width: [3, 3],
                dashArray: [0, 0]
            },
            xaxis: {
                type: 'datetime',
                tooltip: { enabled: true },
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 },
                    datetimeUTC: false,
                    format: 'HH:mm'
                }
            },
            yaxis: [
                {
                    seriesName: 'Voltage',
                    title: { text: 'Voltage (V)', style: { color: '#10b981', fontWeight: 800 } },
                    labels: {
                        style: { colors: '#10b981', fontWeight: 600 },
                        formatter: (val) => val.toFixed(1) + ' V'
                    }
                },
                {
                    opposite: true,
                    seriesName: 'Current',
                    title: { text: 'Current (A)', style: { color: '#f59e0b', fontWeight: 800 } },
                    labels: {
                        style: { colors: '#f59e0b', fontWeight: 600 },
                        formatter: (val) => val.toFixed(2) + ' A'
                    }
                }
            ],
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                padding: { top: 0, right: 20, bottom: 0, left: 10 }
            },
            tooltip: {
                shared: true,
                intersect: false,
                custom: function({ series, seriesIndex, dataPointIndex, w }) {
                    const v = series[0][dataPointIndex];
                    const a = series[1][dataPointIndex];
                    const timestamp = w.globals.seriesX[0][dataPointIndex];
                    const date = new Date(timestamp);
                    const timeStr = date.toLocaleString('id-ID', {
                        day: '2-digit', month: '2-digit', year: 'numeric',
                        hour: '2-digit', minute: '2-digit', second: '2-digit'
                    });
                    
                    return `
                        <div class="bg-slate-900 text-white p-4 rounded-2xl border border-slate-800 shadow-2xl">
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 border-b border-slate-800 pb-1">${timeStr}</div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between gap-8">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase">Voltage</span>
                                    </div>
                                    <div class="text-sm font-black">${v.toFixed(1)} <span class="text-[10px] text-slate-500">V</span></div>
                                </div>
                                <div class="flex items-center justify-between gap-8">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase">Current</span>
                                    </div>
                                    <div class="text-sm font-black">${a.toFixed(2)} <span class="text-[10px] text-slate-500">A</span></div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            }
        };

        if (voltageData.length > 0) {
            const lastPoint = voltageData[voltageData.length - 1];
            options.xaxis.min = lastPoint.x - (24 * 60 * 60 * 1000);
            options.xaxis.max = lastPoint.x;
        }

        const chart = new ApexCharts(document.querySelector("#solar-chart"), options);
        chart.render();

        let currentGlobalStats = @json($globalStats);
        setTimeout(() => updateSummary(currentGlobalStats), 100);

        const updateSummary = (globalData = null) => {
            const lastV = voltageData.length > 0 ? voltageData[voltageData.length - 1] : null;
            const lastA = currentData.length > 0 ? currentData[currentData.length - 1] : null;
            
            if (document.getElementById('current-voltage-large') && lastV) {
                document.getElementById('current-voltage-large').innerText = lastV.y.toFixed(1);
            }
            if (document.getElementById('current-current-large') && lastA) {
                document.getElementById('current-current-large').innerText = lastA.y.toFixed(2);
            }

            if (globalData) {
                if (document.getElementById('stat-max-v')) document.getElementById('stat-max-v').innerText = globalData.max_v.toFixed(1);
                if (document.getElementById('stat-max-a')) document.getElementById('stat-max-a').innerText = globalData.max_a.toFixed(2);
                if (document.getElementById('stat-avg-v')) document.getElementById('stat-avg-v').innerText = globalData.avg_v.toFixed(1);
                if (document.getElementById('stat-count')) document.getElementById('stat-count').innerText = globalData.total;
            }
        };

        const refreshChart = (xaxisOptions = null) => {
            const updateObj = {
                series: [
                    { name: 'Voltage', data: voltageData },
                    { name: 'Current', data: currentData }
                ]
            };
            if (xaxisOptions) updateObj.xaxis = xaxisOptions;
            chart.updateOptions(updateObj, false, true);
        };

        const fetchTimeframeData = async (range) => {
            try {
                document.querySelector("#solar-chart").style.opacity = '0.5';
                const response = await fetch(`{{ route('monitoring.solar_panel.history') }}?range=${range}`);
                const result = await response.json();
                
                if (result.success) {
                    voltageData = parseHistoryData(result.data, 'voltage_panel');
                    currentData = parseHistoryData(result.data, 'current_panel');
                    
                    const durations = { '5m': 300000, '1h': 3600000, '12h': 43200000, '1d': 86400000, '1w': 604800000, '1m': 2592000000 };
                    
                    let minDate = maxDate - (durations[range] || 3600000);
                    if (range === 'custom') {
                        minDate = new Date(result.startTime).getTime();
                    }

                    refreshChart({ 
                        min: minDate, 
                        max: maxDate,
                        labels: {
                            formatter: function(value) {
                                const date = new Date(value);
                                if (range === '1w' || range === '1m' || range === 'custom') {
                                    return date.toLocaleString('id-ID', {
                                        day: '2-digit',
                                        month: '2-digit',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    });
                                }
                                return date.toLocaleString('id-ID', {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    second: '2-digit'
                                });
                            }
                        }
                    });
                    currentGlobalStats = result.global;
                    updateSummary(currentGlobalStats);
                }
            } catch (error) {
                console.error('Error:', error);
            } finally {
                document.querySelector("#solar-chart").style.opacity = '1';
            }
        };

        const fetchCustomRange = () => {
            const start = document.getElementById('start-date').value;
            const end = document.getElementById('end-date').value;
            
            if (!start || !end) {
                alert('Please select both start and end dates');
                return;
            }

            if (new Date(start) > new Date(end)) {
                alert('Start date cannot be after end date');
                return;
            }

            document.querySelectorAll('.timeframe-btn').forEach(b => {
                b.classList.remove('bg-emerald-50', 'text-emerald-600', 'shadow-sm');
                b.classList.add('text-slate-400');
            });

            const range = 'custom';
            const url = `{{ route('monitoring.solar_panel.history') }}?range=${range}&start_date=${start}&end_date=${end}`;
            
            fetchData(url, range);
        };

        const fetchData = async (url, range) => {
            try {
                document.querySelector("#solar-chart").style.opacity = '0.5';
                const response = await fetch(url);
                const result = await response.json();
                
                if (result.success) {
                    voltageData = parseHistoryData(result.data, 'voltage_panel');
                    currentData = parseHistoryData(result.data, 'current_panel');
                    
                    const lastPoint = voltageData.length > 0 ? voltageData[voltageData.length - 1] : null;
                    const maxDate = lastPoint ? lastPoint.x : new Date(result.endTime).getTime();
                    const minDate = new Date(result.startTime).getTime();
                    
                    refreshChart({ 
                        min: minDate, 
                        max: maxDate,
                        labels: {
                            formatter: function(value) {
                                const date = new Date(value);
                                if (range === '1w' || range === '1m' || range === 'custom') {
                                    return date.toLocaleString('id-ID', {
                                        day: '2-digit',
                                        month: '2-digit',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    });
                                }
                                return date.toLocaleString('id-ID', {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    second: '2-digit'
                                });
                            }
                        }
                    });
                    currentGlobalStats = result.global;
                    updateSummary(currentGlobalStats);
                }
            } catch (error) {
                console.error('Error:', error);
            } finally {
                document.querySelector("#solar-chart").style.opacity = '1';
            }
        };

        document.getElementById('apply-filter').addEventListener('click', fetchCustomRange);
        
        const startDateInput = document.getElementById('start-date');
        const endDateInput = document.getElementById('end-date');
        startDateInput.addEventListener('change', () => { if (startDateInput.value) endDateInput.min = startDateInput.value; });
        endDateInput.addEventListener('change', () => { if (endDateInput.value) startDateInput.max = endDateInput.value; });


        document.querySelectorAll('.timeframe-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.timeframe-btn').forEach(b => {
                    b.classList.remove('bg-emerald-50', 'text-emerald-600', 'shadow-sm');
                    b.classList.add('text-slate-400');
                });
                btn.classList.add('bg-emerald-50', 'text-emerald-600', 'shadow-sm');
                btn.classList.remove('text-slate-400');
                fetchTimeframeData(btn.dataset.range);
            });
        });

        if (window.Echo) {
            window.Echo.channel('sensor-data')
                .listen('.new-data', (e) => {
                    const data = e.data;
                    const timestamp = new Date(data.timertc || data.created_at).getTime();

                    const placeholder = document.getElementById('chart-placeholder');
                    if (placeholder) placeholder.classList.add('hidden');

                    if (!voltageData.some(d => d.x === timestamp)) {
                        voltageData.push({ x: timestamp, y: parseFloat(data.voltage_panel) });
                        currentData.push({ x: timestamp, y: parseFloat(data.current_panel) });
                        
                        if (voltageData.length > 1000) {
                            voltageData.shift();
                            currentData.shift();
                        }
                        refreshChart();
                        
                        currentGlobalStats.total++;
                        currentGlobalStats.max_v = Math.max(currentGlobalStats.max_v, parseFloat(data.voltage_panel));
                        currentGlobalStats.max_a = Math.max(currentGlobalStats.max_a, parseFloat(data.current_panel));
                        updateSummary(currentGlobalStats);
                    }
                });
        }
    });
</script>

@endsection
