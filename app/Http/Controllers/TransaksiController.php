<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter');
    
        $query = DB::table('tb_transaksi');
    
        if ($filter == 'pemasukan') {
            $query->whereNotNull('id_pemasukan');
        } elseif ($filter == 'pengeluaran') {
            $query->whereNotNull('id_pengeluaran');
        }
    
        $transaksi = $query->get();
    
        return view('admin.v_tabeltransaksi', compact('transaksi'));
    }
    



    public function create()
    {
        return view('admin.v_inputtransaksi');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'transaksi' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'supplier' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        try {
            // Ambil transaksi terakhir berdasarkan id_transaksi
            $lastTransaction = Transaksi::orderBy('id_transaksi', 'desc')->first();

            if ($lastTransaction && preg_match('/TR(\d+)/', $lastTransaction->id_transaksi, $matches)) {
                $lastNumber = intval($matches[1]);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            // Format id_transaksi sebagai TR0001, TR0002, dst
            $idTransaksi = 'TR' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            // Tentukan pemasukan/pengeluaran
            $pemasukan = $request->input('jenis') === 'pemasukan' ? $request->input('jumlah') : 0;
            $pengeluaran = $request->input('jenis') === 'pengeluaran' ? $request->input('jumlah') : 0;

            Transaksi::create([
                'id_transaksi' => $idTransaksi,
                'transaksi' => $request->input('transaksi'),
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'supplier' => $request->input('supplier'),
                'keterangan' => $request->input('keterangan'),
            ]);

            return response()->json(['success' => true, 'message' => 'Transaksi berhasil disimpan!'], 200);

        } catch (\Exception $e) {
            Log::error('Error saving transaction: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        // Kosong
    }

    public function edit(string $id)
    {
        // Kosong
    }

    public function update(Request $request, string $id)
    {
        // Kosong
    }

    public function destroy(string $id)
    {
        try {
            $transaksi = Transaksi::findOrFail($id);
            $transaksi->delete();
            return redirect('/admin/transaksi')->with('success', 'Transaksi berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting transaction: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}
