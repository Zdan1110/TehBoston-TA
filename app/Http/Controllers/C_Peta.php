<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class C_Peta extends Controller
{
    public function showPetaFranchise()
    {
$lokasiFranchise = DB::table('tb_franchise')
    ->select('nama_franchise', 'titik_koordinat', 'alamat_usaha') // ⬅ Tambahkan alamat di sini
    ->whereNotNull('titik_koordinat')
    ->get()
    ->map(function ($item) {
        $koordinat = explode(',', $item->titik_koordinat);
        $item->latitude = isset($koordinat[0]) ? floatval($koordinat[0]) : null;
        $item->longitude = isset($koordinat[1]) ? floatval($koordinat[1]) : null;
        return $item;
    });


        return view('v_cabang', compact('lokasiFranchise'));
    }
}
