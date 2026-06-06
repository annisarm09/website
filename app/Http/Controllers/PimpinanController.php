<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Pesan;
use App\Models\Galeri;
use App\Models\PageVisit;
use Carbon\Carbon;

class PimpinanController extends Controller
{
    /** Halaman utama dashboard pimpinan */
    public function index()
    {
        $totalPesan    = Pesan::count();
        $totalPending  = Berita::where('status', 'pending')->count();
        $totalApproved = Berita::where('status', 'published')->count();
        $totalRejected = Berita::where('status', 'rejected')->count();
        $totalBerita   = Berita::count();
        $totalGaleri   = Galeri::where('status', 'published')->count();

        // ── Galeri pending (baru ditambahkan) ──
        $totalGaleriPending  = Galeri::where('status', 'pending')->count();
        $totalGaleriApproved = Galeri::where('status', 'published')->count();
        $totalGaleriRejected = Galeri::where('status', 'rejected')->count();
        $totalPendingGabung  = $totalPending + $totalGaleriPending; // untuk badge sidebar

        // Berita pending menunggu keputusan pimpinan
        $approvalList = Berita::where('status', 'pending')
                              ->latest('tanggal')
                              ->get();

        // Galeri pending menunggu keputusan pimpinan
        $galeriApprovalList = Galeri::where('status', 'pending')
                                    ->latest()
                                    ->get();

        // Riwayat berita yang sudah diputuskan
        $laporanList = Berita::whereIn('status', ['published', 'rejected'])
                             ->latest('updated_at')
                             ->get();

        // Riwayat galeri yang sudah diputuskan
        $galeriLaporanApproval = Galeri::whereIn('status', ['published', 'rejected'])
                                       ->latest('updated_at')
                                       ->get();

        // Pesan belum dibaca
        $pesanList = Pesan::where('sudah_dibaca', false)
                          ->latest()
                          ->get();

        // ── Data Chart: Berita per Bulan (tahun ini) ──
        $tahunIni = Carbon::now()->year;
        $beritaPerBulan = Berita::selectRaw('MONTH(tanggal) as bulan, status, COUNT(*) as total')
            ->whereYear('tanggal', $tahunIni)
            ->groupBy('bulan', 'status')
            ->get();

        $published = array_fill(1, 12, 0);
        $pending   = array_fill(1, 12, 0);
        $rejected  = array_fill(1, 12, 0);
        foreach ($beritaPerBulan as $row) {
            if ($row->status === 'published') $published[$row->bulan] = $row->total;
            if ($row->status === 'pending')   $pending[$row->bulan]   = $row->total;
            if ($row->status === 'rejected')  $rejected[$row->bulan]  = $row->total;
        }
        $chartBeritaPerBulan = [
            'labels'    => ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'],
            'published' => array_values($published),
            'pending'   => array_values($pending),
            'rejected'  => array_values($rejected),
        ];

        // ── Data Chart: Pesan per Minggu (4 minggu terakhir) ──
        $pesanPerMinggu = [0, 0, 0, 0];
        $now = Carbon::now();
        $minggu = [
            [Carbon::now()->subWeeks(3)->startOfWeek(), Carbon::now()->subWeeks(3)->endOfWeek()],
            [Carbon::now()->subWeeks(2)->startOfWeek(), Carbon::now()->subWeeks(2)->endOfWeek()],
            [Carbon::now()->subWeeks(1)->startOfWeek(), Carbon::now()->subWeeks(1)->endOfWeek()],
            [Carbon::now()->startOfWeek(),              Carbon::now()->endOfWeek()],
        ];
        foreach ($minggu as $i => $range) {
            $pesanPerMinggu[$i] = Pesan::whereBetween('created_at', [$range[0], $range[1]])->count();
        }
        $chartPesanPerMinggu = [
            'labels' => [
                $minggu[0][0]->format('d M') . '–' . $minggu[0][1]->format('d M'),
                $minggu[1][0]->format('d M') . '–' . $minggu[1][1]->format('d M'),
                $minggu[2][0]->format('d M') . '–' . $minggu[2][1]->format('d M'),
                $minggu[3][0]->format('d M') . '–' . $minggu[3][1]->format('d M'),
            ],
            'jumlah' => $pesanPerMinggu,
        ];

        // ── Data Chart: Pesan per Bulan (tahun ini) – untuk tab Laporan ──
        $pesanPerBulan = array_fill(1, 12, 0);
        $pesanBulanData = Pesan::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', $tahunIni)
            ->groupBy('bulan')
            ->get();
        foreach ($pesanBulanData as $row) {
            $pesanPerBulan[$row->bulan] = $row->total;
        }
        $chartPesanPerBulan = [
            'labels' => ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'],
            'jumlah' => array_values($pesanPerBulan),
        ];

        // ── Semua pesan untuk laporan ──
        $allPesanLaporan = Pesan::latest()->get();

        // ── Data Galeri untuk laporan ──
        $galeriLaporan = Galeri::latest()->get();

        // ── Distribusi Kategori Berita ──
        $kategoriStats = Berita::selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->get();

        // ── Data Kunjungan Website ──
        // (Membutuhkan model PageVisit + tabel page_visits)
        $visitExists = \Schema::hasTable('page_visits');

        if ($visitExists) {
            $visitHariIni  = PageVisit::whereDate('visited_at', Carbon::today())->count();
            $visitMingguIni = PageVisit::whereBetween('visited_at', [
                Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()
            ])->count();
            $visitBulanIni = PageVisit::whereYear('visited_at', $tahunIni)
                ->whereMonth('visited_at', Carbon::now()->month)->count();
            $visitTotal    = PageVisit::count();

            // Per bulan tahun ini
            $visitBulanData = PageVisit::selectRaw('MONTH(visited_at) as bulan, COUNT(*) as total')
                ->whereYear('visited_at', $tahunIni)
                ->groupBy('bulan')->get();
            $visitBulanArr = array_fill(1, 12, 0);
            foreach ($visitBulanData as $row) $visitBulanArr[$row->bulan] = $row->total;
            $chartVisitPerBulan = [
                'labels' => ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'],
                'jumlah' => array_values($visitBulanArr),
            ];

            // Per minggu (4 minggu terakhir)
            $visitMingguArr = [0,0,0,0];
            foreach ($minggu as $i => $range) {
                $visitMingguArr[$i] = PageVisit::whereBetween('visited_at', [$range[0], $range[1]])->count();
            }
            $chartVisitPerMinggu = [
                'labels' => [
                    $minggu[0][0]->format('d M').'–'.$minggu[0][1]->format('d M'),
                    $minggu[1][0]->format('d M').'–'.$minggu[1][1]->format('d M'),
                    $minggu[2][0]->format('d M').'–'.$minggu[2][1]->format('d M'),
                    $minggu[3][0]->format('d M').'–'.$minggu[3][1]->format('d M'),
                ],
                'jumlah' => $visitMingguArr,
            ];

            // Halaman paling populer
            $halamanPopuler = PageVisit::selectRaw('url, COUNT(*) as total')
                ->groupBy('url')->orderByDesc('total')->limit(10)->get()
                ->map(fn($r) => [
                    'url'   => $r->url,
                    'nama'  => match(true) {
                        str_contains($r->url, 'berita')  => 'Berita & Kegiatan',
                        str_contains($r->url, 'galeri')  => 'Galeri Foto',
                        str_contains($r->url, 'tentang') => 'Tentang Kami',
                        str_contains($r->url, 'pesan')   => 'Kontak / Pesan',
                        default                          => 'Beranda',
                    },
                    'total' => $r->total,
                ])->toArray();

            // Log harian 30 hari terakhir
            $logHarian = PageVisit::selectRaw('DATE(visited_at) as tanggal, COUNT(*) as total')
                ->where('visited_at', '>=', Carbon::now()->subDays(29)->startOfDay())
                ->groupBy('tanggal')->orderByDesc('tanggal')->get()
                ->map(fn($r) => [
                    'tanggal' => Carbon::parse($r->tanggal)->translatedFormat('d F Y'),
                    'total'   => $r->total,
                ])->toArray();
        } else {
            // Tabel belum ada – kirim data dummy agar view tidak error
            $visitHariIni = $visitMingguIni = $visitBulanIni = $visitTotal = 0;
            $chartVisitPerBulan  = ['labels'=>['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'],'jumlah'=>array_fill(0,12,0)];
            $chartVisitPerMinggu = ['labels'=>['Minggu 1','Minggu 2','Minggu 3','Minggu 4'],'jumlah'=>[0,0,0,0]];
            $halamanPopuler = [];
            $logHarian      = [];
        }

        $visitStats = [
            'hari_ini'   => $visitHariIni,
            'minggu_ini' => $visitMingguIni,
            'bulan_ini'  => $visitBulanIni,
            'total'      => $visitTotal,
        ];

        return view('admin.pages.pimpinan.index', compact(
            'totalPesan',
            'totalPending',
            'totalApproved',
            'totalRejected',
            'totalBerita',
            'totalGaleri',
            'totalGaleriPending',
            'totalGaleriApproved',
            'totalGaleriRejected',
            'totalPendingGabung',
            'approvalList',
            'galeriApprovalList',
            'laporanList',
            'galeriLaporanApproval',
            'pesanList',
            'allPesanLaporan',
            'galeriLaporan',
            'chartBeritaPerBulan',
            'chartPesanPerMinggu',
            'chartPesanPerBulan',
            'kategoriStats',
            'visitStats',
            'chartVisitPerBulan',
            'chartVisitPerMinggu',
            'halamanPopuler',
            'logHarian'
        ));
    }

