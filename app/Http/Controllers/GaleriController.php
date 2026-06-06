<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Galeri;

class GaleriController extends Controller
{
    /**
     * Tampilan publik galeri — hanya tampilkan foto yang sudah approved/published
     */
    public function publicIndex()
    {
        $galeri = Galeri::where('status', 'published')
                        ->latest()
                        ->get();

        return view('admin.layouts.base2', compact('galeri'));
    }

    /**
     * Admin menyimpan foto baru → status otomatis 'pending' (menunggu persetujuan pimpinan)
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'    => 'required|string|max:255',
            'kategori' => 'required|string|in:pengajian,tahfidz,wisuda,lainnya',
            'foto'     => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $path = $request->file('foto')->store('galeri', 'public');

        Galeri::create([
            'judul'     => $request->judul,
            'kategori'  => $request->kategori,
            'src'       => $path,
            'timestamp' => now()->format('d M Y'),
            'status'    => 'pending',   // ← wajib disetujui pimpinan dulu
        ]);

        return redirect()->route('home')
                         ->with('success', 'Foto berhasil diupload dan menunggu persetujuan Pimpinan.')
                         ->with('active_tab', 'galeri');
    }

    /**
     * Admin menghapus foto (boleh hapus foto miliknya sendiri, status apapun)
     */
    public function destroy(int $id)
    {
        $galeri = Galeri::findOrFail($id);

        if ($galeri->src && Storage::disk('public')->exists($galeri->src)) {
            Storage::disk('public')->delete($galeri->src);
        }

        $galeri->delete();

        return redirect()->route('home')
                         ->with('success', 'Foto berhasil dihapus.')
                         ->with('active_tab', 'galeri');
    }
}
