<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Pastikan ini mengarah ke model User Anda
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; // Import Mail facade
use App\Mail\OtpMail; // Import Mailable Anda
use Carbon\Carbon; // Untuk mengelola waktu kadaluarsa OTP
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function showRegisterForm()
    {
        return view('v_registrasi');
    }

    public function register(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:tb_akun,email',
        'no_hp' => 'required|string|max:15',
        'username' => 'required|string|max:255|unique:tb_akun,username',
        'password' => 'required|string|min:6|confirmed',
    ], [
        'nama.required' => 'Nama wajib di isi',
        'email.required' => 'Email wajib di isi',
        'email.email' => 'Format email tidak valid',
        'email.unique' => 'Email sudah terdaftar, gunakan email lain!',
        'no_hp.required' => 'Nomor Telepon wajib di isi',
        'username.required' => 'Username wajib di isi',
        'username.unique' => 'Username sudah dipakai, gunakan username lain!',
        'password.required' => 'Password wajib di isi',
        'password.min' => 'Password minimal 6 karakter',
        'password.confirmed' => 'Konfirmasi password tidak cocok',
    ]);

    // 🔹 Generate id_akun otomatis dalam format A0001, A0002, ...
    $lastUser = User::orderByDesc('id_akun')->first();
    if ($lastUser) {
        $lastNumber = intval(substr($lastUser->id_akun, 1)); // buang huruf A
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }
    $newId = 'A' . str_pad($newNumber, 4, '0', STR_PAD_LEFT); // hasil: A0001, A0002

    $data = [
        'id_akun' => $newId,
        'email' => $request->email,
        'nama' => $request->nama,
        'no_hp' => $request->no_hp,
        'username' => $request->username,
        'password' => bcrypt($request->password),
        'type_akun' => 'user',
    ];


    try {
        User::create($data);
        Log::info('User berhasil ditambahkan', $data);
    } catch (\Exception $e) {
        Log::error('Gagal menyimpan user: ' . $e->getMessage());
        return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat pendaftaran.']);
    }

    return redirect('/login')->with('success', 'Pendaftaran berhasil! Silakan login.');
}


    // Fungsi untuk menampilkan form edit
    public function showEditForm()
    {
        $user = Auth::user(); // Mengambil user yang sedang login
        return view('v_editakun', compact('user'));
    }

    // Fungsi untuk memproses permintaan update data (mengirim OTP)
    public function update(Request $request)
    {
        $user = Auth::user(); // User yang sedang login akan memiliki properti id_akun jika modelnya benar

        $request->validate([
            'nama' => 'required|string|max:255',
            // Perhatikan: unique rule sekarang menggunakan 'id_akun' sebagai primary key column name
            'email' => 'required|email|unique:tb_akun,email,' . $user->id_akun . ',id_akun',
            'no_hp' => 'required|string|max:15',
            // Perhatikan: unique rule sekarang menggunakan 'id_akun' sebagai primary key column name
            'username' => 'required|string|max:255|unique:tb_akun,username,' . $user->id_akun . ',id_akun',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'nama.required' => 'Nama wajib di isi',
            'email.required' => 'Email wajib di isi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar, gunakan email lain!',
            'no_hp.required' => 'Nomor Telepon wajib di isi',
            'username.required' => 'Username wajib di isi',
            'username.unique' => 'Username sudah dipakai, gunakan username lain!',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        // Simpan data yang akan diupdate ke session, untuk digunakan setelah verifikasi OTP
        $request->session()->put('pending_update_data', [
            'email' => $request->email,
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'username' => $request->username,
            'password' => $request->password ? bcrypt($request->password) : null,
        ]);

        // Generate OTP
        $otp = random_int(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10); // OTP berlaku selama 10 menit

        // Simpan OTP di record user
        DB::table('tb_akun')
            ->where('id_akun', $user->id_akun) // UBAH 'id' menjadi 'id_akun'
            ->update([
                'otp_code' => $otp,
                'otp_expires_at' => $expiresAt,
            ]);

        // Kirim email OTP
        try {
            Mail::to($request->email)->send(new OtpMail($otp));
            Log::info('OTP sent to ' . $request->email, ['otp' => $otp]);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim kode OTP: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengirim kode OTP. Silakan coba lagi. Pastikan konfigurasi email sudah benar.');
        }

        return redirect()->route('user.show-otp-form')->with('success', 'Kode OTP telah dikirim ke email Anda. Silakan cek inbox Anda.');
    }

    // Fungsi untuk menampilkan form verifikasi OTP
    public function showOtpForm()
    {
        // Pastikan ada data pembaruan yang tertunda di session
        if (!session()->has('pending_update_data')) {
            return redirect()->route('user.edit-profile')->with('error', 'Tidak ada data pembaruan yang tertunda.');
        }
        return view('v_verify_otp'); // Buat view ini di langkah berikutnya
    }

    // Fungsi untuk memverifikasi OTP dan melakukan update
    public function verifyOtpAndUpdate(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6', // Pastikan OTP 6 digit dan hanya angka
        ], [
            'otp.required' => 'Kode OTP wajib di isi.',
            'otp.numeric' => 'Kode OTP harus berupa angka.',
            'otp.digits' => 'Kode OTP harus 6 digit.',
        ]);

        $user = Auth::user();
        $enteredOtp = $request->input('otp');

        // Periksa validitas OTP
        // Pastikan user->otp_code ada dan tidak null
        // Gunakan $user->otp_code karena sudah menjadi properti dari model
        if ($user->otp_code !== null && $user->otp_code == $enteredOtp && Carbon::now()->lessThan($user->otp_expires_at)) {
            // OTP valid dan belum kadaluarsa, lanjutkan dengan update
            $pendingUpdateData = $request->session()->get('pending_update_data');

            // Hapus field password jika tidak diubah oleh user (nilai null)
            if ($pendingUpdateData['password'] === null) {
                unset($pendingUpdateData['password']);
            }

            DB::table('tb_akun')
                ->where('id_akun', $user->id_akun) // UBAH 'id' menjadi 'id_akun'
                ->update($pendingUpdateData);

            // Hapus data OTP dari record user setelah berhasil diverifikasi
            DB::table('tb_akun')
                ->where('id_akun', $user->id_akun) // UBAH 'id' menjadi 'id_akun'
                ->update([
                    'otp_code' => null,
                    'otp_expires_at' => null,
                ]);

            // Hapus data pembaruan yang tertunda dari session
            $request->session()->forget('pending_update_data');

            return redirect()->route('user.edit-profile')->with('success', 'Profil berhasil diperbarui!');
        } else {
            // OTP tidak valid atau sudah kadaluarsa
            return redirect()->back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kadaluarsa.'])->withInput();
        }
    }

    // Fungsi opsional untuk mengirim ulang OTP
    public function resendOtp(Request $request)
    {
        $user = Auth::user();

        if (!session()->has('pending_update_data')) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data pembaruan yang tertunda.'], 400);
        }

        $pendingData = session('pending_update_data');
        $emailToSendTo = $pendingData['email'];

        // Generate OTP baru
        $otp = random_int(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        // Update record user dengan OTP baru
        DB::table('tb_akun')
            ->where('id_akun', $user->id_akun) // UBAH 'id' menjadi 'id_akun'
            ->update([
                'otp_code' => $otp,
                'otp_expires_at' => $expiresAt,
            ]);

        // Kirim email OTP baru
        try {
            Mail::to($emailToSendTo)->send(new OtpMail($otp));
            Log::info('OTP resent to ' . $emailToSendTo, ['otp' => $otp]);
            return response()->json(['success' => true, 'message' => 'Kode OTP baru telah dikirimkan ke email Anda.']);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim ulang OTP: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengirim ulang kode OTP. Silakan coba lagi.'], 500);
        }
    }
} 