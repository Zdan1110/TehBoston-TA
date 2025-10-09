<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Strukdc;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class StrukdcController extends Controller
{
    
       public function index(Request $request)
    {
        $query = Strukdc::query();
    
        // 🔍 Filter keyword
        if ($request->filled('keyword')) {
            $query->where('id_transaksi', 'like', "%{$request->keyword}%");
        }
    
        // 📅 Filter bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_transaksi', $request->bulan);
        }
    
        // 📆 Filter tahun
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_transaksi', $request->tahun);
        }
    
        $transaksi = $query->orderByDesc('tanggal_transaksi')->get();
    
        return view('gudang.strukdc', compact('transaksi'));
    }
    
    
    public function download($id)
    {
        $data = \App\Models\Strukdc::findOrFail($id);
    
        $fileName = $data->struk ?: 'nota_' . $id . '.pdf';
        $filePath = public_path('uploads/strukdc/' . $fileName);
    
        // cek dua versi nama file
        if (!file_exists($filePath)) {
            $altFile = 'nota_' . $id . '.pdf';
            $altPath = public_path('uploads/strukdc/' . $altFile);
            if (file_exists($altPath)) {
                $filePath = $altPath;
                $fileName = $altFile;
            } else {
                return back()->with('error', "File struk tidak ditemukan untuk transaksi {$id}.");
            }
        }
    
        return response()->download($filePath, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
    }


}
