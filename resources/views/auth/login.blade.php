@extends('layouts.auth')

@section('title', 'Login - Sistem Pendukung Keputusan Tahfidz')

@section('content')
<div class="auth-logo">
    <h1>🕌 SPK Tahfidz</h1>
    <p>Sistem Pendukung Keputusan Tahfidz Al-Qur'an</p>
</div>

@if ($errors->any())
    <div style="background-color: #fed7d7; color: #c53030; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
        @foreach ($errors->all() as $error)
            <p style="margin: 0;">{{ $error }}</p>
        @endforeach
    </div>
@endif

@if (session('status'))
    <div style="background-color: #c6f6d5; color: #2f855a; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
        <p style="margin: 0;">{{ session('status') }}</p>
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="nama@email.com">
        @error('email')
            <span class="error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password">
        @error('password')
            <span class="error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group" style="margin-bottom: 30px;">
        <label>
            <input type="checkbox" name="remember" style="width: auto; margin-right: 8px;" {{ old('remember') ? 'checked' : '' }}>
            Ingat saya
        </label>
    </div>

    <button type="submit" class="btn">
        Masuk
    </button>
</form>

<div style="background-color: #e6fffa; border: 1px solid #38b2ac; border-radius: 5px; padding: 15px; margin-top: 20px; margin-bottom: 20px;">
    <h4 style="margin: 0 0 10px 0; color: #2d3748; font-size: 14px;">🔑 Akun Demo:</h4>
    <div style="font-size: 12px; color: #4a5568;">
        <p style="margin: 5px 0;"><strong>Admin:</strong> admin@tahfidz.com / password123</p>
        <p style="margin: 5px 0;"><strong>Juri:</strong> juri@tahfidz.com / password123</p>
        <p style="margin: 5px 0;"><strong>Peserta:</strong> peserta@tahfidz.com / password123</p>
    </div>
</div>

<div class="auth-footer">
    <p style="color: #718096; font-size: 14px;">
        🎯 <strong>Gunakan akun demo di atas untuk login</strong><br>
        Sistem ini menggunakan autentikasi berbasis sesi (demo mode)
    </p>
</div>
@endsection