<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    // Halaman konsultasi (form + chat)
    public function index()
    {
        $chat = session()->get('chat_history', []);
        return view('konsultasi', compact('chat'));
    }

    // Kirim pesan konsultasi
    public function send(Request $request)
    {
        $request->validate([
            'motor'      => 'required',
            'tahun'      => 'required|numeric|min:2015',
            'complaint'  => 'required|min:10',
        ]);

        $chat = session()->get('chat_history', []);

        // Simpan pesan user
        $chat[] = [
            'from'     => 'user',
            'motor'    => $request->motor,
            'tahun'    => $request->tahun,
            'message'  => $request->complaint,
            'time'     => now()->format('H:i'),
        ];

        // Balasan otomatis admin (sederhana berbasis kata kunci)
        $reply = $this->autoReply($request->complaint, $request->motor);

        $chat[] = [
            'from'    => 'admin',
            'message' => $reply,
            'time'    => now()->format('H:i'),
        ];

        session()->put('chat_history', $chat);

        return redirect()->route('konsultasi')->with('success', 'Pesan terkirim! Admin akan membalas segera.');
    }

    // Logika balasan otomatis sederhana
    private function autoReply($complaint, $motor)
    {
        $complaint = strtolower($complaint);

        if (str_contains($complaint, 'bergetar') || str_contains($complaint, 'getar')) {
            return "Untuk Honda $motor yang bergetar, kemungkinan besar masalah di CVT. Cek V-Belt, Roller, dan Kampas Ganda. Kalau sudah 20.000 km, sebaiknya ganti V-Belt dan Roller sekaligus. Mau saya bantu carikan part-nya?";
        }

        if (str_contains($complaint, 'brebet') || str_contains($complaint, 'tersendat')) {
            return "Honda $motor brebet biasanya karena busi kotor, filter udara kotor, atau injektor kotor. Coba cek SPARK PLUG dan ELEMENT COMP AIR/C (FILTER UDARA). Kalau perlu ganti busi, saya rekomendasikan yang original Honda.";
        }

        if (str_contains($complaint, 'rem') || str_contains($complaint, 'blong')) {
            return "Rem Honda $motor yang kurang pakem kemungkinan kampas rem sudah tipis. Cek BRAKE PAD SET (depan) dan BRAKE SHOE SET (belakang). Kalau sudah tipis, sebaiknya segera ganti demi keselamatan.";
        }

        if (str_contains($complaint, 'aki') || str_contains($complaint, 'starter') || str_contains($complaint, 'mati')) {
            return "Kalau Honda $motor susah distarter atau lampu redup, kemungkinan aki lemah. Cek tegangan aki (normal 12.5V+). Kalau di bawah itu, sebaiknya ganti BATTERY (AKI) original Honda.";
        }

        if (str_contains($complaint, 'oli') || str_contains($complaint, 'ganti oli')) {
            return "Untuk Honda $motor, saya rekomendasikan AHM OIL MPX 2 10W-30 untuk pemakaian harian, atau AHM OIL SPX 2 kalau mau full synthetic. Ganti tiap 3.000 km atau 3 bulan sekali ya.";
        }

        if (str_contains($complaint, 'suara') || str_contains($complaint, 'bunyi')) {
            return "Suara kasar di Honda $motor bisa dari beberapa sumber: CVT (v-belt/roller), kampas ganda, atau bearing. Bisa dijelaskan lebih detail suaranya seperti apa dan kapan muncul? (saat jalan / idle / ngegas)";
        }

        return "Terima kasih atas pertanyaannya soal Honda $motor. Bisa dijelaskan lebih detail: kapan masalah muncul (saat idle, jalan pelan, atau ngegas), dan sudah berapa lama? Dari situ saya bisa bantu diagnosa lebih tepat.";
    }

    // Reset chat
    public function clear()
    {
        session()->forget('chat_history');
        return redirect()->route('konsultasi')->with('success', 'Riwayat chat dihapus.');
    }
}