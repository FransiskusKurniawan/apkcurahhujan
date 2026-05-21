@extends('layouts.app')

@section('content')
<div class="space-y-6 animate__animated animate__fadeIn">
    
    <!-- Top Bar -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-400 hover:text-primary-600 border border-slate-200 transition-all shadow-sm">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h2 class="text-xl font-extrabold tracking-tight text-slate-800">Rainfall Analysis</h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Real-time Performance Sync</p>
            </div>
        </div>
        
        <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-slate-200">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
            </span>
            <span class="text-xs font-bold text-slate-600 uppercase tracking-tight">Live Tracking</span>
        </div>
    </div>

    <!-- Main Chart Card -->
    <!-- Main Chart Card -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden flex flex-col transition-all">
        <!-- Header -->
        <div class="p-6 md:p-8 border-b border-slate-50">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center">
                        <i data-lucide="cloud-rain" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-xl font-black text-slate-800 tracking-tight">Rainfall Intensity</h3>
                            <span class="px-2 py-0.5 bg-green-100 text-[10px] font-bold text-green-600 rounded-lg uppercase tracking-wider">LIVE</span>
                        </div>
                        <p class="text-xs font-medium text-slate-400">Atmospheric precipitation monitoring</p>
                    </div>
                </div>

                <div class="text-right">
                    <div class="flex items-baseline justify-end gap-1.5">
                        <span class="text-4xl font-black text-slate-900 tabular-nums tracking-tighter" id="current-rainfall-large">{{ number_format($latest->rainfall ?? 0, 2) }}</span>
                        <span class="text-sm font-bold text-slate-400">mm</span>
                    </div>
                    <div class="flex items-center justify-end gap-1 mt-1">
                        <div id="trend-indicator" class="flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-50 text-slate-400">
                            <i data-lucide="minus" class="w-3 h-3"></i>
                            <span>STABLE</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeframe & Controls Bar -->
        <div class="px-6 py-3 bg-slate-50/30 border-b border-slate-50 flex items-center justify-between overflow-x-auto gap-4 no-scrollbar">
            <div class="flex items-center gap-1 p-1 bg-white rounded-xl border border-slate-100 shadow-sm">
                <button class="timeframe-btn px-4 py-1.5 text-[11px] font-bold rounded-lg transition-all text-slate-400 hover:text-slate-600" data-range="5m">5m</button>
                <button class="timeframe-btn px-4 py-1.5 text-[11px] font-bold rounded-lg transition-all text-slate-400 hover:text-slate-600" data-range="1h">1h</button>
                <button class="timeframe-btn px-4 py-1.5 text-[11px] font-bold rounded-lg transition-all text-slate-400 hover:text-slate-600" data-range="12h">12h</button>
                <button class="timeframe-btn px-4 py-1.5 text-[11px] font-bold rounded-lg transition-all active-timeframe bg-primary-50 text-primary-600 shadow-sm" data-range="1d">1D</button>
                <button class="timeframe-btn px-4 py-1.5 text-[11px] font-bold rounded-lg transition-all text-slate-400 hover:text-slate-600" data-range="1w">1W</button>
                <button class="timeframe-btn px-4 py-1.5 text-[11px] font-bold rounded-lg transition-all text-slate-400 hover:text-slate-600" data-range="1m">1M</button>
            </div>


        </div>

        <!-- Chart Area -->
        <div class="relative p-2 md:p-4 bg-slate-50/50">
            <div id="rainfall-chart" class="w-full h-[400px]"></div>
            
            @if($history->isEmpty())
                <div id="chart-placeholder" class="absolute inset-0 flex flex-col items-center justify-center bg-white/50 backdrop-blur-sm z-10">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-4">
                        <i data-lucide="database-zap" class="w-8 h-8"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-500">Waiting for incoming sensor data...</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Period Selector -->
    <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 flex flex-col md:flex-row items-center justify-between gap-6 transition-all">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center">
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
                <input type="date" id="start-date" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2 text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20 transition-all">
            </div>
            <div class="flex flex-col gap-1 w-full md:w-44">
                <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">End Date</label>
                <input type="date" id="end-date" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2 text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20 transition-all">
            </div>
            <button id="apply-filter" class="w-full md:w-auto mt-4 bg-primary-600 hover:bg-primary-700 text-white px-8 py-2.5 rounded-xl text-xs font-bold transition-all shadow-lg shadow-primary-500/20 active:scale-95 flex items-center justify-center gap-2">
                <i data-lucide="filter" class="w-4 h-4"></i>
                Apply Filter
            </button>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm hover:border-primary-200 transition-colors">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-orange-50 text-orange-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                </div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Max Intensity</p>
            </div>
            <div class="flex items-baseline gap-1">
                <span class="text-3xl font-black text-slate-800" id="stat-max">{{ number_format($globalStats['max'], 1) }}</span>
                <span class="text-xs font-bold text-slate-400">mm</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm hover:border-primary-200 transition-colors">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                </div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Global average</p>
            </div>
            <div class="flex items-baseline gap-1">
                <span class="text-3xl font-black text-slate-800" id="stat-avg">{{ number_format($globalStats['avg'], 1) }}</span>
                <span class="text-xs font-bold text-slate-400">mm</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm hover:border-primary-200 transition-colors">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-green-50 text-green-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sync Count</p>
            </div>
            <div class="flex items-baseline gap-1">
                <span class="text-3xl font-black text-slate-800" id="stat-count">{{ $globalStats['total'] }}</span>
                <span class="text-xs font-bold text-slate-400">samples</span>
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
        
        const parseHistoryData = (data) => {
            if (!data) return [];
            const items = Array.isArray(data) ? data : Object.values(data);
            const seen = new Set();
            return items
                .map(item => {
                    const timeSource = item.timertc || item.created_at;
                    if (!timeSource) return null;
                    
                    // Robust date parsing
                    let date;
                    if (typeof timeSource === 'string' && !timeSource.includes('T') && !timeSource.includes('Z') && !timeSource.includes('+')) {
                        // Handle "YYYY-MM-DD HH:MM:SS" by assuming it's in a format the browser can parse,
                        // but if it fails, we try to make it ISO by replacing space with T
                        date = new Date(timeSource.replace(' ', 'T'));
                    } else {
                        date = new Date(timeSource);
                    }
                    
                    const timestamp = date.getTime();
                    const rainfallValue = item.rainfall !== undefined && item.rainfall !== null ? parseFloat(item.rainfall) : null;

                    return {
                        x: timestamp,
                        y: Number.isFinite(rainfallValue) ? rainfallValue : null
                    };
                })
                .filter(item => {
                    // Filter out invalid items, invalid times and duplicates which can break lines
                    if (!item || item.x === null || Number.isNaN(item.x)) return false;
                    if (seen.has(item.x)) return false;
                    seen.add(item.x);
                    return true;
                })
                .sort((a, b) => a.x - b.x);
        };

        let chartData = parseHistoryData(initialHistory);

        // Chart Configuration
        const options = {
            series: [{
                name: 'Rainfall Intensity',
                data: chartData
            }],
            colors: ['#0284c7'],
            annotations: {
                yaxis: [{
                    y: chartData.length > 0 ? chartData[chartData.length - 1].y : 0,
                    borderColor: '#0284c7',
                    label: {
                        borderColor: '#0284c7',
                        style: {
                            color: '#fff',
                            background: '#0284c7',
                            fontWeight: 800,
                            fontSize: '11px',
                            padding: { left: 8, right: 8, top: 4, bottom: 4 }
                        },
                        text: chartData.length > 0 ? chartData[chartData.length - 1].y.toFixed(2) : '0.00'
                    }
                }],
                points: [{
                    x: chartData.length > 0 ? chartData[chartData.length - 1].x : 0,
                    y: chartData.length > 0 ? chartData[chartData.length - 1].y : 0,
                    marker: {
                        size: 6,
                        fillColor: '#0284c7',
                        strokeColor: '#fff',
                        strokeWidth: 3,
                        cssClass: 'apexcharts-custom-marker-blink'
                    }
                }]
            },
            chart: {
                type: 'area',
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
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                },
                sparkline: { enabled: false }
            },
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: 2.5,
                connectNulls: true
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0,
                    stops: [0, 90, 100],
                    colorStops: [
                        { offset: 0, color: '#0284c7', opacity: 0.4 },
                        { offset: 100, color: '#0284c7', opacity: 0 }
                    ]
                }
            },
            markers: {
                size: 0,
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: { size: 6 }
            },
            xaxis: {
                type: 'datetime',
                tooltip: {
                    enabled: true,
                    formatter: (val) => {
                        const date = new Date(val);
                        return date.toLocaleString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        });
                    }
                },
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 },
                    datetimeUTC: false,
                    format: 'HH:mm',
                    formatter: function(value) {
                        const date = new Date(value);
                        // For 1D view, showing HH:mm is better
                        return date.toLocaleString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }
                }
            },
            yaxis: {
                opposite: true,
                labels: {
                    style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 },
                    formatter: (val) => val.toFixed(1)
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                padding: { top: 0, right: 20, bottom: 0, left: 10 },
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            theme: { mode: 'light' },
            tooltip: {
                custom: function({ series, seriesIndex, dataPointIndex, w }) {
                    const value = series[seriesIndex][dataPointIndex];
                    const timestamp = w.globals.seriesX[seriesIndex][dataPointIndex];
                    const date = new Date(timestamp);
                    const timeStr = date.toLocaleString('id-ID', {
                        day: '2-digit',
                        month: '2-digit', 
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
                    
                    return `
                        <div class="bg-slate-900 text-white p-4 rounded-2xl border border-slate-800 shadow-2xl">
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 border-b border-slate-800 pb-1">${timeStr}</div>
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full bg-primary-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]"></div>
                                <div class="text-lg font-black tracking-tight">${value.toFixed(2)} <span class="text-xs font-bold text-slate-400">mm</span></div>
                            </div>
                        </div>
                    `;
                }
            }
        };

        // Calculate initial window for 1D (latest data on the right)
        if (chartData.length > 0) {
            const lastPoint = chartData[chartData.length - 1];
            const maxDate = lastPoint.x;
            const minDate = maxDate - (24 * 60 * 60 * 1000); // 1D window
            
            options.xaxis.min = minDate;
            options.xaxis.max = maxDate;
        }

        const chart = new ApexCharts(document.querySelector("#rainfall-chart"), options);
        chart.render();

        let currentGlobalStats = @json($globalStats);
        setTimeout(() => updateSummary(currentGlobalStats), 100);

        // Helper to update summary stats
        const updateSummary = (globalData = null) => {
            const values = chartData.map(d => d.y).filter(v => v !== null);
            const lastPoint = chartData.length > 0 ? chartData[chartData.length - 1] : null;
            
            // Update big display from latest point
            const largeDisplay = document.getElementById('current-rainfall-large');
            if (largeDisplay && lastPoint) {
                largeDisplay.innerText = lastPoint.y.toFixed(2);
            }

            // Update stats cards from global data if provided, otherwise fallback to chart data
            if (globalData) {
                if (document.getElementById('stat-max')) document.getElementById('stat-max').innerText = globalData.max.toFixed(1);
                if (document.getElementById('stat-avg')) document.getElementById('stat-avg').innerText = globalData.avg.toFixed(1);
                if (document.getElementById('stat-count')) document.getElementById('stat-count').innerText = globalData.total;
            } else {
                const max = values.length > 0 ? Math.max(...values) : 0;
                const avg = values.length > 0 ? (values.reduce((a, b) => a + b, 0) / values.length) : 0;
                
                if (document.getElementById('stat-max')) document.getElementById('stat-max').innerText = max.toFixed(1);
                if (document.getElementById('stat-avg')) document.getElementById('stat-avg').innerText = avg.toFixed(1);
                if (document.getElementById('stat-count')) document.getElementById('stat-count').innerText = chartData.length;
            }
        };

        // Refresh chart with new data ensuring chronological order
        const refreshChart = (xaxisOptions = null) => {
            chartData.sort((a, b) => a.x - b.x);
            const lastPoint = chartData.length > 0 ? chartData[chartData.length - 1] : null;
            
            const updateObj = {
                series: [{ data: chartData }],
                annotations: {
                    yaxis: [{
                        y: lastPoint ? lastPoint.y : 0,
                        borderColor: '#0284c7',
                        label: {
                            borderColor: '#0284c7',
                            style: {
                                color: '#fff',
                                background: '#0284c7',
                                fontWeight: 800,
                                fontSize: '11px'
                            },
                            text: lastPoint ? lastPoint.y.toFixed(2) : '0.00'
                        }
                    }],
                    points: lastPoint ? [{
                        x: lastPoint.x,
                        y: lastPoint.y,
                        marker: {
                            size: 6,
                            fillColor: '#0284c7',
                            strokeColor: '#fff',
                            strokeWidth: 3,
                            cssClass: 'apexcharts-custom-marker-blink'
                        }
                    }] : []
                }
            };

            if (xaxisOptions) {
                updateObj.xaxis = xaxisOptions;
            }

            // Update options and series together in a single call to ensure proper redraw
            chart.updateOptions(updateObj, false, true);
        };

        // Timeframe Switching
        const fetchTimeframeData = async (range) => {
            try {
                // Show loading state
                const chartContainer = document.querySelector("#rainfall-chart");
                chartContainer.style.opacity = '0.5';
                
                const response = await fetch(`{{ route('monitoring.rainfall.history') }}?range=${range}`);
                const result = await response.json();
                
                if (result.success) {
                    chartData = parseHistoryData(result.data);
                    
                    // Use maxTime from server or the latest point in data as the window reference
                    let pivotDate;
                    if (result.maxTime) {
                        const parsedMax = new Date(result.maxTime.replace(' ', 'T'));
                        pivotDate = isNaN(parsedMax.getTime()) ? new Date(result.maxTime) : parsedMax;
                    } else {
                        pivotDate = new Date(result.endTime);
                    }
                    
                    const lastPoint = chartData.length > 0 ? chartData[chartData.length - 1] : null;
                    const referenceTimestamp = lastPoint ? Math.max(lastPoint.x, pivotDate.getTime()) : pivotDate.getTime();
                    
                    const durations = {
                        '5m': 5 * 60 * 1000,
                        '1h': 60 * 60 * 1000,
                        '12h': 12 * 60 * 60 * 1000,
                        '1d': 24 * 60 * 60 * 1000,
                        '1w': 7 * 24 * 60 * 60 * 1000,
                        '1m': 30 * 24 * 60 * 60 * 1000
                    };

                    const maxDate = referenceTimestamp;
                    let minDate = maxDate - (durations[range] || durations['1h']);

                    // Adjust minDate for custom range
                    if (range === 'custom') {
                        minDate = new Date(result.startTime).getTime();
                    }
                    
                    const xaxisOptions = {
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
                    };
                    
                    refreshChart(xaxisOptions);
                    currentGlobalStats = result.global;
                    updateSummary(currentGlobalStats);
                }
            } catch (error) {
                console.error('Error fetching history:', error);
            } finally {
                document.querySelector("#rainfall-chart").style.opacity = '1';
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

            // Remove active state from preset buttons
            document.querySelectorAll('.timeframe-btn').forEach(b => {
                b.classList.remove('bg-primary-50', 'text-primary-600', 'shadow-sm');
                b.classList.add('text-slate-400');
            });

            const range = 'custom';
            const url = `{{ route('monitoring.rainfall.history') }}?range=${range}&start_date=${start}&end_date=${end}`;
            
            fetchData(url, range);
        };

        // Date Validation Logic
        const startDateInput = document.getElementById('start-date');
        const endDateInput = document.getElementById('end-date');

        startDateInput.addEventListener('change', () => {
            if (startDateInput.value) {
                endDateInput.min = startDateInput.value;
            }
        });

        endDateInput.addEventListener('change', () => {
            if (endDateInput.value) {
                startDateInput.max = endDateInput.value;
            }
        });

        const fetchData = async (url, range) => {
            try {
                const chartContainer = document.querySelector("#rainfall-chart");
                chartContainer.style.opacity = '0.5';
                
                const response = await fetch(url);
                const result = await response.json();
                
                if (result.success) {
                    chartData = parseHistoryData(result.data);
                    
                    const lastPoint = chartData.length > 0 ? chartData[chartData.length - 1] : null;
                    const maxDate = lastPoint ? lastPoint.x : new Date(result.endTime).getTime();
                    const minDate = new Date(result.startTime).getTime();
                    
                    const xaxisOptions = {
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
                    };
                    
                    refreshChart(xaxisOptions);
                    currentGlobalStats = result.global;
                    updateSummary(currentGlobalStats);
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            } finally {
                document.querySelector("#rainfall-chart").style.opacity = '1';
            }
        };

        document.getElementById('apply-filter').addEventListener('click', fetchCustomRange);

        document.querySelectorAll('.timeframe-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.timeframe-btn').forEach(b => {
                    b.classList.remove('bg-primary-50', 'text-primary-600', 'shadow-sm');
                    b.classList.add('text-slate-400');
                });
                btn.classList.add('bg-primary-50', 'text-primary-600', 'shadow-sm');
                btn.classList.remove('text-slate-400');
                
                fetchTimeframeData(btn.dataset.range);
            });
        });

        // Real-time Update via Echo
        if (window.Echo) {
            window.Echo.channel('sensor-data')
                .listen('.new-data', (e) => {
                    const data = e.data;
                    console.log('Real-time data received:', data);
                    
                    const rainfallValue = data.rainfall !== undefined && data.rainfall !== null ? parseFloat(data.rainfall) : null;
                    const timeSource = data.timertc || data.created_at;
                    const timestamp = timeSource ? new Date(timeSource).getTime() : Date.now();

                    // Hide placeholder if it exists
                    const placeholder = document.getElementById('chart-placeholder');
                    if (placeholder) placeholder.classList.add('hidden');

                    // Update local data array with duplicate check
                    if (!chartData.some(d => d.x === timestamp)) {
                        chartData.push({ x: timestamp, y: Number.isFinite(rainfallValue) ? rainfallValue : null });
                        // Sort chronologically from left to right
                        chartData.sort((a, b) => a.x - b.x);
                        
                        // Keep it performant:
                        if (chartData.length > 1000) chartData.shift();

                        // Push to chart
                        refreshChart();
                    }

                    // Update UI elements
                    const currentEl = document.getElementById('current-rainfall-large');
                    const trendEl = document.getElementById('trend-indicator');
                    
                    if (currentEl) {
                        const prevValue = parseFloat(currentEl.innerText);
                        const newValue = Number.isFinite(rainfallValue) ? rainfallValue : 0;
                        currentEl.innerText = newValue.toFixed(2);
                        
                        // Update global stats for real-time
                        currentGlobalStats.total++;
                        currentGlobalStats.max = Math.max(currentGlobalStats.max, newValue);
                        // Average is trickier to update perfectly without full sum, but we can approximate or just leave it for now
                        // For a precise average, we'd need total sum:
                        // currentGlobalStats.avg = (currentGlobalStats.avg * (currentGlobalStats.total - 1) + newValue) / currentGlobalStats.total;
                        
                        // Update trend indicator
                        if (trendEl) {
                            if (newValue > prevValue) {
                                trendEl.innerHTML = '<i data-lucide="trending-up" class="w-3 h-3"></i><span>INCREASING</span>';
                                trendEl.className = 'flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-green-50 text-green-600';
                            } else if (newValue < prevValue) {
                                trendEl.innerHTML = '<i data-lucide="trending-down" class="w-3 h-3"></i><span>DECREASING</span>';
                                trendEl.className = 'flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-orange-50 text-orange-600';
                            }
                            lucide.createIcons();
                        }
                    }

                    updateSummary(currentGlobalStats);
                });
        }
    });
</script>

<style>
    .apexcharts-canvas { margin: 0 auto; }
    .apexcharts-tooltip { 
        background: transparent !important; 
        border: none !important; 
        box-shadow: none !important; 
    }
    
    @keyframes blinker {
        0% { opacity: 1; }
        50% { opacity: 0.3; }
        100% { opacity: 1; }
    }
    
    .apexcharts-custom-marker-blink {
        animation: blinker 1s infinite;
        filter: drop-shadow(0 0 4px rgba(2, 132, 199, 0.6));
    }
</style>

@endsection
