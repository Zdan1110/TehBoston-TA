<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BostonGudangChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($tanggal, $data): \ArielMejiaDev\LarapexCharts\LineChart
    {
        $tanggal = $tanggal ?? Carbon::today()->toDateString();

        $jumlahPemasukan = DB::table('tb_pemasukan')
                ->join('tb_transaksi', 'tb_pemasukan.id_pemasukan', '=', 'tb_transaksi.id_pemasukan')
                ->join('tb_bahanbaku', 'tb_pemasukan.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
                ->whereDate('tb_transaksi.tanggal_transaksi', $tanggal)
                ->where('tb_bahanbaku.jenis_bahan', $data)
                ->count();

        $jumlahPengeluaran = DB::table('tb_pengeluaran')
                ->join('tb_transaksi', 'tb_pengeluaran.id_pengeluaran', '=', 'tb_transaksi.id_pengeluaran')
                ->join('tb_bahanbaku', 'tb_pengeluaran.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
                ->whereDate('tb_transaksi.tanggal_transaksi', $tanggal)
                ->where('tb_bahanbaku.jenis_bahan', $data)
                ->count();

        return $this->chart->lineChart()
            ->setTitle('Jumlah Pemasukan dan Pengeluaran')
            ->setXAxis([$tanggal])
            ->addLine('Pemasukan', [$jumlahPemasukan])
            ->addLine('Pengeluaran', [$jumlahPengeluaran]);
    }

}
