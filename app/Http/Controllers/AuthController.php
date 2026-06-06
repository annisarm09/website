<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function showLoginForm()
{
    return view('admin.pages.auth.formlogin');
}
    public function login(Request $request)
    {
        // 1. Validasi inputan
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $data = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        // 2. Cek username & password
        if (Auth::attempt($data)) {

            // 3. Buat session baru
            $request->session()->regenerate();

            // 4. Ambil user yang baru login
            $user = Auth::user();

            // 5. ✅ Redirect sesuai role
            if ($user->role === 'pimpinan') {
                return redirect()->route('pimpinan.dashboard');
            }

            if ($user->role === 'admin') {
                return redirect()->route('home'); // /admin/home
            }

            // Fallback jika role lain
            return redirect('/');
        }

        // 6. Jika gagal login
        return back()->with('error', 'Username atau password salah.');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,pimpinan', // ✅ validasi role
        ]);

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => $request->role, // ✅ ambil dari input, bukan hardcode
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda berhasil logout.');
    }
    // ─── LUPA PASSWORD —  Minta username

    public function showForgot()
    {
        return view('admin.pages.auth.forgot-password');
    }

    /*cek username → buat token → tampilkan form reset*/
    public function processForgot(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
        ]);

        $user = User::whereUsername($request->username)->first();

        if (!$user) {
            return back()->with('error', 'Username tidak ditemukan.');
        }

        // Buat token unik dan simpan di session (berlaku 15 menit)
        $token = Str::random(64);

        session([
            'reset_token'    => $token,
            'reset_username' => $user->username,
            'reset_expire'   => now()->addMinutes(15)->timestamp,
        ]);

        // Langsung arahkan ke form buat password baru
        return redirect()->route('password.reset.form', [
            'token'    => $token,
            'username' => $user->username,
        ]);
    }

    // Form password baru

    public function showReset(Request $request, string $token)
    {
        $username = $request->query('username');

        // Validasi token dari session
        if (
            session('reset_token')    !== $token          ||
            session('reset_username') !== $username        ||
            now()->timestamp > session('reset_expire', 0)
        ) {
            return redirect()->route('password.request')
                ->with('error', 'Link reset tidak valid atau sudah kedaluwarsa. Silakan ulangi.');
        }

        return view('admin.pages.auth.reset', compact('token', 'username'));
    }

    // ─── LUPA PASSWORD — Simpan password baru

    public function processReset(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'username'              => 'required',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ], [
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Validasi token session
        if (
            session('reset_token')    !== $request->token    ||
            session('reset_username') !== $request->username  ||
            now()->timestamp > session('reset_expire', 0)
        ) {
            return redirect()->route('password.request')
                ->with('error', 'Token tidak valid atau sudah kedaluwarsa. Silakan ulangi.');
        }

        // Update password
        $user = User::whereUsername ($request->username)->firstOrFail();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Hapus token dari session
        session()->forget(['reset_token', 'reset_username', 'reset_expire']);

        return redirect()->route('login')
            ->with('success', 'Password berhasil diubah! Silakan login dengan password baru.');
    }
    public function sendForgot(Request $request)
    {
        // Redirect ke proses custom kita
        return $this->processForgot($request);
    }
}
