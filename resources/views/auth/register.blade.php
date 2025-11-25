@extends('layouts.auth')

@section('title', 'Register - Sistem Pendukung Keputusan Tahfidz')

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

<form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="form-group">
        <label for="name">Nama Lengkap</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Masukkan nama lengkap">
        @error('name')
            <span class="error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="nama@email.com">
        @error('email')
            <span class="error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
        @error('password')
            <span class="error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password_confirmation">Konfirmasi Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password">
        @error('password_confirmation')
            <span class="error">{{ $message }}</span>
        @enderror
    </div>

    <button type="submit" class="btn">
        Daftar
    </button>
</form>

<div class="auth-footer">
    <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
</div>
@endsection