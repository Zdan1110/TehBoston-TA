<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class C_Print extends Controller
{
    public function printStruk($id_penjualan)
    {
        try {
            // Ambil data penjualan
            $penjualan = DB::table('tb_penjualan')
                ->where('id_penjualan', $id_penjualan)
                ->first();

            if (!$penjualan) {
                return response()->json(['success' => false, 'message' => 'Data penjualan tidak ditemukan.']);
            }

            // Ambil detail produk
            $detail = DB::table('tb_detailpenjualan')
                ->where('id_penjualan', $id_penjualan)
                ->get();

            // Cetak ke printer
            $connector = new WindowsPrintConnector("TehBoston"); // Ganti dengan nama printer thermal kamu
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Toko Teh Boston\n");
            $printer->text("Jl. Contoh No. 123\n\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Tanggal: " . date("d-m-Y H:i:s", strtotime($penjualan->tanggal)) . "\n");
            $printer->text("No Struk: " . ($penjualan->id_penjualan ?? '-') . "\n");
            $printer->text(str_repeat("-", 32) . "\n");

            foreach ($detail as $d) {
                $nama = str_pad($d->nama_produk, 15);
                $qty  = str_pad("x" . $d->jumlah, 4, " ", STR_PAD_LEFT);
                $sub  = str_pad(number_format($d->harga), 10, " ", STR_PAD_LEFT);
                $printer->text("{$nama}{$qty}{$sub}\n");
            }

            $printer->text(str_repeat("-", 32) . "\n");
            $printer->text("Total: " . str_pad("Rp" . number_format($penjualan->harga), 24, " ", STR_PAD_LEFT) . "\n");

            $printer->feed(2);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Terima Kasih!\n");
            $printer->cut();
            $printer->close();

            return response()->json(['success' => true, 'message' => 'Struk berhasil dicetak!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
