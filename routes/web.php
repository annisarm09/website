<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\PesanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\RegisterController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\PimpinanController;

// ════════════════════════════════
// PUBLIK
// ════════════════════════════════

// ── AJAX Balas Pesan (GET, tanpa CSRF, kompatibel shared hosting) ──
Route::get('/balas-pesan/{id}', function ($id) {
    if (!auth()->check()) {
        return response()->json(['ok' => false, 'error' => 'Unauthenticated'], 401);
    }
    try {
        $pesan = \App\Models\Pesan::findOrFail($id);
        $update = ['sudah_dibaca' => true, 'status' => 'Sudah Dibalas'];
        if (!$pesan->dibalas_at) {
            $update['dibalas_at'] = \Carbon\Carbon::now('Asia/Jakarta');
        }
        $pesan->update($update);
        $fresh = $pesan->fresh();
        return response()->json([
            'ok'         => true,
            'dibalas_at' => $fresh->dibalas_at
                ? $fresh->dibalas_at->toIso8601String()
                : \Carbon\Carbon::now('Asia/Jakarta')->toIso8601String(),
        ]);
    } catch (\Exception $e) {
        return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
    }
});

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Berita publik
Route::get('/berita',        [BeritaController::class, 'publicIndex'])->name('berita');
Route::get('/berita/{slug}', [BeritaController::class, 'publicShow'])->name('berita.show');

// Galeri publik — hanya foto yang sudah published
Route::get('/galeri', [GaleriController::class, 'publicIndex'])->name('galeri');

// Tentang publik
Route::get('/tentang', [TentangController::class, 'publicIndex'])->name('tentang');

// Pesan / kontak publik
Route::get('/pesan',  [PesanController::class, 'publicIndex'])->name('pesan');
Route::post('/pesan', [PesanController::class, 'store'])->name('pesan.kirim');

// ════════════
// AUTH
// ════════════

Route::get('/login',         [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login-proses', [AuthController::class, 'login'])->name('login.proses');

Route::get('/register',  fn() => view('admin.pages.auth.register'))->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.proses');

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password',
    [AuthController::class, 'showForgot'])->name('password.request');
Route::post('/forgot-password',
    [AuthController::class, 'processForgot'])->name('password.request.proses');
Route::get('/reset-password/{token}',
    [AuthController::class, 'showReset'])->name('password.reset.form');
Route::post('/reset-password',
    [AuthController::class, 'processReset'])->name('password.reset.proses');

// ══════════════════════════════════════════
// ADMIN PANEL (wajib login)
// ══════════════════════════════════════════

Route::prefix('admin')->middleware(['auth', 'role:admin,pimpinan'])->group(function () {

    Route::get('/home',        [AdminController::class, 'index'])->name('home');
    Route::put('/home-update', [AdminController::class, 'update'])->name('home.update');
    Route::put('/admin/beranda/slide/{slide}', [DashboardController::class, 'updateSlide'])->name('home.updateSlide')->whereNumber('slide');

    // Berita CRUD
    Route::post('/berita',          [BeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{id}/edit', [BeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{id}',      [BeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{id}',   [BeritaController::class, 'destroy'])->name('berita.destroy');

    // Galeri CRUD — upload langsung ke 'pending', tidak langsung publish
    Route::post('/galeri',        [GaleriController::class, 'store'])->name('galeri.store');
    Route::delete('/galeri/{id}', [GaleriController::class, 'destroy'])->name('galeri.destroy');

    // Tentang
    Route::put('/tentang',                              [TentangController::class, 'update'])->name('tentang.update');
    Route::put('/tentang/update-program',               [TentangController::class, 'updateProgram'])->name('tentang.updateProgram');
    Route::put('/tentang/add-keunggulan',               [TentangController::class, 'addKeunggulan'])->name('tentang.addKeunggulan');
    Route::patch('/tentang/keunggulan/{tipe}',          [TentangController::class, 'updateKeunggulan'])->name('tentang.updateKeunggulan')->where('tipe', '[a-zA-Z0-9_]+');

    Route::delete('/tentang/program/{tipe}',            [TentangController::class, 'destroyProgram'])->name('tentang.destroyProgram')->where('tipe', '[a-zA-Z0-9_]+');
    Route::delete('/tentang/keunggulan/{tipe}',         [TentangController::class, 'destroyKeunggulan'])->name('tentang.destroyKeunggulan')->where('tipe', '[a-zA-Z0-9_]+');
    Route::post('/tentang/char-build/upload',           [TentangController::class, 'uploadCharBuild'])->name('tentang.charBuild.upload');
    Route::patch('/tentang/char-build/{slot}/judul',    [TentangController::class, 'updateCharBuildJudul'])->name('tentang.charBuild.updateJudul');
    Route::delete('/tentang/char-build/{slot}',         [TentangController::class, 'destroyCharBuild'])->name('tentang.charBuild.destroy');

    // Pesan masuk
    Route::patch('/pesan-masuk/{id}/baca', [PesanController::class, 'markRead'])->name('admin.pesan.baca');
    Route::delete('/pesan-masuk/{id}',     [PesanController::class, 'destroy'])->name('admin.pesan.destroy');
    Route::post('/pesan/{id}/balas', [AdminController::class, 'balasPesan'])->name('admin.pesan.balas');
});

// ══════════════════════════════════════════════════
// PIMPINAN (hanya role pimpinan)
// ══════════════════════════════════════════════════

Route::prefix('pimpinan')->middleware(['auth', 'role:pimpinan'])->name('pimpinan.')->group(function () {

    // Dashboard utama pimpinan
    Route::get('/dashboard', [PimpinanController::class, 'index'])->name('dashboard');

    // ── Approval BERITA ──
    Route::patch('/approve/{id}',        [PimpinanController::class, 'approve'])->name('approve');
    Route::patch('/reject/{id}',         [PimpinanController::class, 'reject'])->name('reject');
    // Duplikat POST agar form method="POST" + @method('PATCH') tetap jalan
    Route::post('/approve/{id}',         [PimpinanController::class, 'approve']);
    Route::post('/reject/{id}',          [PimpinanController::class, 'reject']);

    // ── Approval GALERI ── (baru)
    Route::patch('/galeri/approve/{id}', [PimpinanController::class, 'approveGaleri'])->name('galeri.approve');
    Route::patch('/galeri/reject/{id}',  [PimpinanController::class, 'rejectGaleri'])->name('galeri.reject');


    // Tandai pesan dibaca
    Route::patch('/pesan/{id}/baca', [PimpinanController::class, 'bacaPesan'])->name('pesan.baca');
	Route::post('/pesan/{id}/balas', [PimpinanController::class, 'balasPesan'])->name('pesan.balas');
});

// ════════════════════════════════════════════════════════
// AJAX — hanya butuh auth, tanpa role check
// Dipanggil fetch() dari admin & pimpinan panel
// GET dipakai karena InfinityFreeApp memblokir POST dari fetch()
// ════════════════════════════════════════════════════════
Route::middleware('auth')->group(function () {
    Route::post('/ajax/pesan/{id}/balas', [AdminController::class, 'balasPesan'])
         ->name('ajax.pesan.balas');
    // GET fallback untuk shared hosting yang blokir POST fetch()
    Route::get('/ajax/pesan/{id}/balas', [AdminController::class, 'balasPesan'])
         ->name('ajax.pesan.balas.get');
});