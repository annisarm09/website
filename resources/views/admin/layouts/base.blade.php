<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita & Kegiatan - Pondok Pesantren Ash-Shiddiqin</title>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('assets/css/desain.css') }}" rel="stylesheet">
</head>
<body>

    <!-- Header -->
    <header>
        <nav>
            <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
                <a href="{{ route('dashboard') }}" class="logo" style="margin-right:0;">
                    <img src="{{ asset('assets/images/logo_ponpes-removebg-preview.png') }}" class="logo-img" alt="Logo Ash-Shiddiqin">
                    <span class="logo-text">
                        <span class="logo-name">Pesantren Modern Ash-Shiddiqin</span>
                        <span class="logo-sub">Jenjang SMP &amp; SMA Islam · Palembang</span>
                    </span>
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

    <!-- Hero Section -->
    <section class="hero" style="min-height: 60vh; padding-top: 120px;">
        <div class="hero-content fade-in">
            <h1>Berita & Kegiatan Terbaru</h1>
            <p>Update informasi terkini seputar kegiatan Pondok Pesantren Ash-Shiddiqin</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="container">
        <div class="section-title fade-in">
            <h2>Semua Berita</h2>
        </div>

        <!-- Tombol Filter -->
        <div style="display: flex; gap: 1rem; margin-bottom: 3rem; flex-wrap: wrap; justify-content: center;">
            <button class="btn btn-primary active" onclick="filterNews('all', this)">Semua</button>
            <button class="btn btn-secondary" onclick="filterNews('kegiatan', this)">Kegiatan</button>
            <button class="btn btn-secondary" onclick="filterNews('pengumuman', this)">Pengumuman</button>
            <button class="btn btn-secondary" onclick="filterNews('prestasi', this)">Prestasi</button>
        </div>

        {{-- Konten dari child view (index.blade.php) akan dirender di sini --}}
        <div id="newsContainer" class="news-list">
            @yield('content')
        </div>

        {{-- Pagination --}}
        @if(isset($berita) && method_exists($berita, 'links'))
        <div style="text-align: center; margin: 2rem 0;">
            {{ $berita->links() }}
        </div>
        @endif
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="social-links">
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-tiktok"></i></a>
                <a href="#"><i class="fab fa-facebook"></i></a>
            </div>
            <p>&copy; {{ date('Y') }} Pondok Pesantren Ash-Shiddiqin Palembang. All Rights Reserved.</p>
            <p style="opacity: 0.8; font-size: 0.9rem;">Membentuk Generasi Ash-Shiddiqin | Jl. Irigasi, Lr. Mandi Angin, Pakjo Ujung, Palembang</p>
        </div>
    </footer>

    <script src="{{ asset('assets/js/berita.js') }}"></script>
</body>
</html>