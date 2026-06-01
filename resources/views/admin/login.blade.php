@extends('layouts.app')

@section('title', 'Admin Login | LinguaBridge')

@section('content')
<div class="max-w-md mx-auto py-8">
    <div class="bg-brand-card rounded-2xl border border-brand-border shadow-sm overflow-hidden border-t-4 border-brand-gold">
        
        <!-- Header banner -->
        <div class="bg-brand-navy px-6 py-6 text-center text-white relative">
            <div class="absolute inset-0 bg-gradient-to-r from-brand-gold/15 to-transparent opacity-40"></div>
            <h2 class="text-2xl font-bold relative z-10">LinguaBridge Admin</h2>
            <p class="mt-1 text-slate-400 text-sm relative z-10">Sign in to configure portal settings</p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.login.submit') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-semibold text-brand-text-dark">Email Address</label>
                <div class="mt-1">
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                        class="block w-full rounded-lg bg-brand-card border border-brand-border px-3 py-2 text-brand-text-dark shadow-sm focus:border-brand-gold focus:outline-none focus:ring-1 focus:ring-brand-gold sm:text-sm placeholder-slate-400 dark:placeholder-slate-500"
                        placeholder="admin@linguabridge.com">
                </div>
                @error('email')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-brand-text-dark">Password</label>
                <div class="mt-1">
                    <input type="password" name="password" id="password" required
                        class="block w-full rounded-lg bg-brand-card border border-brand-border px-3 py-2 text-brand-text-dark shadow-sm focus:border-brand-gold focus:outline-none focus:ring-1 focus:ring-brand-gold sm:text-sm placeholder-slate-400 dark:placeholder-slate-500"
                        placeholder="••••••••">
                </div>
                @error('password')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember check -->
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="rounded border-brand-border text-brand-gold focus:ring-brand-gold bg-brand-card">
                    <span class="text-brand-text-muted font-medium">Remember me</span>
                </label>
            </div>

            <!-- Submit -->
            <div class="pt-2 border-t border-brand-border">
                <button type="submit" class="w-full flex items-center justify-center bg-brand-gold hover:bg-brand-gold-dark text-brand-navy-dark font-bold py-3.5 px-4 rounded-xl shadow-sm transition-all focus:outline-none cursor-pointer">
                    Sign In to Dashboard
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
