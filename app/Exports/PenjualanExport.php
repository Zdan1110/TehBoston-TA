<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class PenjualanExport implements FromCollection, WithHeadings, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $rows = collect();
        $totalModal = 0;
        $totalJual = 0;
        $totalLaba = 0;

        foreach ($this->data as $item) {
            $modal = $item->harga_modal_total;
            $jual = $item->harga_jual_total;
            $laba = $item->laba;

            $rows->push([
                'Nama Produk' => $item->nama_bahan,
                'Jumlah' => $item->jumlah,
                'Total Modal (Rp)' => $modal,
                'Total Jual (Rp)' => $jual,
                'Laba (Rp)' => $laba,
            ]);

            $totalModal += $modal;
            $totalJual += $jual;
            $totalLaba += $laba;
        }

        $rows->push([]);
        $rows->push([
            'Nama Produk' => '',
            'Jumlah' => 'TOTAL',
            'Total Modal (Rp)' => $totalModal,
            'Total Jual (Rp)' => $totalJual,
            'Laba (Rp)' => $totalLaba,
        ]);

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Nama Produk',
            'Jumlah',
            'Total Modal (Rp)',
            'Total Jual (Rp)',
            'Laba (Rp)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle("A{$highestRow}:F{$highestRow}")->getFont()->setBold(true);
        $sheet->getStyle("C2:F{$highestRow}")
            ->getNumberFormat()
            ->setFormatCode('#,##0');
        $sheet->getStyle('A2:C' . $highestRow)->getAlignment()->setHorizontal('center');
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }
}
