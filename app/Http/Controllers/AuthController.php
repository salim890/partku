<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    
    public function login(Request $request)
    {
        $credentials = $request->validate(['email' => 'required|email', 'password' => 'required']);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            ActivityLog::create(['user_id' => Auth::id(), 'action' => 'LOGIN_SUCCESS', 'description' => Auth::user()->name . ' berhasil login.', 'ip_address' => $request->ip()]);
            return redirect()->intended('/');
        }

        ActivityLog::create(['user_id' => null, 'action' => 'LOGIN_FAILED', 'description' => 'Percobaan login gagal dengan email: ' . $request->email, 'ip_address' => $request->ip()]);
        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function showRegister() { return view('auth.register'); }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Generate 6 digit OTP
        $otp = rand(100000, 999999);

        $user = User::create([
            'name' => $request->name, 
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => 'customer',
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10), // Berlaku 10 menit
        ]);

        // Kirim Email
        try {
            Mail::to($user->email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email verifikasi. Coba lagi nanti.']);
        }

        // Jangan login otomatis. Simpan email di session untuk verifikasi.
        session(['verify_email' => $user->email]);

        return redirect()->route('verify.otp.form')->with('success', 'Kode OTP telah dikirim ke email kamu.');
    }

    // Halaman Form Input OTP
    public function showVerifyForm()
    {
        if (!session('verify_email')) {
            return redirect()->route('register');
        }
        return view('auth.verify-otp');
    }

    // Proses Validasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp_code' => 'required|numeric|digits:6']);

        $email = session('verify_email');
        if (!$email) return redirect()->route('register');

        $user = User::where('email', $email)->first();

        if (!$user) return redirect()->route('register');

        // Cek apakah OTP cocok dan belum kedaluwarsa
        if ($user->otp_code === $request->otp_code && now()->lessThanOrEqualTo($user->otp_expires_at)) {
            // OTP Benar
            $user->update([
                'otp_code' => null, 
                'otp_expires_at' => null,
                'email_verified_at' => now()
            ]);

            Auth::login($user);
            $request->session()->forget('verify_email');
            
            ActivityLog::create(['user_id' => $user->id, 'action' => 'REGISTER', 'description' => 'Mendaftar akun baru (Verified OTP).', 'ip_address' => $request->ip()]);
            
            return redirect('/')->with('success', 'Akun berhasil diverifikasi! Selamat datang di PartKu.');
        }

        return back()->withErrors(['otp_code' => 'Kode OTP salah atau sudah kedaluwarsa.']);
    }

    public function logout(Request $request)
    {
        ActivityLog::create(['user_id' => Auth::id(), 'action' => 'LOGOUT', 'description' => 'Logout dari sistem.']);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}