<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TransaksiAdminExport implements FromView
{
    protected $data, $bulan, $tahun;

    public function __construct($data, $bulan = null, $tahun = null)
    {
        $this->data = $data;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        return view('exports.transaksiadmin_excel', [
            'transaksi' => $this->data,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
        ]);
    }
}
