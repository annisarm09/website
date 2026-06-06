<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Beranda;
use App\Models\Berita;

class DashboardController extends Controller
{
    public function index()
    {
        $beranda        = Beranda::query()->first();
        $beritaTerbaru  = Berita::latest('tanggal')->take(2)->get();
        $beritaList     = Berita::latest('tanggal')->get();
        $beritaPending  = Berita::whereStatus('pending')->latest('tanggal')->get();
        $totalBerita    = Berita::count();
        $beritaTerkini  = Berita::where('status', 'published')->latest('tanggal')->limit(2)->get();

        return view('admin.layouts.base', compact(
            'beranda',
            'beritaTerbaru',
            'beritaList',
            'beritaPending',
            'totalBerita',
            'beritaTerkini'
        ));
    }

    /**
     * Update konten salah satu slide beranda (1, 2, atau 3)
     */
    public function updateSlide(Request $request, int $slideNum)
    {
        $request->validate([
            "slide{$slideNum}_label" => 'nullable|string|max:100',
            "slide{$slideNum}_judul" => 'required|string|max:150',
            "slide{$slideNum}_sub"   => 'nullable|string|max:150',
            "slide{$slideNum}_btn1"  => 'nullable|string|max:80',
            "slide{$slideNum}_btn2"  => 'nullable|string|max:80',
        ]);

        $beranda = Beranda::firstOrCreate([]);

        $beranda->update([
            "slide{$slideNum}_label" => $request->input("slide{$slideNum}_label"),
            "slide{$slideNum}_judul" => $request->input("slide{$slideNum}_judul"),
            "slide{$slideNum}_sub"   => $request->input("slide{$slideNum}_sub"),
            "slide{$slideNum}_btn1"  => $request->input("slide{$slideNum}_btn1"),
            "slide{$slideNum}_btn2"  => $request->input("slide{$slideNum}_btn2"),
        ]);

        return redirect()->route('admin.dashboard')
                         ->with('success', "Slide {$slideNum} berhasil diperbarui!")
                         ->with('active_tab', 'beranda');
    }
}
