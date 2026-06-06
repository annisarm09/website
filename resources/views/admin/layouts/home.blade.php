<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel – Ash-Shiddiqin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body>

{{-- HEADER --}}
<header class="header">
    <div class="header-brand">
        <div class="brand-icon"><i class="fas fa-mosque"></i></div>
        Admin Panel Ash-Shiddiqin
    </div>
    <div class="header-right">
        <div class="user-badge">
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
            <span>{{ Auth::user()->name ?? 'Admin' }}</span>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </div>
</header>

{{-- LAYOUT --}}
<div class="layout">

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-label">Menu Utama</div>

        <button class="nav-item {{ session('active_tab','beranda') == 'beranda' ? 'active' : '' }}"
                onclick="showTab('beranda', this)">
            <i class="fas fa-home"></i> Beranda
        </button>

        <button class="nav-item {{ session('active_tab') == 'berita' ? 'active' : '' }}"
                onclick="showTab('berita', this)">
            <i class="fas fa-newspaper"></i> Berita
        </button>

        <button class="nav-item {{ session('active_tab') == 'galeri' ? 'active' : '' }}"
                onclick="showTab('galeri', this)">
            <i class="fas fa-images"></i> Galeri
            {{-- Badge jika ada galeri yang masih pending --}}
            @php $galeriPendingCount = isset($galeriList) ? $galeriList->where('status','pending')->count() : 0; @endphp
            @if($galeriPendingCount > 0)
                <span class="badge-count">{{ $galeriPendingCount }}</span>
            @endif
        </button>

        <button class="nav-item {{ session('active_tab') == 'tentang' ? 'active' : '' }}"
                onclick="showTab('tentang', this)">
            <i class="fas fa-info-circle"></i> Tentang Kami
        </button>

        <button class="nav-item {{ session('active_tab') == 'pesan' ? 'active' : '' }}"
                onclick="showTab('pesan', this)">
            <i class="fas fa-envelope"></i> Pesan Masuk
            @if(isset($pesanBelumDibaca) && $pesanBelumDibaca > 0)
                <span class="badge-count">{{ $pesanBelumDibaca }}</span>
            @endif
        </button>

        <div class="sidebar-label">Akses Cepat</div>
        <a href="{{ route('dashboard') }}" class="nav-item" style="text-decoration:none;" target="_blank">
            <i class="fas fa-external-link-alt"></i> Lihat Website
        </a>
    </aside>

    {{-- MAIN --}}
    <main class="main">

        {{-- Notifikasi global --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        {{-- ══ TAB: BERANDA ══ --}}
        <div id="tab-beranda" class="tab-panel {{ session('active_tab','beranda') == 'beranda' ? 'active' : '' }}">
            <div class="page-header">
                <h1><i class="fas fa-home"></i> Kelola Beranda</h1>
                <p>Update konten yang tampil di halaman utama website</p>
            </div>

            {{-- Statistik --}}
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fas fa-newspaper"></i></div>
                    <div class="stat-info"><h3>{{ $totalBerita ?? 0 }}</h3><p>Total Berita</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon gold"><i class="fas fa-images"></i></div>
                    <div class="stat-info"><h3>{{ $totalGaleri ?? 0 }}</h3><p>Foto Galeri</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-envelope"></i></div>
                    <div class="stat-info"><h3>{{ $totalPesan ?? 0 }}</h3><p>Pesan Masuk</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red"><i class="fas fa-bell"></i></div>
                    <div class="stat-info"><h3>{{ $pesanBelumDibaca ?? 0 }}</h3><p>Belum Dibaca</p></div>
                </div>
            </div>

            {{-- Form Update Beranda: 3 Slide --}}
            <div class="card">
                <div class="card-title">
                    <i class="fas fa-edit"></i> Update Konten Beranda
                    <span style="font-size:.8rem;font-weight:400;color:#888;margin-left:.5rem;">
                        — 3 slide
                    </span>
                </div>

                {{-- Tab navigasi slide --}}
                <div class="slide-tab-nav" style="display:flex;gap:.5rem;margin-bottom:1.5rem;border-bottom:2px solid #f3f4f6;padding-bottom:.75rem;">
                    <button type="button" class="slide-nav-btn active" onclick="showSlideTab(1, this)"
                        style="padding:.5rem 1.2rem;border-radius:8px;border:1.5px solid #6b1a1a;background:#6b1a1a;color:#fde8a0;font-weight:600;cursor:pointer;font-size:.88rem;">
                        <i class="fas fa-circle" style="font-size:.5rem;margin-right:.3rem;"></i> Slide 1
                    </button>
                    <button type="button" class="slide-nav-btn" onclick="showSlideTab(2, this)"
                        style="padding:.5rem 1.2rem;border-radius:8px;border:1.5px solid #d4a017;background:#fff;color:#6b1a1a;font-weight:600;cursor:pointer;font-size:.88rem;">
                        <i class="fas fa-circle" style="font-size:.5rem;margin-right:.3rem;"></i> Slide 2
                    </button>
                    <button type="button" class="slide-nav-btn" onclick="showSlideTab(3, this)"
                        style="padding:.5rem 1.2rem;border-radius:8px;border:1.5px solid #d4a017;background:#fff;color:#6b1a1a;font-weight:600;cursor:pointer;font-size:.88rem;">
                        <i class="fas fa-circle" style="font-size:.5rem;margin-right:.3rem;"></i> Slide 3
                    </button>
                </div>

                {{-- ── SLIDE 1 ── --}}
                <div id="slide-panel-1" class="slide-panel">
                    {{-- Preview label --}}
                    <div style="background:linear-gradient(135deg,#6b1a1a,#8b2d2d);color:#fde8a0;border-radius:10px;padding:.6rem 1rem;margin-bottom:1.2rem;font-size:.82rem;display:flex;align-items:center;gap:.5rem;">
                        <i class="fas fa-eye"></i>
                        <strong>Slide 1 · Saat ini tampil:</strong>
                        <em>"{{ $beranda->slide1_label ?? 'Selamat datang di' }}" — {{ $beranda->slide1_judul ?? 'ASH-SHIDDIQIN' }}</em>
                    </div>
                    <form action="{{ route('home.updateSlide', 1) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Label Kecil <span style="color:#888;font-weight:400;font-size:.8rem;">(teks kecil di atas judul)</span></label>
                            <input type="text" name="slide1_label" class="form-control"
                                   value="{{ old('slide1_label', $beranda->slide1_label ?? 'Selamat datang di') }}"
                                   placeholder="Contoh: Selamat datang di">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Judul Besar <span style="color:#888;font-weight:400;font-size:.8rem;">(teks besar/nama pesantren)</span></label>
                            <input type="text" name="slide1_judul" class="form-control"
                                   value="{{ old('slide1_judul', $beranda->slide1_judul ?? 'ASH-SHIDDIQIN') }}"
                                   placeholder="Contoh: ASH-SHIDDIQIN" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sub Judul <span style="color:#888;font-weight:400;font-size:.8rem;">(teks di bawah judul besar)</span></label>
                            <input type="text" name="slide1_sub" class="form-control"
                                   value="{{ old('slide1_sub', $beranda->slide1_sub ?? 'Pondok Pesantren Modern Palembang') }}"
                                   placeholder="Contoh: Pondok Pesantren Modern Palembang">
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                            <div class="form-group">
                                <label class="form-label">Teks Tombol Kiri</label>
                                <input type="text" name="slide1_btn1" class="form-control"
                                       value="{{ old('slide1_btn1', $beranda->slide1_btn1 ?? 'Pelajari Lebih Lanjut →') }}"
                                       placeholder="Contoh: Pelajari Lebih Lanjut →">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Teks Tombol Kanan</label>
                                <input type="text" name="slide1_btn2" class="form-control"
                                       value="{{ old('slide1_btn2', $beranda->slide1_btn2 ?? 'Informasi Pendaftaran →') }}"
                                       placeholder="Contoh: Informasi Pendaftaran →">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Slide 1
                        </button>
                    </form>
                </div>

                {{-- ── SLIDE 2 ── --}}
                <div id="slide-panel-2" class="slide-panel" style="display:none;">
                    <div style="background:linear-gradient(135deg,#6b1a1a,#8b2d2d);color:#fde8a0;border-radius:10px;padding:.6rem 1rem;margin-bottom:1.2rem;font-size:.82rem;display:flex;align-items:center;gap:.5rem;">
                        <i class="fas fa-eye"></i>
                        <strong>Slide 2 · Saat ini tampil:</strong>
                        <em>"{{ $beranda->slide2_label ?? 'Generasi Terbaik' }}" — {{ $beranda->slide2_judul ?? 'KOKOH IMAN & PRODUKTIF' }}</em>
                    </div>
                    <form action="{{ route('home.updateSlide', 2) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Label Kecil</label>
                            <input type="text" name="slide2_label" class="form-control"
                                   value="{{ old('slide2_label', $beranda->slide2_label ?? 'Generasi Terbaik') }}"
                                   placeholder="Contoh: Generasi Terbaik">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Judul Besar</label>
                            <input type="text" name="slide2_judul" class="form-control"
                                   value="{{ old('slide2_judul', $beranda->slide2_judul ?? 'KOKOH IMAN & PRODUKTIF') }}"
                                   placeholder="Contoh: KOKOH IMAN & PRODUKTIF" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sub Judul</label>
                            <input type="text" name="slide2_sub" class="form-control"
                                   value="{{ old('slide2_sub', $beranda->slide2_sub ?? 'Manhaj Islam Wasathiyah') }}"
                                   placeholder="Contoh: Manhaj Islam Wasathiyah">
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                            <div class="form-group">
                                <label class="form-label">Teks Tombol Kiri</label>
                                <input type="text" name="slide2_btn1" class="form-control"
                                       value="{{ old('slide2_btn1', $beranda->slide2_btn1 ?? 'Berita Terbaru →') }}"
                                       placeholder="Contoh: Berita Terbaru →">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Teks Tombol Kanan</label>
                                <input type="text" name="slide2_btn2" class="form-control"
                                       value="{{ old('slide2_btn2', $beranda->slide2_btn2 ?? 'Daftar Sekarang →') }}"
                                       placeholder="Contoh: Daftar Sekarang →">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Slide 2
                        </button>
                    </form>
                </div>

                {{-- ── SLIDE 3 ── --}}
                <div id="slide-panel-3" class="slide-panel" style="display:none;">
                    <div style="background:linear-gradient(135deg,#6b1a1a,#8b2d2d);color:#fde8a0;border-radius:10px;padding:.6rem 1rem;margin-bottom:1.2rem;font-size:.82rem;display:flex;align-items:center;gap:.5rem;">
                        <i class="fas fa-eye"></i>
                        <strong>Slide 3 · Saat ini tampil:</strong>
                        <em>"{{ $beranda->slide3_label ?? 'Program Unggulan' }}" — {{ $beranda->slide3_judul ?? 'TAHFIDZ AL-QURAN' }}</em>
                    </div>
                    <form action="{{ route('home.updateSlide', 3) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Label Kecil</label>
                            <input type="text" name="slide3_label" class="form-control"
                                   value="{{ old('slide3_label', $beranda->slide3_label ?? 'Program Unggulan') }}"
                                   placeholder="Contoh: Program Unggulan">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Judul Besar</label>
                            <input type="text" name="slide3_judul" class="form-control"
                                   value="{{ old('slide3_judul', $beranda->slide3_judul ?? 'TAHFIDZ AL-QURAN') }}"
                                   placeholder="Contoh: TAHFIDZ AL-QURAN" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sub Judul</label>
                            <input type="text" name="slide3_sub" class="form-control"
                                   value="{{ old('slide3_sub', $beranda->slide3_sub ?? 'Target 30 Juz Bersama Ustadz Terbaik') }}"
                                   placeholder="Contoh: Target 30 Juz Bersama Ustadz Terbaik">
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                            <div class="form-group">
                                <label class="form-label">Teks Tombol Kiri</label>
                                <input type="text" name="slide3_btn1" class="form-control"
                                       value="{{ old('slide3_btn1', $beranda->slide3_btn1 ?? 'Lihat Program →') }}"
                                       placeholder="Contoh: Lihat Program →">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Teks Tombol Kanan</label>
                                <input type="text" name="slide3_btn2" class="form-control"
                                       value="{{ old('slide3_btn2', $beranda->slide3_btn2 ?? 'Lihat Galeri →') }}"
                                       placeholder="Contoh: Lihat Galeri →">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Slide 3
                        </button>
                    </form>
                </div>

            </div>
        </div>

        {{-- ══ TAB: BERITA ══ --}}
        <div id="tab-berita" class="tab-panel {{ session('active_tab') == 'berita' ? 'active' : '' }}">
            <div class="page-header">
                <h1><i class="fas fa-newspaper"></i> Kelola Berita</h1>
                <p>Tambah dan kelola artikel berita pondok pesantren</p>
            </div>
            <div class="card">
                <div class="card-title"><i class="fas fa-plus-circle"></i> Tambah Berita Baru</div>

                <form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Judul Berita <span style="color:red">*</span></label>
                        <input type="text" name="judul_berita" class="form-control"
                               placeholder="Judul berita terbaru..."
                               value="{{ old('judul_berita') }}" required>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div class="form-group">
                            <label class="form-label">Kategori <span style="color:red">*</span></label>
                            <select name="kategori" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="kegiatan"   {{ old('kategori') == 'kegiatan'   ? 'selected' : '' }}>Kegiatan</option>
                                <option value="pengumuman" {{ old('kategori') == 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                                <option value="prestasi"   {{ old('kategori') == 'prestasi'   ? 'selected' : '' }}>Prestasi</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal <span style="color:red">*</span></label>
                            <input type="date" name="tanggal" class="form-control"
                                   value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status <span style="color:red">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="pending" selected>Pending (Menunggu Approval Pimpinan)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Gambar Berita</label>
                        <div class="file-drop" onclick="document.getElementById('beritaImage').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Klik untuk pilih gambar <br><small>JPG, PNG, WEBP – Maks. 2MB</small></p>
                            <input type="file" id="beritaImage" name="foto" accept="image/*"
                                   onchange="previewImg(this,'prevBerita')" style="display:none">
                        </div>
                        <img id="prevBerita" class="image-preview" style="display:none;max-height:200px;margin-top:.5rem;border-radius:8px;">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Isi Berita <span style="color:red">*</span></label>
                        <textarea name="isi_berita" class="form-control" style="min-height:200px"
                                  placeholder="Tulis isi berita di sini..." required>{{ old('isi_berita') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Kirim ke Pimpinan
                    </button>
                </form>
            </div>

            {{-- Daftar Berita --}}
            <div class="card">
                <div class="card-title"><i class="fas fa-list"></i> Daftar Berita ({{ isset($beritaList) ? $beritaList->count() : 0 }})</div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($beritaList ?? [] as $b)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $b->judul_berita }}</td>
                                <td><span class="badge-kategori">{{ ucfirst($b->kategori) }}</span></td>
                                <td>
                                    @if($b->status == 'published')
                                        <span style="color:green;font-weight:600;">✅ Tampil</span>
                                    @elseif($b->status == 'pending')
                                        <span style="color:#d97706;font-weight:600;">⏳ Menunggu Pimpinan</span>
                                    @elseif($b->status == 'rejected')
                                        <span style="color:#c62828;font-weight:600;">❌ Ditolak</span>
                                    @else
                                        <span style="color:orange;font-weight:600;">📝 Draft</span>
                                    @endif
                                </td>
                                <td>{{ $b->tanggal ? \Carbon\Carbon::parse($b->tanggal)->format('d M Y') : '-' }}</td>
                                <td style="display:flex;gap:.4rem;flex-wrap:wrap;">
                                    @if($b->status == 'published')
                                    <a href="{{ route('berita.show', $b->slug) }}" target="_blank"
                                       class="btn btn-secondary btn-sm" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('berita.edit', $b->id) }}"
                                       class="btn btn-secondary btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('berita.destroy', $b->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus berita ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fas fa-newspaper"></i>
                                        <p>Belum ada berita. Tambahkan berita pertama di atas!</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ══ TAB: GALERI ══ --}}
        <div id="tab-galeri" class="tab-panel {{ session('active_tab') == 'galeri' ? 'active' : '' }}">
            <div class="page-header">
                <h1><i class="fas fa-images"></i> Kelola Galeri</h1>
                <p>Upload foto kegiatan — foto akan tampil setelah disetujui Pimpinan</p>
            </div>

            {{-- Info alur approval --}}
            <div style="background:#fffbeb;border:1px solid #f59e0b;border-radius:10px;padding:1rem 1.2rem;margin-bottom:1.2rem;display:flex;gap:.8rem;align-items:flex-start;">
                <i class="fas fa-info-circle" style="color:#f59e0b;margin-top:.1rem;font-size:1.1rem;flex-shrink:0;"></i>
                <div style="font-size:.88rem;color:#7c4a00;">
                    <strong>Alur Galeri:</strong> Foto yang Anda upload akan masuk status
                    <strong>Menunggu Persetujuan</strong> terlebih dahulu.
                    Pimpinan akan me-review dan menyetujui atau menolaknya.
                    Foto hanya tampil di halaman publik setelah <strong>disetujui</strong>.
                </div>
            </div>

            {{-- Form upload --}}
            <div class="card">
                <div class="card-title"><i class="fas fa-upload"></i> Upload Foto Baru</div>

                <form action="{{ route('galeri.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Judul / Keterangan Foto <span style="color:red">*</span></label>
                        <input type="text" name="judul" class="form-control"
                               placeholder="Contoh: Wisuda Angkatan ke-15, Pengajian Rutin..."
                               value="{{ old('judul') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kategori <span style="color:red">*</span></label>
                        <select name="kategori" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="pengajian" {{ old('kategori') == 'pengajian' ? 'selected' : '' }}>Pengajian</option>
                            <option value="tahfidz"   {{ old('kategori') == 'tahfidz'   ? 'selected' : '' }}>Tahfidz Quran</option>
                            <option value="wisuda"    {{ old('kategori') == 'wisuda'    ? 'selected' : '' }}>Wisuda</option>
                            <option value="lainnya"   {{ old('kategori') == 'lainnya'   ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pilih Foto <span style="color:red">*</span></label>
                        <div class="file-drop" onclick="document.getElementById('galeriImage').click()">
                            <i class="fas fa-image"></i>
                            <p>Klik untuk pilih foto <br><small>JPG, PNG, WEBP – Maks. 5MB</small></p>
                            <input type="file" id="galeriImage" name="foto" accept="image/*"
                                   onchange="previewImg(this,'prevGaleri')" style="display:none" required>
                        </div>
                        <img id="prevGaleri" class="image-preview" style="display:none;max-height:200px;margin-top:.5rem;border-radius:8px;">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Kirim ke Pimpinan
                    </button>
                </form>
            </div>

            {{-- Grid Galeri dengan status badge --}}
            <div class="card">
                <div class="card-title">
                    <i class="fas fa-th"></i> Semua Foto Galeri ({{ isset($galeriList) ? $galeriList->count() : 0 }})
                </div>

                {{-- Filter status tab --}}
                <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1.2rem;">
                    <button onclick="filterGaleri('all', this)"
                            class="btn btn-secondary btn-sm galeri-filter-btn active-filter"
                            style="font-size:.8rem;">
                        Semua
                    </button>
                    <button onclick="filterGaleri('published', this)"
                            class="btn btn-secondary btn-sm galeri-filter-btn"
                            style="font-size:.8rem;color:#2e7d32;">
                        ✅ Disetujui
                    </button>
                    <button onclick="filterGaleri('pending', this)"
                            class="btn btn-secondary btn-sm galeri-filter-btn"
                            style="font-size:.8rem;color:#d97706;">
                        ⏳ Menunggu
                    </button>
                    <button onclick="filterGaleri('rejected', this)"
                            class="btn btn-secondary btn-sm galeri-filter-btn"
                            style="font-size:.8rem;color:#c62828;">
                        ❌ Ditolak
                    </button>
                </div>

                @if(isset($galeriList) && $galeriList->count() > 0)
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:1rem;" id="galeriGrid">
                    @foreach($galeriList as $g)
                    <div class="galeri-card-item"
                         data-status="{{ $g->status }}"
                         style="position:relative;border-radius:12px;overflow:hidden;
                                box-shadow:0 2px 8px rgba(0,0,0,.12);
                                {{ $g->status == 'rejected' ? 'opacity:.65;' : '' }}
                                {{ $g->status == 'pending'  ? 'border:2px solid #f59e0b;' : '' }}
                                {{ $g->status == 'published'? 'border:2px solid #2e7d32;' : '' }}">

                        <img src="{{ asset('storage/' . $g->src) }}" alt="{{ $g->judul }}"
                             style="width:100%;height:140px;object-fit:cover;">

                        <div style="padding:.6rem .8rem;font-size:.78rem;color:#3a5046;font-weight:600;">
                            {{ Str::limit($g->judul, 28) }}
                        </div>

                        {{-- Badge kategori --}}
                        <span style="position:absolute;top:.4rem;left:.4rem;
                                     background:rgba(0,0,0,.55);color:#fff;
                                     font-size:.65rem;padding:.15rem .4rem;border-radius:4px;">
                            {{ $g->kategori }}
                        </span>

                        {{-- Badge status --}}
                        @if($g->status == 'published')
                            <span style="position:absolute;top:2rem;left:.4rem;
                                         background:#2e7d32;color:#fff;
                                         font-size:.62rem;padding:.15rem .4rem;border-radius:4px;">
                                ✅ Disetujui
                            </span>
                        @elseif($g->status == 'pending')
                            <span style="position:absolute;top:2rem;left:.4rem;
                                         background:#f59e0b;color:#fff;
                                         font-size:.62rem;padding:.15rem .4rem;border-radius:4px;">
                                ⏳ Menunggu
                            </span>
                        @elseif($g->status == 'rejected')
                            <span style="position:absolute;top:2rem;left:.4rem;
                                         background:#c62828;color:#fff;
                                         font-size:.62rem;padding:.15rem .4rem;border-radius:4px;">
                                ❌ Ditolak
                            </span>
                        @endif

                        {{-- Tombol hapus --}}
                        <form action="{{ route('galeri.destroy', $g->id) }}" method="POST"
                              style="position:absolute;top:.4rem;right:.4rem;"
                              onsubmit="return confirm('Hapus foto ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                    style="padding:.3rem .5rem;border-radius:6px;" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-images"></i>
                    <p>Belum ada foto di galeri. Upload foto pertama di atas!</p>
                </div>
                @endif
            </div>
        </div>

        {{-- ══ TAB: TENTANG ══ --}}
        <div id="tab-tentang" class="tab-panel {{ session('active_tab') == 'tentang' ? 'active' : '' }}">
            <div class="page-header">
                <h1><i class="fas fa-info-circle"></i> Tentang Kami</h1>
                <p>Kelola profil, program pendidikan, dan keunggulan pondok pesantren</p>
            </div>

            {{-- Preview link --}}
            <div style="background:linear-gradient(135deg,#fffbef,#fdf0d5);border:1px solid #f1b827;border-radius:12px;padding:1rem 1.2rem;margin-bottom:1.5rem;display:flex;gap:.8rem;align-items:center;justify-content:space-between;flex-wrap:wrap;">
                <div style="display:flex;gap:.8rem;align-items:center;">
                    <i class="fas fa-eye" style="color:#d4a017;font-size:1.1rem;"></i>
                    <span style="font-size:.88rem;color:#6b1a1a;font-weight:600;">Lihat tampilan halaman Tentang Kami di website publik</span>
                </div>
                <a href="{{ route('tentang') }}" target="_blank" class="btn btn-secondary btn-sm" style="font-size:.82rem;">
                    <i class="fas fa-external-link-alt"></i> Buka Halaman Publik
                </a>
            </div>

            {{-- ──── SEKSI 1: Profil (Sejarah, Visi, Misi) ──── --}}
            <div class="card">
                <div class="card-title"><i class="fas fa-scroll"></i> Profil Pondok — Sejarah, Visi & Misi</div>
                <form action="{{ route('tentang.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-history" style="color:#d4a017;margin-right:.4rem;"></i>Sejarah Singkat</label>
                        <textarea name="sejarah" class="form-control" style="min-height:150px"
                                  placeholder="Ceritakan sejarah singkat berdirinya pondok pesantren...">{{ old('sejarah', optional(isset($tentang) ? $tentang->where('tipe','sejarah')->first() : null)->deskripsi ?? '') }}</textarea>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-eye" style="color:#d4a017;margin-right:.4rem;"></i>Visi</label>
                            <textarea name="visi" class="form-control" style="min-height:120px"
                                      placeholder="Visi pondok pesantren...">{{ old('visi', optional(isset($tentang) ? $tentang->where('tipe','visi')->first() : null)->deskripsi ?? '') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-bullseye" style="color:#d4a017;margin-right:.4rem;"></i>Misi</label>
                            <textarea name="misi" class="form-control" style="min-height:120px"
                                      placeholder="Misi pondok pesantren (pisahkan per baris)...">{{ old('misi', optional(isset($tentang) ? $tentang->where('tipe','misi')->first() : null)->deskripsi ?? '') }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Profil
                    </button>
                </form>
            </div>

            {{-- ──── SEKSI 2: Program Pendidikan ──── --}}
<div class="card" style="margin-top:1.5rem;">
    <div class="card-title">
        <i class="fas fa-graduation-cap"></i> Program Pendidikan
        <span style="font-size:.75rem;color:#888;font-weight:400;margin-left:.5rem;">(program yang sudah tersimpan tampil otomatis di halaman publik)</span>
    </div>

    @php
    /* ── Icon picker lengkap (sama dengan Keunggulan) ── */
    $iconPicker = [
        ['val'=>'fas fa-quran',               'label'=>'Al-Qur\'an'],
        ['val'=>'fas fa-graduation-cap',      'label'=>'Wisuda'],
        ['val'=>'fas fa-mosque',              'label'=>'Masjid'],
        ['val'=>'fas fa-star-and-crescent',   'label'=>'Islam'],
        ['val'=>'fas fa-book-open',           'label'=>'Buku'],
        ['val'=>'fas fa-chalkboard-teacher',  'label'=>'Guru'],
        ['val'=>'fas fa-users',               'label'=>'Santri'],
        ['val'=>'fas fa-home',                'label'=>'Asrama'],
        ['val'=>'fas fa-language',            'label'=>'Bahasa'],
        ['val'=>'fas fa-laptop-code',         'label'=>'Teknologi'],
        ['val'=>'fas fa-heartbeat',           'label'=>'Kesehatan'],
        ['val'=>'fas fa-trophy',              'label'=>'Prestasi'],
        ['val'=>'fas fa-seedling',            'label'=>'Karakter'],
        ['val'=>'fas fa-university',          'label'=>'Kampus'],
        ['val'=>'fas fa-star',                'label'=>'Bintang'],
        ['val'=>'fas fa-globe',               'label'=>'Global'],
        ['val'=>'fas fa-hands-praying',       'label'=>'Doa'],
        ['val'=>'fas fa-scroll',              'label'=>'Kitab'],
        ['val'=>'fas fa-brain',               'label'=>'Ilmu'],
        ['val'=>'fas fa-lightbulb',           'label'=>'Inovasi'],
        ['val'=>'fas fa-shield-alt',          'label'=>'Disiplin'],
        ['val'=>'fas fa-medal',               'label'=>'Medal'],
        ['val'=>'fas fa-handshake',           'label'=>'Kerjasama'],
        ['val'=>'fas fa-leaf',                'label'=>'Alam'],
        ['val'=>'fas fa-microscope',          'label'=>'Sains'],
        ['val'=>'fas fa-paint-brush',         'label'=>'Seni'],
        ['val'=>'fas fa-flask',               'label'=>'Riset'],
        ['val'=>'fas fa-compass',             'label'=>'Arah'],
        ['val'=>'fas fa-fire',                'label'=>'Semangat'],
        ['val'=>'fas fa-crown',               'label'=>'Kepemimpin'],
        ['val'=>'fas fa-rocket',              'label'=>'Inovatif'],
        ['val'=>'fas fa-briefcase',           'label'=>'Profesional'],
        ['val'=>'fas fa-chart-line',          'label'=>'Wirausaha'],
        ['val'=>'fas fa-user-tie',            'label'=>'Ulama'],
        ['val'=>'fas fa-atom',                'label'=>'Saintis'],
    ];

    /* ── Kumpulkan program tersimpan ── */
    $savedProgs = [];
    for ($pi = 1; $pi <= 50; $pi++) {
        $rec = isset($tentang) ? $tentang->where('tipe','prog'.$pi)->first() : null;
        if ($rec) {
            $dec = json_decode($rec->deskripsi, true);
            if ($dec) $savedProgs[] = array_merge($dec, ['tipe'=>'prog'.$pi]);
        }
    }
    $nextProgTipe = 'prog' . (count($savedProgs) + 1);
    @endphp

    {{-- ── Daftar program yang sudah ada ── --}}
    @if(count($savedProgs) > 0)
    <div class="prog-admin-list" style="margin-bottom:1.2rem;" id="progList">
        @foreach($savedProgs as $idx => $sp)
        <div class="prog-admin-item" id="prog-item-{{ $sp['tipe'] }}">

            {{-- ── Tampilan normal ── --}}
            <div class="prog-view" id="prog-view-{{ $sp['tipe'] }}" style="display:flex;align-items:center;gap:1rem;width:100%;">
                <div class="prog-circle-num">{{ $idx + 1 }}</div>
                <div class="prog-icon-preview">
                    <i class="{{ $sp['icon'] ?? 'fas fa-graduation-cap' }}" id="prog-icon-display-{{ $sp['tipe'] }}"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-weight:700;color:#6b1a1a;font-size:.95rem;">{{ $sp['judul'] ?? '-' }}</div>
                    <div style="font-size:.82rem;color:#666;margin-top:.2rem;">{{ $sp['deskripsi'] ?? '' }}</div>
                </div>
                <div style="display:flex;gap:.4rem;flex-shrink:0;">
                    {{-- Tombol Edit --}}
                    <button type="button"
                            onclick="toggleProgEdit('{{ $sp['tipe'] }}')"
                            class="btn btn-secondary btn-sm" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    {{-- Tombol Hapus --}}
                    <form action="{{ route('tentang.destroyProgram', $sp['tipe']) }}" method="POST"
                          onsubmit="return confirm('Hapus program ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- ── Form edit (tersembunyi) ── --}}
            <div class="prog-edit-form" id="prog-edit-{{ $sp['tipe'] }}"
                 style="display:none;width:100%;margin-top:.8rem;padding-top:.8rem;border-top:1px dashed #d4a017;">

                <form action="{{ route('tentang.updateProgram') }}" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="tipe" value="{{ $sp['tipe'] }}">
                    <input type="hidden" name="icon" id="edit-prog-icon-{{ $sp['tipe'] }}" value="{{ $sp['icon'] ?? 'fas fa-graduation-cap' }}">

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:.75rem;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:.78rem;">Judul Program <span style="color:red">*</span></label>
                            <input type="text" name="judul" class="form-control"
                                   value="{{ $sp['judul'] ?? '' }}" required
                                   style="font-size:.85rem;">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:.78rem;">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" style="min-height:52px;font-size:.85rem;">{{ $sp['deskripsi'] ?? '' }}</textarea>
                        </div>
                    </div>

                    {{-- Icon Picker edit ── --}}
                    <div class="form-group" style="margin-bottom:.75rem;">
                        <label class="form-label" style="font-size:.78rem;">Pilih Ikon</label>
                        <div class="icon-picker-grid">
                            @foreach($iconPicker as $ic)
                            <button type="button"
                                    onclick="pickEditProgIcon('{{ $sp['tipe'] }}','{{ $ic['val'] }}',this)"
                                    class="icon-pick-btn {{ ($sp['icon'] ?? 'fas fa-graduation-cap') === $ic['val'] ? 'icon-pick-active' : '' }}"
                                    title="{{ $ic['label'] }}">
                                <i class="{{ $ic['val'] }}"></i>
                                <span>{{ $ic['label'] }}</span>
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <div style="display:flex;gap:.5rem;">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <button type="button" onclick="toggleProgEdit('{{ $sp['tipe'] }}')"
                                class="btn btn-secondary btn-sm">
                            <i class="fas fa-times"></i> Batal
                        </button>
                    </div>
                </form>
            </div>

        </div>
        @endforeach
    </div>
    @else
    <p style="color:#888;font-size:.87rem;margin-bottom:1rem;">Belum ada program. Tambahkan program pertama di bawah.</p>
    @endif

    {{-- ── Form tambah program baru ── --}}
    <div style="border-top:1px dashed #d4a017;padding-top:1rem;">
        <div style="font-size:.83rem;font-weight:600;color:#6b1a1a;margin-bottom:.8rem;">
            <i class="fas fa-plus-circle"></i> Tambah Program Baru
        </div>
        <form action="{{ route('tentang.updateProgram') }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="tipe" value="{{ $nextProgTipe }}">
            <input type="hidden" name="icon" id="newProgIcon" value="fas fa-graduation-cap">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:.75rem;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" style="font-size:.8rem;">Judul Program <span style="color:red">*</span></label>
                    <input type="text" name="judul" class="form-control"
                           placeholder="Contoh: Tahfidz Al-Qur'an" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" style="font-size:.8rem;">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" style="min-height:52px;"
                              placeholder="Deskripsi singkat (opsional)..."></textarea>
                </div>
            </div>

            <div class="form-group" style="margin-top:.4rem;">
                <label class="form-label" style="font-size:.8rem;">Pilih Ikon (opsional)</label>
                <div class="icon-picker-grid">
                    @foreach($iconPicker as $ic)
                    <button type="button"
                            onclick="pickNewProgIcon('{{ $ic['val'] }}', this)"
                            class="icon-pick-btn {{ $ic['val'] === 'fas fa-graduation-cap' ? 'icon-pick-active' : '' }}"
                            title="{{ $ic['label'] }}">
                        <i class="{{ $ic['val'] }}"></i>
                        <span>{{ $ic['label'] }}</span>
                    </button>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-sm" style="margin-top:.5rem;">
                <i class="fas fa-plus"></i> Simpan Program
            </button>
        </form>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════════
     SEKSI 3: Keunggulan Pendidikan  (edit inline + icon picker)
     Ganti blok lama mulai dari:
       {{-- ──── SEKSI 3: Keunggulan ──── --}}
       <div class="card" style="margin-top:1.5rem;">
     sampai </div> penutup card seksi 3.
══════════════════════════════════════════════════════════════ --}}

{{-- ──── SEKSI 3: Keunggulan Pendidikan ──── --}}
<div class="card" style="margin-top:1.5rem;">
    <div class="card-title">
        <i class="fas fa-trophy"></i> Keunggulan Pendidikan
        <span style="font-size:.75rem;color:#888;font-weight:400;margin-left:.5rem;">(tampil otomatis di halaman publik, tidak dibatasi jumlahnya)</span>
    </div>

    @php
    /* Icon picker keunggulan — sama persis dengan program */
    $keungIcons = $iconPicker; /* sudah didefinisikan di seksi 2 */

    $savedKeung = [];
    for ($ki = 1; $ki <= 100; $ki++) {
        $rec = isset($tentang) ? $tentang->where('tipe','keunggulan_'.$ki)->first() : null;
        if ($rec) {
            $dec = json_decode($rec->deskripsi, true);
            if ($dec && !empty($dec['judul'])) {
                $savedKeung[] = ['tipe' => 'keunggulan_'.$ki, 'judul' => $dec['judul'], 'icon' => $dec['icon'] ?? 'fas fa-star'];
            }
        }
    }
    @endphp

    {{-- ── Daftar keunggulan yang sudah ada ── --}}
    @if(count($savedKeung) > 0)
    <div style="display:flex;flex-direction:column;gap:.5rem;margin-bottom:1.2rem;" id="keungList">
        @foreach($savedKeung as $ki => $sk)
        <div style="border-radius:10px;border:1px solid rgba(107,26,26,.12);overflow:hidden;" id="keung-item-{{ $sk['tipe'] }}">

            {{-- ── Tampilan normal ── --}}
            <div id="keung-view-{{ $sk['tipe'] }}"
                 style="display:flex;align-items:center;gap:.8rem;background:#fdf6e8;padding:.65rem 1rem;">
                <div style="width:28px;height:28px;flex-shrink:0;background:linear-gradient(135deg,#d4a017,#f1b827);color:#6b1a1a;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:800;">
                    {{ str_pad($ki+1,2,'0',STR_PAD_LEFT) }}
                </div>
                <div style="width:32px;height:32px;flex-shrink:0;background:linear-gradient(135deg,#6b1a1a,#8b2d2d);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <i class="{{ $sk['icon'] }}" style="color:#fde8a0;font-size:.85rem;" id="keung-icon-display-{{ $sk['tipe'] }}"></i>
                </div>
                <span style="flex:1;font-size:.9rem;font-weight:600;color:#3a2a00;">{{ $sk['judul'] }}</span>
                <div style="display:flex;gap:.4rem;flex-shrink:0;">
                    {{-- Tombol Edit --}}
                    <button type="button"
                            onclick="toggleKeungEdit('{{ $sk['tipe'] }}')"
                            class="btn btn-secondary btn-sm" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    {{-- Tombol Hapus --}}
                    <form action="{{ route('tentang.destroyKeunggulan', $sk['tipe']) }}" method="POST"
                          onsubmit="return confirm('Hapus keunggulan ini?')" style="flex-shrink:0;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- ── Form edit (tersembunyi) ── --}}
            <div id="keung-edit-{{ $sk['tipe'] }}"
                 style="display:none;background:#fffbef;padding:.85rem 1rem;border-top:1px dashed #d4a017;">
                <form action="{{ route('tentang.updateKeunggulan', $sk['tipe']) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="icon" id="edit-keung-icon-{{ $sk['tipe'] }}" value="{{ $sk['icon'] }}">

                    <div class="form-group" style="margin-bottom:.65rem;">
                        <label class="form-label" style="font-size:.78rem;">Judul Keunggulan <span style="color:red">*</span></label>
                        <input type="text" name="judul" class="form-control"
                               value="{{ $sk['judul'] }}" required style="font-size:.85rem;">
                    </div>

                    <div class="form-group" style="margin-bottom:.75rem;">
                        <label class="form-label" style="font-size:.78rem;">Pilih Ikon</label>
                        <div class="icon-picker-grid">
                            @foreach($keungIcons as $ic)
                            <button type="button"
                                    onclick="pickEditKeungIcon('{{ $sk['tipe'] }}','{{ $ic['val'] }}',this)"
                                    class="icon-pick-btn {{ $sk['icon'] === $ic['val'] ? 'icon-pick-active' : '' }}"
                                    title="{{ $ic['label'] }}">
                                <i class="{{ $ic['val'] }}"></i>
                                <span>{{ $ic['label'] }}</span>
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <div style="display:flex;gap:.5rem;">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <button type="button" onclick="toggleKeungEdit('{{ $sk['tipe'] }}')"
                                class="btn btn-secondary btn-sm">
                            <i class="fas fa-times"></i> Batal
                        </button>
                    </div>
                </form>
            </div>

        </div>
        @endforeach
    </div>
    @else
    <p style="color:#888;font-size:.87rem;margin-bottom:1rem;">Belum ada keunggulan. Tambahkan di bawah.</p>
    @endif

    {{-- ── Form tambah keunggulan baru ── --}}
    <div style="border-top:1px dashed #d4a017;padding-top:1rem;">
        <div style="font-size:.83rem;font-weight:600;color:#6b1a1a;margin-bottom:.6rem;">
            <i class="fas fa-plus-circle"></i> Tambah Keunggulan Baru
        </div>
        <form action="{{ route('tentang.addKeunggulan') }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="icon" id="newKeungIcon" value="fas fa-star-and-crescent">
            <div class="form-group">
                <label class="form-label" style="font-size:.8rem;">Judul Keunggulan <span style="color:red">*</span></label>
                <input type="text" name="judul" class="form-control"
                       placeholder="Contoh: Hafalan Al-Qur'an Terstruktur" required>
            </div>
            <div class="form-group" style="margin-top:.4rem;">
                <label class="form-label" style="font-size:.8rem;">Pilih Ikon</label>
                <div class="icon-picker-grid">
                    @foreach($keungIcons as $ic)
                    <button type="button"
                            onclick="pickNewKeungIcon('{{ $ic['val'] }}', this)"
                            class="icon-pick-btn {{ $ic['val'] === 'fas fa-star-and-crescent' ? 'icon-pick-active' : '' }}"
                            title="{{ $ic['label'] }}">
                        <i class="{{ $ic['val'] }}"></i>
                        <span>{{ $ic['label'] }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm" style="margin-top:.5rem;">
                <i class="fas fa-plus"></i> Simpan Keunggulan
            </button>
        </form>
    </div>
</div>


            {{-- ──── SEKSI 4: Foto Character Building (Multi) ──── --}}
<div class="card" style="margin-top:1.5rem;">
    <div class="card-title">
        <i class="fas fa-images"></i> Foto Character Building
        <span style="font-size:.75rem;color:#888;font-weight:400;margin-left:.5rem;">
            (upload sebanyak yang diperlukan — tampil sebagai galeri di halaman Tentang Kami)
        </span>
    </div>

    @php
    /*
     * Kumpulkan semua foto char_build_foto_N yang tersimpan.
     * Format JSON per record: { "path": "...", "judul": "...", "urutan": N }
     */
    $charBuildList = collect();
    for ($cbi = 1; $cbi <= 100; $cbi++) {
        $rec = isset($tentang) ? $tentang->where('tipe', 'char_build_foto_' . $cbi)->first() : null;
        if (!$rec) break;
        $dec = json_decode($rec->deskripsi, true);
        if ($dec && !empty($dec['path'])) {
            $dec['slot'] = $cbi;
            $charBuildList->push($dec);
        }
    }
    @endphp

    {{-- ── Daftar foto yang sudah ada ── --}}
    @if($charBuildList->count() > 0)
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem;" id="charBuildGrid">
        @foreach($charBuildList as $cb)
        <div style="border-radius:12px;overflow:hidden;border:2px solid #d4a017;
                    box-shadow:0 3px 12px rgba(0,0,0,.1);background:#fdf6e8;position:relative;">

            {{-- Thumbnail --}}
            <div style="position:relative;overflow:hidden;height:140px;">
                <img src="{{ asset('storage/' . $cb['path']) }}"
                     alt="{{ $cb['judul'] ?? 'Character Building' }}"
                     style="width:100%;height:100%;object-fit:cover;display:block;">
                {{-- Nomor urut --}}
                <span style="position:absolute;top:.4rem;left:.4rem;
                             background:linear-gradient(135deg,#6b1a1a,#8b2d2d);
                             color:#fde8a0;font-size:.65rem;font-weight:800;
                             padding:.2rem .55rem;border-radius:20px;">
                    #{{ $cb['slot'] }}
                </span>
            </div>

            {{-- Caption inline-edit --}}
            <form action="{{ route('tentang.charBuild.updateJudul', $cb['slot']) }}"
                  method="POST"
                  style="padding:.6rem .75rem .4rem;">
                @csrf @method('PATCH')
                <input type="text" name="judul"
                       value="{{ $cb['judul'] ?? '' }}"
                       placeholder="Caption / keterangan..."
                       class="form-control"
                       style="font-size:.78rem;padding:.3rem .6rem;border-radius:6px;">
                <button type="submit"
                        class="btn btn-secondary btn-sm"
                        style="margin-top:.35rem;width:100%;font-size:.72rem;">
                    <i class="fas fa-save"></i> Simpan Caption
                </button>
            </form>

            {{-- Tombol hapus --}}
            <form action="{{ route('tentang.charBuild.destroy', $cb['slot']) }}"
                  method="POST"
                  onsubmit="return confirm('Hapus foto #{{ $cb['slot'] }} ini?')"
                  style="padding:0 .75rem .75rem;">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm" style="width:100%;font-size:.72rem;">
                    <i class="fas fa-trash"></i> Hapus Foto
                </button>
            </form>
        </div>
        @endforeach
    </div>
    @else
    <p style="color:#888;font-size:.85rem;margin-bottom:1rem;">
        <i class="fas fa-info-circle" style="color:#d4a017;"></i>
        Belum ada foto character building. Upload foto pertama di bawah.
    </p>
    @endif

    {{-- ── Form upload foto baru ── --}}
    <div style="border-top:1px dashed #d4a017;padding-top:1.2rem;">
        <div style="font-size:.83rem;font-weight:600;color:#6b1a1a;margin-bottom:.8rem;">
            <i class="fas fa-plus-circle"></i>
            Upload Foto Baru
            <span style="font-weight:400;color:#888;">
                — Foto #{{ $charBuildList->count() + 1 }} (tidak ada batas jumlah)
            </span>
        </div>

        <form action="{{ route('tentang.charBuild.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;align-items:start;">
                {{-- Drop zone --}}
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" style="font-size:.8rem;">Pilih Foto <span style="color:red">*</span></label>
                    <div class="file-drop" onclick="document.getElementById('charBuildNewImg').click()"
                         style="min-height:100px;padding:1rem;">
                        <i class="fas fa-file-image"></i>
                        <p style="font-size:.8rem;">Klik untuk pilih foto<br>
                            <small>JPG, PNG, WEBP – Maks. 5MB</small>
                        </p>
                        <input type="file" id="charBuildNewImg" name="foto"
                               accept="image/*"
                               onchange="previewImg(this,'prevCharBuildNew')"
                               style="display:none" required>
                    </div>
                    <img id="prevCharBuildNew" class="image-preview"
                         style="display:none;max-height:160px;margin-top:.5rem;border-radius:8px;">
                </div>

                {{-- Caption + submit --}}
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" style="font-size:.8rem;">Caption / Keterangan</label>
                    <input type="text" name="judul" class="form-control"
                           placeholder="Contoh: Noble Character #3 – Amanah"
                           style="font-size:.85rem;">
                    <p style="font-size:.72rem;color:#999;margin-top:.35rem;line-height:1.5;">
                        <i class="fas fa-lightbulb" style="color:#d4a017;"></i>
                        Isi caption dengan nama karakter, nomor, atau keterangan singkat
                        agar mudah diidentifikasi.
                    </p>
                    <button type="submit" class="btn btn-primary btn-sm" style="margin-top:.6rem;">
                        <i class="fas fa-upload"></i> Upload Foto
                    </button>
                </div>
            </div>
        </form>
    </div>
        </div>{{-- /card char-build --}}
        </div>{{-- /tab-tentang --}}

        {{-- ══ TAB: PESAN ══ --}}
        <div id="tab-pesan" class="tab-panel {{ session('active_tab') == 'pesan' ? 'active' : '' }}">
            <div class="page-header">
                <h1><i class="fas fa-envelope"></i> Pesan Masuk</h1>
                <p>Pesan dan pertanyaan dari pengunjung website
                    @if(isset($pesanBelumDibaca) && $pesanBelumDibaca > 0)
                        — <strong style="color:red">{{ $pesanBelumDibaca }} belum dibaca</strong>
                    @endif
                </p>
            </div>

            <div class="card">
                <div class="card-title"><i class="fas fa-inbox"></i> Daftar Pesan ({{ isset($pesanList) ? $pesanList->count() : 0 }})</div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Kontak</th>
                                <th>Pesan</th>
                                <th>Tanggal</th>
                                <th>Waktu Respons</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanList ?? [] as $p)
                            <tr id="row-{{ $p->id }}" style="{{ !$p->sudah_dibaca ? 'background:#fffbeb;font-weight:500;' : '' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $p->nama }}</strong></td>
                                <td>{{ $p->kontak }}</td>
                                <td style="max-width:220px;">{{ Str::limit($p->pesan, 80) }}</td>
                                <td>{{ $p->created_at->format('d M Y, H:i') }}</td>
                                <td style="font-size:.82rem;" id="waktu-{{ $p->id }}">
                                    @if($p->dibalas_at)
                                        @php
                                            $menit = $p->created_at->diffInMinutes($p->dibalas_at);
                                            if ($menit < 60)       $respons = $menit . ' menit';
                                            elseif ($menit < 1440) $respons = round($menit/60, 1) . ' jam';
                                            else                   $respons = round($menit/1440, 1) . ' hari';
                                            $color = $menit <= 30 ? '#2e7d32' : ($menit <= 120 ? '#f59e0b' : '#c62828');
                                        @endphp
                                        <span style="font-weight:600;color:{{ $color }};">⚡ {{ $respons }}</span>
                                    @else
                                        <span style="color:#9ca3af;font-size:.8rem;">— Belum dibalas</span>
                                    @endif
                                </td>
                                <td id="status-{{ $p->id }}">
                                    @if($p->status === 'Sudah Dibalas')
                                        <span style="color:#2e7d32;font-size:.8rem;font-weight:600;">✅ Sudah Dibalas</span>
                                    @elseif($p->sudah_dibaca)
                                        <span style="color:green;font-size:.8rem;">👁 Dibaca</span>
                                    @else
                                        <span style="color:orange;font-weight:700;font-size:.8rem;">🔔 Baru</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display:flex;gap:.3rem;flex-wrap:wrap;align-items:center;">
                                        @if(preg_match('/^[0-9]+$/', preg_replace('/\D/','',$p->kontak)))
                                        <button type="button"
                                                class="btn btn-sm"
                                                style="background:#25d366;color:#fff;border:none;padding:.35rem .7rem;border-radius:6px;font-size:.8rem;cursor:pointer;"
                                        onclick="balasWAAdmin({{ $p->id }}, '{{ ltrim(preg_replace('/\D/','',$p->kontak),'0') }}', '{{ urlencode($p->nama) }}', this, '{{ $p->created_at->toIso8601String() }}')"
                                                title="Balas via WhatsApp">
                                            <i class="fab fa-whatsapp"></i> Balas WA
                                        </button>
                                        @endif
                                        <form action="{{ route('admin.pesan.destroy', $p->id) }}" method="POST"
                                              onsubmit="return confirm('Hapus pesan ini?')" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>Belum ada pesan masuk dari pengunjung.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>
</div>

<script>
// Definisi showTab SEBELUM admin.js load — mencegah "showTab is not defined"
// bahkan jika admin.js crash karena Blade syntax
function showTab(name, el) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.nav-item').forEach(b => b.classList.remove('active'));
    var tabEl = document.getElementById('tab-' + name);
    if (tabEl) tabEl.classList.add('active');
    if (el) el.classList.add('active');
}
function previewImg(input, previewId) {
    var file = input.files[0];
    var preview = document.getElementById(previewId);
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}
window.ACTIVE_TAB = '{{ session("active_tab", "beranda") }}';
</script>
<script src="{{ asset('assets/js/admin.js') }}"></script>
<script>
// Jalankan tab aktif setelah admin.js (meski admin.js error, showTab sudah ada di atas)
document.addEventListener('DOMContentLoaded', function () {
    var activeTab = '{{ session("active_tab", "beranda") }}';
    showTab(activeTab, null);
    document.querySelectorAll('.nav-item').forEach(function(btn) {
        var oc = btn.getAttribute('onclick') || '';
        if (oc.indexOf("'" + activeTab + "'") !== -1) btn.classList.add('active');
    });
});
</script>
<script>
// ── Toggle Slide Tab di form beranda ──
function showSlideTab(num, btn) {
    // sembunyikan semua panel
    document.querySelectorAll('.slide-panel').forEach(p => p.style.display = 'none');
    // reset semua tombol
    document.querySelectorAll('.slide-nav-btn').forEach(b => {
        b.style.background = '#fff';
        b.style.color = '#6b1a1a';
    });
    // tampilkan panel yang dipilih
    document.getElementById('slide-panel-' + num).style.display = 'block';
    // aktifkan tombol yang dipilih
    btn.style.background = '#6b1a1a';
    btn.style.color = '#fde8a0';
}

// ── Filter galeri berdasarkan status ──────────────────────────────
function filterGaleri(status, btn) {
    document.querySelectorAll('.galeri-filter-btn').forEach(b => {
        b.classList.remove('active-filter');
        b.style.fontWeight = '400';
    });
    btn.classList.add('active-filter');
    btn.style.fontWeight = '700';

    document.querySelectorAll('.galeri-card-item').forEach(card => {
        if (status === 'all' || card.dataset.status === status) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

/* ── Icon Picker untuk program baru ── */
function pickNewProgIcon(iconClass, btn) {
    document.getElementById('newProgIcon').value = iconClass;
    const grid = btn.closest('.icon-picker-grid');
    grid.querySelectorAll('.icon-pick-btn').forEach(b => b.classList.remove('icon-pick-active'));
    btn.classList.add('icon-pick-active');
}
/* ── Icon Picker untuk keunggulan baru ── */
function pickNewKeungIcon(iconClass, btn) {
    document.getElementById('newKeungIcon').value = iconClass;
    const grid = btn.closest('.icon-picker-grid');
    grid.querySelectorAll('.icon-pick-btn').forEach(b => b.classList.remove('icon-pick-active'));
    btn.classList.add('icon-pick-active');
}
/* ── Toggle edit Program ── */
function toggleProgEdit(tipe) {
    const view = document.getElementById('prog-view-' + tipe);
    const form = document.getElementById('prog-edit-' + tipe);
    const isHidden = form.style.display === 'none';
    form.style.display = isHidden ? 'block' : 'none';
    /* tombol edit visual feedback */
    view.querySelectorAll('.btn-secondary').forEach(b => {
        b.style.background = isHidden ? '#6b1a1a' : '';
        b.style.color       = isHidden ? '#fde8a0' : '';
    });
}

/* ── Toggle edit Keunggulan ── */
function toggleKeungEdit(tipe) {
    const view = document.getElementById('keung-view-' + tipe);
    const form = document.getElementById('keung-edit-' + tipe);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

/* ── Icon picker — Program baru ── */
function pickNewProgIcon(iconClass, btn) {
    document.getElementById('newProgIcon').value = iconClass;
    btn.closest('.icon-picker-grid')
       .querySelectorAll('.icon-pick-btn')
       .forEach(b => b.classList.remove('icon-pick-active'));
    btn.classList.add('icon-pick-active');
}

/* ── Icon picker — Program edit ── */
function pickEditProgIcon(tipe, iconClass, btn) {
    document.getElementById('edit-prog-icon-' + tipe).value = iconClass;
    btn.closest('.icon-picker-grid')
       .querySelectorAll('.icon-pick-btn')
       .forEach(b => b.classList.remove('icon-pick-active'));
    btn.classList.add('icon-pick-active');
    /* update preview icon di baris tampilan normal */
    const disp = document.getElementById('prog-icon-display-' + tipe);
    if (disp) { disp.className = iconClass; }
}

/* ── Icon picker — Keunggulan baru ── */
function pickNewKeungIcon(iconClass, btn) {
    document.getElementById('newKeungIcon').value = iconClass;
    btn.closest('.icon-picker-grid')
       .querySelectorAll('.icon-pick-btn')
       .forEach(b => b.classList.remove('icon-pick-active'));
    btn.classList.add('icon-pick-active');
}

/* ── Icon picker — Keunggulan edit ── */
function pickEditKeungIcon(tipe, iconClass, btn) {
    document.getElementById('edit-keung-icon-' + tipe).value = iconClass;
    btn.closest('.icon-picker-grid')
       .querySelectorAll('.icon-pick-btn')
       .forEach(b => b.classList.remove('icon-pick-active'));
    btn.classList.add('icon-pick-active');
    const disp = document.getElementById('keung-icon-display-' + tipe);
    if (disp) { disp.className = iconClass + ' '; disp.style.color = '#fde8a0'; disp.style.fontSize = '.85rem'; }
}
</script>

<style>
/* ─── Program list admin ─── */
.prog-admin-list { display:flex; flex-direction:column; gap:1rem; }
.prog-admin-item {
    display:flex; align-items:center; gap:1rem;
    background:linear-gradient(135deg,#fffbef,#fdf6e8);
    border:1px solid rgba(212,160,23,.35); border-radius:14px;
    padding:1rem 1.2rem; position:relative;
}
.prog-admin-item::before {
    content:''; position:absolute; top:0; left:0; right:0; height:3px;
    background:linear-gradient(to right,#6b1a1a,#d4a017);
    border-radius:14px 14px 0 0;
}
.prog-circle-num {
    width:36px; height:36px; flex-shrink:0;
    background:linear-gradient(135deg,#6b1a1a,#8b2d2d); color:#fde8a0;
    border-radius:50%; display:flex; align-items:center; justify-content:center;
    font-size:.8rem; font-weight:700;
}
.prog-icon-preview {
    width:44px; height:44px; flex-shrink:0;
    background:linear-gradient(135deg,#6b1a1a,#8b2d2d); border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:1.1rem; color:#fde8a0;
}
.prog-fields { display:flex; gap:.75rem; flex-wrap:wrap; }
/* ─── Icon Picker (form program baru) ─── */
.icon-picker-grid { display:flex; flex-wrap:wrap; gap:.4rem; }
.icon-pick-btn {
    width:44px; height:44px;
    border:1.5px solid rgba(107,26,26,.15); border-radius:8px;
    background:#fff; display:flex; flex-direction:column;
    align-items:center; justify-content:center; gap:.1rem;
    cursor:pointer; transition:all .2s; padding:0;
}
.icon-pick-btn i    { font-size:.9rem; color:#6b1a1a; }
.icon-pick-btn span { font-size:.5rem; color:#888; line-height:1; }
.icon-pick-btn:hover { border-color:#d4a017; background:#fffbef; }
.icon-pick-active { border-color:#6b1a1a !important; background:linear-gradient(135deg,#6b1a1a,#8b2d2d) !important; }
.icon-pick-active i    { color:#fde8a0 !important; }
.icon-pick-active span { color:rgba(255,255,255,.7) !important; }
</style>

<script>
function balasWAAdmin(id, noHp, nama, btn, createdAt) {
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

    // Gunakan GET ke route sederhana yang pasti tidak diblokir hosting
    fetch(`/balas-pesan/${id}`, {
        method: 'GET',
        headers: { 'Accept': 'application/json' },
    })
    .then(res => {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
    })
    .then(data => {
        // Buka WhatsApp
        window.open(
            `https://wa.me/62${noHp}?text=Halo+${nama}%2C+kami+dari+Pondok+Pesantren+Ash-Shiddiqin.`,
            '_blank'
        );
        if (data.ok) {
            const created = new Date(createdAt);
            const dibalasAt = data.dibalas_at ? new Date(data.dibalas_at) : new Date();
            const menit = Math.floor((dibalasAt - created) / 60000);
            let respons;
            if (menit < 60)        { respons = menit + ' menit'; }
            else if (menit < 1440) { respons = (menit/60).toFixed(1) + ' jam'; }
            else                   { respons = (menit/1440).toFixed(1) + ' hari'; }
            const color = menit <= 30 ? '#2e7d32' : (menit <= 120 ? '#f59e0b' : '#c62828');

            const waktuCell = document.getElementById('waktu-' + id);
            if (waktuCell) {
                waktuCell.innerHTML = `<span style="font-weight:600;color:${color};">⚡ ${respons}</span>`;
            }
            const statusCell = document.getElementById('status-' + id);
            if (statusCell) {
                statusCell.innerHTML = '<span style="color:#2e7d32;font-size:.8rem;font-weight:600;">✅ Sudah Dibalas</span>';
            }
            const row = document.getElementById('row-' + id);
            if (row) { row.style.background = ''; row.style.fontWeight = ''; }
        }
        btn.disabled = false;
        btn.innerHTML = '<i class="fab fa-whatsapp"></i> Balas WA';
    })
    .catch(err => {
        console.error('Balas WA error:', err);
        // Tetap buka WA meski gagal simpan
        window.open(
            `https://wa.me/62${noHp}?text=Halo+${nama}%2C+kami+dari+Pondok+Pesantren+Ash-Shiddiqin.`,
            '_blank'
        );
        btn.disabled = false;
        btn.innerHTML = '<i class="fab fa-whatsapp"></i> Balas WA';
        alert('Gagal menyimpan ke database: ' + err.message + '\nCek Console untuk detail.');
    });
}
</script>
</body>
</html>