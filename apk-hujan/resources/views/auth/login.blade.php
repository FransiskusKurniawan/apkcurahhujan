@extends('layouts.guest')

@section('content')
<div class="min-h-[90vh] flex flex-col items-center justify-center p-6" x-data="{ isLogin: new URLSearchParams(window.location.search).get('action') !== 'register' }">
    <!-- Logo/Header Area -->
    <div class="mb-8 text-center animate__animated animate__fadeInDown">
        <div class="w-20 h-20 bg-blue-50/50 rounded-3xl flex items-center justify-center mx-auto mb-4 animate-float shadow-lg shadow-blue-500/5 overflow-hidden">
            <img src="{{ asset('images/logosimocanobg.png') }}" class="w-16 h-16 object-contain" alt="SIMOCA Logo">
        </div>
        <h1 class="text-3xl font-extrabold tracking-tight">
            <span class="text-blue-600" x-text="isLogin ? 'Welcome Back' : 'Join Us'"></span>
        </h1>
        <p class="text-slate-500 mt-2" x-text="isLogin ? 'Sign in to monitor your IoT sensors' : 'Create an account to start monitoring'"></p>
    </div>

    <!-- Auth Card -->
    <div class="w-full max-w-md glass-card p-8 md:p-10 rounded-[2.5rem] shadow-2xl shadow-blue-500/10 animate__animated animate__zoomIn">
        
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl flex items-center gap-3 animate__animated animate__fadeIn">
            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
            <p class="text-sm font-bold text-green-800">{{ session('success') }}</p>
        </div>
        @endif

        <!-- Toggle Switch -->
        <div class="flex p-1.5 bg-slate-100/50 backdrop-blur-sm rounded-2xl mb-8 border border-slate-200/50">
            <button 
                @click="isLogin = true" 
                class="flex-1 py-2.5 text-sm font-bold rounded-xl transition-all duration-300"
                :class="isLogin ? 'bg-white text-blue-600 shadow-md' : 'text-slate-500 hover:text-slate-700'"
            >
                Login
            </button>
            <button 
                @click="isLogin = false" 
                class="flex-1 py-2.5 text-sm font-bold rounded-xl transition-all duration-300"
                :class="!isLogin ? 'bg-white text-blue-600 shadow-md' : 'text-slate-500 hover:text-slate-700'"
            >
                Register
            </button>
        </div>

        <!-- Login Form -->
        <form x-show="isLogin" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" action="/login" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="user" class="w-5 h-5 text-slate-400"></i>
                    </span>
                    <input type="text" name="username" value="{{ old('username') }}" required class="w-full pl-11 pr-4 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" placeholder="Enter your username">
                </div>
                @error('username')
                    <p class="text-red-500 text-xs mt-2 ml-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="w-5 h-5 text-slate-400"></i>
                    </span>
                    <input type="password" name="password" required class="w-full pl-11 pr-4 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" placeholder="Enter your password">
                </div>
            </div>

            <div class="flex items-center justify-between ml-1 text-sm">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-slate-500 group-hover:text-slate-700 transition-colors">Remember me</span>
                </label>
                <a href="#" class="text-blue-600 font-bold hover:text-blue-700 transition-colors">Forgot Password?</a>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-600/20 hover:bg-blue-700 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                <span>Login</span>
                <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </button>
        </form>

        <!-- Register Form -->
        <form x-show="!isLogin" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" action="/register?action=register" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Full Name</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="tag" class="w-5 h-5 text-slate-400"></i>
                    </span>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full pl-11 pr-4 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" placeholder="Your full name">
                </div>
                @error('name')
                    <p class="text-red-500 text-xs mt-2 ml-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="user" class="w-5 h-5 text-slate-400"></i>
                    </span>
                    <input type="text" name="username" value="{{ old('username') }}" required class="w-full pl-11 pr-4 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" placeholder="Choose a username">
                </div>
                @error('username')
                    <p class="text-red-500 text-xs mt-2 ml-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="w-5 h-5 text-slate-400"></i>
                    </span>
                    <input type="password" name="password" required class="w-full pl-11 pr-4 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" placeholder="Create password">
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-2 ml-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Confirm Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="check-circle" class="w-5 h-5 text-slate-400"></i>
                    </span>
                    <input type="password" name="password_confirmation" required class="w-full pl-11 pr-4 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" placeholder="Repeat password">
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-600/20 hover:bg-blue-700 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2 mt-4">
                <span>Create Account</span>
                <i data-lucide="user-plus" class="w-5 h-5"></i>
            </button>
        </form>
    </div>

    <!-- Footer Information -->
    <div class="mt-8 text-center animate__animated animate__fadeIn animate-delay-500">
        <p class="text-slate-400 text-sm">
            &copy; {{ date('Y') }} SIMOCA (Sistem Monitoring Cuaca). All rights reserved.
        </p>
    </div>
</div>

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.6);
    }
    
    [x-cloak] { display: none !important; }

    input::placeholder {
        color: #94a3b8;
    }
</style>


@endsection