    // ══════════════════════════════════════════════════════════════
    //  APPROVAL BERITA
    // ══════════════════════════════════════════════════════════════

    /** Setujui berita → status published */
    public function approve(Request $request, int $id)
    {
        $berita = Berita::findOrFail($id);
        $berita->update([
            'status'         => 'published',
            'tgl_keputusan'  => Carbon::now('Asia/Jakarta'),
        ]);

        return redirect()->route('pimpinan.dashboard')
                         ->with('success', 'Berita "' . $berita->judul_berita . '" berhasil dipublikasikan!')
                         ->with('active_tab', 'approval');
    }

    /** Tolak berita → status rejected */
    public function reject(Request $request, int $id)
    {
        $request->validate(['catatan' => 'nullable|string|max:500']);

        $berita = Berita::findOrFail($id);
        $berita->update([
            'status'        => 'rejected',
            'tgl_keputusan' => Carbon::now('Asia/Jakarta'),
        ]);

        return redirect()->route('pimpinan.dashboard')
                         ->with('success', 'Berita "' . $berita->judul_berita . '" telah ditolak.')
                         ->with('active_tab', 'approval');
    }

    // ══════════════════════════════════════════════════════════════
    //  APPROVAL GALERI  ← baru
    // ══════════════════════════════════════════════════════════════

