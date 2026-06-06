<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesan;

class PesanController extends Controller
{
    public function index()
    {
    /** Daftar pesan masuk di admin */

        $pesanList = Pesan::query()->latest()->get();
        return redirect()->route('home'); // ditangani AdminController
    }

    /** Tandai pesan sudah dibaca */
    public function markRead(int $id)
    {
        $pesan = Pesan::findOrFail($id);
        $pesan->update(['sudah_dibaca' => true]);

        return back()->with('success', 'Pesan ditandai sudah dibaca.');
    }

    /** Hapus pesan */
    public function destroy(int $id)
    {
        Pesan::findOrFail($id)->delete();
        return back()->with('success', 'Pesan berhasil dihapus.');
    }

    // ─── PUBLIK ──────────────────────────────────────────────

    /** Tampilkan form kontak publik (/pesan) */
    public function publicIndex()
    {
        return view('admin.pages.pesan.index');
    }

    /** Simpan pesan dari pengunjung */
    public function store(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:100',
            'kontak' => 'required|string|max:100',
            'pesan'  => 'required|string|max:2000',
        ]);

        Pesan::create([
            'nama'        => $request->nama,
            'kontak'      => $request->kontak,
            'pesan'       => $request->pesan,
            'sudah_dibaca' => 0,
            'tanggal'      => now(),
            'status'       => 'Baru',
        ]);

        return redirect()->route('pesan')
                         ->with('success', 'Pesan Anda telah terkirim! Kami akan menghubungi Anda segera.');
    }
}
