@extends('admin.layouts.base3')
@section('content')

{{-- ═══════════════════════════════════════
     HERO
═══════════════════════════════════════ --}}
<section class="tentang-hero">
    <div class="container">
        <h1>Tentang Kami</h1>
        <p>Mengenal lebih dekat Pondok Pesantren Ash-Shiddiqin Palembang</p>
    </div>
</section>

{{-- ═══════════════════════════════════════
     SEJARAH
═══════════════════════════════════════ --}}
<section class="tentang-sejarah">
    <div class="container">
        <div class="tentang-sejarah-inner">
            <div class="tentang-sejarah-img">
                <div class="sejarah-ornament"></div>
                <img src="{{ asset('assets/images/ponpes2.jpeg') }}"
                     alt="Pondok Pesantren Ash-Shiddiqin" class="sejarah-logo">
            </div>
            <div class="tentang-sejarah-text">
                <span class="section-label">Sejarah Pondok</span>
                <h2>Perjalanan Panjang Menuju Keunggulan</h2>
                <p>{{ $sejarah->deskripsi ?? 'Data sejarah belum tersedia.' }}</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════
     VISI & MISI
═══════════════════════════════════════ --}}
<section class="tentang-visimisi">
    <div class="container">
        <div class="visimisi-grid">
            <div class="visimisi-card visimisi-visi">
                <div class="vm-icon"><i class="fas fa-eye"></i></div>
                <h3>Visi</h3>
                <p>{{ $visi->deskripsi ?? 'Visi belum diatur.' }}</p>
            </div>
            <div class="visimisi-card visimisi-misi">
                <div class="vm-icon"><i class="fas fa-bullseye"></i></div>
                <h3>Misi</h3>
                <ul class="misi-list">
                    @if($misi && $misi->deskripsi)
                        @foreach(explode("\n", trim($misi->deskripsi)) as $baris)
                            @if(trim($baris) !== '')
                                <li><i class="fas fa-check-circle"></i> {{ trim($baris) }}</li>
                            @endif
                        @endforeach
                    @else
                        <li>Misi belum diatur.</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════
     PROGRAM PENDIDIKAN  (4 kartu)
═══════════════════════════════════════ --}}
<section class="tentang-program">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Pendidikan Formal &amp; Non-Formal</span>
            <h2>Program Pendidikan</h2>
            <p>Empat program unggulan yang membentuk generasi berilmu, berakhlak, dan berprestasi</p>
        </div>

        @if($programs->count())
        <div class="program-grid">
            @foreach($programs as $idx => $prog)
            <div class="program-card">
                <div class="program-card-top">
                    @if(!empty($prog['badge']))
                        <span class="program-badge">{{ $prog['badge'] }}</span>
                    @endif
                    <div class="program-icon">
                        <i class="{{ $prog['icon'] ?? 'fas fa-graduation-cap' }}"></i>
                    </div>
                    <h3>{{ $prog['judul'] ?? 'Program ' . ($idx + 1) }}</h3>
                    <p>{{ $prog['deskripsi'] ?? '' }}</p>
                </div>
                @if(!empty($prog['poin']))
                <ul class="program-poin">
                    @foreach(array_filter(array_map('trim', explode("\n", $prog['poin']))) as $poin)
                        @if($poin !== '')
                            <li><i class="fas fa-arrow-right"></i> {{ $poin }}</li>
                        @endif
                    @endforeach
                </ul>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="tentang-empty">
            <i class="fas fa-graduation-cap"></i>
            <p>Program pendidikan belum diatur oleh admin.</p>
        </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════
     KEUNGGULAN  (grid dinamis)
