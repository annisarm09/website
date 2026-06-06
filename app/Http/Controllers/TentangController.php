<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tentang;

class TentangController extends Controller
{
    public function index()
    {
        return redirect()->route('home');
    }

    /** Update sejarah, visi, misi */
    public function update(Request $request)
    {
        $request->validate([
            'sejarah' => 'required|string',
            'visi'    => 'required|string',
            'misi'    => 'required|string',
        ]);

        Tentang::updateOrCreate(['tipe' => 'sejarah'], ['deskripsi' => $request->sejarah]);
        Tentang::updateOrCreate(['tipe' => 'visi'],    ['deskripsi' => $request->visi]);
        Tentang::updateOrCreate(['tipe' => 'misi'],    ['deskripsi' => $request->misi]);

        session(['active_tab' => 'tentang']);

        return redirect()->route('home')->with('success', 'Profil Pondok berhasil diperbarui!');
    }

    /** Update / tambah salah satu program pendidikan */
    public function updateProgram(Request $request)
    {
        $request->validate([
            'tipe'  => ['required', 'string', 'regex:/^prog\d+$/'],
            'judul' => 'required|string|max:100',
        ]);

        $data = json_encode([
            'judul'     => $request->judul,
            'icon'      => $request->icon ?? 'fas fa-graduation-cap',
            'deskripsi' => $request->deskripsi ?? '',
        ]);

        Tentang::updateOrCreate(
            ['tipe' => $request->tipe],
            ['deskripsi' => $data]
        );

        $nomorProgram = str_replace('prog', '', $request->tipe);

        session(['active_tab' => 'tentang']);

        return redirect()->route('home')
            ->with('success', "Program Pendidikan #{$nomorProgram} berhasil disimpan!");
    }

