<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OmsetExport implements FromCollection, WithHeadings, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $rows = collect();
        $totalJual = 0;
        $totalLaba = 0;

        foreach ($this->data as $item) {
            $totalModal = $item->harga_modal * $item->jumlah;
            $totalJualItem = $item->harga_jual * $item->jumlah;
            $laba = $totalJualItem - $totalModal;

            $rows->push([
                'Tanggal' => \Carbon\Carbon::parse($item->created_at)->format('d-m-Y'),
                'Nama Produk' => $item->nama_bahan,
                'Jumlah' => $item->jumlah,
                'Total Modal' => $totalModal,
                'Total Jual' => $totalJualItem,
                'Laba' => $laba,
            ]);

            $totalJual += $totalJualItem;
            $totalLaba += $laba;
        }

        // Tambahkan baris kosong + total
        $rows->push([]);
        $rows->push([
            'Tanggal' => '',
            'Nama Produk' => '',
            'Jumlah' => '',
            'Total Modal' => 'Total:',
            'Total Jual' => $totalJual,
            'Keuntungan' => $totalLaba,
        ]);

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Produk',
            'Jumlah',
            'Total Modal',
            'Total Jual',
            'Laba'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        // Bold heading
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        // Bold total row
        $sheet->getStyle("A{$highestRow}:F{$highestRow}")->getFont()->setBold(true);

        // Format angka dengan separator ribuan
        $sheet->getStyle("C2:F{$highestRow}")
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        return [];
    }
}
