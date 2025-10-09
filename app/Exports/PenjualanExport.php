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
            $modal = $item->harga_modal * $item->jumlah;
            $jual = $item->harga_jual * $item->jumlah;
            $laba = $jual - $modal;

            $rows->push([
                'Tanggal' => Carbon::parse($item->created_at)->format('d-m-Y'),
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

        // Tambahkan baris kosong & total di akhir
        $rows->push([]);
        $rows->push([
            'Tanggal' => '',
            'Nama Produk' => '',
            'Jumlah' => '',
            'Total Modal (Rp)' => $totalModal,
            'Total Jual (Rp)' => $totalJual,
            'Laba (Rp)' => $totalLaba,
        ]);

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
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

        // Bold header
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        // Bold total baris terakhir
        $sheet->getStyle("A{$highestRow}:F{$highestRow}")->getFont()->setBold(true);

        // Format angka (jumlah, modal, jual, laba)
        $sheet->getStyle("C2:F{$highestRow}")
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        // Rata tengah untuk kolom tanggal & jumlah
        $sheet->getStyle('A2:C' . $highestRow)->getAlignment()->setHorizontal('center');

        // Auto width kolom
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }
}
