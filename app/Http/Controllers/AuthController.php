<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function index()
    {
        return view('absensi.login'); 
    }

    // Memproses login
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ])->onlyInput('email');
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }

    // ==========================================================
    // FITUR LUPA PASSWORD (LANGSUNG DI WEB)
    // ==========================================================

    // 1. Menampilkan halaman form input email
    public function showForgotPasswordForm()
    {
        return view('absensi.forgot-password');
    }

    // 2. Memproses pembuatan token & menampilkan link di web
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        // Validasi jika user tidak ditemukan
        if (!$user) {
            return back()->with('error', 'Email tidak terdaftar di sistem kami.');
        }

        // BUAT TABEL OTOMATIS JIKA BELUM ADA 
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        // Generate token acak
        $token = Str::random(60);

        // Simpan token ke database 
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );
        
        // Buat link reset
        $resetLink = route('password.reset', ['token' => $token]) . '?email=' . urlencode($user->email);

        // Kembalikan ke view dengan membawa variabel link reset
        return back()->with('reset_link', $resetLink);
    }

    // 3. Menampilkan halaman input password baru 
    public function showResetPasswordForm($token, Request $request)
    {
        $email = $request->email;
        return view('absensi.reset-password', compact('token', 'email'));
    }

    // 4. Memproses update password baru ke database
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Pastikan tabel sudah ada saat reset juga
        if (!Schema::hasTable('password_reset_tokens')) {
            return back()->with('error', 'Sistem error: Tabel reset tidak ditemukan.');
        }

        $resetData = DB::table('password_reset_tokens')
                        ->where('email', $request->email)
                        ->where('token', $request->token)
                        ->first();

        if (!$resetData) {
            return back()->with('error', 'Link reset password tidak valid atau sudah kadaluarsa.');
        }

        if (Carbon::parse($resetData->created_at)->addHours(1)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->with('error', 'Waktu reset sudah habis. Silakan ulangi.');
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = $request->password;
            $user->save();

            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return redirect('/login')->with('success', 'Password berhasil diperbarui! Silakan login.');
        }

        return back()->with('error', 'Gagal mengubah password.');
    }
}