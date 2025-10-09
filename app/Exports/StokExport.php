<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StokExport implements FromView
{
    protected $bahan;

    public function __construct($bahan)
    {
        $this->bahan = $bahan;
    }

    public function view(): View
    {
        return view('exports.stok_excel', [
            'bahan' => $this->bahan,
            'tanggalCetak' => now()->format('d-m-Y H:i'),
        ]);
    }
}
