<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import DB facade
use Illuminate\Support\Str; // Untuk meng-generate ID

class KontakController extends Controller
{
    /**
     * Menampilkan halaman kontak.
     */
    public function index()
    {
        return view('kontak');
    }

    /**
     * Menyimpan ulasan/pesan dari formulir.
     */
    public function storeUlasan(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'nama_lengkap' => 'required|string|max:30',
            'email' => 'required|email|max:30',
            'rating' => 'required|in:1,2,3,4,5', // Rating harus salah satu dari 1-5
            'subjek' => 'required|string|max:255',
            'ulasan_pesan' => 'required|string|max:255',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max' => 'Nama lengkap tidak boleh lebih dari 30 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email tidak boleh lebih dari 30 karakter.',
            'rating.required' => 'Rating wajib diberikan.',
            'rating.in' => 'Rating tidak valid.',
            'subjek.required' => 'Subjek wajib diisi.',
            'subjek.max' => 'Subjek tidak boleh lebih dari 255 karakter.',
            'ulasan_pesan.required' => 'Ulasan/Pesan wajib diisi.',
            'ulasan_pesan.max' => 'Ulasan/Pesan tidak boleh lebih dari 255 karakter.',
        ]);

        // 2. Generate id_ulasan unik (misal: ULAS0001, ULAS0002, dst.)
        // Anda perlu memastikan tabel `ulasan` memiliki data minimal satu untuk ini berfungsi,
        // atau tangani kasus ketika tabel masih kosong.
        // Untuk ID yang benar-benar unik dan acak, bisa gunakan Str::random(10) atau UUID.
        // Contoh ini akan mengambil ID terakhir dan menaikkannya.
        try {
            // Ambil ID ulasan terakhir untuk menentukan ID berikutnya
            $latestUlasan = DB::table('ulasan')
                            ->orderByDesc('id_ulasan') // Menggunakan orderByDesc
                            ->first();

            $nextIdNumber = 1;
            if ($latestUlasan) {
                // Asumsi format ID adalah 'ULASXXXX'
                $lastIdNumber = (int) substr($latestUlasan->id_ulasan, 4);
                $nextIdNumber = $lastIdNumber + 1;
            }
            $id_ulasan = 'ULAS' . str_pad($nextIdNumber, 4, '0', STR_PAD_LEFT);

            // 3. Simpan Data ke Database menggunakan Query Builder
            DB::table('ulasan')->insert([
                'id_ulasan' => $id_ulasan,
                'id_akun' => auth()->check() ? auth()->user()->id_akun : null, // Ambil id_akun jika pengguna login, jika tidak null
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'rating' => $request->rating,
                'subjek' => $request->subjek,
                'ulasan_pesan' => $request->ulasan_pesan,
                // Jika ada kolom `created_at` atau `updated_at` di tabel Anda,
                // dan Anda tidak menggunakan timestamp Eloquent, Anda perlu menambahkannya secara manual:
                // 'created_at' => now(),
                // 'updated_at' => now(),
            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('kontak.index')->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');

        } catch (\Exception $e) {
            // Redirect dengan pesan error
            // Untuk debugging, bisa print $e->getMessage()
            // return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat mengirim ulasan: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Kamu Belum Login. Login Dulu Yuk.');
        }
    }
}