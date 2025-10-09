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
            ->distinct() // pastikan hanya 1 per transaksi
            ->sum('tb_transaksi.total');

        $jumlahPengeluaran = DB::table('tb_transaksi')
            ->join('tb_pengeluaran', 'tb_transaksi.id_transaksi', '=', 'tb_pengeluaran.id_transaksi')
            ->join('tb_bahanbaku', 'tb_pengeluaran.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
            ->whereYear('tanggal_transaksi', $tahun)
            ->whereMonth('tanggal_transaksi', $angkaBulan)
            ->where('tb_bahanbaku.jenis_bahan', $data)
            ->where('tb_transaksi.jenis_transaksi', '=', 'Pengeluaran')
            ->distinct() // pastikan hanya 1 per transaksi
            ->sum('tb_transaksi.total');

            $tanggalfix = Carbon::parse($bulan)->format('d/m/Y');
            $total = $jumlahPemasukan + $jumlahPengeluaran;

            if ($total == 0) {
                return $this->chart->pieChart()
                    ->setTitle("Distribusi Kosong ($data - $tanggalfix)")
                    ->setLabels(['Tidak Ada Data'])
                    ->addData([1])
                    ->setWidth(400) // default untuk PC
                    ->setHeight(400)
                    ->setOptions([
                        'responsive' => [
                            [
                                'breakpoint' => 768, // untuk perangkat <768px seperti smartphone
                                'options' => [
                                    'chart' => [
                                        'width' => 400
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
            ->setTitle("Distribusi Pemasukan dan Pengeluaran ($data - $tanggalfix)")
            ->setLabels(['Pemasukan', 'Pengeluaran'])
            ->addData([(int)$jumlahPemasukan, (int)$jumlahPengeluaran])
            ->setWidth(400) // default untuk PC
            ->setHeight(400)
            ->setOptions([
                'responsive' => [
                    [
                        'breakpoint' => 768, // untuk perangkat <768px seperti smartphone
                        'options' => [
                            'chart' => [
                                'width' => 400
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