═══════════════════════════════════════ --}}
<section class="tentang-keunggulan">
    <div class="container">
        <div class="section-header section-header-dark">
            <span class="section-label section-label-gold">Mengapa Ash-Shiddiqin?</span>
            <h2>Keunggulan Pendidikan Kami</h2>
            <p>Keistimewaan yang menjadikan Ash-Shiddiqin pilihan terbaik untuk putra-putri Anda</p>
        </div>

        @if($keunggulan->count())
        <div class="keunggulan-grid">
            @foreach($keunggulan as $k)
            <div class="keunggulan-card">
                <span class="keunggulan-no">{{ $k['no'] ?? '01' }}</span>
                <div class="keunggulan-icon">
                    <i class="{{ $k['icon'] ?? 'fas fa-star-and-crescent' }}"></i>
                </div>
                <h4>{{ $k['judul'] ?? 'Keunggulan' }}</h4>
            </div>
            @endforeach
        </div>
        @else
        <div class="tentang-empty">
            <i class="fas fa-trophy"></i>
            <p>Keunggulan pendidikan belum diatur oleh admin.</p>
        </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════
     CHARACTER BUILDING (foto)
═══════════════════════════════════════ --}}
@if(isset($charBuildFotos) && $charBuildFotos->count() > 0)
<section class="char-build-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Proses Pembentukan Karakter</span>
            <h2>Character Building</h2>
            <p>Metode pendidikan karakter berbasis Al-Qur'an dan Sunnah yang diterapkan di Ash-Shiddiqin</p>
        </div>

        {{-- Grid foto --}}
        <div class="char-build-grid" id="charBuildGallery">
            @foreach($charBuildFotos as $i => $cb)
            <div class="char-build-item" onclick="openLightbox('{{ asset('storage/'.$cb['path']) }}','{{ addslashes($cb['judul'] ?? '') }}')">
                <img src="{{ asset('storage/' . $cb['path']) }}"
                     alt="{{ $cb['judul'] ?? 'Character Building #' . ($i+1) }}"
                     loading="lazy">
                @if(!empty($cb['judul']))
                <div class="char-build-caption">
                    <span>{{ $cb['judul'] }}</span>
                </div>
                @endif
                <div class="char-build-overlay">
                    <i class="fas fa-expand-alt"></i>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Lightbox --}}
<div id="charLightbox" class="char-lightbox" onclick="closeLightbox()">
    <button class="char-lightbox-close" onclick="closeLightbox()">
        <i class="fas fa-times"></i>
    </button>
    <div class="char-lightbox-inner" onclick="event.stopPropagation()">
        <img id="charLightboxImg" src="" alt="">
        <div id="charLightboxCaption" class="char-lightbox-caption"></div>
    </div>
</div>

<style>
/* ── Section wrapper ── */
.char-build-section {
    background: #fff;
    padding: 5rem 0;
}

/* ── Grid responsif: 1–4 kolom tergantung jumlah & layar ── */
.char-build-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 1.25rem;
    margin-top: 2rem;
}

/* Kalau hanya 1 foto → tampilkan besar di tengah */
.char-build-grid:has(.char-build-item:only-child) {
    grid-template-columns: minmax(0, 640px);
    justify-content: center;
}

/* ── Item kartu ── */
.char-build-item {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    cursor: pointer;
    box-shadow: 0 4px 16px rgba(107,26,26,.12);
    border: 2.5px solid rgba(212,160,23,.35);
    aspect-ratio: 4/3;
    transition: transform .3s, box-shadow .3s;
}
.char-build-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 32px rgba(107,26,26,.2);
    border-color: #d4a017;
}
.char-build-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .4s;
}
.char-build-item:hover img {
    transform: scale(1.04);
}

/* ── Caption bar ── */
.char-build-caption {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    background: linear-gradient(to top, rgba(107,26,26,.88), transparent);
    padding: 1.8rem .9rem .65rem;
}
.char-build-caption span {
    color: #fde8a0;
    font-size: .82rem;
    font-weight: 600;
    line-height: 1.4;
    display: block;
}

/* ── Overlay zoom icon ── */
.char-build-overlay {
    position: absolute;
    inset: 0;
    background: rgba(107,26,26,.0);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background .3s;
}
.char-build-overlay i {
    color: rgba(255,255,255,0);
    font-size: 1.8rem;
    transition: color .3s;
}
.char-build-item:hover .char-build-overlay {
    background: rgba(107,26,26,.12);
}
.char-build-item:hover .char-build-overlay i {
    color: rgba(255,255,255,.8);
}

