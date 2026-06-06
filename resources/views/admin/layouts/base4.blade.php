<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Kami - Pondok Pesantren Ash-Shiddiqin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
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
            <ul class="nav-links" id="navLinks">
                <li><a href="{{ route('dashboard') }}">Beranda</a></li>
                <li><a href="{{ route('berita') }}">Berita</a></li>
                <li><a href="{{ route('galeri') }}">Galeri</a></li>
                <li><a href="{{ route('tentang') }}">Tentang Kami</a></li>
                <li><a href="{{ route('pesan') }}" class="active">Kontak</a></li>
            </ul>
            <div class="hamburger" id="hamburger">
                <span></span><span></span><span></span>
            </div>
        </nav>
    </header>

    <!-- Page Hero -->
    <section class="page-hero">
        <div class="container">
            <h1>Hubungi Kami</h1>
            <p>Kami siap menjawab semua pertanyaan dan memberikan informasi yang Anda butuhkan</p>
        </div>
    </section>

    <!-- Banner Pendaftaran -->
    <section style="background: linear-gradient(135deg, #7a5800 0%, #b8860a 35%, #8b2d2d 70%, #5a1010 100%); padding: 6rem 0; position: relative; overflow: hidden;">

        {{-- Dekorasi background --}}
        <div style="position:absolute;top:-80px;right:-80px;width:320px;height:320px;border-radius:50%;background:rgba(255,200,50,0.08);pointer-events:none;"></div>
        <div style="position:absolute;bottom:-60px;left:-60px;width:240px;height:240px;border-radius:50%;background:rgba(107,26,26,0.20);pointer-events:none;"></div>
        <div style="position:absolute;top:50%;left:5%;width:80px;height:80px;border-radius:50%;background:rgba(255,200,50,0.06);pointer-events:none;"></div>

        <div class="container" style="position:relative;z-index:1;">

            {{-- Badge --}}
            <div style="text-align:center; margin-bottom:2rem;">
                <span style="display:inline-flex;align-items:center;gap:.5rem;background:rgba(212,160,23,0.18);border:1px solid rgba(212,160,23,0.4);color:var(--gold);padding:.45rem 1.4rem;border-radius:999px;font-size:.88rem;font-weight:700;letter-spacing:.06em;">
                    <i class="fas fa-star"></i> PENERIMAAN SANTRI BARU
                </span>
            </div>

            {{-- Judul --}}
            <h2 style="text-align:center;color:var(--putih);font-size:clamp(2rem,4vw,3rem);margin-bottom:1rem;line-height:1.25;">
                Daftarkan Putra-Putri Anda<br>
                <span style="color:var(--gold);">Tahun Ajaran 2026/2027</span>
            </h2>
            <p style="text-align:center;color:rgba(255,255,255,0.75);font-size:1.1rem;max-width:560px;margin:0 auto 3.5rem;">
                Bergabunglah bersama keluarga besar Ash-Shiddiqin dan wujudkan generasi yang kokoh imannya
            </p>

            {{-- 3 Info Card --}}
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.5rem;max-width:800px;margin:0 auto 3.5rem;">
                <div style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);border-radius:var(--radius-md);padding:1.75rem 1.5rem;text-align:center;backdrop-filter:blur(6px);transition:transform .3s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="width:52px;height:52px;border-radius:50%;background:rgba(212,160,23,0.2);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                        <i class="fas fa-user-graduate" style="color:var(--gold);font-size:1.4rem;"></i>
                    </div>
                    <div style="color:var(--putih);font-weight:700;font-size:1rem;margin-bottom:.35rem;">Putra & Putri</div>
                    <div style="color:rgba(255,255,255,0.6);font-size:.88rem;">Usia 13 – 15 Tahun</div>
                </div>
                <div style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);border-radius:var(--radius-md);padding:1.75rem 1.5rem;text-align:center;backdrop-filter:blur(6px);transition:transform .3s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="width:52px;height:52px;border-radius:50%;background:rgba(212,160,23,0.2);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                        <i class="fas fa-calendar-check" style="color:var(--gold);font-size:1.4rem;"></i>
                    </div>
                    <div style="color:var(--putih);font-weight:700;font-size:1rem;margin-bottom:.35rem;">Gelombang 1</div>
                    <div style="color:rgba(255,255,255,0.6);font-size:.88rem;">Pendaftaran Dibuka</div>
                </div>
                <div style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);border-radius:var(--radius-md);padding:1.75rem 1.5rem;text-align:center;backdrop-filter:blur(6px);transition:transform .3s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="width:52px;height:52px;border-radius:50%;background:rgba(212,160,23,0.2);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                        <i class="fas fa-book-quran" style="color:var(--gold);font-size:1.4rem;"></i>
                    </div>
                    <div style="color:var(--putih);font-weight:700;font-size:1rem;margin-bottom:.35rem;">Program Tahfidz</div>
                    <div style="color:rgba(255,255,255,0.6);font-size:.88rem;">Target 6 Juz / Tahun</div>
                </div>
            </div>

            {{-- Tombol CTA --}}
            <div style="text-align:center;display:flex;gap:1.25rem;justify-content:center;flex-wrap:wrap;">
                <a href="https://ash-shiddiqin.org/" target="_blank"
                   style="display:inline-flex;align-items:center;gap:.6rem;background:var(--gold);color:var(--hijau-tua);font-weight:800;font-size:1.05rem;padding:1rem 2.5rem;border-radius:999px;text-decoration:none;box-shadow:0 8px 24px rgba(212,160,23,0.4);transition:all .3s;letter-spacing:.02em;"
                   onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 14px 32px rgba(212,160,23,0.5)'"
                   onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 24px rgba(212,160,23,0.4)'">
                    <i class="fas fa-file-alt"></i> Daftar Sekarang
                </a>
                <a href="#form-pesan"
                   style="display:inline-flex;align-items:center;gap:.6rem;background:transparent;color:var(--putih);font-weight:700;font-size:1.05rem;padding:1rem 2.5rem;border-radius:999px;text-decoration:none;border:2px solid rgba(255,255,255,0.45);transition:all .3s;"
                   onmouseover="this.style.borderColor='rgba(255,255,255,0.9)';this.style.background='rgba(255,255,255,0.08)'"
                   onmouseout="this.style.borderColor='rgba(255,255,255,0.45)';this.style.background='transparent'">
                    <i class="fas fa-envelope"></i> Tanya Dulu
                </a>
            </div>

        </div>
    </section>

    <!-- Contact Info -->
    <section class="container">
        <h2 class="section-title">Informasi Kontak</h2>
        <div class="contact-grid">
            <div class="contact-card">
                <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                <h3>Alamat</h3>
                <div class="contact-info">
                    Jl. Irigasi, Lrg. Mandi Angin (Pakjo Ujung)<br>
                    Kel. Srijaya, Kec. Alang-Alang Lebar<br>
                    Kota Palembang, Sumatera Selatan 30138
                </div>
                <a href="https://maps.app.goo.gl/B6mMc9fghSbbQ2gH6" target="_blank" class="contact-btn">
                    <i class="fas fa-map"></i> Buka Peta
                </a>
            </div>
            <div class="contact-card">
                <div class="contact-icon"><i class="fas fa-phone"></i></div>
                <h3>Telepon & WhatsApp</h3>
                <div class="contact-info">
                    <strong>+62 821-7609-7976</strong>
                </div>
                <a href="https://wa.me/6282176097976?text=Assalamu%27alaikum%20wr.wb%2C%0ASaya%20ingin%20bertanya%20tentang%20Pondok%20Pesantren%20Ash-Shiddiqin"
                   class="contact-btn" target="_blank">
                    <i class="fab fa-whatsapp"></i> Chat WhatsApp
                </a>
            </div>
        </div>
    </section>

    <!-- Social Media -->
    <section class="social-section">
        <div class="container">
            <h2 class="section-title">Ikuti Kami</h2>
            <div class="social-grid">
                <div class="social-card">
                    <a href="https://www.tiktok.com/@ponpes.ashshiddiqin" class="social-icon social-tiktok" target="_blank">
                        <i class="fab fa-tiktok"></i>
                    </a>
                    <h3>TikTok</h3>
                    <p>Kegiatan santri dan tips pendidikan Islam</p>
                </div>
                <div class="social-card">
                    <a href="https://www.instagram.com/ponpes_ashshiddiqin" class="social-icon social-instagram" target="_blank">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <h3>Instagram</h3>
                    <p>Momen indah kegiatan pondok pesantren</p>
                </div>
                <div class="social-card">
                    <a href="https://www.facebook.com/share/18YDPFC538/" class="social-icon social-facebook" target="_blank">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <h3>Facebook</h3>
                    <p>Komunitas orang tua dan alumni</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ✅ Form Pesan — pakai POST Laravel, bukan JavaScript only -->
    <section class="container" id="form-pesan">
        <div class="form-pesan-container">
            <h2 class="section-title">
                <i class="fas fa-envelope-open-text"></i>
                Pesan & Pertanyaan
            </h2>
            <p style="text-align: center; color: #666; font-size: 1.1rem; margin-bottom: 3rem;">
                Punya pertanyaan tentang pendaftaran, fasilitas, atau kegiatan pondok?
                Tulis pesan Anda di sini!
            </p>

            {{-- Notifikasi sukses --}}
            @if(session('success'))
            <div style="background:#fdf0d5;color:#6b1a1a;border:1px solid #d4a017;padding:1rem 1.5rem;border-radius:10px;margin-bottom:1.5rem;text-align:center;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif

            {{-- Notifikasi error validasi --}}
            @if($errors->any())
            <div style="background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;padding:1rem 1.5rem;border-radius:10px;margin-bottom:1.5rem;">
                <i class="fas fa-exclamation-circle"></i>
                <ul style="margin:.5rem 0 0 1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="pesan-grid">
                <div class="kontak-form-wrapper">
                    <div class="card" style="padding: 2.5rem; border-radius: 25px;">
                        <h3 style="font-family: 'Amiri', serif; color: var(--hijau-tua); margin-bottom: 1.5rem; text-align: center;">
                            <i class="fas fa-envelope-open-text"></i> Kirim Pesan
                        </h3>

                        {{-- ✅ action dan method POST sudah benar --}}
                        <form action="{{ route('pesan.kirim') }}" method="POST" class="kontak-form">
                            @csrf

                            <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                                <div class="form-group">
                                    <label><i class="fas fa-user"></i> Nama Lengkap</label>
                                    <input type="text" name="nama"
                                           placeholder="Masukkan nama lengkap Anda"
                                           value="{{ old('nama') }}" required>
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-mobile-alt"></i> Email / No. WhatsApp</label>
                                    <input type="text" name="kontak"
                                           placeholder="contoh@email.com / 081234567890"
                                           value="{{ old('kontak') }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-comment-dots"></i> Pesan / Pertanyaan</label>
                                <textarea name="pesan" rows="5"
                                          placeholder="Tulis pesan atau pertanyaan Anda di sini..."
                                          required>{{ old('pesan') }}</textarea>
                            </div>

                            <div style="text-align: center;">
                                <button type="submit" class="btn btn-primary" style="padding: 1.2rem 3rem; font-size: 1.1rem;">
                                    <i class="fas fa-paper-plane"></i> Kirim Pesan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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

    <script src = "{{ asset('assets/js/pesan.js') }}"></script>
</body>
</html>