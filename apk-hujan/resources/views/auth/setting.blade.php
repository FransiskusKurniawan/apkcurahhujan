@extends('layouts.app')

@section('content')
<div class="space-y-6 animate__animated animate__fadeIn">
    <!-- Header -->
    <div class="flex flex-col gap-1">
        <h2 class="text-2xl font-extrabold tracking-tight text-slate-800">
            Profile Settings
        </h2>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
            Manage your account and preferences
        </p>
    </div>

    @if (session('success'))
    <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-2xl text-sm font-bold animate__animated animate__fadeInDown">
        {{ session('success') }}
    </div>
    @endif

    <!-- User Profile Card (clicking the avatar changes photo) -->
    <div class="block bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm overflow-hidden relative">
        <div class="absolute right-0 top-0 w-32 h-32 bg-primary-50 rounded-full -mr-16 -mt-16"></div>

        <div class="relative flex flex-col items-center">
            <!-- Hidden upload form -->
            <form id="photo-form" action="{{ route('upload-photo') }}" method="POST" enctype="multipart/form-data" class="hidden">
                @csrf
                <input type="file" id="profile_photo_input" name="profile_photo" accept="image/*" onchange="document.getElementById('photo-form').submit()">
            </form>

            <!-- Clickable Avatar -->
            <button type="button" onclick="document.getElementById('profile_photo_input').click()"
                class="relative group w-24 h-24 rounded-full mb-4 focus:outline-none">
                @if(Auth::user()->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                         alt="Profile Photo"
                         class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                @else
                    <div class="w-24 h-24 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 border-4 border-white shadow-lg">
                        <i data-lucide="user" class="w-12 h-12"></i>
                    </div>
                @endif
                <!-- Camera Overlay -->
                <div class="absolute inset-0 rounded-full bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <i data-lucide="camera" class="w-8 h-8 text-white"></i>
                </div>
            </button>

            @error('profile_photo')
                <p class="text-red-500 text-[10px] font-bold uppercase tracking-wider mb-2">{{ $message }}</p>
            @enderror

            <a href="{{ route('change-username') }}" class="text-center group">
                <h3 class="text-xl font-black text-slate-800 tracking-tight group-hover:text-primary-600 transition-colors">{{ Auth::user()->name }}</h3>
                <p class="text-sm font-bold text-slate-400 tracking-tight">@<span>{{ Auth::user()->username }}</span></p>
            </a>

            <div class="mt-4 flex gap-2">
                <span class="px-3 py-1 bg-primary-50 text-primary-600 text-[10px] font-black uppercase rounded-full tracking-wider">IoT Subscriber</span>
                <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black uppercase rounded-full tracking-wider">Active</span>
            </div>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-3">Tap avatar to change photo</p>
        </div>
    </div>

    <!-- Settings Options -->
    <div class="space-y-3">
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest px-2">Preferences</h3>
        
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden divide-y divide-slate-50">
            <a href="#" class="flex items-center justify-between p-5 hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-slate-50 text-slate-500 rounded-xl flex items-center justify-center">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-700 tracking-tight">Notifications</h4>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Alerts & Messaging</p>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-slate-300"></i>
            </a>

            <a href="{{ route('change-password') }}" class="flex items-center justify-between p-5 hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-slate-50 text-slate-500 rounded-xl flex items-center justify-center">
                        <i data-lucide="lock" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-700 tracking-tight">Security</h4>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Password & 2FA</p>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-slate-300"></i>
            </a>

            <a href="#" class="flex items-center justify-between p-5 hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-slate-50 text-slate-500 rounded-xl flex items-center justify-center">
                        <i data-lucide="help-circle" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-700 tracking-tight">Help Center</h4>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Support & FAQs</p>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-slate-300"></i>
            </a>
        </div>
    </div>

    <!-- Logout Area -->
    <div class="pt-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-red-50 text-red-600 font-black py-4 rounded-2xl hover:bg-red-600 hover:text-white transition-all duration-300 flex items-center justify-center gap-3">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                <span class="uppercase tracking-widest text-xs">Sign Out</span>
            </button>
        </form>
    </div>
</div>
@endsection
