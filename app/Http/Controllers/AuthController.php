<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    
    public function login(Request $request)
    {
        $credentials = $request->validate(['email' => 'required|email', 'password' => 'required']);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Fitur Cart Merge (Bab 5.5)
            if ($request->session()->has('cart')) {
                // Di dunia nyata, kita memindahkan cart session ke tabel database 'carts' milik user.
                // Karena kita pakai session, kita cukup log aktivitasnya.
                \App\Models\ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'CART_MERGE',
                    'description' => 'Menggabungkan keranjang tamu ke akun.',
                ]);
            }
            
            // Log Security: Login Berhasil
            ActivityLog::create([
                'user_id' => Auth::id(), 'action' => 'LOGIN_SUCCESS', 
                'description' => Auth::user()->name . ' berhasil login.', 'ip_address' => $request->ip()
            ]);
            return redirect()->intended('/');
        }

        // Log Security: Login Gagal
        ActivityLog::create([
            'user_id' => null, 'action' => 'LOGIN_FAILED', 
            'description' => 'Percobaan login gagal dengan email: ' . $request->email, 'ip_address' => $request->ip()
        ]);

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function showRegister() { return view('auth.register'); }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
'terms' => 'accepted',
        ]);

        $user = User::create([
            'name' => $request->name, 'email' => $request->email,
            'password' => Hash::make($request->password), 'role' => 'customer',
        ]);

        Auth::login($user);
        ActivityLog::create([
            'user_id' => $user->id, 'action' => 'REGISTER', 
            'description' => 'Mendaftar akun baru.', 'ip_address' => $request->ip()
        ]);
return redirect('/')->with('success', 'Akun berhasil dibuat! Selamat datang di PartKu.');

        return redirect('/');
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