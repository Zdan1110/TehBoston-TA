<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class RiwayatMasukExport implements FromView
{
    protected $from, $to, $keywordMasuk;

    public function __construct($from, $to, $keywordMasuk)
    {
        $this->from = $from;
        $this->to = $to;
        $this->keywordMasuk = $keywordMasuk;
    }

    public function view(): View
    {
        $riwayatmasuk = DB::table('tb_transaksi')
            ->join('tb_pemasukan', 'tb_pemasukan.id_transaksi', '=', 'tb_transaksi.id_transaksi')
            ->join('tb_bahanbaku', 'tb_pemasukan.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
            ->leftJoin('tb_supplier', 'tb_bahanbaku.id_supplier', '=', 'tb_supplier.id_supplier')
            ->select(
                'tb_transaksi.id_transaksi',
                'tb_transaksi.tanggal_transaksi',
                DB::raw('GROUP_CONCAT(tb_bahanbaku.nama_bahan SEPARATOR ", ") as nama_bahan'),
                DB::raw('GROUP_CONCAT(tb_pemasukan.jumlah SEPARATOR ", ") as jumlah'),
                DB::raw('GROUP_CONCAT(tb_pemasukan.harga SEPARATOR ", ") as harga'),
                DB::raw('GROUP_CONCAT(tb_bahanbaku.satuan SEPARATOR ", ") as satuan'),
                'tb_supplier.nama_supplier',
                'tb_transaksi.total',
                DB::raw('MAX(tb_pemasukan.created_at) as created_at'),
                DB::raw('MAX(tb_pemasukan.updated_at) as updated_at')
            )
            ->where('tb_transaksi.jenis_transaksi', '=', 'Pemasukan')
            ->when($this->keywordMasuk, function ($query, $keywordMasuk) {
                $query->where(function ($q) use ($keywordMasuk) {
                    $q->where('tb_bahanbaku.nama_bahan', 'like', "%$keywordMasuk%")
                      ->orWhere('tb_supplier.nama_supplier', 'like', "%$keywordMasuk%");
                });
            })
            ->when($this->from && $this->to, function ($query) {
                $query->whereBetween('tb_transaksi.tanggal_transaksi', [$this->from, $this->to]);
            })
            ->groupBy('tb_transaksi.id_transaksi', 'tb_transaksi.tanggal_transaksi', 'tb_supplier.nama_supplier', 'tb_transaksi.total')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('exports.riwayatmasuk_excel', compact('riwayatmasuk'));
    }
}
