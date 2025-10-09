<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class C_LupaPassword extends Controller
{
    // 🔸 Tampilkan halaman lupa password
    public function showForgot()
    {
        return view('v_forgot');
    }

    // 🔸 Proses kirim OTP ke email
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Cek apakah email ada di tb_akun
        $cekEmail = DB::table('tb_akun')->where('email', $request->email)->first();

        if (!$cekEmail) {
            return redirect()->back()->with('error', 'Email tidak terdaftar.');
        }

        // Generate kode OTP (6 digit angka)
        $kodeOtp = random_int(100000, 999999);

        // Simpan ke table password_resets
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $kodeOtp,
                'created_at' => Carbon::now()
            ]
        );

        // Kirim email OTP
        try {
            Mail::raw("Kode OTP untuk reset password Teh Boston Anda adalah: {$kodeOtp}", function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Kode OTP Reset Password - Teh Boston');
            });

            return redirect('/resetpassword')->with('success', 'Kode OTP berhasil dikirim ke email Anda.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim email. Pastikan konfigurasi email benar.');
        }
    }

    // 🔸 Tampilkan halaman reset password
    public function showReset()
    {
        return view('v_resetpass');
    }

    // 🔸 Proses reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'kode' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        // Cari kode OTP
        $cekKode = DB::table('password_resets')
            ->where('token', $request->kode)
            ->first();

        if (!$cekKode) {
            return redirect()->back()->with('error', 'Kode OTP tidak valid atau salah.');
        }

        // Update password user di tb_akun berdasarkan email
        DB::table('tb_akun')
            ->where('email', $cekKode->email)
            ->update([
                'password' => bcrypt($request->password)
            ]);

        // Hapus kode OTP dari table password_resets
        DB::table('password_resets')->where('email', $cekKode->email)->delete();

        return redirect('/login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }
}
