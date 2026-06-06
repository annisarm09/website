<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Beranda;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Tentang;
use App\Models\Pesan;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // Ambil / buat data beranda
        $beranda = Beranda::query()->first();
        if (!$beranda) {
            $beranda = Beranda::create([
                'judul_utama' => 'Pondok Pesantren Ash-Shiddiqin',
                'sub_judul'   => 'Selamat Datang',
                'deskripsi'   => 'Membentuk Generasi yang Kokoh Imaninya dan Produktif Amal Sholihnya',
            ]);
        }

        // Data untuk tab Berita
        $beritaList = Berita::latest('tanggal')->get();

        // Data untuk tab Galeri
        $galeriList = Galeri::query()->latest()->get();

        // Data untuk tab Tentang
        $tentang = Tentang::all();

        // Data untuk tab Pesan
        $pesanList = Pesan::query()->latest()->get();

        // Statistik
        $totalBerita      = Berita::count();
        $totalGaleri      = Galeri::count();
        $totalPesan       = Pesan::count();
        $pesanBelumDibaca = Pesan::where('sudah_dibaca', false)->count();

        // Berita menunggu approval
        $beritaPending = Berita::whereStatus('pending')->latest()->get();
        $totalPending  = $beritaPending->count();
        // ambil 2 berita published terbaru untuk beranda
        $beritaTerkini = Berita::where('status', 'published')
                           ->latest('tanggal')
                           ->limit(2)
                           ->get();

        return view('admin.layouts.home', compact(
            'beranda',
            'beritaList',
            'galeriList',
            'tentang',
            'pesanList',
            'totalBerita',
            'totalGaleri',
            'totalPesan',
            'pesanBelumDibaca',
            'beritaPending',
            'totalPending',
            'beritaTerkini'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'judul_utama' => 'required|string|max:255',
            'sub_judul'   => 'required|string|max:255',
            'deskripsi'   => 'required',
        ]);

        Beranda::updateOrCreate(
            ['id' => 1],
            [
                'judul_utama' => $request->judul_utama,
                'sub_judul'   => $request->sub_judul,
                'deskripsi'   => $request->deskripsi,
            ]
        );

        return redirect()->route('home')->with('success', 'Beranda berhasil diperbarui!');
    }

    /** Catat waktu balas ketika admin klik tombol "Balas WA" */
    public function balasPesan(int $id)
    {
        try {
            $pesan = Pesan::findOrFail($id);

            $update = [
                'sudah_dibaca' => true,
                'status'       => 'Sudah Dibalas',
            ];

            if (!$pesan->dibalas_at) {
                $update['dibalas_at'] = Carbon::now('Asia/Jakarta');
            }

            $pesan->update($update);
            $fresh = $pesan->fresh();

            return response()->json([
                'ok'         => true,
                'dibalas_at' => $fresh->dibalas_at
                    ? $fresh->dibalas_at->toIso8601String()
                    : Carbon::now('Asia/Jakarta')->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }
}