<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita – Pondok Pesantren Ash-Shiddiqin</title>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('assets/css/desain.css') }}" rel="stylesheet">
    {{-- Bootstrap untuk card, grid, form di show/edit/create --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Pastikan navbar tidak menindih konten */
        body { padding-top: 0; }
        .content-wrapper { padding-top: 80px; }

        /* Override Bootstrap agar sesuai tema */
        .btn-success  { background-color: var(--hijau-tua, #1a5c2e);  border-color: var(--hijau-tua, #1a5c2e); }
        .btn-success:hover { background-color: var(--hijau-muda, #2e7d4f); border-color: var(--hijau-muda, #2e7d4f); }
        .card { border: none; box-shadow: 0 4px 20px rgba(0,0,0,.08); border-radius: 16px; }
        .card-body { padding: 2.5rem; }
        h1, h2 { font-family: 'Amiri', serif; }
    </style>
</head>
<body>

    {{-- ══ HEADER / NAVBAR ══ --}}
    <header>
        <nav>
            <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
                <a href="{{ route('dashboard') }}" class="logo" style="margin-right:0;">
                    <img src="{{ asset('assets/images/logo_ponpes-removebg-preview.png') }}" style="height:38px;margin-right:6px;" alt="Logo">
                    <span style="font-family:'Amiri',serif;font-size:1rem;font-weight:700;">Pesantren Modern Ash-Shiddiqin</span>
                </a>
                <img src="{{ asset('assets/images/logo_akre.png') }}" alt="Akreditasi A" title="Akreditasi A – SMP Islam Ash-Shiddiqin" style="width:60px;height:60px;object-fit:contain;flex-shrink:0;filter:drop-shadow(0 2px 6px rgba(0,0,0,.35));">
            </div>
            <ul class="nav-links">
                <li><a href="{{ route('dashboard') }}">Beranda</a></li>
                <li><a href="{{ route('berita') }}">Berita</a></li>
                <li><a href="{{ route('galeri') }}">Galeri</a></li>
                <li><a href="{{ route('tentang') }}">Tentang Kami</a></li>
                <li><a href="{{ route('pesan') }}">Kontak</a></li>

            </ul>
            <div class="hamburger" id="hamburger">
                <span></span><span></span><span></span>
            </div>
        </nav>
    </header>

    {{-- ══ KONTEN HALAMAN ══ --}}
    <div class="content-wrapper">
        @yield('content')
    </div>

    {{-- ══ FOOTER ══ --}}
    <footer>
        <div class="container">
            <div class="social-links">
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-tiktok"></i></a>
                <a href="#"><i class="fab fa-facebook"></i></a>
            </div>
            <p>&copy; {{ date('Y') }} Pondok Pesantren Ash-Shiddiqin Palembang. All Rights Reserved.</p>
            <p style="opacity:.8; font-size:.9rem;">Membentuk Generasi Ash-Shiddiqin | Jl. Irigasi, Lr. Mandi Angin, Pakjo Ujung, Palembang</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Hamburger menu
        const hamburger = document.getElementById('hamburger');
        const navLinks  = document.querySelector('.nav-links');
        if (hamburger) {
            hamburger.addEventListener('click', () => navLinks.classList.toggle('active'));
        }
    </script>
</body>
</html>