<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PengeluaranExport implements FromView
{
    protected $data;
    protected $bulan;
    protected $tahun;

    // beri default agar lebih tahan banting jika dipanggil tanpa semua argumen
    public function __construct($data, $bulan = null, $tahun = null)
    {
        $this->data = $data;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        // kirim variabel dengan nama 'pengeluaran' supaya blade konsisten
        return view('exports.pengeluaran_excel', [
            'pengeluaran' => $this->data,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
        ]);
    }
}
