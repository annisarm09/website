<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    /** Admin: daftar semua berita */
    public function index(Request $request)
    {
        $berita = Berita::latest('tanggal')
                        ->paginate(9);
        return view('admin.pages.berita.index', compact('berita'));
    }

    /** Form tambah berita */
    public function create()
    {
        return view('admin.pages.berita.create');
    }

    /** Simpan berita baru */
    public function store(Request $request)
    {
        $request->validate([
            'judul_berita' => 'required|string|max:255',
            'isi_berita'   => 'required|string',
            'kategori'     => 'required|in:kegiatan,pengumuman,prestasi',
            'tanggal'      => 'required|date',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'       => 'required|in:draft,pending,published,rejected',
        ]);

        // Upload foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('berita', 'public');
        }

        // Buat slug unik
        $slug = Str::slug($request->judul_berita);
        $base = $slug;
        $i = 1;
        while (Berita::whereSlug($slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        Berita::create([
            'judul_berita' => $request->judul_berita,
            'isi_berita'   => $request->isi_berita,
            'slug'         => $slug,
            'kategori'     => $request->kategori,
            'tanggal'      => $request->tanggal,
            'foto'         => $fotoPath,
            'status'       => $request->status,
        ]);

        return redirect()->route('home')->with('success', 'Berita berhasil disimpan!');
    }

    /** Form edit berita */
    public function edit(int $id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.pages.berita.edit', compact('berita'));
    }

    /** Update berita */
    public function update(Request $request, int $id)
    {
        $berita = Berita::findOrFail($id);

        $request->validate([
            'judul_berita' => 'required|string|max:255',
            'isi_berita'   => 'required|string',
            'kategori'     => 'required|in:kegiatan,pengumuman,prestasi',
            'tanggal'      => 'required|date',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'       => 'required|in:draft,pending,published,rejected',
        ]);

        // Update foto jika ada file baru
        if ($request->hasFile('foto')) {
            if ($berita->foto) {
                Storage::disk('public')->delete($berita->foto);
            }
            $berita->foto = $request->file('foto')->store('berita', 'public');
        }

        $berita->update([
            'judul_berita' => $request->judul_berita,
            'isi_berita'   => $request->isi_berita,
            'kategori'     => $request->kategori,
            'tanggal'      => $request->tanggal,
            'status'       => $request->status,
            'foto'         => $berita->foto,
        ]);

        return redirect()->route('home')->with('success', 'Berita berhasil diperbarui!');
    }

    /** Hapus berita */
    public function destroy(int $id)
    {
        $berita = Berita::findOrFail($id);
        if ($berita->foto) {
            Storage::disk('public')->delete($berita->foto);
        }
        $berita->delete();

        return redirect()->route('home')->with('success', 'Berita berhasil dihapus!');
    }

    /** PIMPINAN: Approve berita */
    public function approve(int $id)
    {
        $berita = Berita::findOrFail($id);
        $berita->update(['status' => 'published']);

        session(['active_tab' => 'approval']);
        return redirect()->route('home')->with('success', 'Berita "' . $berita->judul_berita . '" telah dipublikasikan!');
    }

    /** PIMPINAN: Tolak berita */
    public function reject(int $id)
    {
        $berita = Berita::findOrFail($id);
        $berita->update(['status' => 'rejected']);

        session(['active_tab' => 'approval']);
        return redirect()->route('home')->with('success', 'Berita "' . $berita->judul_berita . '" telah ditolak.');
    }

    // ─── PUBLIK ──────────────────────────────────────────────

    /** Halaman daftar berita publik (hanya yang published) */
    public function publicIndex(Request $request)
    {
        $query = Berita::whereStatus('published')->latest('tanggal');

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori', $request->kategori);
        }

        $berita = $query->paginate(9);
        return view('admin.pages.berita.index', compact('berita'));
    }

    /**
     * Halaman detail berita publik.
     * ── PERBAIKAN: hapus filter ->where('status','published') ──
     * supaya berita yang masih pending/draft tetap bisa dilihat
     * lewat slug (misalnya dari preview admin/pimpinan).
     * Kalau ingin membatasi hanya published di URL publik, ubah
     * kembali dengan menambahkan ->whereStatus('published').
     */
    public function publicShow(string $slug)
    {
        // Cari berdasarkan slug saja (tanpa filter status)
        $berita = Berita::whereSlug($slug)->firstOrFail();

        return view('admin.pages.berita.show', compact('berita'));
    }
}
