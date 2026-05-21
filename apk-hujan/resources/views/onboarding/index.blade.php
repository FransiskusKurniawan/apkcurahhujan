@extends('layouts.guest')

@section('content')
    @auth
    <!-- Authenticated Header -->
    <div class="fixed top-6 right-6 z-50 animate__animated animate__fadeIn">
        <div class="glass-card px-6 py-3 rounded-2xl shadow-xl shadow-blue-500/5 border border-white/50 flex items-center gap-4">
            <div class="text-right hidden sm:block">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Logged in as</p>
                <p class="text-sm font-extrabold text-blue-600 truncate max-w-[150px]">{{ Auth::user()->name }}</p>
            </div>
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 font-bold border border-blue-200">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="w-10 h-10 bg-red-50 text-red-500 rounded-xl flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm group" title="Logout">
                    <i data-lucide="log-out" class="w-5 h-5 transition-transform group-hover:scale-110"></i>
                </button>
            </form>
        </div>
    </div>
    @endauth

    <div class="max-w-7xl mx-auto px-6 py-12 lg:py-20">
        
        <!-- Desktop View (Large Screens) -->
        <div class="hidden lg:grid grid-cols-3 gap-8 items-stretch">
            
            <!-- Section 1: Welcome -->
            <div class="glass-card p-10 rounded-[2.5rem] shadow-xl shadow-blue-500/5 flex flex-col justify-between transition-all hover:shadow-2xl hover:shadow-blue-500/10 hover:-translate-y-1 animate__animated animate__fadeInUp">
                <div>
                    <div class="w-16 h-16 bg-blue-50/50 rounded-2xl flex items-center justify-center mb-8 animate-float overflow-hidden">
                        <img src="{{ asset('images/logosimocanobg.png') }}" class="w-12 h-12 object-contain" alt="SIMOCA Logo">
                    </div>
                    <h1 class="text-3xl font-extrabold tracking-tight mb-4 leading-tight">
                        Welcome to <span class="text-blue-600">SIMOCA</span>
                    </h1>
                    <p class="text-slate-500 text-lg leading-relaxed">
                        Sistem Monitoring Cuaca - Monitor environmental conditions in real-time with our advanced smart IoT sensors. Precision data for a safer tomorrow.
                    </p>
                </div>
                <div class="mt-12 bg-blue-50/50 rounded-2xl p-6 border border-blue-100/50 animate__animated animate__fadeIn animate-delay-500">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                            <i data-lucide="cpu" class="w-6 h-6 text-blue-500"></i>
                        </div>
                        <div class="text-sm font-semibold text-blue-800">IoT Integrated System</div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Features -->
            <div class="glass-card p-10 rounded-[2.5rem] shadow-xl shadow-blue-500/5 transition-all hover:shadow-2xl hover:shadow-blue-500/10 hover:-translate-y-1 animate__animated animate__fadeInUp animate-delay-100">
                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-8 animate-float animate-delay-200">
                    <i data-lucide="activity" class="w-8 h-8 text-blue-600"></i>
                </div>
                <h2 class="text-3xl font-extrabold tracking-tight mb-8">Real-Time Sensor Data</h2>
                
                <div class="space-y-4">
                    @foreach([
                        ['droplets', 'Rainfall Intensity'],
                        ['waves', 'Water Level Tracking'],
                        ['thermometer-sun', 'Temp & Humidity'],
                        ['sun', 'Light Intensity'],
                        ['zap', 'Power Management']
                    ] as $index => $item)
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-white/50 border border-white hover:bg-white transition-all hover:scale-[1.02] animate__animated animate__fadeInLeft animate-delay-{{ ($index + 1) * 100 }}">
                        <i data-lucide="{{ $item[0] }}" class="w-6 h-6 text-blue-500"></i>
                        <span class="font-medium text-slate-700">{{ $item[1] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Section 3: Smart System & CTA -->
            <div class="glass-card p-10 rounded-[2.5rem] shadow-xl shadow-blue-500/5 flex flex-col transition-all hover:shadow-2xl hover:shadow-blue-500/10 hover:-translate-y-1 animate__animated animate__fadeInUp animate-delay-200">
                <div class="flex-grow">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-8 animate-float animate-delay-300">
                        <i data-lucide="layout-dashboard" class="w-8 h-8 text-blue-600"></i>
                    </div>
                    <h2 class="text-3xl font-extrabold tracking-tight mb-4 leading-tight">Efficient & Smart Control</h2>
                    <p class="text-slate-500 text-lg mb-8 leading-relaxed">
                        Powered by solar energy with intelligent monitoring and deep historical data tracking.
                    </p>
                    
                    <div class="relative w-full aspect-video bg-blue-600 rounded-3xl overflow-hidden shadow-lg mb-8 group animate__animated animate__zoomIn animate-delay-500">
                         <!-- Mockup UI -->
                        <div class="absolute inset-4 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm border border-white/20">
                            <i data-lucide="sun" class="w-12 h-12 text-white animate-pulse"></i>
                        </div>
                        <div class="absolute bottom-4 left-4 right-4 h-12 bg-white/10 rounded-lg backdrop-blur-sm border border-white/10 flex items-center px-4 gap-2">
                            <div class="w-full h-1 bg-white/20 rounded-full overflow-hidden">
                                <div class="w-2/3 h-full bg-blue-300 animate-[shimmer_2s_infinite]"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @auth
                        <a href="/dashboard" class="w-full bg-blue-600 text-white font-bold py-5 rounded-2xl text-center shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all hover:scale-[1.02] active:scale-[0.98]">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="/login?action=register" class="w-full bg-blue-600 text-white font-bold py-5 rounded-2xl text-center shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all hover:scale-[1.02] active:scale-[0.98]">
                            Get Started
                        </a>
                        <a href="/login" class="w-full bg-white text-blue-600 font-bold py-5 rounded-2xl text-center border-2 border-blue-50 shadow-sm hover:bg-blue-50 transition-all hover:scale-[1.02] active:scale-[0.98]">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile View (Slider) -->
        <div 
            x-data="{ 
                activeSlide: 0, 
                totalSlides: 3,
                next() { this.activeSlide = (this.activeSlide + 1) % this.totalSlides },
                prev() { this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides }
            }" 
            class="lg:hidden h-[85vh] flex flex-col justify-between"
            x-cloak
        >
            
            <!-- Slides Container -->
            <div class="relative flex-grow overflow-hidden">
                
                <!-- Slide 1 -->
                <div x-show="activeSlide === 0" 
                     x-transition:enter="animate__animated animate__fadeInRight animate__faster"
                     x-transition:leave="animate__animated animate__fadeOutLeft animate__faster"
                     class="absolute inset-0 flex flex-col items-center text-center space-y-6 pt-10">
                    <div class="w-24 h-24 bg-blue-50/50 rounded-3xl flex items-center justify-center mb-4 animate-float overflow-hidden">
                        <img src="{{ asset('images/logosimocanobg.png') }}" class="w-20 h-20 object-contain" alt="SIMOCA Logo">
                    </div>
                    <h1 class="text-3xl font-extrabold px-4 animate__animated animate__fadeInDown">Welcome to SIMOCA</h1>
                    <p class="text-slate-500 text-lg px-8 leading-relaxed">
                        Sistem Monitoring Cuaca - Monitor environmental conditions in real-time with smart IoT sensors.
                    </p>
                    <div class="w-full h-48 flex items-center justify-center">
                        <div class="relative group">
                            <div class="w-32 h-32 bg-blue-500/10 rounded-full animate-ping absolute -inset-2"></div>
                            <div class="w-32 h-32 bg-blue-600 rounded-full flex items-center justify-center relative shadow-xl shadow-blue-600/40 transition-transform group-hover:scale-110">
                                <i data-lucide="wifi" class="w-16 h-16 text-white text-blue-200"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div x-show="activeSlide === 1" 
                     x-transition:enter="animate__animated animate__fadeInRight animate__faster"
                     x-transition:leave="animate__animated animate__fadeOutLeft animate__faster"
                     class="absolute inset-0 flex flex-col items-center text-center space-y-6 pt-10">
                    <div class="w-24 h-24 bg-blue-100 rounded-3xl flex items-center justify-center mb-4 animate-float">
                        <i data-lucide="activity" class="w-12 h-12 text-blue-600"></i>
                    </div>
                    <h1 class="text-3xl font-extrabold px-4 animate__animated animate__fadeInDown">Real-Time Sensor Data</h1>
                    <p class="text-slate-500 text-lg px-8 leading-relaxed mb-4">
                        Track rainfall, water level, temperature, and more instantly.
                    </p>
                    
                    <div class="grid grid-cols-2 gap-4 w-full px-6">
                        @foreach(['droplets' => 'Rainfall', 'waves' => 'Levels', 'thermometer-sun' => 'Temp', 'zap' => 'Power'] as $icon => $label)
                        <div class="bg-white/80 backdrop-blur-sm p-4 rounded-3xl shadow-sm border border-blue-50 flex flex-col items-center gap-2 transition-all active:scale-95">
                            <i data-lucide="{{ $icon }}" class="w-6 h-6 text-blue-500"></i>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ $label }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Slide 3 -->
                <div x-show="activeSlide === 2" 
                     x-transition:enter="animate__animated animate__fadeInRight animate__faster"
                     x-transition:leave="animate__animated animate__fadeOutLeft animate__faster"
                     class="absolute inset-0 flex flex-col items-center text-center space-y-6 pt-10">
                    <div class="w-24 h-24 bg-blue-100 rounded-3xl flex items-center justify-center mb-4 animate-float">
                        <i data-lucide="layout-dashboard" class="w-12 h-12 text-blue-600"></i>
                    </div>
                    <h1 class="text-3xl font-extrabold px-4 animate__animated animate__fadeInDown">Efficient and Smart Control</h1>
                    <p class="text-slate-500 text-lg px-8 leading-relaxed mb-6">
                        Powered by solar energy with intelligent monitoring.
                    </p>
                    
                    <div class="w-full px-6 space-y-4 animate__animated animate__zoomIn">
                        @auth
                            <a href="/dashboard" class="block w-full bg-blue-600 text-white font-bold py-5 rounded-2xl shadow-lg shadow-blue-600/20 active:scale-95 transition-transform text-center font-bold">
                                Go to Monitoring
                            </a>
                        @else
                            <a href="/login?action=register" class="block w-full bg-blue-600 text-white font-bold py-5 rounded-2xl shadow-lg shadow-blue-600/20 active:scale-95 transition-transform text-center font-bold">
                                Get Started
                            </a>
                            <a href="/login" class="block w-full bg-white text-blue-600 font-bold py-5 rounded-2xl border-2 border-blue-50 active:scale-95 transition-transform text-center font-bold">
                                Login
                            </a>
                        @endauth
                    </div>
                </div>

            </div>

            <!-- Mobile Controls -->
            <div class="py-10 space-y-8 mt-auto">
                <!-- Pagination Dots -->
                <div class="flex justify-center gap-2">
                    <template x-for="i in totalSlides" :key="i-1">
                        <button 
                            @click="activeSlide = i-1"
                            class="h-2 rounded-full transition-all duration-300"
                            :class="activeSlide === i-1 ? 'w-8 bg-blue-600' : 'w-2 bg-blue-200'"
                        ></button>
                    </template>
                </div>

                <!-- Navigation Btns -->
                <div class="flex justify-between px-10" x-show="activeSlide < 2">
                     <button @click="activeSlide = 2" class="text-slate-400 font-semibold hover:text-slate-600">Skip</button>
                     <button @click="next()" class="bg-blue-600 w-12 h-12 rounded-full flex items-center justify-center text-white shadow-lg shadow-blue-600/30 active:scale-90 transition-transform">
                        <i data-lucide="arrow-right" class="w-6 h-6"></i>
                     </button>
                </div>
            </div>
        </div>

    </div>

    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
    </style>
@endsection
