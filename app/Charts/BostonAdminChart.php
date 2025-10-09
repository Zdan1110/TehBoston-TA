<?php

namespace App\Charts;

use Illuminate\Support\Facades\DB;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;

class BostonAdminChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($bulanAwal = null, $bulanAkhir = null): \ArielMejiaDev\LarapexCharts\LineChart
    {

        $chart = $this->chart->lineChart()
            ->setTitle('Data Penjualan Franchise')
            ->setSubtitle('Total Penjualan per Franchise');

        // Ambil semua franchise (id dan nama)
        $franchises = DB::table('tb_franchise')
            ->select('id_franchise', 'nama_franchise')
            ->get();

        $rangeBulan = $this->getRangeBulan($bulanAwal, $bulanAkhir);
        $labelBulan = $this->getLabelBulan($rangeBulan);

        // Loop setiap franchise
        foreach ($franchises as $franchise) {
            $data = $this->getMonthlySalesByFranchise($franchise->id_franchise, $bulanAwal, $bulanAkhir, $rangeBulan);
            $chart->addData($franchise->nama_franchise, $data);
        }

        $chart->setXAxis($labelBulan);

        return $chart;
    }

    private function getMonthlySalesByFranchise($idFranchise, $bulanAwal = null, $bulanAkhir = null, $rangeBulan = [])
    {
        $query = DB::table('tb_penjualan')
            ->selectRaw('MONTH(tanggal) as bulan, SUM(harga) as total')
            ->where('id_franchise', $idFranchise);
    
        if ($bulanAwal && $bulanAkhir) {
            $query->whereBetween('tanggal', [$bulanAwal . '-01', $bulanAkhir . '-31']);
        }
    
        $monthlySales = $query->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');
    
        $result = [];
        foreach ($rangeBulan as $bulan) {
            $result[] = $monthlySales[$bulan] ?? 0;
        }
    
        return $result;
    }
    

    private function getRangeBulan($bulanAwal, $bulanAkhir)
    {
        $start = $bulanAwal ? Carbon::parse($bulanAwal)->month : 1;
        $end = $bulanAkhir ? Carbon::parse($bulanAkhir)->month : 12;

        return range($start, $end);
    }

    private function getLabelBulan($rangeBulan)
    {
        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return array_map(fn($b) => $bulanIndo[$b], $rangeBulan);
    }

}
