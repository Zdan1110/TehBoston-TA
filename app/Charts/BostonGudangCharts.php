<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BostonGudangCharts
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($bulan, $data): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $bulan = $bulan ?? \Carbon\Carbon::now()->format('Y-m');

        list($tahun, $angkaBulan) = explode('-', $bulan);

        $jumlahPemasukan = DB::table('tb_pemasukan')
            ->join('tb_transaksi', 'tb_pemasukan.id_transaksi', '=', 'tb_transaksi.id_transaksi')
            ->join('tb_bahanbaku', 'tb_pemasukan.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
            ->whereYear('tanggal_transaksi', $tahun)
            ->whereMonth('tanggal_transaksi', $angkaBulan)
            ->where('tb_bahanbaku.jenis_bahan', $data)
            ->where('tb_transaksi.jenis_transaksi', '=', 'Pemasukan')
            ->sum('tb_pemasukan.jumlah');

        $jumlahPengeluaran = DB::table('tb_transaksi')
            ->join('tb_pengeluaran', 'tb_transaksi.id_transaksi', '=', 'tb_pengeluaran.id_transaksi')
            ->join('tb_detailpengeluaran', 'tb_pengeluaran.id_pengeluaran', '=', 'tb_detailpengeluaran.id_pengeluaran')
            ->join('tb_bahanbaku', 'tb_detailpengeluaran.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
            ->whereYear('tanggal_transaksi', $tahun)
            ->whereMonth('tanggal_transaksi', $angkaBulan)
            ->where('tb_bahanbaku.jenis_bahan', $data)
            ->where('tb_transaksi.jenis_transaksi', '=', 'Pengeluaran')
            ->sum('tb_detailpengeluaran.jumlah');

            $tanggalfix = Carbon::parse($bulan)->format('d/m/Y');
            $total = $jumlahPemasukan + $jumlahPengeluaran;

            if ($total == 0) {
                return $this->chart->pieChart()
                    ->setTitle('')
                    ->setLabels(['Tidak Ada Data'])
                    ->addData([1])
                    ->setWidth(500)
                    ->setHeight(400)
                    ->setOptions([
                        'title' => [
                            'style' => [
                                'fontSize' => '16px'
                            ]
                        ],
                        'responsive' => [
                            [
                                'breakpoint' => 768,
                                'options' => [
                                    'chart' => [
                                        'width' => 400
                                    ],
                                    'title' => [
                                        'style' => [
                                            'fontSize' => '12px'
                                        ]
                                    ],
                                    'legend' => [
                                        'position' => 'bottom'
                                    ]
                                ]
                            ]
                        ]
                    ]);
            }

        return $this->chart->pieChart()
            ->setTitle('')
            ->setLabels(['Pemasukan', 'Pengeluaran'])
            ->addData([(int)$jumlahPemasukan, (int)$jumlahPengeluaran])
            ->setWidth(500) 
            ->setHeight(400)
            ->setOptions([
                'title' => [
                    'style' => [
                        'fontSize' => '16px'
                    ]
                ],
                'responsive' => [
                    [
                        'breakpoint' => 768,
                        'options' => [
                            'chart' => [
                                'width' => 400
                            ],
                            'title' => [
                                'style' => [
                                    'fontSize' => '12px'
                                ]
                            ],
                            'legend' => [
                                'position' => 'bottom'
                            ]
                        ]
                    ]
                ]
            ]);
    }
}
