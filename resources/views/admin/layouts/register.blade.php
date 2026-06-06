<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Akun – Ash-Shiddiqin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/desain.css') }}" rel="stylesheet">
</head>

<body class="auth-page">

<div class="auth-card">

    <div style="margin-bottom:1.2rem;">
        <i class="fas fa-mosque" style="font-size:2.5rem;color:var(--hijau-tua);"></i>
        <p style="font-family:'Amiri',serif;font-size:1.1rem;color:var(--hijau-tua);font-weight:700;margin-top:.3rem;">
            Ash-Shiddiqin
        </p>
    </div>

    <h2>Daftar Akun</h2>

    @if($errors->any())
        <div class="error">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('error'))
        <div class="error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('register.proses') }}" method="POST">
        @csrf
        <input type="text"     name="name"                  placeholder="Nama Lengkap" required
               value="{{ old('name') }}">
        <input type="text"     name="username"               placeholder="Username"     required
               value="{{ old('username') }}">
        <input type="password" name="password"               placeholder="Password (min. 6 karakter)" required>
        <input type="password" name="password_confirmation"  placeholder="Konfirmasi Password" required>

        <button type="submit">
            <i class="fas fa-user-plus"></i> Daftar
        </button>
    </form>

    <div class="auth-links">
        Sudah punya akun? <a href="{{ route('login') }}">Login</a>
    </div>

</div>

</body>
</html>
