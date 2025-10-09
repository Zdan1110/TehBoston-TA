<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    // Fungsi tambahan notifikasi
    private function tambahNotifikasi($pesan)
    {
        DB::table('notifikasi_admin')->insert([
            'pesan' => $pesan,
            'dibuat_pada' => now()
        ]);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $ulasan = DB::table('tb_ulasan')->get();

        if ($search) {
            $ulasan = collect($ulasan)->filter(function ($item) use ($search) {
                return stripos($item->nama_lengkap, $search) !== false ||
                    stripos($item->ulasan_pesan, $search) !== false ||
                    stripos((string)$item->rating, $search) !== false;
            });
        }

        return view('admin.v_tabellulasan', ['ulasan' => $ulasan]);
    }

    public function indexuser()
    {
        $testimonials = DB::table('tb_ulasan')
        ->where('status_tampil', 1)
        ->orderByDesc('id_ulasan')
        ->limit(10)
        ->get();

        return view('v_index', compact('testimonials'));
    }

    public function home()
    {
        $testimonials = DB::table('tb_ulasan')
        ->where('status_tampil', 1)
        ->orderByDesc('id_ulasan')
        ->limit(10)
        ->get();

        $idAkun = Auth::id();
        $sudahDaftar = DB::table('tb_calonmitra')->where('id_akun', $idAkun)->exists();

        return view('v_home', compact('testimonials', 'sudahDaftar'));
    }

    public function kkemitraanlog()
    {
        $isLoggedIn = Auth::check();
        $idAkun = Auth::id();
        $sudahDaftar = false;

        if ($isLoggedIn) {
            $sudahDaftar = DB::table('tb_calonmitra')->where('id_akun', $idAkun)->exists();
        }

        return view('v_kemitraanlog', compact('isLoggedIn', 'sudahDaftar'));
    }


    public function destroy($id)
    {
        $ulasan = DB::table('tb_ulasan')->where('id_ulasan', $id)->first();
        DB::table('tb_ulasan')->where('id_ulasan', $id)->delete();

        if ($ulasan) {
            $this->tambahNotifikasi("Ulasan dari {$ulasan->nama_lengkap} berhasil dihapus.");
        }

        return redirect()->back()->with('success', 'Ulasan berhasil dihapus.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'name' => 'required_without:user|string|max:255',
            'email' => 'required_without:user|email|max:255',
        ]);

        $lastUlasan = DB::table('tb_ulasan')->select('id_ulasan')->orderByDesc('id_ulasan')->first();

        $idUlasan = $lastUlasan
            ? 'U' . str_pad((int) substr($lastUlasan->id_ulasan, 1) + 1, 4, '0', STR_PAD_LEFT)
            : 'U0001';

        $sessionUser = Session::get('user');
        $idAkun = $sessionUser['id'] ?? null;
        $namaLengkap = $sessionUser['nama'] ?? $request->input('name');
        $email = $sessionUser['email'] ?? $request->input('email');

        if ($idAkun) {
            $akunExists = DB::table('tb_akun')->where('id', $idAkun)->exists();
            if (!$akunExists) {
                return redirect()->back()->withErrors(['Akun tidak ditemukan.']);
            }
        }

        DB::table('tb_ulasan')->insert([
            'id_ulasan'     => $idUlasan,
            'id_akun'       => $idAkun,
            'nama_lengkap'  => $namaLengkap,
            'email'         => $email,
            'rating'        => $request->input('rating'),
            'subjek'        => $request->input('subject'),
            'ulasan_pesan'  => $request->input('message'),
        ]);

        $this->tambahNotifikasi("Ulasan baru dari {$namaLengkap} berhasil dikirim.");

        return redirect()->back()->with('success', 'Ulasan berhasil dikirim!');
    }

    public function tampilkan($id)
    {
        $ulasan = DB::table('tb_ulasan')->where('id_ulasan', $id)->first();
        DB::table('tb_ulasan')->where('id_ulasan', $id)->update(['status_tampil' => 1]);

        if ($ulasan) {
            $this->tambahNotifikasi("Ulasan dari {$ulasan->nama_lengkap} ditampilkan ke halaman utama.");
        }

        return redirect()->back()->with('success', 'Ulasan berhasil ditampilkan.');
    }

    public function sembunyikan($id)
    {
        $ulasan = DB::table('tb_ulasan')->where('id_ulasan', $id)->first();
        DB::table('tb_ulasan')->where('id_ulasan', $id)->update(['status_tampil' => 0]);

        if ($ulasan) {
            $this->tambahNotifikasi("Ulasan dari {$ulasan->nama_lengkap} disembunyikan dari halaman utama.");
        }

        return redirect()->back()->with('success', 'Ulasan disembunyikan dari halaman depan.');
    }
}
