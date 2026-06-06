<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login Admin – Ash-Shiddiqin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/desain.css') }}" rel="stylesheet">
</head>

{{-- ✅ class="auth-page" pada body agar background hijau muncul --}}
<body class="auth-page">

<div class="auth-card">

    {{-- Logo / ikon --}}
    <div style="margin-bottom:1.2rem;">
        <i class="fas fa-mosque" style="font-size:2.5rem;color:var(--hijau-tua);"></i>
        <p style="font-family:'Amiri',serif;font-size:1.1rem;color:var(--hijau-tua);font-weight:700;margin-top:.3rem;">
            Ash-Shiddiqin
        </p>
    </div>

    <h2>Login Admin</h2>

    {{-- Pesan error --}}
    @if(session('error'))
        <div class="error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Pesan sukses (misal setelah register) --}}
    @if(session('success'))
        <div class="success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('login.proses') }}" method="POST">
        @csrf
        <input type="text"     name="username" placeholder="Username" required
               value="{{ old('username') }}">
        <input type="password" name="password" placeholder="Password"  required>
        <button type="submit">
            <i class="fas fa-sign-in-alt"></i> Login
        </button>
    </form>

    <div class="auth-links">
        <a href="{{ route('register') }}">Daftar Akun</a>
        &nbsp;|&nbsp;
        <a href="{{ route('password.request') }}">Lupa Password?</a>
    </div>

</div>

</body>
</html>
