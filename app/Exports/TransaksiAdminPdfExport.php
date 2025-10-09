<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TransaksiAdminPdfExport implements FromView
{
    protected $data, $bulan, $tahun;

    public function __construct($data, $bulan, $tahun)
    {
        $this->data = $data;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        return view('exports.transaksiadmin_pdf', [
            'data' => $this->data,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
        ]);
    }
}