/* ── Lightbox ── */
.char-lightbox {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.88);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
}
.char-lightbox.open {
    display: flex;
}
.char-lightbox-inner {
    max-width: 92vw;
    max-height: 90vh;
    text-align: center;
}
.char-lightbox-inner img {
    max-width: 100%;
    max-height: 80vh;
    border-radius: 12px;
    border: 3px solid #d4a017;
    box-shadow: 0 16px 64px rgba(0,0,0,.5);
    display: block;
    margin: 0 auto;
}
.char-lightbox-caption {
    color: #fde8a0;
    font-size: .95rem;
    font-weight: 600;
    margin-top: .9rem;
    text-shadow: 0 1px 4px rgba(0,0,0,.5);
    min-height: 1.2em;
}
.char-lightbox-close {
    position: fixed;
    top: 1.2rem; right: 1.4rem;
    background: rgba(255,255,255,.12);
    border: none;
    color: #fff;
    font-size: 1.4rem;
    width: 42px; height: 42px;
    border-radius: 50%;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .2s;
    z-index: 10000;
}
.char-lightbox-close:hover {
    background: rgba(255,255,255,.25);
}

/* ── Responsive ── */
@media (max-width: 768px) {
    .char-build-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: .85rem;
    }
}
@media (max-width: 420px) {
    .char-build-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function openLightbox(src, caption) {
    document.getElementById('charLightboxImg').src = src;
    document.getElementById('charLightboxCaption').textContent = caption || '';
    document.getElementById('charLightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('charLightbox').classList.remove('open');
    document.getElementById('charLightboxImg').src = '';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
});
</script>
@endif

{{-- ═══════════════════════════════════════
     CTA
═══════════════════════════════════════ --}}
<section class="tentang-cta">
    <div class="container">
        <h2>Siap Bergabung Bersama Kami?</h2>
        <p>Daftarkan putra-putri Anda dan jadikan mereka bagian dari keluarga besar Ash-Shiddiqin.</p>
        <a href="{{ route('pesan') }}" class="btn-tentang-cta">
            <i class="fas fa-envelope"></i> Hubungi Kami
        </a>
    </div>
</section>

{{-- ═══════════════════════════════════════
     INLINE STYLES
═══════════════════════════════════════ --}}
<style>
/* ── HERO ── */
.tentang-hero {
    background: linear-gradient(135deg, #6b1a1a 0%, #8b2d2d 50%, #a0491a 80%, #d4a017 100%);
    padding: 9rem 0 5rem;
    text-align: center;
    color: #fff;
    position: relative;
    overflow: hidden;
}
.tentang-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/svg%3E");
}
.tentang-hero h1 { color: #fff; margin-bottom: .5rem; text-shadow: 0 2px 8px rgba(0,0,0,.3); position: relative; }
.tentang-hero p  { color: rgba(255,255,255,.85); font-size: 1.1rem; position: relative; }

/* ── SECTION LABEL & HEADER ── */
.section-label {
    display: inline-block;
    background: linear-gradient(135deg, #6b1a1a, #a0491a);
    color: #fde8a0;
    font-size: .75rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    padding: .3rem 1rem;
    border-radius: 20px;
    margin-bottom: .9rem;
}
.section-label-gold { background: linear-gradient(135deg, #d4a017, #f1b827); color: #6b1a1a; }

.section-header { text-align: center; margin-bottom: 3rem; }
.section-header h2 { margin-bottom: .6rem; }
.section-header p  { color: #666; max-width: 600px; margin: 0 auto; font-size: 1.05rem; }

.section-header-dark h2 { color: #fff; }
.section-header-dark p  { color: rgba(255,255,255,.75); }

/* ── SEJARAH ── */
.tentang-sejarah { background: #fdf6e8; padding: 5rem 0; }
.tentang-sejarah-inner {
    display: grid;
    grid-template-columns: minmax(0, 5fr) minmax(0, 4fr);
    gap: 3rem;
    align-items: center;
}
.tentang-sejarah-img {
    position: relative;
    text-align: left;
    min-width: 0;
}
.sejarah-logo {
    width: 100%;
    aspect-ratio: 16 / 9;
    object-fit: cover;
    object-position: center center;
    border-radius: 16px;
    border: 4px solid rgba(212,160,23,.4);
    box-shadow: 0 16px 50px rgba(107,26,26,.30);
    position: relative;
    z-index: 1;
    display: block;
}
.sejarah-ornament {
    display: none;
}
.tentang-sejarah-text {
    min-width: 0;
}
.tentang-sejarah-text .section-label { display: block; margin-bottom: .8rem; }
.tentang-sejarah-text h2 {
    margin-bottom: 1.2rem;
    font-size: clamp(1.4rem, 1.8vw, 2rem);
    line-height: 1.3;
    word-break: break-word;
}
.tentang-sejarah-text > p {
    color: #444;
    line-height: 1.85;
    font-size: 1.02rem;
    text-align: justify;
    overflow-wrap: break-word;
    word-break: break-word;
}

/* ── VISI MISI ── */
.tentang-visimisi { background: #fff; padding: 5rem 0; }
.visimisi-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
.visimisi-card { border-radius: 20px; padding: 2.5rem; }
.visimisi-visi { background: linear-gradient(135deg, #6b1a1a, #8b2d2d); }
.visimisi-misi { background: linear-gradient(135deg, #fdf6e8, #f5ede0); border: 2px solid rgba(212,160,23,.4); }
.vm-icon {
    width: 54px; height: 54px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; margin-bottom: 1.2rem;
}
.visimisi-visi .vm-icon { background: rgba(255,255,255,.15); color: #fde8a0; }
.visimisi-misi .vm-icon { background: linear-gradient(135deg, #6b1a1a, #a0491a); color: #fde8a0; }
.visimisi-visi h3 { color: #fde8a0; font-family: 'Amiri', serif; margin-bottom: .8rem; }
.visimisi-misi h3 { color: #6b1a1a; font-family: 'Amiri', serif; margin-bottom: .8rem; }
.visimisi-visi p  { color: rgba(255,255,255,.88); line-height: 1.8; }
.misi-list { list-style: none; padding: 0; }
.misi-list li {
    display: flex; align-items: flex-start; gap: .6rem;
    color: #444; line-height: 1.7; margin-bottom: .5rem; font-size: .97rem;
}
.misi-list li i { color: #6b1a1a; margin-top: .3rem; flex-shrink: 0; font-size: .82rem; }

/* ── PROGRAM PENDIDIKAN ── */
.tentang-program { background: #fdf6e8; padding: 5rem 0; }
.program-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}
.program-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(107,26,26,.08);
    display: flex; flex-direction: column;
    transition: transform .3s, box-shadow .3s;
    border-top: 4px solid #6b1a1a;
}
.program-card:hover {
    transform: translateY(-7px);
    box-shadow: 0 18px 50px rgba(107,26,26,.15);
}
.program-card-top { padding: 1.8rem 1.5rem 1.2rem; flex: 1; }
.program-badge {
    display: inline-block;
    background: linear-gradient(135deg, #d4a017, #f1b827);
    color: #6b1a1a;
    font-size: .7rem; font-weight: 800;
    letter-spacing: 1px; text-transform: uppercase;
    padding: .2rem .75rem; border-radius: 20px;
    margin-bottom: 1rem;
}
.program-icon {
    width: 52px; height: 52px; border-radius: 14px;
    background: linear-gradient(135deg, #6b1a1a, #8b2d2d);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; color: #fde8a0; margin-bottom: 1rem;
}
.program-card h3 { font-size: 1.05rem; color: #6b1a1a; margin-bottom: .6rem; }
.program-card-top > p { color: #666; font-size: .9rem; line-height: 1.7; }
.program-poin {
    list-style: none; padding: 1rem 1.5rem 1.5rem;
    border-top: 1px solid #f0e8d8;
    display: flex; flex-direction: column; gap: .4rem;
}
.program-poin li { display: flex; align-items: center; gap: .5rem; font-size: .85rem; color: #555; font-weight: 600; }
.program-poin li i { color: #d4a017; font-size: .75rem; }

/* ── KEUNGGULAN 5×2 ── */
.tentang-keunggulan {
    background: linear-gradient(135deg, #6b1a1a 0%, #8b2d2d 60%, #a0491a 100%);
    padding: 5rem 0;
    position: relative; overflow: hidden;
}
.tentang-keunggulan::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M40 0L80 20v40L40 80 0 60V20L40 0z'/%3E%3C/g%3E%3C/svg%3E");
}
.tentang-keunggulan .container { position: relative; z-index: 1; }
.keunggulan-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1.25rem;
}
.keunggulan-card {
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 16px; padding: 1.6rem 1.2rem;
    text-align: center;
    transition: background .3s, transform .3s;
    position: relative; overflow: hidden;
}
.keunggulan-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(to right, #d4a017, #f1b827);
    transform: scaleX(0); transform-origin: left;
    transition: transform .3s;
}
.keunggulan-card:hover::before { transform: scaleX(1); }
.keunggulan-card:hover { background: rgba(255,255,255,.13); transform: translateY(-5px); }
.keunggulan-no {
    position: absolute; top: .8rem; right: .85rem;
    font-size: .7rem; font-weight: 800;
    color: rgba(255,255,255,.22); letter-spacing: 1px;
}
.keunggulan-icon {
    width: 52px; height: 52px;
    background: linear-gradient(135deg, #d4a017, #f1b827);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; color: #6b1a1a;
    margin: 0 auto 1rem;
}
.keunggulan-card h4 { color: #fde8a0; font-family: 'Amiri', serif; font-size: 1rem; margin-bottom: .5rem; }
.keunggulan-card p  { color: rgba(255,255,255,.72); font-size: .82rem; line-height: 1.65; }

/* ── CTA ── */
.tentang-cta {
    background: linear-gradient(135deg, #d4a017, #f1b827);
    padding: 4.5rem 0; text-align: center;
}
.tentang-cta h2 { color: #6b1a1a; margin-bottom: .8rem; }
.tentang-cta p  { color: rgba(107,26,26,.8); margin-bottom: 2rem; font-size: 1.05rem; }
.btn-tentang-cta {
    display: inline-flex; align-items: center; gap: .6rem;
    background: #6b1a1a; color: #fde8a0;
    padding: .9rem 2.4rem; border-radius: 50px;
    font-weight: 700; font-size: 1rem;
    transition: all .3s; text-decoration: none;
}
.btn-tentang-cta:hover {
    background: #8b2d2d; color: #fff;
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(107,26,26,.4);
}

/* ── EMPTY STATE ── */
.tentang-empty { text-align: center; padding: 3rem; color: #999; }
.tentang-empty i { font-size: 3rem; margin-bottom: 1rem; display: block; }

/* ── RESPONSIVE ── */
@media (max-width: 1200px) {
    .keunggulan-grid { grid-template-columns: repeat(4, 1fr); }
    .tentang-sejarah-inner { grid-template-columns: minmax(0, 3fr) minmax(0, 2fr); gap: 2.5rem; }
}
@media (max-width: 1024px) {
    .program-grid          { grid-template-columns: repeat(2, 1fr); }
    .keunggulan-grid       { grid-template-columns: repeat(3, 1fr); }
    .tentang-sejarah-inner { grid-template-columns: 1fr; text-align: center; gap: 2rem; }
    .tentang-sejarah-text > p { text-align: justify; }
    .tentang-sejarah-text h2 { text-align: center; }
    .sejarah-ornament      { left: 50%; }
}
@media (max-width: 768px) {
    .visimisi-grid   { grid-template-columns: 1fr; }
    .program-grid    { grid-template-columns: 1fr; }
    .keunggulan-grid { grid-template-columns: repeat(2, 1fr); }
    .tentang-hero    { padding: 7rem 0 4rem; }
    .tentang-sejarah-inner { gap: 1.5rem; }
}
@media (max-width: 480px) {
    .keunggulan-grid { grid-template-columns: 1fr; }
}
</style>

@endsection