    /** Tambah 1 keunggulan baru (judul + icon) — tidak dibatasi */
    public function addKeunggulan(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:150',
            'icon'  => 'nullable|string|max:60',
        ]);

        $slot = 1;
        while (Tentang::where('tipe', 'keunggulan_' . $slot)->exists()) {
            $slot++;
        }

        Tentang::create([
            'tipe'      => 'keunggulan_' . $slot,
            'deskripsi' => json_encode([
                'judul' => $request->judul,
                'icon'  => $request->icon ?? 'fas fa-star-and-crescent',
            ]),
        ]);

        session(['active_tab' => 'tentang']);

        return redirect()->route('home')
            ->with('success', 'Keunggulan berhasil ditambahkan!');
    }
    public function updateKeunggulan(Request $request, $tipe)
    {
        abort_unless(preg_match('/^keunggulan_\d+$/', $tipe), 404);

        $request->validate([
            'judul' => 'required|string|max:150',
            'icon'  => 'nullable|string|max:60',
        ]);

        $rec = Tentang::where('tipe', $tipe)->firstOrFail();
        $old = json_decode($rec->deskripsi, true) ?? [];

        $rec->update([
            'deskripsi' => json_encode([
                'judul' => $request->judul,
                'icon'  => $request->icon ?? ($old['icon'] ?? 'fas fa-star'),
            ]),
        ]);

        session(['active_tab' => 'tentang']);

        return redirect()->route('home')
            ->with('success', 'Keunggulan berhasil diperbarui!');
    }

    /** Hapus satu program */
    public function destroyProgram($tipe)
    {
        abort_unless(preg_match('/^prog\d+$/', $tipe), 404);
        Tentang::where('tipe', $tipe)->delete();

        session(['active_tab' => 'tentang']);

        return redirect()->route('home')
            ->with('success', 'Program berhasil dihapus.');
    }

    /** Hapus satu keunggulan */
    public function destroyKeunggulan($tipe)
    {
        abort_unless(preg_match('/^keunggulan_\d+$/', $tipe), 404);
        Tentang::where('tipe', $tipe)->delete();

        session(['active_tab' => 'tentang']);

        return redirect()->route('home')
            ->with('success', 'Keunggulan berhasil dihapus.');
    }

    // ═══════════════════════════════════════════════════════════
    //  CHARACTER BUILDING — MULTI FOTO (tidak dibatasi)
    //  Tipe disimpan: char_build_foto_1, char_build_foto_2, dst.
    //  Nilai JSON: { "path": "...", "judul": "...", "urutan": N }
    // ═══════════════════════════════════════════════════════════

    /** Upload satu foto Character Building baru */
    public function uploadCharBuild(Request $request)
    {
        $request->validate([
            'foto'  => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'judul' => 'nullable|string|max:150',
        ]);

        // Cari slot kosong berikutnya
        $slot = 1;
        while (Tentang::where('tipe', 'char_build_foto_' . $slot)->exists()) {
            $slot++;
        }

        $path = $request->file('foto')->store('tentang/char_build', 'public');

        Tentang::create([
            'tipe'      => 'char_build_foto_' . $slot,
            'deskripsi' => json_encode([
                'path'   => $path,
                'judul'  => $request->judul ?? '',
                'urutan' => $slot,
            ]),
        ]);

        session(['active_tab' => 'tentang']);

        return redirect()->route('home')
            ->with('success', "Foto Character Building #{$slot} berhasil diupload!");
    }

    /** Hapus satu foto Character Building berdasarkan slot */
    public function destroyCharBuild($slot)
    {
        abort_unless(is_numeric($slot) && $slot > 0, 404);

        $rec = Tentang::where('tipe', 'char_build_foto_' . $slot)->first();
        if ($rec) {
            $data = json_decode($rec->deskripsi, true);
            if (!empty($data['path'])) {
                \Storage::disk('public')->delete($data['path']);
            }
            $rec->delete();
        }

        session(['active_tab' => 'tentang']);

        return redirect()->route('home')
            ->with('success', "Foto Character Building #{$slot} berhasil dihapus.");
    }

    /**
     * Update judul/caption foto yang sudah ada
     * (opsional — memudahkan admin mengedit caption tanpa re-upload)
     */
    public function updateCharBuildJudul(Request $request, $slot)
    {
        abort_unless(is_numeric($slot) && $slot > 0, 404);

        $request->validate(['judul' => 'nullable|string|max:150']);

        $rec = Tentang::where('tipe', 'char_build_foto_' . $slot)->first();
        if ($rec) {
            $data = json_decode($rec->deskripsi, true) ?? [];
            $data['judul'] = $request->judul ?? '';
            $rec->update(['deskripsi' => json_encode($data)]);
        }

        session(['active_tab' => 'tentang']);

        return redirect()->route('home')
            ->with('success', "Caption foto #{$slot} berhasil diperbarui!");
    }

    // ═══════════════════════════════════════════════════════════
    //  HALAMAN PUBLIK /tentang
    // ═══════════════════════════════════════════════════════════

    /** Halaman publik /tentang */
    public function publicIndex()
    {
        $sejarah = Tentang::whereTipe('sejarah')->first();
        $visi    = Tentang::whereTipe('visi')->first();
        $misi    = Tentang::whereTipe('misi')->first();

        // Program pendidikan
        $programs = collect();
        for ($i = 1; $i <= 50; $i++) {
            $rec = Tentang::whereTipe('prog' . $i)->first();
            if (!$rec) break;
            $decoded = json_decode($rec->deskripsi, true);
            if ($decoded) $programs->push($decoded);
        }

        // Keunggulan
        $keunggulan = collect();
        for ($i = 1; $i <= 100; $i++) {
            $rec = Tentang::whereTipe('keunggulan_' . $i)->first();
            if (!$rec) break;
            $decoded = json_decode($rec->deskripsi, true);
            if ($decoded && !empty($decoded['judul'])) {
                $decoded['no'] = str_pad($keunggulan->count() + 1, 2, '0', STR_PAD_LEFT);
                $keunggulan->push($decoded);
            }
        }

        // Foto Character Building (multi, tidak dibatasi)
        $charBuildFotos = collect();
        for ($i = 1; $i <= 100; $i++) {
            $rec = Tentang::whereTipe('char_build_foto_' . $i)->first();
            if (!$rec) break;
            $decoded = json_decode($rec->deskripsi, true);
            if ($decoded && !empty($decoded['path'])) {
                $decoded['slot'] = $i;
                $charBuildFotos->push($decoded);
            }
        }

        return view('admin.pages.tentang.index', compact(
            'sejarah', 'visi', 'misi', 'programs', 'keunggulan', 'charBuildFotos'
        ));
    }
}
