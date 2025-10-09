<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class RiwayatKeluarExport implements FromView
{
    protected $from, $to, $keywordKeluar;

    public function __construct($from, $to, $keywordKeluar)
    {
        $this->from = $from;
        $this->to = $to;
        $this->keywordKeluar = $keywordKeluar;
    }

    public function view(): View
    {
        $riwayatkeluar = DB::table('tb_transaksi')
            ->join('tb_pengeluaran', 'tb_pengeluaran.id_transaksi', '=', 'tb_transaksi.id_transaksi')
            ->leftJoin('tb_bahanbaku', 'tb_pengeluaran.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
            ->leftJoin('tb_franchise', 'tb_pengeluaran.id_franchise', '=', 'tb_franchise.id_franchise')
            ->select(
                'tb_transaksi.id_transaksi',
                'tb_transaksi.tanggal_transaksi',
                DB::raw('GROUP_CONCAT(tb_bahanbaku.nama_bahan SEPARATOR ", ") as nama_bahan'),
                DB::raw('GROUP_CONCAT(tb_pengeluaran.jumlah SEPARATOR ", ") as jumlah'),
                DB::raw('GROUP_CONCAT(tb_pengeluaran.harga SEPARATOR ", ") as harga'),
                DB::raw('GROUP_CONCAT(tb_bahanbaku.satuan SEPARATOR ", ") as satuan'),
                DB::raw('GROUP_CONCAT(tb_franchise.nama_franchise SEPARATOR ", ") as nama_franchise'),
                DB::raw('GROUP_CONCAT(tb_franchise.alamat_usaha SEPARATOR ", ") as alamat_usaha'),
                'tb_transaksi.total',
                DB::raw('MAX(tb_pengeluaran.created_at) as created_at'),
                DB::raw('MAX(tb_pengeluaran.updated_at) as updated_at')
            )
            ->where('tb_transaksi.jenis_transaksi', '=', 'Pengeluaran')
            ->when($this->keywordKeluar, function ($query, $keywordKeluar) {
                $query->where(function ($q) use ($keywordKeluar) {
                    $q->where('tb_bahanbaku.nama_bahan', 'like', "%$keywordKeluar%")
                      ->orWhere('tb_franchise.nama_franchise', 'like', "%$keywordKeluar%");
                });
            })
            ->when($this->from && $this->to, function ($query) {
                $query->whereBetween('tb_transaksi.tanggal_transaksi', [$this->from, $this->to]);
            })
            ->groupBy(
                'tb_transaksi.id_transaksi',
                'tb_transaksi.tanggal_transaksi',
                'tb_transaksi.total'
            )
            ->orderBy('created_at', 'desc')
            ->get();

        return view('exports.riwayatkeluar_excel', compact('riwayatkeluar'));
    }
}
