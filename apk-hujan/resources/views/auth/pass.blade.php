@extends('layouts.app')

@section('content')
<div class="space-y-6 animate__animated animate__fadeIn">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('setting') }}" class="w-10 h-10 bg-white rounded-xl border border-slate-100 shadow-sm flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors">
            <i data-lucide="chevron-left" class="w-5 h-5"></i>
        </a>
        <div class="flex flex-col gap-1">
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-800">
                Change Password
            </h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">
                Update your account security
            </p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm relative overflow-hidden">
        <div class="absolute right-0 top-0 w-32 h-32 bg-slate-50 rounded-full -mr-16 -mt-16 opacity-50"></div>
        
        <form action="{{ route('update-password') }}" method="POST" class="space-y-6 relative">
            @csrf
            
            <!-- Current Password -->
            <div class="space-y-2">
                <label for="current_password" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Current Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary-500 transition-colors">
                        <i data-lucide="lock" class="w-5 h-5"></i>
                    </div>
                    <input type="password" name="current_password" id="current_password" 
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border-transparent rounded-[1.5rem] focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-bold text-slate-700"
                        placeholder="••••••••" required>
                </div>
                @error('current_password')
                    <p class="text-red-500 text-[10px] font-bold uppercase tracking-wider ml-1 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <hr class="border-slate-50">

            <!-- New Password -->
            <div class="space-y-2">
                <label for="new_password" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">New Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary-500 transition-colors">
                        <i data-lucide="key-round" class="w-5 h-5"></i>
                    </div>
                    <input type="password" name="new_password" id="new_password" 
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border-transparent rounded-[1.5rem] focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-bold text-slate-700"
                        placeholder="Min. 8 characters" required>
                </div>
                @error('new_password')
                    <p class="text-red-500 text-[10px] font-bold uppercase tracking-wider ml-1 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <label for="new_password_confirmation" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Confirm New Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary-500 transition-colors">
                        <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                    </div>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border-transparent rounded-[1.5rem] focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-bold text-slate-700"
                        placeholder="Repeat your password" required>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" class="w-full bg-primary-600 text-white font-black py-5 rounded-[1.5rem] shadow-xl shadow-primary-600/20 hover:bg-primary-700 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3 active:scale-95">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                    <span class="uppercase tracking-widest text-sm">Save New Password</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Security Tips -->
    <div class="bg-amber-50 rounded-[2rem] p-6 border border-amber-100 flex gap-4">
        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-amber-500 shadow-sm flex-shrink-0">
            <i data-lucide="shield-alert" class="w-6 h-6"></i>
        </div>
        <div>
            <h4 class="text-sm font-bold text-amber-800 tracking-tight">Security Tip</h4>
            <p class="text-xs text-amber-700 font-medium leading-relaxed mt-1">Use a combination of uppercase letters, numbers, and special symbols to make your password stronger and harder to guess.</p>
        </div>
    </div>
</div>
@endsection