    /** Setujui foto galeri → status published → tampil di halaman publik */
    public function approveGaleri(Request $request, int $id)
    {
        $galeri = Galeri::findOrFail($id);
        $galeri->update([
            'status'        => 'published',
            'tgl_keputusan' => Carbon::now('Asia/Jakarta'),
        ]);

        return redirect()->route('pimpinan.dashboard')
                         ->with('success', 'Foto "' . $galeri->judul . '" berhasil dipublikasikan!')
                         ->with('active_tab', 'approval');
    }

    /** Tolak foto galeri → status rejected → tidak tampil di halaman publik */
    public function rejectGaleri(Request $request, int $id)
    {
        $request->validate(['catatan' => 'nullable|string|max:500']);

        $galeri = Galeri::findOrFail($id);
        $galeri->update([
            'status'        => 'rejected',
            'tgl_keputusan' => Carbon::now('Asia/Jakarta'),
        ]);

        return redirect()->route('pimpinan.dashboard')
                         ->with('success', 'Foto "' . $galeri->judul . '" telah ditolak.')
                         ->with('active_tab', 'approval');
    }

    // ══════════════════════════════════════════════════════════════
    //  PESAN
    // ══════════════════════════════════════════════════════════════

    /** Tandai pesan sudah dibaca + catat waktu balas pertama */
    public function bacaPesan(int $id)
    {
        $pesan = Pesan::findOrFail($id);
        $update = ['sudah_dibaca' => true];

        // Catat dibalas_at hanya sekali (pertama kali dibalas)
        if (empty($pesan->dibalas_at)) {
            $update['dibalas_at'] = Carbon::now('Asia/Jakarta');
        }

        $pesan->update($update);

        return redirect()->route('pimpinan.dashboard')
                         ->with('success', 'Pesan ditandai sudah dibaca.')
                         ->with('active_tab', 'pesan');
    }

    /**
     * Catat waktu balas ketika pimpinan klik tombol "Balas WA"
     * Dipanggil via fetch() (AJAX) dari tombol WA
     */
    public function balasPesan(int $id)
    {
        $pesan = Pesan::findOrFail($id);

        $update = [
            'sudah_dibaca' => true,
            'status'       => 'Sudah Dibalas',
        ];

        // Hanya catat dibalas_at sekali (pertama kali dibalas)
        if (!$pesan->dibalas_at) {
            $update['dibalas_at'] = Carbon::now('Asia/Jakarta');
        }

        $pesan->update($update);

        return response()->json([
            'ok'         => true,
            'dibalas_at' => $pesan->fresh()->dibalas_at,
        ]);
    }
}