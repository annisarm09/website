<!DOCTYPE html>
<html lang="id">
<head>
    <title>Reset Password – Ash-Shiddiqin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/desain.css') }}" rel="stylesheet">
</head>

<body class="auth-page">

<div class="auth-card">

    <div style="margin-bottom:1.2rem;">
        <i class="fas fa-key" style="font-size:2.5rem;color:var(--hijau-tua);"></i>
        <p style="font-family:'Amiri',serif;font-size:1.1rem;color:var(--hijau-tua);font-weight:700;margin-top:.3rem;">
            Buat Password Baru
        </p>
    </div>

    <h2>Reset Password</h2>
    <p style="font-size:.88rem;color:var(--abu-gelap);margin-bottom:1.2rem;">
        Halo, <strong>{{ $username }}</strong>. Silakan buat password baru Anda.
    </p>

    @if($errors->any())
        <div class="error">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('password.reset.proses') }}" method="POST">
        @csrf
        {{-- Token tersimpan hidden, tidak perlu diketik user --}}
        <input type="hidden" name="token"    value="{{ $token }}">
        <input type="hidden" name="username" value="{{ $username }}">

        <input type="password" name="password"
               placeholder="Password Baru (min. 6 karakter)" required>
        <input type="password" name="password_confirmation"
               placeholder="Konfirmasi Password Baru" required>

        <button type="submit">
            <i class="fas fa-save"></i> Simpan Password Baru
        </button>
    </form>

    <div class="auth-links">
        <a href="{{ route('login') }}"><i class="fas fa-arrow-left"></i> Kembali ke Login</a>
    </div>

</div>

</body>
</html>
