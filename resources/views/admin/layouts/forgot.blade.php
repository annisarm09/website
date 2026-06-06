<!DOCTYPE html>
<html lang="id">
<head>
    <title>Lupa Password – Ash-Shiddiqin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/desain.css') }}" rel="stylesheet">
</head>

<body class="auth-page">

<div class="auth-card">

    <div style="margin-bottom:1.2rem;">
        <i class="fas fa-lock" style="font-size:2.5rem;color:var(--hijau-tua);"></i>
        <p style="font-family:'Amiri',serif;font-size:1.1rem;color:var(--hijau-tua);font-weight:700;margin-top:.3rem;">
            Reset Password
        </p>
    </div>

    <h2>Lupa Password?</h2>
    <p style="font-size:.88rem;color:var(--abu-gelap);margin-bottom:1.2rem;line-height:1.5;">
        Masukkan username Anda. Kami akan memandu Anda untuk mengatur password baru.
    </p>

    @if(session('status'))
        <div class="success">
            <i class="fas fa-check-circle"></i> {{ session('status') }}
        </div>
    @endif

    @if(session('error'))
        <div class="error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="error">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
    @endif

    {{-- Form kirim username --}}
    <form action="{{ route('password.request.proses') }}" method="POST">
        @csrf
        <input type="text" name="username" placeholder="Masukkan Username" required
               value="{{ old('username') }}">
        <button type="submit">
            <i class="fas fa-paper-plane"></i> Lanjutkan Reset Password
        </button>
    </form>

    <div class="auth-links">
        <a href="{{ route('login') }}"><i class="fas fa-arrow-left"></i> Kembali ke Login</a>
    </div>

</div>

</body>
</html>
