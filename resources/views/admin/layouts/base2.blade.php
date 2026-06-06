<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Foto - Pondok Pesantren Ash-Shiddiqin</title>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <link href="{{ asset('assets/css/desain.css') }}" rel="stylesheet">
    <style>
        /* Galeri: responsive grid */
        @media (max-width: 900px) {
            .gallery-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 540px) {
            .gallery-grid { grid-template-columns: 1fr; }
            .gallery-item { height: 260px; }
        }
    </style>
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
                <li><a href="{{ route('galeri') }}" class="active">Galeri</a></li>
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
            <h1>Galeri Kegiatan</h1>
            <p>Momen-momen indah kegiatan santri dan ustadz Pondok Pesantren Ash-Shiddiqin</p>
        </div>
    </section>

    <!-- Main Gallery -->
    <section class="container" style="padding: 4rem 2rem 6rem;">
        <div class="section-title fade-in">
            <h2>Galeri Foto Kegiatan</h2>
        </div>

        <!-- Category Tabs -->
        <div class="category-tabs">
            <button class="category-btn active" data-category="all">Semua Kegiatan</button>
            <button class="category-btn" data-category="pengajian">Pengajian</button>
            <button class="category-btn" data-category="tahfidz">Tahfidz Quran</button>
            <button class="category-btn" data-category="wisuda">Wisuda</button>
            <button class="category-btn" data-category="lainnya">Lainnya</button>
        </div>

        <!-- ✅ Gallery Grid — dari DATABASE, bukan localStorage -->
        <div class="gallery-grid" id="galleryContainer">
            @forelse($galeri as $item)
            <a href="{{ asset('storage/' . $item->src) }}"
               class="gallery-item fade-in"
               data-lightbox="galeri-ponpes"
               data-title="{{ $item->judul }}"
               data-category="{{ $item->kategori }}">
                <img src="{{ asset('storage/' . $item->src) }}"
                     alt="{{ $item->judul }}"
                     loading="lazy">
                <div class="gallery-overlay">
                    <h4>{{ $item->judul }}</h4>
                    <p>{{ $item->timestamp ?? $item->created_at->format('d M Y') }}</p>
                </div>
            </a>
            @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 4rem; color: #666;">
                <i class="fas fa-images" style="font-size: 4rem; opacity: 0.5; margin-bottom: 3rem; display:block;"></i>
                <h3>Galeri masih kosong</h3>
                <p>Upload foto kegiatan pertama melalui admin panel!</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="social-links">
                <a href="https://www.instagram.com/ponpes_ashshiddiqin" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://www.tiktok.com/@ponpes.ashshiddiqin" target="_blank"><i class="fab fa-tiktok"></i></a>
                <a href="https://www.facebook.com/share/18YDPFC538/" target="_blank"><i class="fab fa-facebook"></i></a>
            </div>
            <p>&copy; {{ date('Y') }} Pondok Pesantren Ash-Shiddiqin Palembang. All Rights Reserved.</p>
            <p style="opacity: 0.8; font-size: 0.9rem;">Membentuk Generasi Ash-Shiddiqin | Jl. Irigasi, Lr. Mandi Angin, Pakjo Ujung, Palembang</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <script src="{{ asset('assets/js/galeri.js') }}"></script>
</body>
</html>