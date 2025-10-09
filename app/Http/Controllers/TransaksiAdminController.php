<?php

namespace App\Http\Controllers;

use App\Models\TransaksiAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Exports\TransaksiAdminExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class TransaksiAdminController extends Controller
{
    public function index(Request $request)
{
    $query = \App\Models\TransaksiAdmin::query();

    if ($request->filter) {
        $query->where('jenis', $request->filter);
    }

    if ($request->bulan) {
        $query->whereMonth('created_at', $request->bulan);
    }

    if ($request->tahun) {
        $query->whereYear('created_at', $request->tahun);
    }

    $transaksi = $query->orderBy('created_at', 'desc')->get();

    return view('gudang.v_tabeltransaksi', compact('transaksi'));
}


    public function create()
    {
        return view('gudang.v_inputtransaksi');
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaksi' => 'required|string|max:255',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $id_transaksi = 'TD' . str_pad(TransaksiAdmin::count() + 1, 4, '0', STR_PAD_LEFT);

            TransaksiAdmin::create([
                'id_transaksi' => $id_transaksi,
                'transaksi' => $request->transaksi,
                'jenis' => $request->jenis,
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan,
            ]);

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

public function edit($id)
{
    try {
        $transaksi = TransaksiAdmin::where('id_transaksi', $id)->firstOrFail();
        return view('gudang.v_edittransaksi', compact('transaksi'));
    } catch (\Exception $e) {
        dd($e->getMessage()); // <--- tambahkan ini sementara untuk lihat error sebenarnya
    }
}


    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis' => 'required',
            'transaksi' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $transaksi = TransaksiAdmin::where('id_transaksi', $id)->firstOrFail();
        $transaksi->update($request->only(['jenis', 'transaksi', 'jumlah', 'keterangan']));

        return redirect()->route('gudang.transaksi.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy($id)
{
    try {
        $transaksi = TransaksiAdmin::where('id_transaksi', $id)->firstOrFail();
        $transaksi->delete();

        return response()->json(['success' => true, 'message' => 'Transaksi berhasil dihapus']);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
}

public function exportExcel(Request $request)
{
    $bulan = $request->bulan;
    $tahun = $request->tahun;

    $query = DB::table('tb_transaksiadmin');

    if ($bulan) {
        $query->whereMonth('created_at', $bulan);
    }

    if ($tahun) {
        $query->whereYear('created_at', $tahun);
    }

    $data = $query->orderBy('created_at', 'desc')->get();

    // Format tanggal sekarang untuk nama file
    $tanggalSekarang = Carbon::now()->format('Y-m-d');
    $namaFile = "TransaksiAdmin_{$tanggalSekarang}.xlsx";

    return Excel::download(new TransaksiAdminExport($data, $bulan, $tahun), $namaFile);
}

public function exportPdf(Request $request)
{
    $bulan = $request->bulan;
    $tahun = $request->tahun;

    $data = DB::table('tb_transaksiadmin')->get();

    if ($bulan) {
        $query->whereMonth('created_at', $bulan);
    }

    if ($tahun) {
        $query->whereYear('created_at', $tahun);
    }


    $tanggalSekarang = Carbon::now()->format('Y-m-d');
    $pdf = Pdf::loadView('exports.transaksiadmin_pdf', compact('data', 'bulan', 'tahun'))
            ->setPaper('a4', 'landscape');
    return $pdf->download("TransaksiAdmin_{$tanggalSekarang}.pdf");
}


}
