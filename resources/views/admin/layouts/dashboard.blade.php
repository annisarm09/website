<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pimpinan Dashboard – Ash-Shiddiqin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pimpinan.css') }}">
    <style>
        /* ── Tambahan gaya untuk fitur baru ── */
        .laporan-section { margin-bottom: 1.8rem; }
        .laporan-section-title {
            font-size: 1rem; font-weight: 700; color: #1a4731;
            padding: .6rem 1rem; background: #f0faf4;
            border-left: 4px solid #2e7d32; border-radius: 0 6px 6px 0;
            margin-bottom: 1rem; display: flex; align-items: center; gap: .5rem;
        }
        .stat-mini-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(140px,1fr));
            gap: .8rem; margin-bottom: 1.2rem;
        }
        .stat-mini {
            background: #fff; border: 1px solid #e5e7eb; border-radius: 10px;
            padding: .9rem 1rem; text-align: center;
        }
        .stat-mini .val { font-size: 1.6rem; font-weight: 800; color: #1a4731; }
        .stat-mini .lbl { font-size: .75rem; color: #6b7280; margin-top: .2rem; }
        .chart-card {
            background: #fff; border: 1px solid #e5e7eb; border-radius: 12px;
            padding: 1.2rem 1.4rem; margin-bottom: 1.2rem;
        }
        .chart-card h4 {
            font-size: .9rem; font-weight: 700; color: #374151;
            margin-bottom: 1rem; display: flex; align-items: center; gap: .4rem;
        }
        .chart-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; }
        @media(max-width:768px){ .chart-grid { grid-template-columns: 1fr; } }

        /* Status badges */
        .badge-pending  { background:#fff8e1; color:#f59e0b; padding:.25rem .7rem; border-radius:15px; font-size:.78rem; font-weight:600; }
        .badge-approved { background:#e8f5e9; color:#2e7d32; padding:.25rem .7rem; border-radius:15px; font-size:.78rem; font-weight:600; }
        .badge-rejected { background:#ffebee; color:#c62828; padding:.25rem .7rem; border-radius:15px; font-size:.78rem; font-weight:600; }
        .badge-baru     { background:#ffebee; color:#c62828; padding:.25rem .7rem; border-radius:15px; font-size:.78rem; font-weight:600; }
        .badge-dibaca   { background:#e8f5e9; color:#2e7d32; padding:.25rem .7rem; border-radius:15px; font-size:.78rem; font-weight:600; }

        /* Tabel laporan */
        .tbl-laporan { width:100%; border-collapse: collapse; font-size:.88rem; }
        .tbl-laporan thead tr { background: #2e7d32; color:#fff; }
        .tbl-laporan thead th { padding: .7rem 1rem; text-align:left; font-weight:600; }
        .tbl-laporan tbody tr { border-bottom: 1px solid #f3f4f6; }
        .tbl-laporan tbody tr:hover { background: #f9fafb; }
        .tbl-laporan td { padding: .65rem 1rem; vertical-align: middle; }
        .tbl-laporan tbody tr:nth-child(even) td { background: #fafafa; }

        /* Ikon status warna */
        .icon-approved { color: #2e7d32; }
        .icon-pending  { color: #f59e0b; }
        .icon-rejected { color: #c62828; }

        /* Dashboard Overview cards */
        .overview-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.8rem;
        }
        @media(max-width: 900px) {
            .overview-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media(max-width: 480px) {
            .overview-grid { grid-template-columns: 1fr 1fr; }
        }
        .ov-card {
            background: #fff; border: 1px solid #e5e7eb; border-radius: 12px;
            padding: 1.1rem 1.2rem; display: flex; flex-direction: column; gap: .5rem;
        }
        .ov-card .ov-icon {
            width: 42px; height: 42px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; margin-bottom: .3rem;
        }
        .ov-card .ov-val { font-size: 1.8rem; font-weight: 800; color: #111; }
        .ov-card .ov-lbl { font-size: .78rem; color: #6b7280; }
        .ov-icon-green  { background: #e8f5e9; color: #2e7d32; }
        .ov-icon-amber  { background: #fff8e1; color: #f59e0b; }
        .ov-icon-blue   { background: #e3f2fd; color: #1565c0; }
        .ov-icon-red    { background: #ffebee; color: #c62828; }
        .ov-icon-purple { background: #ede7f6; color: #6a1b9a; }
        .ov-icon-teal   { background: #e0f2f1; color: #00695c; }
    </style>
</head>
<body>

{{-- HEADER --}}
<header class="header">
    <div class="header-brand">
        <div class="brand-icon" style="background:linear-gradient(135deg,#d4a017,#f4d03f);">
            <i class="fas fa-crown" style="color:#fff;"></i>
        </div>
        Pimpinan Dashboard – Ash-Shiddiqin
    </div>
    <div class="header-right">
        <div class="user-badge">
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}</div>
            <span>{{ Auth::user()->name ?? 'Pimpinan' }}</span>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </div>
</header>

<div class="layout">

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-label">Menu Pimpinan</div>

        {{-- 1. Dashboard (baru) --}}
        <button class="nav-item {{ session('active_tab','dashboard') == 'dashboard' ? 'active' : '' }}"
                onclick="showTab('dashboard', this)">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </button>

        {{-- 2. Approval Konten --}}
        <button class="nav-item {{ session('active_tab') == 'approval' ? 'active' : '' }}"
                onclick="showTab('approval', this)">
            <i class="fas fa-check-double"></i> Approval Konten
            @if(($totalPendingGabung ?? ($totalPending + ($totalGaleriPending ?? 0))) > 0)
                <span class="badge-count">{{ $totalPendingGabung ?? ($totalPending + ($totalGaleriPending ?? 0)) }}</span>
            @endif
        </button>

        {{-- 3. Pesan Prioritas (dipindah ke sini, sesudah approval) --}}
        <button class="nav-item {{ session('active_tab') == 'pesan' ? 'active' : '' }}"
                onclick="showTab('pesan', this)">
            <i class="fas fa-inbox"></i> Pesan Prioritas
            @if($pesanList->count() > 0)
                <span class="badge-count">{{ $pesanList->count() }}</span>
            @endif
        </button>

        {{-- 4. Laporan (dipindah ke bawah) --}}
        <button class="nav-item {{ session('active_tab') == 'laporan' ? 'active' : '' }}"
                onclick="showTab('laporan', this)">
            <i class="fas fa-chart-bar"></i> Laporan
        </button>

        <div class="sidebar-label">Akses Cepat</div>
        <a href="{{ route('dashboard') }}" class="nav-item" style="text-decoration:none;" target="_blank">
            <i class="fas fa-external-link-alt"></i> Lihat Website
        </a>
    </aside>

    {{-- MAIN --}}
    <main class="main">

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        {{-- TAB: DASHBOARD --}}
        <div id="tab-dashboard" class="tab-panel {{ session('active_tab','dashboard') == 'dashboard' ? 'active' : '' }}">
            <div class="page-header">
                <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
                <p>Ringkasan aktivitas dan statistik Pondok Pesantren Ash-Shiddiqin</p>
            </div>

            {{-- Overview Cards – 2 baris rapi (4 + 3) --}}
            <div class="overview-grid">
                {{-- Baris 1 --}}
                <div class="ov-card">
                    <div class="ov-icon ov-icon-blue"><i class="fas fa-newspaper"></i></div>
                    <div class="ov-val">{{ $totalBerita }}</div>
                    <div class="ov-lbl">Total Berita</div>
                </div>
                <div class="ov-card">
                    <div class="ov-icon ov-icon-amber"><i class="fas fa-clock"></i></div>
                    <div class="ov-val">{{ $totalPending }}</div>
                    <div class="ov-lbl">Menunggu Persetujuan</div>
                </div>
                <div class="ov-card">
                    <div class="ov-icon ov-icon-green"><i class="fas fa-check-circle"></i></div>
                    <div class="ov-val">{{ $totalApproved }}</div>
                    <div class="ov-lbl">Konten Disetujui</div>
                </div>
                <div class="ov-card">
                    <div class="ov-icon ov-icon-red"><i class="fas fa-times-circle"></i></div>
                    <div class="ov-val">{{ $totalRejected ?? 0 }}</div>
                    <div class="ov-lbl">Konten Ditolak</div>
                </div>
                {{-- Baris 2 --}}
                <div class="ov-card">
                    <div class="ov-icon ov-icon-teal"><i class="fas fa-envelope"></i></div>
                    <div class="ov-val">{{ $totalPesan }}</div>
                    <div class="ov-lbl">Total Pesan Masuk</div>
                </div>
                <div class="ov-card">
                    <div class="ov-icon ov-icon-red"><i class="fas fa-bell"></i></div>
                    <div class="ov-val">{{ $pesanList->count() }}</div>
                    <div class="ov-lbl">Pesan Belum Dibaca</div>
                </div>
                <div class="ov-card">
                    <div class="ov-icon ov-icon-purple"><i class="fas fa-images"></i></div>
                    <div class="ov-val">{{ $totalGaleri ?? 0 }}</div>
                    <div class="ov-lbl">Total Foto Galeri</div>
                </div>
                {{-- Slot kosong untuk simetri baris ke-2 --}}
                <div class="ov-card" style="background:transparent;border:1px dashed #e5e7eb;visibility:hidden;"></div>
            </div>
            {{-- Grafik --}}
            <div class="chart-grid">
                <div class="chart-card">
                    <h4><i class="fas fa-chart-bar" style="color:#2e7d32;"></i> Jumlah Berita per Bulan</h4>
                    <canvas id="chartBeritaBulan" height="200"></canvas>
                </div>
                <div class="chart-card">
                    <h4><i class="fas fa-chart-line" style="color:#1565c0;"></i> Pesan Masuk per Minggu</h4>
                    <canvas id="chartPesanMinggu" height="200"></canvas>
                </div>
            </div>

            {{-- Ringkasan Status — 3 Kartu Sejajar --}}
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.2rem;margin-bottom:1.2rem;">

                {{-- 1. Status Berita --}}
                <div class="chart-card" style="margin-bottom:0;">
                    <h4><i class="fas fa-chart-pie" style="color:#6a1b9a;"></i> Status Berita Keseluruhan</h4>
                    <div style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;">
                        <div style="width:140px;height:140px;flex-shrink:0;">
                            <canvas id="chartStatusBerita"></canvas>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:.5rem;">
                            <div style="display:flex;align-items:center;gap:.5rem;">
                                <span style="width:12px;height:12px;border-radius:50%;background:#2e7d32;display:inline-block;flex-shrink:0;"></span>
                                <span style="font-size:.83rem;">Disetujui: <strong>{{ $totalApproved }}</strong></span>
                            </div>
                            <div style="display:flex;align-items:center;gap:.5rem;">
                                <span style="width:12px;height:12px;border-radius:50%;background:#f59e0b;display:inline-block;flex-shrink:0;"></span>
                                <span style="font-size:.83rem;">Menunggu: <strong>{{ $totalPending }}</strong></span>
                            </div>
                            <div style="display:flex;align-items:center;gap:.5rem;">
                                <span style="width:12px;height:12px;border-radius:50%;background:#c62828;display:inline-block;flex-shrink:0;"></span>
                                <span style="font-size:.83rem;">Ditolak: <strong>{{ $totalRejected ?? 0 }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Status Galeri --}}
                <div class="chart-card" style="margin-bottom:0;">
                    <h4><i class="fas fa-images" style="color:#6a1b9a;"></i> Status Galeri Keseluruhan</h4>
                    <div style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;">
                        <div style="width:140px;height:140px;flex-shrink:0;">
                            <canvas id="chartStatusGaleri"></canvas>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:.5rem;">
                            <div style="display:flex;align-items:center;gap:.5rem;">
                                <span style="width:12px;height:12px;border-radius:50%;background:#6a1b9a;display:inline-block;flex-shrink:0;"></span>
                                <span style="font-size:.83rem;">Total Foto: <strong>{{ $totalGaleri ?? 0 }}</strong></span>
                            </div>
                            <div style="display:flex;align-items:center;gap:.5rem;">
                                <span style="width:12px;height:12px;border-radius:50%;background:#2e7d32;display:inline-block;flex-shrink:0;"></span>
                                <span style="font-size:.83rem;">Disetujui: <strong>{{ $totalGaleriApproved ?? 0 }}</strong></span>
                            </div>
                            <div style="display:flex;align-items:center;gap:.5rem;">
                                <span style="width:12px;height:12px;border-radius:50%;background:#f59e0b;display:inline-block;flex-shrink:0;"></span>
                                <span style="font-size:.83rem;">Menunggu: <strong>{{ $totalGaleriPending ?? 0 }}</strong></span>
                            </div>
                            <div style="display:flex;align-items:center;gap:.5rem;">
                                <span style="width:12px;height:12px;border-radius:50%;background:#c62828;display:inline-block;flex-shrink:0;"></span>
                                <span style="font-size:.83rem;">Ditolak: <strong>{{ $totalGaleriRejected ?? 0 }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. Status Pesan --}}
                <div class="chart-card" style="margin-bottom:0;">
                    <h4><i class="fas fa-envelope" style="color:#00695c;"></i> Status Pesan Masuk</h4>
                    <div style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;">
                        <div style="width:140px;height:140px;flex-shrink:0;">
                            <canvas id="chartStatusPesan"></canvas>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:.5rem;">
                            <div style="display:flex;align-items:center;gap:.5rem;">
                                <span style="width:12px;height:12px;border-radius:50%;background:#00695c;display:inline-block;flex-shrink:0;"></span>
                                <span style="font-size:.83rem;">Total Pesan: <strong>{{ $totalPesan }}</strong></span>
                            </div>
                            <div style="display:flex;align-items:center;gap:.5rem;">
                                <span style="width:12px;height:12px;border-radius:50%;background:#c62828;display:inline-block;flex-shrink:0;"></span>
                                <span style="font-size:.83rem;">Belum Dibaca: <strong>{{ $pesanList->count() }}</strong></span>
                            </div>
                            <div style="display:flex;align-items:center;gap:.5rem;">
                                <span style="width:12px;height:12px;border-radius:50%;background:#2e7d32;display:inline-block;flex-shrink:0;"></span>
                                <span style="font-size:.83rem;">Sudah Dibaca: <strong>{{ $totalPesan - $pesanList->count() }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            {{-- Responsive style 3 kolom --}}
            <style>
                @media(max-width:900px){
                    .status-3grid { grid-template-columns: 1fr !important; }
                }
            </style>
        </div>

        {{-- TAB: APPROVAL --}}
        <div id="tab-approval" class="tab-panel {{ session('active_tab') == 'approval' ? 'active' : '' }}">
            <div class="page-header">
                <h1><i class="fas fa-tasks"></i> Menunggu Persetujuan</h1>
                <p>Konten yang dikirim admin dan menunggu persetujuan Pimpinan</p>
            </div>

            <div class="card">
                <div class="card-title">
                    <i class="fas fa-check-double"></i>
                    Daftar Konten Pending ({{ $approvalList->count() + ($galeriApprovalList->count() ?? 0) }})
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipe</th>
                                <th>Judul Konten</th>
                                <th>Kategori</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Preview</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($approvalList as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="badge-kategori">Berita</span></td>
                                <td><strong>{{ $item->judul_berita }}</strong></td>
                                <td>{{ ucfirst($item->kategori) }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                <td>
                                    <span class="badge-pending">
                                        <i class="fas fa-circle icon-pending" style="font-size:.6rem;"></i>
                                        Pending
                                    </span>
                                </td>
                                <td>
                                    <button class="btn-preview"
                                            onclick="bukaPreviewById({{ $item->id }})">
                                        <i class="fas fa-eye"></i> Baca Isi
                                    </button>
                                </td>
                                <td>
                                    <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                                        <form action="{{ route('pimpinan.approve', $item->id) }}" method="POST"
                                              onsubmit="return confirm('Setujui dan publikasikan berita ini?')">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-primary btn-sm" title="Setujui">
                                                <i class="fas fa-check"></i> Setujui
                                            </button>
                                        </form>
                                        <button class="btn btn-danger btn-sm"
                                                onclick="bukaModalTolakById({{ $item->id }}, beritaData[{{ $item->id }}]?.judul ?? '')"
                                                title="Tolak">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            @endforelse

                            {{-- ── GALERI PENDING ── --}}
                            @forelse($galeriApprovalList ?? [] as $item)
                            <tr>
                                <td>{{ $approvalList->count() + $loop->iteration }}</td>
                                <td>
                                    <span class="badge-kategori" style="background:#ede7f6;color:#6a1b9a;">
                                        <i class="fas fa-image" style="margin-right:.2rem;"></i>Galeri
                                    </span>
                                </td>
                                <td><strong>{{ $item->judul }}</strong></td>
                                <td>{{ ucfirst($item->kategori) }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                                <td>
                                    <span class="badge-pending">
                                        <i class="fas fa-circle icon-pending" style="font-size:.6rem;"></i>
                                        Pending
                                    </span>
                                </td>
                                <td>
                                    <button class="btn-preview"
                                            onclick="bukaPreviewGaleri({{ $item->id }}, '{{ addslashes($item->judul) }}', '{{ asset('storage/'.$item->src) }}', '{{ $item->kategori }}', '{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}')">
                                        <i class="fas fa-eye"></i> Lihat Foto
                                    </button>
                                </td>
                                <td>
                                    <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                                        <form action="{{ route('pimpinan.galeri.approve', $item->id) }}" method="POST"
                                              onsubmit="return confirm('Setujui dan publikasikan foto ini?')">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-primary btn-sm" title="Setujui">
                                                <i class="fas fa-check"></i> Setujui
                                            </button>
                                        </form>
                                        <button class="btn btn-danger btn-sm"
                                                onclick="bukaModalTolakGaleriById({{ $item->id }}, '{{ addslashes($item->judul) }}')"
                                                title="Tolak">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            @endforelse

                            {{-- Empty state jika keduanya kosong --}}
                            @if($approvalList->count() == 0 && ($galeriApprovalList ?? collect())->count() == 0)
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fas fa-check-double"></i>
                                        <p>Tidak ada konten yang menunggu persetujuan.</p>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Riwayat Konten: sudah disetujui / ditolak --}}
            <div class="card" style="margin-top:1.5rem;">
                <div class="card-title">
                    <i class="fas fa-history"></i>
                    Riwayat Konten (Disetujui &amp; Ditolak)
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipe</th>
                                <th>Judul Konten</th>
                                <th>Kategori</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Tgl Keputusan</th>
                                <th>Preview</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporanList as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="badge-kategori">Berita</span></td>
                                <td><strong>{{ $item->judul_berita }}</strong></td>
                                <td>{{ ucfirst($item->kategori) }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                <td>
                                    @if($item->status == 'published')
                                        <span class="badge-approved">
                                            <i class="fas fa-circle icon-approved" style="font-size:.55rem;"></i>
                                            Disetujui
                                        </span>
                                    @elseif($item->status == 'rejected')
                                        <span class="badge-rejected">
                                            <i class="fas fa-circle icon-rejected" style="font-size:.55rem;"></i>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td style="font-size:.82rem;color:#888;">
                                    {{ $item->updated_at->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    <button class="btn-preview"
                                            onclick="bukaPreviewById({{ $item->id }})">
                                        <i class="fas fa-eye"></i> Lihat Isi
                                    </button>
                                </td>
                            </tr>
                            @empty
                            @endforelse

                            {{-- Riwayat Galeri --}}
                            @forelse($galeriLaporanApproval ?? [] as $item)
                            <tr>
                                <td>{{ $laporanList->count() + $loop->iteration }}</td>
                                <td>
                                    <span class="badge-kategori" style="background:#ede7f6;color:#6a1b9a;">
                                        <i class="fas fa-image" style="margin-right:.2rem;"></i>Galeri
                                    </span>
                                </td>
                                <td><strong>{{ $item->judul }}</strong></td>
                                <td>{{ ucfirst($item->kategori) }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                                <td>
                                    @if($item->status == 'published')
                                        <span class="badge-approved">
                                            <i class="fas fa-circle icon-approved" style="font-size:.55rem;"></i>
                                            Disetujui
                                        </span>
                                    @elseif($item->status == 'rejected')
                                        <span class="badge-rejected">
                                            <i class="fas fa-circle icon-rejected" style="font-size:.55rem;"></i>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td style="font-size:.82rem;color:#888;">
                                    {{ $item->updated_at->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    <button class="btn-preview"
                                            onclick="bukaPreviewGaleri({{ $item->id }}, '{{ addslashes($item->judul) }}', '{{ asset('storage/'.$item->src) }}', '{{ $item->kategori }}', '{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}')">
                                        <i class="fas fa-eye"></i> Lihat Foto
                                    </button>
                                </td>
                            </tr>
                            @empty
                            @endforelse

                            @if($laporanList->count() == 0 && ($galeriLaporanApproval ?? collect())->count() == 0)
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fas fa-history"></i>
                                        <p>Belum ada riwayat konten yang diproses.</p>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- TAB: PESAN PRIORITAS--}}
        <div id="tab-pesan" class="tab-panel {{ session('active_tab') == 'pesan' ? 'active' : '' }}">
            <div class="page-header">
                <h1><i class="fas fa-inbox"></i> Pesan Prioritas</h1>
                <p>Pesan dari pengunjung yang belum dibaca</p>
            </div>

            <div class="card">
                <div class="card-title">
                    <i class="fas fa-star"></i> Pesan Belum Dibaca ({{ $pesanList->count() }})
                </div>

                @forelse($pesanList as $p)
                <div class="laporan-item" style="border-left-color:#dc3545;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:.5rem;margin-bottom:.8rem;">
                        <div>
                            <strong style="font-size:1rem;">{{ $p->nama }}</strong>
                            <span class="badge-baru" style="margin-left:.5rem;">
                                <i class="fas fa-circle" style="font-size:.5rem;"></i> Baru
                            </span>
                        </div>
                        <span style="font-size:.82rem;color:#888;">
                            <i class="fas fa-calendar"></i>
                            {{ $p->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>

                    <p style="color:#333;margin-bottom:.8rem;">{{ $p->pesan }}</p>

                    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.5rem;">
                        <div style="font-size:.88rem;color:#666;">
                            <i class="fas fa-mobile-alt"></i> {{ $p->kontak }}
                        </div>
                        <div style="display:flex;gap:.4rem;">
                            <form action="{{ route('pimpinan.pesan.baca', $p->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-secondary btn-sm">
                                    <i class="fas fa-check"></i> Sudah Dibaca
                                </button>
                            </form>
                            @if(is_numeric(preg_replace('/\D/', '', $p->kontak)))
                            {{-- Tombol Balas WA — AJAX supaya dibalas_at tersimpan ke DB --}}
                            <button type="button"
                                    class="btn btn-sm"
                                    style="background:#25d366;color:#fff;border:none;padding:.4rem .9rem;border-radius:6px;font-size:.83rem;cursor:pointer;"
                                    onclick="balasPimpinan({{ $p->id }}, '{{ ltrim(preg_replace('/\D/','',$p->kontak),'0') }}', '{{ urlencode($p->nama) }}', this, '{{ $p->created_at->toIso8601String() }}')">
                                <i class="fab fa-whatsapp"></i> Balas WA
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Tidak ada pesan baru yang perlu ditinjau.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{--TAB: LAPORAN --}}
        <div id="tab-laporan" class="tab-panel {{ session('active_tab') == 'laporan' ? 'active' : '' }}">
            <div class="page-header">
                <h1><i class="fas fa-chart-bar"></i> Laporan Aktivitas</h1>
                <p>Statistik lengkap berita, pesan masuk, dan galeri</p>
            </div>

            {{-- ── BAGIAN 4: LAPORAN KUNJUNGAN WEBSITE ── --}}
            <div class="card laporan-section">
                <div class="laporan-section-title">
                    <i class="fas fa-eye"></i> Laporan Kunjungan Website
                </div>

                <div class="stat-mini-grid">
                    <div class="stat-mini" style="border-top:3px solid #0277bd;">
                        <div class="val" style="color:#0277bd;" id="visitTotalBulanIni">–</div>
                        <div class="lbl">Kunjungan Bulan Ini</div>
                    </div>
                    <div class="stat-mini" style="border-top:3px solid #2e7d32;">
                        <div class="val" style="color:#2e7d32;" id="visitTotalMingguIni">–</div>
                        <div class="lbl">Kunjungan Minggu Ini</div>
                    </div>
                    <div class="stat-mini" style="border-top:3px solid #6a1b9a;">
                        <div class="val" style="color:#6a1b9a;" id="visitTotalHariIni">–</div>
                        <div class="lbl">Kunjungan Hari Ini</div>
                    </div>
                    <div class="stat-mini" style="border-top:3px solid #f59e0b;">
                        <div class="val" style="color:#f59e0b;" id="visitTotalKeseluruhan">–</div>
                        <div class="lbl">Total Semua Kunjungan</div>
                    </div>
                </div>

                {{-- Grafik kunjungan per bulan --}}
                <div class="chart-grid" style="margin-bottom:1.2rem;">
                    <div class="chart-card">
                        <h4><i class="fas fa-chart-bar" style="color:#0277bd;"></i> Kunjungan per Bulan (Tahun Ini)</h4>
                        <canvas id="chartVisitBulan" height="130"></canvas>
                    </div>
                    <div class="chart-card">
                        <h4><i class="fas fa-chart-line" style="color:#2e7d32;"></i> Kunjungan per Minggu (4 Minggu Terakhir)</h4>
                        <canvas id="chartVisitMinggu" height="130"></canvas>
                    </div>
                </div>

                {{-- Halaman yang paling banyak dikunjungi --}}
                <div style="margin-bottom:.8rem;">
                    <strong style="font-size:.9rem;color:#374151;">Halaman Paling Banyak Dikunjungi</strong>
                </div>
                <table class="tbl-laporan" style="margin-bottom:1.4rem;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Halaman</th>
                            <th>URL</th>
                            <th>Kunjungan</th>
                            <th>Persentase</th>
                        </tr>
                    </thead>
                    <tbody id="tblHalamanPopuler">
                        <tr>
                            <td colspan="5" style="text-align:center;color:#888;padding:1.5rem;">
                                <i class="fas fa-spinner fa-spin"></i> Memuat data...
                            </td>
                        </tr>
                    </tbody>
                </table>

                {{-- Riwayat log kunjungan harian --}}
                <div style="margin-bottom:.8rem;">
                    <strong style="font-size:.9rem;color:#374151;">Log Kunjungan Harian (30 Hari Terakhir)</strong>
                </div>
                <table class="tbl-laporan">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Jumlah Kunjungan</th>
                            <th>Tren</th>
                        </tr>
                    </thead>
                    <tbody id="tblLogHarian">
                        <tr>
                            <td colspan="4" style="text-align:center;color:#888;padding:1.5rem;">
                                <i class="fas fa-spinner fa-spin"></i> Memuat data...
                            </td>
                        </tr>
                    </tbody>
                </table>

                <p style="font-size:.78rem;color:#9ca3af;margin-top:1rem;">
                    <i class="fas fa-info-circle"></i>
                    Data kunjungan diambil dari tabel <code>page_visits</code>.
                    Pastikan middleware <code>RecordPageVisit</code> sudah terpasang di route publik.
                    Lihat petunjuk integrasi di bagian bawah halaman ini.
                </p>
            </div>

            {{-- ── BAGIAN 2: LAPORAN PESAN MASUK ── --}}
            <div class="card laporan-section">
                <div class="laporan-section-title">
                    <i class="fas fa-envelope"></i> Laporan Pesan Masuk
                </div>

                <div class="stat-mini-grid">
                    <div class="stat-mini">
                        <div class="val">{{ $totalPesan }}</div>
                        <div class="lbl">Total Pesan</div>
                    </div>
                    <div class="stat-mini" style="border-top:3px solid #c62828;">
                        <div class="val" style="color:#c62828;">{{ $pesanList->count() }}</div>
                        <div class="lbl">Belum Dibaca</div>
                    </div>
                    <div class="stat-mini" style="border-top:3px solid #2e7d32;">
                        <div class="val" style="color:#2e7d32;">{{ $totalPesan - $pesanList->count() }}</div>
                        <div class="lbl">Sudah Dibaca</div>
                    </div>
                </div>

                {{-- Grafik pesan per bulan --}}
                <div class="chart-card" style="margin-bottom:1.2rem;">
                    <h4><i class="fas fa-chart-line" style="color:#1565c0;"></i> Pesan Masuk per Bulan (Tahun Ini)</h4>
                    <canvas id="chartPesanBulanLaporan" height="120"></canvas>
                </div>

                <table class="tbl-laporan">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Pengirim</th>
                            <th>Kontak</th>
                            <th>Pesan (ringkasan)</th>
                            <th>Tanggal Kirim</th>
                            <th>Waktu Respons</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allPesanLaporan ?? [] as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $p->nama }}</strong></td>
                            <td style="font-size:.85rem;">{{ $p->kontak }}</td>
                            <td style="font-size:.85rem;color:#555;max-width:240px;">
                                {{ \Str::limit($p->pesan, 60) }}
                            </td>
                            <td style="font-size:.82rem;">{{ $p->created_at->format('d M Y, H:i') }}</td>
                            <td style="font-size:.82rem;">
                                @if($p->dibalas_at)
                                    @php
                                        $menit = $p->created_at->diffInMinutes($p->dibalas_at);
                                        if ($menit < 60)
                                            $respons = $menit . ' menit';
                                        elseif ($menit < 1440)
                                            $respons = round($menit/60, 1) . ' jam';
                                        else
                                            $respons = round($menit/1440, 1) . ' hari';

                                        $color = $menit <= 30  ? '#2e7d32'
                                               : ($menit <= 120 ? '#f59e0b'
                                               : '#c62828');
                                    @endphp
                                    <span style="font-weight:600;color:{{ $color }};">
                                        ⚡ {{ $respons }}
                                    </span>
                                @else
                                    <span style="color:#9ca3af;font-size:.8rem;">— Belum dibalas</span>
                                @endif
                            </td>
                            <td>
                                @if($p->status === 'Sudah Dibalas' || $p->dibalas_at)
                                    <span class="badge-approved">
                                        <i class="fas fa-circle icon-approved" style="font-size:.55rem;"></i>
                                        Sudah Dibalas
                                    </span>
                                @elseif($p->sudah_dibaca)
                                    <span class="badge-dibaca">
                                        <i class="fas fa-circle icon-approved" style="font-size:.55rem;"></i>
                                        Dibaca
                                    </span>
                                @else
                                    <span class="badge-baru">
                                        <i class="fas fa-circle icon-rejected" style="font-size:.55rem;"></i>
                                        Baru
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" style="text-align:center;color:#888;padding:1.5rem;">Belum ada pesan masuk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            
            

        </div>{{-- end tab-laporan --}}

    </main>
</div>

{{-- MODAL: PREVIEW ISI BERITA --}}
<div class="modal-overlay" id="modalPreview">
    <div class="modal-preview">
        <div class="modal-preview-header">
            <h2><i class="fas fa-newspaper"></i> Preview Berita</h2>
            <button class="modal-close-btn" onclick="tutupPreview()" title="Tutup">✕</button>
        </div>
        <div class="modal-preview-body">
            <img id="previewFoto" src="" alt="Foto Berita" style="display:none;">
            <h1 id="previewJudul"></h1>
            <div class="modal-meta">
                <span><i class="fas fa-calendar-alt"></i> <span id="previewTanggal"></span></span>
                <span><i class="fas fa-tag"></i> <span id="previewKategori"></span></span>
                <span class="badge-pending">
                    <i class="fas fa-circle icon-pending" style="font-size:.55rem;"></i>
                    Menunggu Persetujuan
                </span>
            </div>
            <div class="modal-isi" id="previewIsi"></div>
        </div>
        <div class="modal-preview-footer">
            <button class="btn btn-secondary" onclick="tutupPreview()">
                <i class="fas fa-times"></i> Tutup
            </button>
            <button class="btn btn-danger"
                    onclick="tutupPreview(); bukaModalTolakById(currentPreviewId, currentPreviewJudul)">
                <i class="fas fa-times-circle"></i> Tolak
            </button>
            <form id="formApprovePreview" method="POST" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-primary" style="background:#16a34a;"
                        onclick="return confirm('Publikasikan berita ini?')">
                    <i class="fas fa-check-circle"></i> Approve & Publikasikan
                </button>
            </form>
        </div>
    </div>
</div>

{{-- MODAL: TOLAK KONTEN --}}
<div class="modal-overlay" id="modalTolak">
    <div class="modal-tolak">
        <h3><i class="fas fa-times-circle"></i> Tolak Konten</h3>
        <p style="color:#555;margin-bottom:.25rem;">
            Konten: <strong id="modalTolakJudul"></strong>
        </p>
        <form id="formTolak" method="POST">
            @csrf @method('PATCH')
            <label style="font-weight:600;display:block;margin-top:1rem;margin-bottom:.4rem;color:#374151;">
                Catatan Penolakan <span style="color:#9ca3af;font-weight:400;">(opsional)</span>
            </label>
            <textarea name="catatan" placeholder="Tulis alasan penolakan untuk admin..."></textarea>
            <div class="modal-actions">
                <button type="button" onclick="tutupModalTolak()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-times"></i> Tolak Konten
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: PREVIEW FOTO GALERI --}}
<div class="modal-overlay" id="modalPreviewGaleri" style="display:none;">
    <div class="modal-preview" style="max-width:680px;">
        <div class="modal-preview-header">
            <h2><i class="fas fa-images" style="color:#6a1b9a;"></i> Preview Foto Galeri</h2>
            <button class="modal-close-btn" onclick="tutupPreviewGaleri()" title="Tutup">✕</button>
        </div>
        <div class="modal-preview-body" style="text-align:center;">
            <img id="previewGaleriFoto" src="" alt="Foto Galeri"
                 style="max-width:100%;max-height:420px;border-radius:10px;margin-bottom:1rem;object-fit:contain;">
            <h1 id="previewGaleriJudul" style="font-size:1.15rem;margin-bottom:.5rem;"></h1>
            <div class="modal-meta" style="justify-content:center;">
                <span><i class="fas fa-calendar-alt"></i> <span id="previewGaleriTanggal"></span></span>
                <span><i class="fas fa-tag"></i> <span id="previewGaleriKategori"></span></span>
                <span class="badge-pending">
                    <i class="fas fa-circle icon-pending" style="font-size:.55rem;"></i>
                    Menunggu Persetujuan
                </span>
            </div>
        </div>
        <div class="modal-preview-footer">
            <button class="btn btn-secondary" onclick="tutupPreviewGaleri()">
                <i class="fas fa-times"></i> Tutup
            </button>
            <button class="btn btn-danger"
                    onclick="tutupPreviewGaleri(); bukaModalTolakGaleriById(currentPreviewGaleriId, currentPreviewGaleriJudul)">
                <i class="fas fa-times-circle"></i> Tolak
            </button>
            <form id="formApproveGaleriPreview" method="POST" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-primary" style="background:#16a34a;"
                        onclick="return confirm('Publikasikan foto galeri ini?')">
                    <i class="fas fa-check-circle"></i> Approve & Publikasikan
                </button>
            </form>
        </div>
    </div>
</div>

{{-- MODAL: TOLAK GALERI --}}
<div class="modal-overlay" id="modalTolakGaleri" style="display:none;">
    <div class="modal-tolak">
        <h3><i class="fas fa-times-circle" style="color:#c62828;"></i> Tolak Foto Galeri</h3>
        <p style="color:#555;margin-bottom:.25rem;">
            Foto: <strong id="modalTolakGaleriJudul"></strong>
        </p>
        <form id="formTolakGaleri" method="POST">
            @csrf @method('PATCH')
            <label style="font-weight:600;display:block;margin-top:1rem;margin-bottom:.4rem;color:#374151;">
                Catatan Penolakan <span style="color:#9ca3af;font-weight:400;">(opsional)</span>
            </label>
            <textarea name="catatan" placeholder="Tulis alasan penolakan untuk admin..."
                      style="width:100%;min-height:90px;padding:.6rem .8rem;border:1px solid #d1d5db;border-radius:8px;font-size:.9rem;resize:vertical;"></textarea>
            <div class="modal-actions">
                <button type="button" onclick="tutupModalTolakGaleri()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-times"></i> Tolak Foto
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Data berita untuk modal preview (approval + riwayat) --}}
<script>
    const beritaData = {
        @foreach($approvalList as $item)
        {{ $item->id }}: {
            id:       {{ $item->id }},
            judul:    {!! json_encode($item->judul_berita, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE) !!},
            isi:      {!! json_encode($item->isi_berita,   JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE) !!},
            kategori: {!! json_encode($item->kategori,     JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE) !!},
            tanggal:  {!! json_encode(\Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y'), JSON_UNESCAPED_UNICODE) !!},
            foto:     {!! json_encode($item->foto ? asset('storage/'.$item->foto) : '', JSON_UNESCAPED_UNICODE) !!}
        },
        @endforeach
        @foreach($laporanList as $item)
        {{ $item->id }}: {
            id:       {{ $item->id }},
            judul:    {!! json_encode($item->judul_berita, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE) !!},
            isi:      {!! json_encode($item->isi_berita,   JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE) !!},
            kategori: {!! json_encode($item->kategori,     JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE) !!},
            tanggal:  {!! json_encode(\Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y'), JSON_UNESCAPED_UNICODE) !!},
            foto:     {!! json_encode($item->foto ? asset('storage/'.$item->foto) : '', JSON_UNESCAPED_UNICODE) !!}
        },
        @endforeach
    };

    // ── Data untuk Chart.js (dari PHP → JS) ──
    const chartBeritaPerBulan = {!! json_encode($chartBeritaPerBulan ?? [
        'labels' => ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'],
        'published' => [0,0,0,0,0,0,0,0,0,0,0,0],
        'pending'   => [0,0,0,0,0,0,0,0,0,0,0,0],
        'rejected'  => [0,0,0,0,0,0,0,0,0,0,0,0],
    ]) !!};

    const chartPesanPerMinggu = {!! json_encode($chartPesanPerMinggu ?? [
        'labels'  => ['Minggu 1','Minggu 2','Minggu 3','Minggu 4'],
        'jumlah'  => [0,0,0,0],
    ]) !!};

    const chartPesanPerBulan = {!! json_encode($chartPesanPerBulan ?? [
        'labels' => ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'],
        'jumlah' => [0,0,0,0,0,0,0,0,0,0,0,0],
    ]) !!};

    const statusBeritaData = {
        published: {{ $totalApproved }},
        pending:   {{ $totalPending }},
        rejected:  {{ $totalRejected ?? 0 }},
    };
</script>

<script>window.ACTIVE_TAB = '{{ session("active_tab", "dashboard") }}';</script>
<script src="{{ asset('assets/js/pimpinan.js') }}"></script>
<script>
// Fix: pimpinan.js masih pakai Blade syntax di querySelector → override DOMContentLoaded
document.addEventListener('DOMContentLoaded', function () {
    const activeTab = '{{ session("active_tab", "dashboard") }}';
    document.querySelectorAll('.nav-item').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    const tabEl = document.getElementById('tab-' + activeTab);
    if (tabEl) tabEl.classList.add('active');
    document.querySelectorAll('.nav-item').forEach(btn => {
        if (btn.getAttribute('onclick') && btn.getAttribute('onclick').includes("'" + activeTab + "'")) {
            btn.classList.add('active');
        }
    });
});
</script>

<script>
// ════════════════════════════════════════════════
//  FUNGSI PREVIEW & MODAL GALERI
// ════════════════════════════════════════════════
let currentPreviewGaleriId    = null;
let currentPreviewGaleriJudul = '';

window.bukaPreviewGaleri = function(id, judul, fotoUrl, kategori, tanggal) {
    currentPreviewGaleriId    = id;
    currentPreviewGaleriJudul = judul;

    document.getElementById('previewGaleriFoto').src                 = fotoUrl;
    document.getElementById('previewGaleriJudul').textContent        = judul;
    document.getElementById('previewGaleriKategori').textContent     = kategori.charAt(0).toUpperCase() + kategori.slice(1);
    document.getElementById('previewGaleriTanggal').textContent      = tanggal;

    // Set action form approve (gunakan route Laravel yang benar)
    document.getElementById('formApproveGaleriPreview').action = '/pimpinan/galeri/' + id + '/approve';

    document.getElementById('modalPreviewGaleri').style.display = 'flex';
    document.body.style.overflow = 'hidden';
};

window.tutupPreviewGaleri = function() {
    document.getElementById('modalPreviewGaleri').style.display = 'none';
    document.body.style.overflow = '';
};

window.bukaModalTolakGaleriById = function(id, judul) {
    document.getElementById('modalTolakGaleriJudul').textContent = judul;
    document.getElementById('formTolakGaleri').action = '/pimpinan/galeri/' + id + '/reject';
    document.getElementById('modalTolakGaleri').style.display = 'flex';
    document.body.style.overflow = 'hidden';
};

window.tutupModalTolakGaleri = function() {
    document.getElementById('modalTolakGaleri').style.display = 'none';
    document.body.style.overflow = '';
};

document.addEventListener('DOMContentLoaded', function() {
    // Tutup modal galeri saat klik overlay
    var mgp = document.getElementById('modalPreviewGaleri');
    if (mgp) mgp.addEventListener('click', function(e) { if (e.target === this) tutupPreviewGaleri(); });
    var mtg = document.getElementById('modalTolakGaleri');
    if (mtg) mtg.addEventListener('click', function(e) { if (e.target === this) tutupModalTolakGaleri(); });
});
</script>

<script>
// ── Inisialisasi semua Chart.js setelah DOM siap ──
document.addEventListener('DOMContentLoaded', function () {

    // Warna status
    const C_GREEN  = '#2e7d32';
    const C_AMBER  = '#f59e0b';
    const C_RED    = '#c62828';
    const C_BLUE   = '#1565c0';

    const chartConfig = (labels, datasets, type = 'bar') => ({
        type,
        data: { labels, datasets },
        options: {
            responsive: true,
            plugins: { legend: { display: datasets.length > 1 } },
            scales: type !== 'pie' && type !== 'doughnut' ? {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f3f4f6' } },
                x: { grid: { display: false } }
            } : {}
        }
    });

    // ── 1. Grafik Berita per Bulan (Dashboard tab) ──
    const elBB = document.getElementById('chartBeritaBulan');
    if (elBB) {
        new Chart(elBB, chartConfig(
            chartBeritaPerBulan.labels,
            [
                { label: 'Disetujui', data: chartBeritaPerBulan.published, backgroundColor: C_GREEN + 'cc', borderColor: C_GREEN, borderWidth: 1 },
                { label: 'Pending',   data: chartBeritaPerBulan.pending,   backgroundColor: C_AMBER + 'cc', borderColor: C_AMBER,   borderWidth: 1 },
                { label: 'Ditolak',   data: chartBeritaPerBulan.rejected,  backgroundColor: C_RED   + 'cc', borderColor: C_RED,   borderWidth: 1 },
            ]
        ));
    }

    // ── 2. Grafik Pesan per Minggu (Dashboard tab) ──
    const elPM = document.getElementById('chartPesanMinggu');
    if (elPM) {
        new Chart(elPM, {
            type: 'line',
            data: {
                labels: chartPesanPerMinggu.labels,
                datasets: [{
                    label: 'Pesan Masuk',
                    data: chartPesanPerMinggu.jumlah,
                    borderColor: C_BLUE, backgroundColor: C_BLUE + '22',
                    borderWidth: 2, fill: true, tension: 0.4,
                    pointBackgroundColor: C_BLUE
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f3f4f6' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // ── 3. Pie chart status berita (Dashboard tab) ──
    const elPie = document.getElementById('chartStatusBerita');
    if (elPie) {
        new Chart(elPie, {
            type: 'doughnut',
            data: {
                labels: ['Disetujui', 'Pending', 'Ditolak'],
                datasets: [{
                    data: [statusBeritaData.published, statusBeritaData.pending, statusBeritaData.rejected],
                    backgroundColor: [C_GREEN, C_AMBER, C_RED],
                    borderWidth: 2, borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                cutout: '60%'
            }
        });
    }

    // ── 3b. Pie chart status galeri ──
    const elGaleri = document.getElementById('chartStatusGaleri');
    if (elGaleri) {
        new Chart(elGaleri, {
            type: 'doughnut',
            data: {
                labels: ['Disetujui', 'Menunggu', 'Ditolak'],
                datasets: [{
                    data: [
                        {{ $totalGaleriApproved ?? 0 }},
                        {{ $totalGaleriPending ?? 0 }},
                        {{ $totalGaleriRejected ?? 0 }}
                    ],
                    backgroundColor: [C_GREEN, C_AMBER, C_RED],
                    borderWidth: 2, borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                cutout: '60%'
            }
        });
    }

    // ── 3c. Pie chart status pesan ──
    const elPesan = document.getElementById('chartStatusPesan');
    if (elPesan) {
        const totalPesan    = {{ $totalPesan }};
        const belumDibaca   = {{ $pesanList->count() }};
        const sudahDibaca   = totalPesan - belumDibaca;
        new Chart(elPesan, {
            type: 'doughnut',
            data: {
                labels: ['Sudah Dibaca', 'Belum Dibaca'],
                datasets: [{
                    data: [sudahDibaca, belumDibaca],
                    backgroundColor: [C_GREEN, C_RED],
                    borderWidth: 2, borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                cutout: '60%'
            }
        });
    }

    // ── 4. Grafik Berita per Bulan (Laporan tab) ──
    const elBBL = document.getElementById('chartBeritaBulanLaporan');
    if (elBBL) {
        new Chart(elBBL, chartConfig(
            chartBeritaPerBulan.labels,
            [
                { label: 'Disetujui', data: chartBeritaPerBulan.published, backgroundColor: C_GREEN + 'cc', borderColor: C_GREEN, borderWidth: 1 },
                { label: 'Pending',   data: chartBeritaPerBulan.pending,   backgroundColor: C_AMBER + 'cc', borderColor: C_AMBER,   borderWidth: 1 },
                { label: 'Ditolak',   data: chartBeritaPerBulan.rejected,  backgroundColor: C_RED   + 'cc', borderColor: C_RED,   borderWidth: 1 },
            ]
        ));
    }

    // ── 6. Grafik & Tabel Kunjungan Website ──
    @php
        $chartVisitPerBulan  = $chartVisitPerBulan  ?? ['labels'=>['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'],'jumlah'=>[0,0,0,0,0,0,0,0,0,0,0,0]];
        $chartVisitPerMinggu = $chartVisitPerMinggu ?? ['labels'=>['Minggu 1','Minggu 2','Minggu 3','Minggu 4'],'jumlah'=>[0,0,0,0]];
        $visitStats          = $visitStats          ?? ['hari_ini'=>0,'minggu_ini'=>0,'bulan_ini'=>0,'total'=>0];
        $halamanPopuler      = $halamanPopuler      ?? [];
        $logHarian           = $logHarian           ?? [];
    @endphp
    (function() {
        const visitBulan     = @json($chartVisitPerBulan);
        const visitMinggu    = @json($chartVisitPerMinggu);
        const visitStats     = @json($visitStats);
        const halamanPopuler = @json($halamanPopuler);
        const logHarian      = @json($logHarian);

        // Isi stat cards
        document.getElementById('visitTotalHariIni').textContent      = visitStats.hari_ini ?? 0;
        document.getElementById('visitTotalMingguIni').textContent     = visitStats.minggu_ini ?? 0;
        document.getElementById('visitTotalBulanIni').textContent      = visitStats.bulan_ini ?? 0;
        document.getElementById('visitTotalKeseluruhan').textContent   = visitStats.total ?? 0;

        // Chart kunjungan per bulan
        const elVB = document.getElementById('chartVisitBulan');
        if (elVB) {
            new Chart(elVB, {
                type: 'bar',
                data: {
                    labels: visitBulan.labels,
                    datasets: [{
                        label: 'Kunjungan',
                        data: visitBulan.jumlah,
                        backgroundColor: '#0277bdcc',
                        borderColor: '#0277bd',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f3f4f6' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // Chart kunjungan per minggu
        const elVM = document.getElementById('chartVisitMinggu');
        if (elVM) {
            new Chart(elVM, {
                type: 'line',
                data: {
                    labels: visitMinggu.labels,
                    datasets: [{
                        label: 'Kunjungan',
                        data: visitMinggu.jumlah,
                        borderColor: '#2e7d32',
                        backgroundColor: '#2e7d3222',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#2e7d32'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f3f4f6' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // Tabel halaman populer
        const tblHP = document.getElementById('tblHalamanPopuler');
        if (tblHP) {
            if (halamanPopuler.length === 0) {
                tblHP.innerHTML = '<tr><td colspan="5" style="text-align:center;color:#888;padding:1.5rem;">Belum ada data kunjungan.</td></tr>';
            } else {
                const maxKunjungan = Math.max(...halamanPopuler.map(h => h.total)) || 1;
                tblHP.innerHTML = halamanPopuler.map((h, i) => `
                    <tr>
                        <td>${i + 1}</td>
                        <td><strong>${h.nama ?? h.url ?? '-'}</strong></td>
                        <td style="font-size:.83rem;color:#6b7280;">${h.url ?? '-'}</td>
                        <td><strong>${h.total}</strong></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:.5rem;">
                                <div style="flex:1;background:#e5e7eb;border-radius:4px;height:8px;">
                                    <div style="width:${Math.round((h.total/maxKunjungan)*100)}%;background:#0277bd;height:8px;border-radius:4px;"></div>
                                </div>
                                <span style="font-size:.82rem;min-width:36px;">${Math.round((h.total/maxKunjungan)*100)}%</span>
                            </div>
                        </td>
                    </tr>
                `).join('');
            }
        }

        // Tabel log harian
        const tblLH = document.getElementById('tblLogHarian');
        if (tblLH) {
            if (logHarian.length === 0) {
                tblLH.innerHTML = '<tr><td colspan="4" style="text-align:center;color:#888;padding:1.5rem;">Belum ada data kunjungan harian.</td></tr>';
            } else {
                tblLH.innerHTML = logHarian.map((d, i) => {
                    const prev = logHarian[i + 1]?.total ?? d.total;
                    const tren = d.total > prev
                        ? '<i class="fas fa-arrow-up" style="color:#2e7d32;"></i> Naik'
                        : d.total < prev
                            ? '<i class="fas fa-arrow-down" style="color:#c62828;"></i> Turun'
                            : '<i class="fas fa-minus" style="color:#f59e0b;"></i> Stabil';
                    return `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${d.tanggal}</td>
                            <td><strong>${d.total}</strong></td>
                            <td style="font-size:.85rem;">${tren}</td>
                        </tr>
                    `;
                }).join('');
            }
        }
    })();

    const elPML = document.getElementById('chartPesanBulanLaporan');
    if (elPML) {
        new Chart(elPML, {
            type: 'bar',
            data: {
                labels: chartPesanPerBulan.labels,
                datasets: [{
                    label: 'Pesan Masuk',
                    data: chartPesanPerBulan.jumlah,
                    backgroundColor: C_BLUE + 'cc',
                    borderColor: C_BLUE,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f3f4f6' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});
</script>

<script>
function balasPimpinan(id, noHp, nama, btn, createdAt) {
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

    fetch(`/balas-pesan/${id}`, {
        method: 'GET',
        headers: { 'Accept': 'application/json' },
    })
    .then(res => res.json())
    .then(data => {
        window.open(
            `https://wa.me/62${noHp}?text=Halo+${nama}%2C+kami+dari+Pondok+Pesantren+Ash-Shiddiqin.`,
            '_blank'
        );
        if (data.ok) {
            // Gunakan dibalas_at dari server untuk akurasi waktu respons
            const created = new Date(createdAt);
            const dibalasAt = data.dibalas_at ? new Date(data.dibalas_at) : new Date();
            const menit = Math.floor((dibalasAt - created) / 60000);
            let respons, color;
            if (menit < 60)        { respons = menit + ' menit'; }
            else if (menit < 1440) { respons = (menit/60).toFixed(1) + ' jam'; }
            else                   { respons = (menit/1440).toFixed(1) + ' hari'; }
            color = menit <= 30 ? '#2e7d32' : (menit <= 120 ? '#f59e0b' : '#c62828');

            // Update badge status di card pesan prioritas
            const item = btn.closest('.laporan-item');
            if (item) {
                item.style.borderLeftColor = '#2e7d32';
                const badge = item.querySelector('.badge-baru');
                if (badge) {
                    badge.className = 'badge-approved';
                    badge.innerHTML = '<i class="fas fa-circle" style="font-size:.5rem;"></i> Sudah Dibalas';
                }
                // Tambahkan info waktu respons di bawah kontak
                const kontakDiv = item.querySelector('[style*="fa-mobile-alt"]')?.parentElement;
                if (kontakDiv) {
                    const waktuSpan = kontakDiv.querySelector('.waktu-respons-pimpinan');
                    if (!waktuSpan) {
                        const el = document.createElement('span');
                        el.className = 'waktu-respons-pimpinan';
                        el.style.cssText = `font-weight:600;color:${color};font-size:.83rem;margin-left:.8rem;`;
                        el.innerHTML = `⚡ ${respons}`;
                        kontakDiv.appendChild(el);
                    }
                }
            }
            btn.innerHTML = `<i class="fab fa-whatsapp"></i> ⚡ ${respons}`;
            btn.style.background = color;
        }
        btn.disabled = false;
    })
    .catch(() => {
        window.open(
            `https://wa.me/62${noHp}?text=Halo+${nama}%2C+kami+dari+Pondok+Pesantren+Ash-Shiddiqin.`,
            '_blank'
        );
        btn.disabled = false;
        btn.innerHTML = '<i class="fab fa-whatsapp"></i> Balas WA';
    });
}
</script>
</body>
</html>