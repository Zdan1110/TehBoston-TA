<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class C_Status extends Controller
{
        public function cek(Request $request)
        {
            $id = trim($request->input('id_pendaftar'));

            $calon = DB::table('tb_calonmitra')
                ->where('id_calon', $id)
                ->first();

            if ($calon) {
                return view('status', ['calon' => $calon]); // ⬅ ini harus cocok
            }

            // Jika tidak ditemukan, coba cari di tb_franchisebaru
            $franchise = DB::table('tb_franchisebaru')
            ->leftJoin('tb_mitra', 'tb_franchisebaru.id_mitra', '=', 'tb_mitra.id_mitra')
            ->where('id_franchisebaru', $id)
            ->select('tb_franchisebaru.*', 'tb_mitra.nama_lengkap')
            ->first();

            if ($franchise) {
                return view('statusfranchise', ['franchise' => $franchise]); // pakai view yang sama
            }

            // Jika tidak ditemukan di keduanya
            return redirect()->back()->with('error', 'ID tidak ditemukan.');
        }

}


