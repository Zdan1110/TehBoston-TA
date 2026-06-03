<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Charts\BostonGudangCharts;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\Charts\BostonOmsetChart;
use App\Exports\PengeluaranExport;
use App\Exports\StokExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\RiwayatMasukExport;
use App\Exports\RiwayatKeluarExport;

class GudangController extends Controller
{

public function index(Request $request, BostonGudangCharts $chartBuilder)
    {
        $bahan = $request->query('bahan');

        if ($bahan) {
            $data = $bahan;
        } else {
            $data = 'serbuk';
        }

        $stok_terendah = DB::table('tb_bahanbaku')
                ->where('jenis_bahan', $data)
                ->orderBy('stok', 'asc')
                ->first();

        $stok_tertinggi = DB::table('tb_bahanbaku')
                ->where('jenis_bahan', $data)
                ->orderBy('stok', 'desc')
                ->first();

        $totalpemasukan = DB::table('tb_pemasukan')
                ->join('tb_bahanbaku', 'tb_pemasukan.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
                ->where('tb_bahanbaku.jenis_bahan', $data)
                ->count();

        $totalpengeluaran = DB::table('tb_pengeluaran')
                ->join('tb_paket', 'tb_pengeluaran.id_paket', '=', 'tb_paket.id_paket')
                ->where('tb_paket.nama_paket', $data)
                ->count();
            
        $totalbahan = DB::table('tb_bahanbaku')
                ->where('jenis_bahan', $data)
                ->count();
        
        $totalsupplier = DB::table('tb_supplier')
                ->join('tb_bahanbaku', 'tb_supplier.id_supplier', '=', 'tb_bahanbaku.id_supplier')
                ->where('tb_bahanbaku.jenis_bahan', $data)
                ->distinct('tb_supplier.id_supplier')
                ->count('tb_supplier.id_supplier');
        
        $tanggal = $request->input('tanggal');
        $chart = $chartBuilder->build($tanggal, $data);

        $tanggal = Carbon::now()->startOfMonth()->toDateString();

        $bahanBakuLists = DB::table('tb_bahanbaku')->get();
        foreach ($bahanBakuLists as $bahans) {
            $dataSudahAda = DB::table('tb_laporanstok')
                ->where('id_bahanbaku', $bahans->id_bahanbaku)
                ->whereMonth('tanggal', Carbon::now()->month)
                ->whereYear('tanggal', Carbon::now()->year)
                ->get();
                
                if ($dataSudahAda->isEmpty()) {
                    // Ambil semua data dari tabel bahan baku
                    $bahanBakuList = DB::table('tb_bahanbaku')->get();
                    
                    $lastCalon = DB::table('tb_laporanstok')
                    ->select('id_laporan')
                    ->orderByDesc('id_laporan')
                    ->first();
                    
                    if ($lastCalon) {
                        $lastNumcalon = (int) substr($lastCalon->id_laporan, 1); // ambil angka setelah 'C'
                        $idLaporan = 'L' . str_pad($lastNumcalon + 1, 4, '0', STR_PAD_LEFT);
                    } else {
                        $idLaporan = 'L0001'; // Kalau belum ada data
                    }
                    
                    DB::table('tb_laporanstok')->insert([
                        'id_laporan' => $idLaporan,
                        'id_bahanbaku' => $bahans->id_bahanbaku,
                        'stok_awal' => $bahans->stok,
                        'barang_masuk' => 0,
                        'barang_keluar' => 0,
                        'stok_akhir' => $bahans->stok,
                        'tanggal' => $tanggal,
                    ]);
                    
                }
        }
         $logAktivitas = DB::table('tb_log_gudang')
                ->orderBy('waktu', 'desc')
                ->limit(10)
                ->get();

        return view('gudang.index', compact('tanggal', 'chart', 'data', 'bahan', 'stok_terendah', 'stok_tertinggi', 'totalpemasukan', 'totalpengeluaran', 'totalbahan', 'totalsupplier', 'logAktivitas'));
    }

    public function showFormBarangMasuk()
    {
        $suppliers = DB::table('tb_supplier')->get();
        $bahanbaku = DB::table('tb_bahanbaku')->get();
        return view('gudang.masuk', compact('suppliers', 'bahanbaku'));
    }

    public function Pesananbahanbaku()
    {
        $rows = DB::table('tb_transaksi')
            ->leftJoin('tb_pengeluaran', 'tb_transaksi.id_transaksi', '=', 'tb_pengeluaran.id_transaksi')
            ->leftJoin('tb_franchise', 'tb_pengeluaran.id_franchise', '=', 'tb_franchise.id_franchise')
            ->leftJoin('tb_paket', 'tb_pengeluaran.id_paket', '=', 'tb_paket.id_paket')
            ->leftJoin('tb_detailpengeluaran', 'tb_pengeluaran.id_pengeluaran', '=', 'tb_detailpengeluaran.id_pengeluaran')
            ->leftJoin('tb_bahanbaku', 'tb_detailpengeluaran.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
            ->where('tb_transaksi.jenis_transaksi', 'pengeluaran')
            ->where('tb_transaksi.status_transaksi', '!=', 'Selesai')
            ->select(
                'tb_transaksi.id_transaksi',
                'tb_transaksi.tanggal_transaksi',
                'tb_transaksi.total',
                'tb_transaksi.metode_pembayaran',
                'tb_transaksi.status_pembayaran',
                'tb_transaksi.status_transaksi',

                'tb_pengeluaran.id_pengeluaran',
                'tb_pengeluaran.jumlah as jumlah_paket',
                'tb_pengeluaran.harga as subtotal_paket',

                'tb_franchise.nama_franchise',
                'tb_franchise.alamat_usaha',

                'tb_paket.nama_paket',

                'tb_bahanbaku.nama_bahan',
                'tb_bahanbaku.satuan',
                'tb_detailpengeluaran.jumlah as jumlah_bahan'
            )
            ->orderBy('tb_transaksi.tanggal_transaksi', 'desc')
            ->get();

        $pesanan = [];

        foreach ($rows as $row) {
            $idTransaksi = $row->id_transaksi;
            $idPengeluaran = $row->id_pengeluaran;

            if (!isset($pesanan[$idTransaksi])) {
                $pesanan[$idTransaksi] = [
                    'id_transaksi' => $row->id_transaksi,
                    'tanggal_transaksi' => $row->tanggal_transaksi,
                    'total' => $row->total,
                    'metode_pembayaran' => $row->metode_pembayaran,
                    'status_pembayaran' => $row->status_pembayaran,
                    'status_transaksi' => $row->status_transaksi,
                    'nama_franchise' => $row->nama_franchise,
                    'alamat_usaha' => $row->alamat_usaha,
                    'paket' => []
                ];
            }

            if ($idPengeluaran && !isset($pesanan[$idTransaksi]['paket'][$idPengeluaran])) {
                $pesanan[$idTransaksi]['paket'][$idPengeluaran] = [
                    'nama_paket' => $row->nama_paket,
                    'jumlah_paket' => $row->jumlah_paket,
                    'subtotal_paket' => $row->subtotal_paket,
                    'bahan' => []
                ];
            }

            if ($idPengeluaran && $row->nama_bahan) {
                $pesanan[$idTransaksi]['paket'][$idPengeluaran]['bahan'][] = [
                    'nama_bahan' => $row->nama_bahan,
                    'jumlah_bahan' => $row->jumlah_bahan,
                    'satuan' => $row->satuan,
                ];
            }
        }

        return view('gudang.keluar', compact('pesanan'));
    }

    public function lunasiPembayaran($id)
    {
        try {
            DB::table('tb_transaksi')
                ->where('id_transaksi', $id)
                ->update([
                    'status_pembayaran' => 'settlement'
                ]);

            return redirect()->back()->with('success', 'Pembayaran berhasil dilunasi.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal melunasi pembayaran.');
        }
    }

    public function kirimPesanan($id)
    {
        DB::table('tb_transaksi')
            ->where('id_transaksi', $id)
            ->update([
                'status_transaksi' => 'Dikirim'
            ]);

    return redirect()->route('gudang.printnota', $id);
    }

    public function selesaiPesanan($id)
    {
        DB::beginTransaction();

        try {
            $transaksi = DB::table('tb_transaksi')
                ->where('id_transaksi', $id)
                ->first();

            if (!$transaksi) {
                return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
            }

            if ($transaksi->status_transaksi == 'Selesai') {
                return redirect()->back()->with('error', 'Pesanan sudah selesai.');
            }

            $detailBahan = DB::table('tb_pengeluaran')
                ->join('tb_detailpengeluaran', 'tb_pengeluaran.id_pengeluaran', '=', 'tb_detailpengeluaran.id_pengeluaran')
                ->where('tb_pengeluaran.id_transaksi', $id)
                ->select(
                    'tb_pengeluaran.id_franchise',
                    'tb_detailpengeluaran.id_bahanbaku',
                    DB::raw('SUM(tb_detailpengeluaran.jumlah) as total_jumlah')
                )
                ->groupBy(
                    'tb_pengeluaran.id_franchise',
                    'tb_detailpengeluaran.id_bahanbaku'
                )
                ->get();

            foreach ($detailBahan as $item) {
                $idBahan = $item->id_bahanbaku;
                $jumlahKeluar = $item->total_jumlah;
                $idFranchise = $item->id_franchise;

                DB::table('tb_bahanbaku')
                    ->where('id_bahanbaku', $idBahan)
                    ->update([
                        'stok' => DB::raw("stok - {$jumlahKeluar}")
                    ]);

                $laporan = DB::table('tb_laporanstok')
                    ->where('id_bahanbaku', $idBahan)
                    ->orderByDesc('id_laporan')
                    ->first();

                if ($laporan) {
                    DB::table('tb_laporanstok')
                        ->where('id_laporan', $laporan->id_laporan)
                        ->update([
                            'barang_keluar' => DB::raw("barang_keluar + {$jumlahKeluar}"),
                            'stok_akhir' => DB::raw("stok_akhir - {$jumlahKeluar}")
                        ]);
                }

                $stokFranchise = DB::table('tb_stokfranchise')
                    ->where('id_franchise', $idFranchise)
                    ->where('id_bahanbaku', $idBahan)
                    ->first();

                if ($stokFranchise) {
                    DB::table('tb_stokfranchise')
                        ->where('id_stokfranchise', $stokFranchise->id_stokfranchise)
                        ->update([
                            'stok' => DB::raw("stok + {$jumlahKeluar}"),
                            'updated_at' => now(),
                        ]);
                } else {
                    $lastStok = DB::table('tb_stokfranchise')
                        ->orderBy('id_stokfranchise', 'desc')
                        ->first();

                    $lastNumber = $lastStok
                        ? (int) substr($lastStok->id_stokfranchise, 2)
                        : 0;

                    $idStokFranchise = 'SF' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

                    DB::table('tb_stokfranchise')->insert([
                        'id_stokfranchise' => $idStokFranchise,
                        'id_franchise' => $idFranchise,
                        'id_bahanbaku' => $idBahan,
                        'stok' => $jumlahKeluar,
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::table('tb_transaksi')
                ->where('id_transaksi', $id)
                ->update([
                    'status_transaksi' => 'Selesai'
                ]);

            DB::commit();

            return redirect()->back()->with('success', 'Pesanan selesai dan stok berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error menyelesaikan pesanan', [
                'message' => $e->getMessage(),
                'id_transaksi' => $id,
            ]);

            return redirect()->back()->with('error', 'Gagal menyelesaikan pesanan.');
        }
    }

    public function simpanBarangMasuk(Request $request)
    {
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'id_supplier' => 'required|exists:tb_supplier,id_supplier',
            'id_bahanbaku.*' => 'required|exists:tb_bahanbaku,id_bahanbaku',
            'jumlah.*' => 'required|numeric|min:1',
            'harga.*' => 'required|numeric',
            'total_harga' => 'nullable|int',
        ], [
            'id_supplier.required' => 'Silakan pilih supplier terlebih dahulu.',
            'id_bahanbaku.required' => 'Silakan pilih bahan baku terlebih dahulu.',
            'jumlah.required' => 'Jumlah tidak boleh kosong.',
            'jumlah.min' => 'Jumlah minimal 1.',
        ]);
        
        $id_supplier = $request->id_supplier;
        $tanggal = $request->tanggal_masuk;
        $bahanbakuList = $request->id_bahanbaku;
        $jumlahList = $request->jumlah;
        $hargaList = $request->harga;
        
        $totalHarga = array_sum(array_map(function ($jml, $hrg) {
            return $jml * ($hrg ?? 0);
        }, $jumlahList, $hargaList));

        try {
            $lastCalon = DB::table('tb_transaksi')
            ->select('id_transaksi')
            ->orderByDesc('id_transaksi')
            ->first();

            if ($lastCalon) {
            $lastNumcalon = (int) substr($lastCalon->id_transaksi, 1); 
            $idGudang = 'G' . str_pad($lastNumcalon + 1, 4, '0', STR_PAD_LEFT);
            } else {
            $idGudang = 'G0001'; 
            }

            DB::table('tb_transaksi')->insert([
                'id_transaksi' => $idGudang,
                'tanggal_transaksi' => \Carbon\Carbon::parse($request->tanggal_masuk)->format('Y-m-d H:i:s'),
                'jenis_transaksi' => 'Pemasukan',
                'total' => $totalHarga,
            ]);
            
            $last = DB::table('tb_pemasukan')->orderBy('id_pemasukan', 'desc')->first();
            $lastId = $last ? intval(substr($last->id_pemasukan, 1)) : 0;
            
            foreach ($bahanbakuList as $i => $id_bahanbaku) {
            $lastCalon = DB::table('tb_pemasukan')
            ->select('id_pemasukan')
            ->orderByDesc('id_pemasukan')
            ->first();

            if ($lastCalon) {
            $lastNumcalon = (int) substr($lastCalon->id_pemasukan, 1); 
            $idPemasukan = 'M' . str_pad($lastNumcalon + 1, 4, '0', STR_PAD_LEFT);
            } else {
            $idPemasukan = 'M0001';
            }
            
            $totalMasuk = $hargaList[$i] * $jumlahList[$i];
            
            DB::table('tb_pemasukan')->insert([
                'id_pemasukan' => $idPemasukan,
                'id_transaksi' => $idGudang,
                'id_bahanbaku' => $id_bahanbaku,
                'jumlah' => $jumlahList[$i],
                'harga' => $totalMasuk,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            $barang_masuk = DB::table('tb_laporanstok')
                    ->where('id_bahanbaku', $id_bahanbaku)
                    ->whereMonth('tanggal', Carbon::now()->month)
                    ->whereYear('tanggal', Carbon::now()->year)
                    ->first();
            
            if ($barang_masuk->barang_masuk > 0 || $barang_masuk->barang_keluar > 0) {
                DB::table('tb_laporanstok')
                    ->where('id_bahanbaku', $id_bahanbaku)
                    ->whereMonth('tanggal', Carbon::now()->month)
                    ->whereYear('tanggal', Carbon::now()->year)
                    ->update([
                        'barang_masuk' => DB::raw("barang_masuk + $jumlahList[$i]"),
                        'stok_akhir' => DB::raw("stok_akhir + $jumlahList[$i]"),
                ]);
            } else {
                DB::table('tb_laporanstok')
                    ->where('id_bahanbaku', $id_bahanbaku)
                    ->whereMonth('tanggal', Carbon::now()->month)
                    ->whereYear('tanggal', Carbon::now()->year)
                    ->update([
                        'barang_masuk' => DB::raw("barang_masuk + $jumlahList[$i]"),
                        'stok_akhir' => DB::raw("stok_awal + $jumlahList[$i]"),
                    ]);
                }
            

            $bahan = DB::table('tb_bahanbaku')->where('id_bahanbaku', $id_bahanbaku)->first();
            DB::table('tb_log_gudang')->insert([
                'aksi' => 'Barang Masuk',
                'keterangan' => 'Barang Masuk: ' . $jumlahList[$i] . ' ' . $bahan->satuan . ' - ' . $bahan->nama_bahan,
                'waktu' => now(),
            ]);

            DB::table('tb_bahanbaku')->where('id_bahanbaku',$id_bahanbaku)->increment('stok', $jumlahList[$i]);
            }
            return redirect()->route('gudang.printnotamasuk', ['id' => $idGudang])->with('success', 'Barang masuk & struk berhasil disimpan!');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan data barang masuk: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan barang masuk. Silakan coba lagi.');
        }
    }

   public function simpanBarangKeluar(Request $request)
{
    $request->validate([
        'id_franchise' => 'required',
        'tanggal_keluar' => 'required|date',
        'id_bahanbaku.*' => 'required',
        'jumlah.*' => 'required|integer|min:1',
        'harga.*' => 'nullable|numeric|min:0',
        'struk_dc' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $id_franchise = $request->id_franchise;
    $tanggal = $request->tanggal_keluar;
    $bahanbakuList = $request->id_bahanbaku;
    $jumlahList = $request->jumlah;
    $hargaList = $request->harga;

    // Cek stok
    foreach ($bahanbakuList as $i => $id_bahanbaku) {
        $stok = DB::table('tb_bahanbaku')->where('id_bahanbaku', $id_bahanbaku)->value('stok');
        if ($stok < $jumlahList[$i]) {
            return back()->with('error', 'Stok bahan baku tidak mencukupi.');
        }
    }

    // Generate ID transaksi
    $lastTransId = DB::table('tb_transaksi')->max('id_transaksi');
    $newTransId = 'G' . str_pad(((int)substr($lastTransId, 1)) + 1, 4, '0', STR_PAD_LEFT);

    // Total harga semua item
    $totalHarga = array_sum(array_map(function ($jml, $hrg) {
        return $jml * ($hrg ?? 0);
    }, $jumlahList, $hargaList));

    // Simpan transaksi
    DB::table('tb_transaksi')->insert([
        'id_transaksi' => $newTransId,
        'tanggal_transaksi' => Carbon::parse($tanggal)->format('Y-m-d H:i:s'),
        'jenis_transaksi' => 'Pengeluaran',
        'total' => $totalHarga,
    ]);

    // Ambil ID pengeluaran terakhir
    $last = DB::table('tb_pengeluaran')->orderBy('id_pengeluaran', 'desc')->first();
    $lastId = $last ? intval(substr($last->id_pengeluaran, 1)) : 0;

    $strukPath = null;
    if ($request->hasFile('struk_dc')) {
        // sementara kita simpan file dulu di variabel
        $uploadedFile = $request->file('struk_dc');
    }

    foreach ($bahanbakuList as $i => $id_bahanbaku) {
        $newId = 'K' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
        $lastId++;

        $totalKeluar = $hargaList[$i] * $jumlahList[$i];

        // simpan data pengeluaran
        DB::table('tb_pengeluaran')->insert([
            'id_pengeluaran' => $newId,
            'id_transaksi' => $newTransId,
            'id_franchise' => $id_franchise,
            'id_bahanbaku' => $id_bahanbaku,
            'jumlah' => $jumlahList[$i],
            'harga' => $totalKeluar,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $barang_keluar = DB::table('tb_laporanstok')
        ->where('id_bahanbaku', $id_bahanbaku)
        ->whereMonth('tanggal', Carbon::now()->month)
        ->whereYear('tanggal', Carbon::now()->year)
        ->first();
            
        if ($barang_keluar->barang_masuk > 0 || $barang_keluar->barang_keluar > 0) {
            DB::table('tb_laporanstok')
                ->where('id_bahanbaku', $id_bahanbaku)
                ->whereMonth('tanggal', Carbon::now()->month)
                ->whereYear('tanggal', Carbon::now()->year)
                ->update([
                    'barang_keluar' => DB::raw("barang_keluar + $jumlahList[$i]"),
                    'stok_akhir' => DB::raw("stok_akhir - $jumlahList[$i]"),
            ]);
        } else {
            DB::table('tb_laporanstok')
                ->where('id_bahanbaku', $id_bahanbaku)
                ->whereMonth('tanggal', Carbon::now()->month)
                ->whereYear('tanggal', Carbon::now()->year)
                ->update([
                    'barang_keluar' => DB::raw("barang_keluar + $jumlahList[$i]"),
                    'stok_akhir' => DB::raw("stok_awal - $jumlahList[$i]"),
                ]);
            }

        // ðŸ”¥ simpan file struk dengan nama dinamis berdasarkan id_pengeluaran
        if (isset($uploadedFile)) {
            $extension = $uploadedFile->getClientOriginalExtension();
            $timestamp = now()->format('Ymd_His');
            $fileName = "{$newId}-{$timestamp}.{$extension}";
            $uploadedFile->move(public_path('upload/struk_dc'), $fileName);
        }

        // kurangi stok
        DB::table('tb_bahanbaku')
            ->where('id_bahanbaku', $id_bahanbaku)
            ->decrement('stok', $jumlahList[$i]);
    }

    return redirect()->route('gudang.printnota', ['id' => $newTransId])
        ->with('success', 'Barang keluar & struk berhasil disimpan!');
}



    public function laporanstok(Request $request)
    {
        $sort = $request->input('sort', 'tb_laporanstok.tanggal');
        $direction = $request->input('direction', 'asc');
        $keyword = $request->input('keyword');
        $editId = $request->input('edit');
    
        $allowedSorts = ['barang_masuk', 'barang_keluar', 'stok_akhir'];
    
        $query = DB::table('tb_bahanbaku')
            ->leftJoin('tb_laporanstok', function ($join) {
                $join->on('tb_bahanbaku.id_bahanbaku', '=', 'tb_laporanstok.id_bahanbaku')
                    ->whereMonth('tb_laporanstok.tanggal', now()->month)
                    ->whereYear('tb_laporanstok.tanggal', now()->year);
            })
            ->select(
                'tb_bahanbaku.nama_bahan',
                'tb_bahanbaku.id_bahanbaku',
                'tb_bahanbaku.stok as stok_terkini',
                'tb_laporanstok.stok_awal',
                'tb_laporanstok.barang_masuk',
                'tb_laporanstok.barang_keluar',
                'tb_laporanstok.stok_akhir'
            );
    
        if ($keyword) {
            $query->where('tb_bahanbaku.nama_bahan', 'like', '%' . $keyword . '%');
        }
    
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy("tb_laporanstok.$sort", $direction);
        }
    
        $bahan = $query->get();
    
        $editData = null;
        if ($editId) {
            $editData = DB::table('tb_laporanstok')
                ->join('tb_bahanbaku', 'tb_bahanbaku.id_bahanbaku', '=', 'tb_laporanstok.id_bahanbaku')
                ->where('tb_laporanstok.id_bahanbaku', $editId)
                ->whereMonth('tb_laporanstok.tanggal', now()->month)
                ->whereYear('tb_laporanstok.tanggal', now()->year)
                ->select('tb_laporanstok.*', 'tb_bahanbaku.nama_bahan')
                ->first();
        }
    
        return view('gudang.stok', compact('bahan', 'editData'));
    }

public function riwayatgudang(Request $request)
{
    $from = $request->input('from');
    $to = $request->input('to');

    $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
    $toDate = $to ? Carbon::parse($to)->endOfDay() : null;

    $keywordMasuk = $request->input('keyword_masuk');
    $keywordKeluar = $request->input('keyword_keluar');

    // Riwayat Pemasukan
$riwayatmasuk = DB::table('tb_transaksi')
    ->join('tb_pemasukan', 'tb_pemasukan.id_transaksi', '=', 'tb_transaksi.id_transaksi')
    ->join('tb_bahanbaku', 'tb_pemasukan.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
    ->leftJoin('tb_supplier', 'tb_bahanbaku.id_supplier', '=', 'tb_supplier.id_supplier')
    ->select(
        'tb_transaksi.id_transaksi',
        'tb_transaksi.tanggal_transaksi',
        'tb_transaksi.struk',
        DB::raw('GROUP_CONCAT(tb_bahanbaku.nama_bahan SEPARATOR ", ") as nama_bahan'),
        DB::raw('GROUP_CONCAT(tb_pemasukan.jumlah SEPARATOR ", ") as jumlah'),
        DB::raw('GROUP_CONCAT(tb_pemasukan.harga SEPARATOR ", ") as harga'),
        DB::raw('GROUP_CONCAT(tb_bahanbaku.satuan SEPARATOR ", ") as satuan'),
        'tb_supplier.nama_supplier',
        'tb_transaksi.total',
        DB::raw('MAX(tb_pemasukan.created_at) as created_at'),
        DB::raw('MAX(tb_pemasukan.updated_at) as updated_at')
    )
    ->where('tb_transaksi.jenis_transaksi', '=', 'Pemasukan')
    ->when($keywordMasuk, function ($query, $keywordMasuk) {
        $query->where(function ($q) use ($keywordMasuk) {
            $q->where('tb_bahanbaku.nama_bahan', 'like', "%$keywordMasuk%")
              ->orWhere('tb_supplier.nama_supplier', 'like', "%$keywordMasuk%");
        });
    })
    ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
        $query->whereBetween('tb_transaksi.tanggal_transaksi', [$fromDate, $toDate]);
    })
    ->groupBy('tb_transaksi.id_transaksi', 'tb_transaksi.tanggal_transaksi', 'tb_supplier.nama_supplier', 'tb_transaksi.total', 'tb_transaksi.struk')
    ->orderBy('created_at', 'desc')
    ->get();


    // Total uang keluar dari transaksi pemasukan
    $totalUangKeluar = DB::table('tb_transaksi')
    ->where('jenis_transaksi', '=', 'Pemasukan')
    ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
        $query->whereBetween('tanggal_transaksi', [$fromDate, $toDate]);
    })
    ->sum('total');


    // Riwayat Pengeluaran
$riwayatkeluar = DB::table('tb_transaksi')
    ->join('tb_pengeluaran', 'tb_pengeluaran.id_transaksi', '=', 'tb_transaksi.id_transaksi')
    ->leftJoin('tb_paket', 'tb_pengeluaran.id_paket', '=', 'tb_paket.id_paket')
    ->leftJoin('tb_franchise', 'tb_pengeluaran.id_franchise', '=', 'tb_franchise.id_franchise')
    ->select(
        'tb_transaksi.id_transaksi',
        'tb_transaksi.tanggal_transaksi',
        'tb_transaksi.struk',
        DB::raw('GROUP_CONCAT(tb_paket.nama_paket SEPARATOR ", ") as nama_bahan'),
        DB::raw('GROUP_CONCAT(tb_pengeluaran.jumlah SEPARATOR ", ") as jumlah'),
        DB::raw('GROUP_CONCAT(tb_pengeluaran.harga SEPARATOR ", ") as harga'),
        DB::raw('GROUP_CONCAT(tb_paket.harga SEPARATOR ", ") as satuan'),
        DB::raw('GROUP_CONCAT(tb_franchise.nama_franchise SEPARATOR ", ") as nama_franchise'),
        DB::raw('GROUP_CONCAT(tb_franchise.alamat_usaha SEPARATOR ", ") as alamat_usaha'),
        'tb_transaksi.total',
        DB::raw('MAX(tb_pengeluaran.created_at) as created_at'),
        DB::raw('MAX(tb_pengeluaran.updated_at) as updated_at')
    )
    ->where('tb_transaksi.jenis_transaksi', '=', 'Pengeluaran')
    ->when($keywordKeluar, function ($query, $keywordKeluar) {
        $query->where(function ($q) use ($keywordKeluar) {
            $q->where('tb_paket.nama_paket', 'like', "%$keywordKeluar%")
              ->orWhere('tb_franchise.nama_franchise', 'like', "%$keywordKeluar%");
        });
    })
    ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
        $query->whereBetween('tb_transaksi.tanggal_transaksi', [$fromDate, $toDate]);
    })
    ->groupBy(
        'tb_transaksi.id_transaksi',
        'tb_transaksi.tanggal_transaksi',
        'tb_transaksi.total',
        'tb_transaksi.struk'
    )
    ->orderBy('created_at', 'desc')
    ->get();

    // Total uang masuk dari transaksi pengeluaran
    $totalUangMasuk = DB::table('tb_transaksi')
    ->where('jenis_transaksi', '=', 'Pengeluaran')
    ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
        $query->whereBetween('tanggal_transaksi', [$fromDate, $toDate]);
    })
    ->sum('total');

    $bahanbaku = DB::table('tb_bahanbaku')->get();

    return view('gudang.riwayat', compact(
        'riwayatmasuk',
        'riwayatkeluar',
        'totalUangKeluar',
        'totalUangMasuk',
        'from',
        'to',
        'keywordMasuk',
        'keywordKeluar',
        'bahanbaku'
    ));
}

public function Editmasuk($id)
{
    $data = DB::table('tb_transaksi')
        ->join('tb_pemasukan', 'tb_pemasukan.id_transaksi', '=', 'tb_transaksi.id_transaksi')
        ->leftJoin('tb_bahanbaku', 'tb_pemasukan.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
        ->select(
            'tb_bahanbaku.id_bahanbaku',
            'tb_bahanbaku.nama_bahan',
            'tb_bahanbaku.harga_modal',
            'tb_pemasukan.id_pemasukan',
            'tb_pemasukan.jumlah',
            'tb_pemasukan.harga',
            'tb_bahanbaku.satuan',
            'tb_transaksi.total',
            'tb_pemasukan.created_at',
            'tb_pemasukan.updated_at'
        )
        ->where('tb_transaksi.jenis_transaksi', '=', 'Pemasukan')
        ->where('tb_transaksi.id_transaksi', '=', $id)
        ->get();
    
    Log::info('Data modal:', ['data' => $id]);
    // Kembalikan JSON untuk AJAX
    return response()->json($data);
}

public function hapusmasuk($id)
{
    try {
        Log::info('Menghapus id_pemasukan: ' . $id);
        $id_transaksi = DB::table('tb_pemasukan')
                            ->where('id_pemasukan', $id)
                            ->select('id_transaksi', 'harga', 'id_bahanbaku', 'jumlah')
                            ->first();
        
        $totalsekarang = DB::table('tb_transaksi')
                            ->where('id_transaksi', $id_transaksi->id_transaksi)
                            ->select('total')
                            ->first();
        
        $totalupdate = $totalsekarang->total - $id_transaksi->harga;

        DB::table('tb_transaksi')
            ->where('id_transaksi', $id_transaksi->id_transaksi)
            ->update([
                'total' => $totalupdate,
                'tanggal_transaksi' => now(),
            ]);

        DB::table('tb_laporanstok')
            ->where('id_bahanbaku', $id_transaksi->id_bahanbaku)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->update([
                'barang_masuk' => DB::raw("barang_masuk - $id_transaksi->jumlah"),
                'stok_akhir' => DB::raw("stok_akhir - $id_transaksi->jumlah"),
        ]);

        DB::table('tb_bahanbaku')
            ->where('id_bahanbaku', $id_transaksi->id_bahanbaku)
            ->update([
                'stok' => DB::raw("stok - $id_transaksi->jumlah"),
            ]);

        DB::table('tb_pemasukan')->where('id_pemasukan', $id)->delete();

        $data = DB::table('tb_pemasukan')
            ->join('tb_bahanbaku', 'tb_pemasukan.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
            ->join('tb_supplier', 'tb_bahanbaku.id_supplier', '=', 'tb_supplier.id_supplier')
            ->where('tb_pemasukan.id_transaksi', $id_transaksi->id_transaksi)
            ->select(
                'tb_pemasukan.*',
                'tb_bahanbaku.nama_bahan',
                'tb_bahanbaku.satuan',
                'tb_supplier.nama_supplier'
            )
            ->get();

        $total = DB::table('tb_transaksi')
                ->where('id_transaksi', '=', $id_transaksi->id_transaksi)
                ->first();


        $header = $data->first(); // untuk keperluan header nota
        
        // 🔹 Generate PDF dari view yang sama
        $pdf = Pdf::loadView('gudang.nota_masuk', compact('data', 'header', 'total'))
            ->setPaper([0, 0, 226.77, 600], 'portrait'); // ukuran thermal 80mm
    
        // 🔹 Simpan file PDF ke folder public/uploads/nota
        $filename = 'nota_' . $id_transaksi->id_transaksi . '.pdf';
        $path = public_path('uploads/strukdc/' . $filename);
        $pdf->save($path);
        
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        Log::error('Error hapuskeluar: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

public function updatemasuk(Request $request)
{
    try {
        $datas = $request->input('data');

        Log::info('Isi variabel $datas:', ['data' => $datas]);

        if (!is_array($datas) || empty($datas)) {
            return response()->json(['error' => 'Tidak ada data untuk diperbarui.'], 400);
        }

        $grandTotal = 0;

        foreach ($datas as $item) {
            try {
                $total = floatval($item['total']);
                $jumlahinput = floatval($item['jumlah']);
                $grandTotal += $total;

                $jumlahasal = DB::table('tb_pemasukan')
                                ->where('id_pemasukan', $item['id_pemasukan'])
                                ->select('jumlah', 'id_bahanbaku')
                                ->first();

                if ($jumlahasal->jumlah < $jumlahinput)
                {
                    $jumlahupdate = $jumlahinput - $jumlahasal->jumlah;

                    DB::table('tb_laporanstok')
                        ->where('id_bahanbaku', $jumlahasal->id_bahanbaku)
                        ->whereMonth('tanggal', Carbon::now()->month)
                        ->whereYear('tanggal', Carbon::now()->year)
                        ->update([
                            'barang_masuk' => DB::raw("barang_masuk + $jumlahupdate"),
                            'stok_akhir' => DB::raw("stok_akhir + $jumlahupdate"),
                        ]);
                    
                    DB::table('tb_bahanbaku')
                        ->where('id_bahanbaku', $jumlahasal->id_bahanbaku)
                        ->update([
                            'stok' => DB::raw("stok + $jumlahupdate"),
                        ]);

                } elseif ($jumlahasal->jumlah > $jumlahinput){
                    $jumlahupdate = $jumlahasal->jumlah - $jumlahinput;

                    DB::table('tb_laporanstok')
                        ->where('id_bahanbaku', $jumlahasal->id_bahanbaku)
                        ->whereMonth('tanggal', Carbon::now()->month)
                        ->whereYear('tanggal', Carbon::now()->year)
                        ->update([
                            'barang_masuk' => DB::raw("barang_masuk - $jumlahupdate"),
                            'stok_akhir' => DB::raw("stok_akhir - $jumlahupdate"),
                        ]);

                    DB::table('tb_bahanbaku')
                        ->where('id_bahanbaku', $jumlahasal->id_bahanbaku)
                        ->update([
                            'stok' => DB::raw("stok - $jumlahupdate"),
                        ]);
                }

                DB::table('tb_pemasukan')
                    ->where('id_pemasukan', $item['id_pemasukan'])
                    ->update([
                        'jumlah' => $item['jumlah'],
                        'harga' => $item['total'],
                        'updated_at' => now()
                    ]);

                Log::info('updateMasuk: Data pemasukan berhasil diupdate.', [
                    'id_pemasukan' => $item['id_pemasukan'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $total
                    ]);

            } catch (\Exception $e) {
                Log::error('updateMasuk: Gagal update tb_pemasukan.', [
                    'id_pemasukan' => $item['id_pemasukan'],
                    'error' => $e->getMessage()
                ]);
            }
        }

        DB::table('tb_transaksi')
            ->where('id_transaksi', $datas[0]['id_transaksi'])
            ->update([
                'total' => $grandTotal,
                'tanggal_transaksi' => now()
            ]);

            $data = DB::table('tb_pemasukan')
            ->join('tb_bahanbaku', 'tb_pemasukan.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
            ->join('tb_supplier', 'tb_bahanbaku.id_supplier', '=', 'tb_supplier.id_supplier')
            ->where('tb_pemasukan.id_transaksi', $datas[0]['id_transaksi'])
            ->select(
                'tb_pemasukan.*',
                'tb_bahanbaku.nama_bahan',
                'tb_bahanbaku.satuan',
                'tb_supplier.nama_supplier'
            )
            ->get();

        $total = DB::table('tb_transaksi')
                ->where('id_transaksi', '=', $datas[0]['id_transaksi'])
                ->first();


        $header = $data->first(); // untuk keperluan header nota
        
        // 🔹 Generate PDF dari view yang sama
        $pdf = Pdf::loadView('gudang.nota_masuk', compact('data', 'header', 'total'))
            ->setPaper([0, 0, 226.77, 600], 'portrait'); // ukuran thermal 80mm
    
        // 🔹 Simpan file PDF ke folder public/uploads/nota
        $filename = 'nota_' . $datas[0]['id_transaksi'] . '.pdf';
        $path = public_path('uploads/strukdc/' . $filename);
        $pdf->save($path);

        Log::info('updateMasuk: PDF berhasil dibuat.', ['path' => $path]);

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        Log::error('updateMasuk: Terjadi kesalahan fatal.', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data.'], 500);
    }
    
}

public function tambahBahanMasuk(Request $request)
{
    $request->validate([
        'id_transaksi' => 'required|exists:tb_transaksi,id_transaksi',
        'id_bahanbaku' => 'required|exists:tb_bahanbaku,id_bahanbaku',
        'jumlah' => 'required|numeric|min:1',
        'total' => 'required|numeric|min:0',
    ]);

    try {
        $last = DB::table('tb_pemasukan')->orderBy('id_pemasukan', 'desc')->first();
        $lastId = $last ? intval(substr($last->id_pemasukan, 1)) : 0;
        $newId = 'M' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        $idPengeluaran = DB::table('tb_pemasukan')->insert([
            'id_pemasukan' => $newId,
            'id_transaksi' => $request->id_transaksi,
            'id_bahanbaku' => $request->id_bahanbaku,
            'jumlah' => $request->jumlah,
            'harga' => $request->total,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('tb_transaksi')
            ->where('id_transaksi', $request->id_transaksi)
            ->update([
                'total' => DB::raw("total + $request->total"),
                'tanggal_transaksi' => now()
            ]);
        
        DB::table('tb_laporanstok')
            ->where('id_bahanbaku', $request->id_bahanbaku)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->update([
                'barang_masuk' => DB::raw("barang_masuk + $request->jumlah"),
                'stok_akhir' => DB::raw("stok_akhir + $request->jumlah"),
            ]);

        DB::table('tb_bahanbaku')
            ->where('id_bahanbaku', $request->id_bahanbaku)
            ->update([
                'stok' => DB::raw("stok + $request->jumlah"),
            ]);

            $data = DB::table('tb_pemasukan')
            ->join('tb_bahanbaku', 'tb_pemasukan.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
            ->join('tb_supplier', 'tb_bahanbaku.id_supplier', '=', 'tb_supplier.id_supplier')
            ->where('tb_pemasukan.id_transaksi', $request->id_transaksi)
            ->select(
                'tb_pemasukan.*',
                'tb_bahanbaku.nama_bahan',
                'tb_bahanbaku.satuan',
                'tb_supplier.nama_supplier'
            )
            ->get();

        $total = DB::table('tb_transaksi')
                ->where('id_transaksi', '=', $request->id_transaksi)
                ->first();


        $header = $data->first(); // untuk keperluan header nota
        
        // 🔹 Generate PDF dari view yang sama
        $pdf = Pdf::loadView('gudang.nota_masuk', compact('data', 'header', 'total'))
            ->setPaper([0, 0, 226.77, 600], 'portrait'); // ukuran thermal 80mm
    
        // 🔹 Simpan file PDF ke folder public/uploads/nota
        $filename = 'nota_' . $request->id_transaksi . '.pdf';
        $path = public_path('uploads/strukdc/' . $filename);
        $pdf->save($path);

        return response()->json([
            'success' => true,
            'id_pemasukan' => $idPengeluaran
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function Editkeluar($id)
{
    $data = DB::table('tb_transaksi')
        ->join('tb_pengeluaran', 'tb_pengeluaran.id_transaksi', '=', 'tb_transaksi.id_transaksi')
        ->leftJoin('tb_bahanbaku', 'tb_pengeluaran.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
        ->leftJoin('tb_franchise', 'tb_pengeluaran.id_franchise', '=', 'tb_franchise.id_franchise')
        ->select(
            'tb_transaksi.id_transaksi',
            'tb_transaksi.tanggal_transaksi',
            'tb_transaksi.struk',
            'tb_bahanbaku.id_bahanbaku',
            'tb_bahanbaku.nama_bahan',
            'tb_bahanbaku.harga_jual',
            'tb_pengeluaran.id_pengeluaran',
            'tb_pengeluaran.jumlah',
            'tb_pengeluaran.harga',
            'tb_bahanbaku.satuan',
            'tb_franchise.nama_franchise',
            'tb_franchise.alamat_usaha',
            'tb_transaksi.total',
            'tb_pengeluaran.created_at',
            'tb_pengeluaran.updated_at'
        )
        ->where('tb_transaksi.jenis_transaksi', '=', 'Pengeluaran')
        ->where('tb_transaksi.id_transaksi', '=', $id)
        ->get();

    // Kembalikan JSON untuk AJAX
    return response()->json($data);
}

public function hapuskeluar($id)
{
    try {
        Log::info('Menghapus id_pengeluaran: ' . $id);
        $id_transaksi = DB::table('tb_pengeluaran')
                            ->where('id_pengeluaran', $id)
                            ->select('id_transaksi', 'harga', 'id_bahanbaku', 'jumlah')
                            ->first();
        
        $totalsekarang = DB::table('tb_transaksi')
                            ->where('id_transaksi', $id_transaksi->id_transaksi)
                            ->select('total')
                            ->first();
        
        $totalupdate = $totalsekarang->total - $id_transaksi->harga;

        DB::table('tb_transaksi')
            ->where('id_transaksi', $id_transaksi->id_transaksi)
            ->update([
                'total' => $totalupdate,
                'tanggal_transaksi' => now(),
            ]);

        DB::table('tb_laporanstok')
            ->where('id_bahanbaku', $id_transaksi->id_bahanbaku)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->update([
                'barang_keluar' => DB::raw("barang_keluar - $id_transaksi->jumlah"),
                'stok_akhir' => DB::raw("stok_akhir + $id_transaksi->jumlah"),
        ]);

        DB::table('tb_bahanbaku')
            ->where('id_bahanbaku', $id_transaksi->id_bahanbaku)
            ->update([
                'stok' => DB::raw("stok + $id_transaksi->jumlah"),
            ]);

        DB::table('tb_pengeluaran')->where('id_pengeluaran', $id)->delete();

        $data = DB::table('tb_pengeluaran')
            ->join('tb_bahanbaku', 'tb_pengeluaran.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
            ->join('tb_franchise', 'tb_pengeluaran.id_franchise', '=', 'tb_franchise.id_franchise')
            ->where('tb_pengeluaran.id_transaksi', $id_transaksi->id_transaksi)
            ->select(
                'tb_pengeluaran.*',
                'tb_bahanbaku.nama_bahan',
                'tb_bahanbaku.satuan',
                'tb_franchise.nama_franchise',
                'tb_franchise.alamat_usaha'
            )
            ->get();

        $total = DB::table('tb_transaksi')
                ->where('id_transaksi', '=', $id_transaksi->id_transaksi)
                ->first();
                

        $header = $data->first(); // untuk keperluan header nota
        
        // 🔹 Generate PDF dari view yang sama
        $pdf = Pdf::loadView('gudang.nota_keluar', compact('data', 'header', 'total'))
            ->setPaper([0, 0, 226.77, 600], 'portrait'); // ukuran thermal 80mm

        // 🔹 Simpan file PDF ke folder public/uploads/nota
        $filename = 'nota_' . $id_transaksi->id_transaksi . '.pdf';
        $path = public_path('uploads/strukdc/' . $filename);
        $pdf->save($path);
        
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        Log::error('Error hapuskeluar: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

public function updatekeluar(Request $request)
{
    try {
        $datas = $request->input('data');

        Log::info('Isi variabel $datas:', ['data' => $datas]);

        if (!is_array($datas) || empty($datas)) {
            return response()->json(['error' => 'Tidak ada data untuk diperbarui.'], 400);
        }

        $grandTotal = 0;

        foreach ($datas as $item) {
            try {
                $total = floatval($item['total']);
                $jumlahinput = floatval($item['jumlah']);
                $grandTotal += $total;

                $jumlahasal = DB::table('tb_pengeluaran')
                                ->where('id_pengeluaran', $item['id_pengeluaran'])
                                ->select('jumlah', 'id_bahanbaku')
                                ->first();

                if ($jumlahasal->jumlah < $jumlahinput)
                {
                    $jumlahupdate = $jumlahinput - $jumlahasal->jumlah;

                    DB::table('tb_laporanstok')
                        ->where('id_bahanbaku', $jumlahasal->id_bahanbaku)
                        ->whereMonth('tanggal', Carbon::now()->month)
                        ->whereYear('tanggal', Carbon::now()->year)
                        ->update([
                            'barang_keluar' => DB::raw("barang_keluar + $jumlahupdate"),
                            'stok_akhir' => DB::raw("stok_akhir - $jumlahupdate"),
                        ]);

                    DB::table('tb_bahanbaku')
                        ->where('id_bahanbaku', $jumlahasal->id_bahanbaku)
                        ->update([
                            'stok' => DB::raw("stok - $jumlahupdate"),
                        ]);

                } elseif ($jumlahasal->jumlah > $jumlahinput){
                    $jumlahupdate = $jumlahasal->jumlah - $jumlahinput;

                    DB::table('tb_laporanstok')
                        ->where('id_bahanbaku', $jumlahasal->id_bahanbaku)
                        ->whereMonth('tanggal', Carbon::now()->month)
                        ->whereYear('tanggal', Carbon::now()->year)
                        ->update([
                            'barang_keluar' => DB::raw("barang_keluar - $jumlahupdate"),
                            'stok_akhir' => DB::raw("stok_akhir + $jumlahupdate"),
                        ]);

                    DB::table('tb_bahanbaku')
                        ->where('id_bahanbaku', $jumlahasal->id_bahanbaku)
                        ->update([
                            'stok' => DB::raw("stok + $jumlahupdate"),
                        ]);
                }

                DB::table('tb_pengeluaran')
                    ->where('id_pengeluaran', $item['id_pengeluaran'])
                    ->update([
                        'jumlah' => $item['jumlah'],
                        'harga' => $item['total'],
                        'updated_at' => now()
                    ]);

                Log::info('updateKeluar: Data pengeluaran berhasil diupdate.', [
                    'id_pengeluaran' => $item['id_pengeluaran'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $total
                    ]);

            } catch (\Exception $e) {
                Log::error('updateKeluar: Gagal update tb_pengeluaran.', [
                    'id_pengeluaran' => $item['id_pengeluaran'],
                    'error' => $e->getMessage()
                ]);
            }
        }

        DB::table('tb_transaksi')
            ->where('id_transaksi', $datas[0]['id_transaksi'])
            ->update([
                'total' => $grandTotal,
                'tanggal_transaksi' => now()
            ]);

        $data = DB::table('tb_pengeluaran')
            ->join('tb_bahanbaku', 'tb_pengeluaran.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
            ->join('tb_franchise', 'tb_pengeluaran.id_franchise', '=', 'tb_franchise.id_franchise')
            ->where('tb_pengeluaran.id_transaksi', $datas[0]['id_transaksi'])
            ->select(
                'tb_pengeluaran.*',
                'tb_bahanbaku.nama_bahan',
                'tb_bahanbaku.satuan',
                'tb_franchise.nama_franchise',
                'tb_franchise.alamat_usaha'
            )
            ->get();

        $total = DB::table('tb_transaksi')
                ->where('id_transaksi', '=', $datas[0]['id_transaksi'])
                ->first();
                

        $header = $data->first(); // untuk keperluan header nota
        
        // 🔹 Generate PDF dari view yang sama
        $pdf = Pdf::loadView('gudang.nota_keluar', compact('data', 'header', 'total'))
            ->setPaper([0, 0, 226.77, 600], 'portrait'); // ukuran thermal 80mm

        // 🔹 Simpan file PDF ke folder public/uploads/nota
        $filename = 'nota_' . $datas[0]['id_transaksi'] . '.pdf';
        $path = public_path('uploads/strukdc/' . $filename);
        $pdf->save($path);

        Log::info('updateKeluar: PDF berhasil dibuat.', ['path' => $path]);

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        Log::error('updateKeluar: Terjadi kesalahan fatal.', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data.'], 500);
    }
    
}

public function tambahBahanKeluar(Request $request)
{
    $request->validate([
        'id_transaksi' => 'required|exists:tb_transaksi,id_transaksi',
        'id_bahanbaku' => 'required|exists:tb_bahanbaku,id_bahanbaku',
        'jumlah' => 'required|numeric|min:1',
        'total' => 'required|numeric|min:0',
    ]);

    try {
        $last = DB::table('tb_pengeluaran')->orderBy('id_pengeluaran', 'desc')->first();
        $lastId = $last ? intval(substr($last->id_pengeluaran, 1)) : 0;
        $newId = 'K' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
        $idfranchise = DB::table('tb_pengeluaran')
                        ->where('id_transaksi', $request->id_transaksi)
                        ->select('id_franchise')
                        ->first();

        $idPengeluaran = DB::table('tb_pengeluaran')->insert([
            'id_pengeluaran' => $newId,
            'id_transaksi' => $request->id_transaksi,
            'id_franchise' => $idfranchise->id_franchise,
            'id_bahanbaku' => $request->id_bahanbaku,
            'jumlah' => $request->jumlah,
            'harga' => $request->total,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('tb_transaksi')
            ->where('id_transaksi', $request->id_transaksi)
            ->update([
                'total' => DB::raw("total + $request->total"),
                'tanggal_transaksi' => now()
            ]);
        
        DB::table('tb_laporanstok')
            ->where('id_bahanbaku', $request->id_bahanbaku)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->update([
                'barang_keluar' => DB::raw("barang_keluar + $request->jumlah"),
                'stok_akhir' => DB::raw("stok_akhir - $request->jumlah"),
            ]);

        DB::table('tb_bahanbaku')
            ->where('id_bahanbaku', $request->id_bahanbaku)
            ->update([
                'stok' => DB::raw("stok - $request->jumlah")
            ]);

        $data = DB::table('tb_pengeluaran')
            ->join('tb_bahanbaku', 'tb_pengeluaran.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
            ->join('tb_franchise', 'tb_pengeluaran.id_franchise', '=', 'tb_franchise.id_franchise')
            ->where('tb_pengeluaran.id_transaksi', $request->id_transaksi)
            ->select(
                'tb_pengeluaran.*',
                'tb_bahanbaku.nama_bahan',
                'tb_bahanbaku.satuan',
                'tb_franchise.nama_franchise',
                'tb_franchise.alamat_usaha'
            )
            ->get();

        $total = DB::table('tb_transaksi')
                ->where('id_transaksi', '=', $request->id_transaksi)
                ->first();
                

        $header = $data->first(); // untuk keperluan header nota
        
        // 🔹 Generate PDF dari view yang sama
        $pdf = Pdf::loadView('gudang.nota_keluar', compact('data', 'header', 'total'))
            ->setPaper([0, 0, 226.77, 600], 'portrait'); // ukuran thermal 80mm

        // 🔹 Simpan file PDF ke folder public/uploads/nota
        $filename = 'nota_' . $request->id_transaksi . '.pdf';
        $path = public_path('uploads/strukdc/' . $filename);
        $pdf->save($path);

        return response()->json([
            'success' => true,
            'id_pengeluaran' => $idPengeluaran
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function hapusTransaksi($id)
{
    try {
        $idbahan = DB::table('tb_pengeluaran')
                    ->where('id_transaksi', $id)
                    ->select('id_bahanbaku', 'jumlah')
                    ->get();

        foreach ($idbahan as $bahan) {
            DB::table('tb_laporanstok')
                ->where('id_bahanbaku', $bahan->id_bahanbaku)
                ->whereMonth('tanggal', Carbon::now()->month)
                ->whereYear('tanggal', Carbon::now()->year)
                ->update([
                    'barang_keluar' => DB::raw("barang_keluar - {$bahan->jumlah}"),
                    'stok_akhir' => DB::raw("stok_akhir + {$bahan->jumlah}"),
                ]);

            DB::table('tb_bahanbaku')
                ->where('id_bahanbaku', $bahan->id_bahanbaku)
                ->update([
                    'stok' => DB::raw("stok + {$bahan->jumlah}"),
                ]);
        }

        // Jika ingin, juga hapus semua bahan terkait dari tb_pengeluaran
        DB::table('tb_pengeluaran')->where('id_transaksi', $id)->delete();

        // Hapus berdasarkan id_transaksi
        DB::table('tb_transaksi')->where('id_transaksi', $id)->delete();

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

public function hapusTransaksimasuk($id)
{
    try {
        $idbahan = DB::table('tb_pemasukan')
                    ->where('id_transaksi', $id)
                    ->select('id_bahanbaku', 'jumlah')
                    ->get();

        foreach ($idbahan as $bahan) {
            DB::table('tb_laporanstok')
                ->where('id_bahanbaku', $bahan->id_bahanbaku)
                ->whereMonth('tanggal', Carbon::now()->month)
                ->whereYear('tanggal', Carbon::now()->year)
                ->update([
                    'barang_masuk' => DB::raw("barang_masuk - {$bahan->jumlah}"),
                    'stok_akhir' => DB::raw("stok_akhir - {$bahan->jumlah}"),
                ]);

            DB::table('tb_bahanbaku')
                ->where('id_bahanbaku', $bahan->id_bahanbaku)
                ->update([
                    'stok' => DB::raw("stok - {$bahan->jumlah}"),
                ]);
        }

        // Jika ingin, juga hapus semua bahan terkait dari tb_pengeluaran
        DB::table('tb_pemasukan')->where('id_transaksi', $id)->delete();

        // Hapus berdasarkan id_transaksi
        DB::table('tb_transaksi')->where('id_transaksi', $id)->delete();

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

    public function dataSupplier()
    {
        $suppliers = DB::table('tb_supplier')->orderBy('nama_supplier')->get();
        $bahanbaku = DB::table('tb_bahanbaku')->orderBy('nama_bahan')->get();

        return view('gudang.datasupplier', compact('suppliers', 'bahanbaku'));
    }

    public function updateSupplier(Request $request, $id)
    {
        $request->validate([
            'nama_supplier' => 'required',
            'no_telp' => 'required',
            'alamat' => 'required',
        ]);

        DB::table('tb_supplier')
            ->where('id_supplier', $id)
            ->update([
                'nama_supplier' => $request->nama_supplier,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
            ]);

        return redirect()->back()->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function deleteSuppliers($id)
    {
        $supplier = DB::table('tb_supplier')
            ->where('id_supplier', $id)
            ->first();

        if (!$supplier) {
            return redirect()->back()
                ->with('error', 'Supplier tidak ditemukan.');
        }

        DB::table('tb_supplier')
            ->where('id_supplier', $id)
            ->delete();

        return redirect()->back()->with('success', 'Supplier berhasil dihapus.');
    }

    public function editakun($id)
    {
        $akun = DB::table('tb_akun')->where('id_akun', $id)->first();

        if (!$akun) {
            return redirect()->back()->with('pesan', 'Akun tidak ditemukan.');
        }

        return view('gudang.editakun', compact('akun'));
    }

    public function updateakun(Request $request, $id)
    {
        $akun = DB::table('tb_akun')->where('id_akun', $id)->first();

        if (!$akun) {
            return redirect()->back()->with('pesan', 'Akun tidak ditemukan');
        }

        $data = [
            'username' => $request->username,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        DB::table('tb_akun')->where('id_akun', $id)->update($data);

        return redirect()->back()->with('pesan', 'Akun gudang berhasil diperbarui');
    }

    public function storeSupplier(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:100',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:100',
        ]);

        $last = DB::table('tb_supplier')->orderByDesc('id_supplier')->first();
        if ($last) {
            $lastNum = (int) substr($last->id_supplier, 2); // SP0001 -> 1
            $idSupplier = 'SP' . str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $idSupplier = 'SP0001';
        }
        if (!empty($request->no_telp) && !empty($request->alamat)) {
            DB::table('tb_supplier')->insert([
                'id_supplier' => $idSupplier,
                'nama_supplier' => $request->nama_supplier,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat
            ]);
        } elseif (!empty($request->no_telp) && empty($request->alamat)) {
            DB::table('tb_supplier')->insert([
                'id_supplier' => $idSupplier,
                'nama_supplier' => $request->nama_supplier,
                'no_telp' => $request->no_telp
            ]);
        } elseif (empty($request->no_telp) && !empty($request->alamat)) {
            DB::table('tb_supplier')->insert([
                'id_supplier' => $idSupplier,
                'nama_supplier' => $request->nama_supplier,
                'alamat' => $request->alamat,
            ]);
        } else {
            DB::table('tb_supplier')->insert([
                'id_supplier' => $idSupplier,
                'nama_supplier' => $request->nama_supplier,
            ]);
        }

        return redirect()->route('gudang.datasupplier')->with('success', 'Supplier berhasil ditambahkan!');
    }

    public function storeBahan(Request $request)
    {
        $request->validate([
            'id_supplier' => 'required|exists:tb_supplier,id_supplier',
            'nama_bahan' => 'required|string|max:100',
            'jenis_bahan' => 'required|string|max:50',
            'harga_modal' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:20',
        ]);

        $last = DB::table('tb_bahanbaku')->orderByDesc('id_bahanbaku')->first();
        if ($last) {
            $num = (int) substr($last->id_bahanbaku, 3);
            $id = 'BHN' . str_pad($num + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $id = 'BHN0001';
        }

        DB::table('tb_bahanbaku')->insert([
            'id_bahanbaku' => $id,
            'id_supplier' => $request->id_supplier,
            'nama_bahan' => $request->nama_bahan,
            'jenis_bahan' => $request->jenis_bahan,
            'harga_modal' => $request->harga_modal,
            'harga_jual' => $request->harga_jual,
            'stok' => $request->stok,
            'satuan' => $request->satuan,
        ]);

        return redirect('/gudang')->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function deleteSupplier($id)
    {
        try {
            // Hapus bahan baku yang terkait dengan supplier
            DB::table('tb_bahanbaku')->where('id_supplier', $id)->delete();

            // Hapus supplier
            DB::table('tb_supplier')->where('id_supplier', $id)->delete();

            return redirect()->back()->with('success', 'Supplier dan bahan terkait berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus supplier: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus supplier.');
        }
    }

    public function deleteBahan($id)
    {
        try {
            DB::table('tb_bahanbaku')->where('id_bahanbaku', $id)->delete();
            return redirect()->back()->with('success', 'Bahan baku berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus bahan baku: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus bahan baku.');
        }
    }

    public function printNota($id)
    {
        $data = DB::table('tb_pengeluaran')
            ->join('tb_transaksi', 'tb_pengeluaran.id_transaksi', '=', 'tb_transaksi.id_transaksi')
            ->join('tb_franchise', 'tb_pengeluaran.id_franchise', '=', 'tb_franchise.id_franchise')
            ->join('tb_paket', 'tb_pengeluaran.id_paket', '=', 'tb_paket.id_paket')
            ->join('tb_detailpengeluaran', 'tb_pengeluaran.id_pengeluaran', '=', 'tb_detailpengeluaran.id_pengeluaran')
            ->join('tb_bahanbaku', 'tb_detailpengeluaran.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
            ->where('tb_pengeluaran.id_transaksi', $id)
            ->select(
                'tb_transaksi.id_transaksi',
                'tb_transaksi.tanggal_transaksi',
                'tb_transaksi.total',

                'tb_pengeluaran.id_pengeluaran',
                'tb_pengeluaran.jumlah as jumlah_paket',
                'tb_pengeluaran.harga as subtotal_paket',

                'tb_franchise.nama_franchise',
                'tb_franchise.alamat_usaha',

                'tb_paket.nama_paket',

                'tb_bahanbaku.nama_bahan',
                'tb_bahanbaku.satuan',
                'tb_detailpengeluaran.jumlah as jumlah_bahan'
            )
            ->get();

        if ($data->isEmpty()) {
            return redirect()->back()->with('error', 'Data nota tidak ditemukan.');
        }

        $total = DB::table('tb_transaksi')
            ->where('id_transaksi', $id)
            ->first();

        $header = $data->first();

        $pdf = Pdf::loadView('gudang.nota_keluar', compact('data', 'header', 'total'))
            ->setPaper([0, 0, 226.77, 600], 'portrait');

        $filename = 'nota_' . $id . '.pdf';
        $path = public_path('uploads/strukdc/' . $filename);

        if (!file_exists(public_path('uploads/strukdc'))) {
            mkdir(public_path('uploads/strukdc'), 0777, true);
        }

        $pdf->save($path);

        DB::table('tb_transaksi')
            ->where('id_transaksi', $id)
            ->update([
                'struk' => $filename,
            ]);

        return view('gudang.nota_keluar', compact('data', 'header', 'total'));
    }
    
    public function printNotamasuk($id)
    {
        $data = DB::table('tb_pemasukan')
            ->join('tb_bahanbaku', 'tb_pemasukan.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
            ->join('tb_supplier', 'tb_bahanbaku.id_supplier', '=', 'tb_supplier.id_supplier')
            ->where('tb_pemasukan.id_transaksi', $id)
            ->select(
                'tb_pemasukan.*',
                'tb_bahanbaku.nama_bahan',
                'tb_bahanbaku.satuan',
                'tb_supplier.nama_supplier'
            )
            ->get();

        $total = DB::table('tb_transaksi')
                ->where('id_transaksi', '=', $id)
                ->first();


        $header = $data->first(); 
        
        $pdf = Pdf::loadView('gudang.nota_masuk', compact('data', 'header', 'total'))
            ->setPaper([0, 0, 226.77, 600], 'portrait'); 
    
        $filename = 'nota_' . $id . '.pdf';
        $path = public_path('uploads/strukdc/' . $filename);
        $pdf->save($path);
        
        DB::table('tb_transaksi')
        ->where('id_transaksi', $id)
        ->update([
            'struk' => $filename, 
        ]);

        return view('gudang.nota_masuk', compact('data', 'header', 'total'));
    }

  
public function updateStok(Request $request, $id)
{
    $request->validate([
        'stok_awal'     => 'required|numeric|min:0',
        'barang_masuk'  => 'required|numeric|min:0',
        'barang_keluar' => 'required|numeric|min:0',
    ]);

    // Hitung stok akhir otomatis
    $stok_akhir = $request->stok_awal + $request->barang_masuk - $request->barang_keluar;

    // Update ke tb_laporanstok
    DB::table('tb_laporanstok')
        ->where('id_bahanbaku', $id)
        ->whereMonth('tanggal', now()->month)
        ->whereYear('tanggal', now()->year)
        ->update([
            'stok_awal'     => $request->stok_awal,
            'barang_masuk'  => $request->barang_masuk,
            'barang_keluar' => $request->barang_keluar,
            'stok_akhir'    => $stok_akhir,
        ]);

    return redirect()->route('laporan.stok')->with('success', 'Data stok berhasil diperbarui.');
}





    public function update(Request $request, $id)
        {
            $request->validate([
                'stok_awal' => 'required|integer|min:0',
                'barang_masuk' => 'required|integer|min:0',
                'barang_keluar' => 'required|integer|min:0',
                'stok_akhir' => 'required|integer|min:0',
            ]);

            try {
                $bahan = Bahan::findOrFail($id);
                
                $bahan->update([
                    'stok_awal' => $request->stok_awal,
                    'barang_masuk' => $request->barang_masuk,
                    'barang_keluar' => $request->barang_keluar,
                    'stok_akhir' => $request->stok_akhir,
                ]);

                return redirect()->route('laporan.stok')->with('success', 'Data stok berhasil diperbarui!');
            } catch (\Exception $e) {
                return redirect()->route('laporan.stok')->with('error', 'Gagal memperbarui data stok: ' . $e->getMessage());
            }
        }

 public function tambahLaporan(Request $request)
{
    $request->validate([
        'id_bahanbaku' => 'required|exists:tb_bahanbaku,id_bahanbaku',
        'stok_awal' => 'required|numeric|min:0',
    ]);

    $id_bahan = $request->id_bahanbaku;
    $stok_awal = $request->stok_awal;
    $tanggal = now()->startOfMonth()->toDateString();

    // Cegah duplikasi laporan stok bulanan
    $sudahAda = DB::table('tb_laporanstok')
        ->where('id_bahanbaku', $id_bahan)
        ->whereMonth('tanggal', now()->month)
        ->whereYear('tanggal', now()->year)
        ->exists();

    if ($sudahAda) {
        return redirect()->back()->with('error', 'Data laporan stok untuk bahan ini sudah ada bulan ini.');
    }

    // Hitung total barang masuk dari transaksi pemasukan bulan ini
    $barangMasuk = DB::table('tb_pemasukan')
        ->where('id_bahanbaku', $id_bahan)
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('jumlah');

    // Hitung total barang keluar dari transaksi pengeluaran bulan ini
    $barangKeluar = DB::table('tb_pengeluaran')
        ->where('id_bahanbaku', $id_bahan)
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('jumlah');

    // Hitung stok akhir
    $stokAkhir = $stok_awal + $barangMasuk - $barangKeluar;

    // Generate ID laporan
    $last = DB::table('tb_laporanstok')->orderByDesc('id_laporan')->first();
    $lastNum = $last ? (int) substr($last->id_laporan, 1) : 0;
    $idLaporan = 'L' . str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);

    // Simpan
    DB::table('tb_laporanstok')->insert([
        'id_laporan' => $idLaporan,
        'id_bahanbaku' => $id_bahan,
        'stok_awal' => $stok_awal,
        'barang_masuk' => $barangMasuk,
        'barang_keluar' => $barangKeluar,
        'stok_akhir' => $stokAkhir,
        'tanggal' => $tanggal,
    ]);

    return redirect()->back()->with('success', 'Data stok berhasil ditambahkan!');
}

public function tambahLaporanStok(Request $request)
{
    $request->validate([
        'id_bahanbaku' => 'required|exists:tb_bahanbaku,id_bahanbaku',
        'stok_awal' => 'required|integer|min:0',
    ]);

    // Cek apakah data stok bahan ini sudah ada untuk bulan ini
    $exists = DB::table('tb_laporanstok')
        ->where('id_bahanbaku', $request->id_bahanbaku)
        ->whereMonth('tanggal', now()->month)
        ->whereYear('tanggal', now()->year)
        ->exists();

    if ($exists) {
        return redirect()->route('laporan.stok')
            ->with('error', 'Data untuk bahan ini sudah ada di bulan ini.');
    }

    DB::table('tb_laporanstok')->insert([
        'id_bahanbaku' => $request->id_bahanbaku,
        'stok_awal' => $request->stok_awal,
        'barang_masuk' => 0,
        'barang_keluar' => 0,
        'tanggal' => now()
    ]);

    return redirect()->route('laporan.stok')->with('success', 'Laporan stok berhasil ditambahkan.');
}


public function updateLaporanStok(Request $request, $id)
{
    $request->validate([
        'stok_awal' => 'required|integer|min:0',
        'barang_masuk' => 'required|integer|min:0',
        'barang_keluar' => 'required|integer|min:0',
    ]);

    DB::table('tb_laporanstok')
        ->where('id_bahanbaku', $id)
        ->whereMonth('tanggal', now()->month)
        ->whereYear('tanggal', now()->year)
        ->update([
            'stok_awal' => $request->stok_awal,
            'barang_masuk' => $request->barang_masuk,
            'barang_keluar' => $request->barang_keluar,
        ]);

    return redirect()->route('laporan.stok')->with('success', 'Laporan stok berhasil diperbarui.');
}

public function dashboardOmset(Request $request)
{
    $bulan = $request->input('bulan');
    $tahun = $request->input('tahun', date('Y'));

    $tahunSekarang = date('Y');
    $tahunList = range($tahunSekarang - 5, $tahunSekarang + 5);

    // === Query utama (Omset dari pengeluaran ke franchise) ===
    $query = DB::table('tb_pengeluaran')
        ->join('tb_bahanbaku', 'tb_pengeluaran.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
        ->whereYear('tb_pengeluaran.created_at', $tahun);

    if (!empty($bulan)) {
        $query->whereMonth('tb_pengeluaran.created_at', $bulan);
    }

    $totals = (clone $query)
        ->selectRaw('
            SUM(tb_pengeluaran.harga) as omset_kotor,
            SUM((tb_pengeluaran.harga - tb_bahanbaku.harga_modal) * tb_pengeluaran.jumlah) as omset_bersih
        ')
        ->first();

    $omsetKotor = $totals->omset_kotor ?? 0;
    $omsetBersih = $totals->omset_bersih ?? 0;

    // Detail transaksi Omset
    $transaksiDetail = (clone $query)
        ->select(
            'tb_pengeluaran.id_transaksi',
            'tb_bahanbaku.nama_bahan',
            'tb_pengeluaran.jumlah',
            'tb_bahanbaku.harga_modal',
            'tb_pengeluaran.harga as harga_jual',
            DB::raw('(tb_pengeluaran.harga - tb_bahanbaku.harga_modal) * tb_pengeluaran.jumlah as laba'),
            'tb_pengeluaran.created_at'
        )
        ->orderBy('tb_pengeluaran.created_at', 'desc')
        ->get();

    // Total transaksi
    $totalTransaksi = (clone $query)
        ->distinct('tb_pengeluaran.id_transaksi')
        ->count('tb_pengeluaran.id_transaksi');

    // === Chart Data ===
    // Pengeluaran DC (pembelian bahan dari supplier â†’ tb_pemasukan)
    $pengeluaranChart = DB::table('tb_pemasukan')
        ->join('tb_bahanbaku', 'tb_pemasukan.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
        ->selectRaw('
            MONTH(tb_pemasukan.created_at) as bulan,
            SUM(tb_bahanbaku.harga_modal * tb_pemasukan.jumlah) as pengeluaran
        ')
        ->whereYear('tb_pemasukan.created_at', $tahun)
        ->when($bulan, fn($q) => $q->whereMonth('tb_pemasukan.created_at', $bulan))
        ->groupBy(DB::raw('MONTH(tb_pemasukan.created_at)'))
        ->orderBy('bulan')
        ->get();

    // Omset Bersih (tb_pengeluaran)
    $omsetBersihChart = DB::table('tb_pengeluaran')
        ->join('tb_bahanbaku', 'tb_pengeluaran.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
        ->selectRaw('
            MONTH(tb_pengeluaran.created_at) as bulan,
            SUM((tb_pengeluaran.harga - tb_bahanbaku.harga_modal) * tb_pengeluaran.jumlah) as omset_bersih
        ')
        ->whereYear('tb_pengeluaran.created_at', $tahun)
        ->when($bulan, fn($q) => $q->whereMonth('tb_pengeluaran.created_at', $bulan))
        ->groupBy(DB::raw('MONTH(tb_pengeluaran.created_at)'))
        ->orderBy('bulan')
        ->get();

    // Gabungkan chart data
    // Gabungkan chart data (selalu 12 bulan, kalau ada filter bulan lain = 0)
$chartData = collect(range(1, 12))->map(function ($bln) use ($pengeluaranChart, $omsetBersihChart, $bulan) {
    if ($bulan && (int)$bulan !== $bln) {
        // kalau ada filter bulan, selain bulan itu isinya 0
        return (object)[
            'bulan' => $bln,
            'pengeluaran' => 0,
            'omset_bersih' => 0,
        ];
    }

    return (object)[
        'bulan' => $bln,
        'pengeluaran' => optional($pengeluaranChart->firstWhere('bulan', $bln))->pengeluaran ?? 0,
        'omset_bersih' => optional($omsetBersihChart->firstWhere('bulan', $bln))->omset_bersih ?? 0,
    ];
});


    // === Pengeluaran DC (tabel detail pembelian bahan dari supplier) ===
    $pengeluaranQuery = DB::table('tb_pemasukan')
        ->join('tb_bahanbaku', 'tb_pemasukan.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
        ->whereYear('tb_pemasukan.created_at', $tahun);

    if (!empty($bulan)) {
        $pengeluaranQuery->whereMonth('tb_pemasukan.created_at', $bulan);
    }

    $pengeluaran = $pengeluaranQuery
        ->select('tb_pemasukan.*', 'tb_bahanbaku.harga_modal')
        ->get();

    $totalPengeluaranDC = $pengeluaran->sum(fn($p) => $p->harga_modal * $p->jumlah);

    // === Return ke view ===
    return view('gudang.omset', [
        'chartData' => $chartData,
        'omsetKotor' => $omsetKotor,
        'omsetBersih' => $omsetBersih,
        'pengeluaran' => $pengeluaran,
        'totalPengeluaranDC' => $totalPengeluaranDC,
        'totalTransaksi' => $totalTransaksi,
        'bulanSekarang' => (int) ($bulan ?: date('m')),
        'tahunSekarang' => (int) $tahun,
        'tahunList' => $tahunList,
        'transaksiDetail' => $transaksiDetail,
    ]);
}

public function exportPenjualanPDF(Request $request)
{
    $bulan = $request->input('bulan');
    $tahun = $request->input('tahun', date('Y'));
    $direction = $request->input('direction', 'desc');

    $query = DB::table('tb_pengeluaran')
        ->join('tb_detailpengeluaran', 'tb_pengeluaran.id_pengeluaran', '=', 'tb_detailpengeluaran.id_pengeluaran')
        ->join('tb_bahanbaku', 'tb_detailpengeluaran.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
        ->whereYear('tb_pengeluaran.created_at', $tahun);

    if (!empty($bulan)) {
        $query->whereMonth('tb_pengeluaran.created_at', $bulan);
    }

    $transaksiDetail = $query->select(
            'tb_bahanbaku.id_bahanbaku',
            'tb_bahanbaku.nama_bahan',
            DB::raw('SUM(tb_detailpengeluaran.jumlah) as jumlah'),
            DB::raw('SUM(tb_bahanbaku.harga_modal * tb_detailpengeluaran.jumlah) as harga_modal_total'),
            DB::raw('SUM(tb_bahanbaku.harga_jual * tb_detailpengeluaran.jumlah) as harga_jual_total'),
            DB::raw('SUM((tb_bahanbaku.harga_jual - tb_bahanbaku.harga_modal) * tb_detailpengeluaran.jumlah) as laba'),
            DB::raw('MAX(tb_pengeluaran.created_at) as created_at')
        )
        ->groupBy(
            'tb_bahanbaku.id_bahanbaku',
            'tb_bahanbaku.nama_bahan'
        )
        ->orderBy('created_at', $direction)
        ->get();

        $pdf = Pdf::loadView('exports.penjualan_pdf', [
            'data' => $transaksiDetail,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ])->setPaper('a4', 'landscape');

    return $pdf->download('Laporan_Penjualan_' .($bulan ?: 'SemuaBulan') .'_' .$tahun .'.pdf');
}


public function exportPenjualanExcel(Request $request)
{
    $bulan = $request->input('bulan');
    $tahun = $request->input('tahun', date('Y'));
    $direction = $request->input('direction', 'desc');

    $query = DB::table('tb_pengeluaran')
        ->join('tb_detailpengeluaran', 'tb_pengeluaran.id_pengeluaran', '=', 'tb_detailpengeluaran.id_pengeluaran')
        ->join('tb_bahanbaku', 'tb_detailpengeluaran.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
        ->whereYear('tb_pengeluaran.created_at', $tahun);

    if (!empty($bulan)) {
        $query->whereMonth('tb_pengeluaran.created_at', $bulan);
    }

    $data = $query->select(
            'tb_bahanbaku.id_bahanbaku',
            'tb_bahanbaku.nama_bahan',
            DB::raw('SUM(tb_detailpengeluaran.jumlah) as jumlah'),
            DB::raw('SUM(tb_bahanbaku.harga_modal * tb_detailpengeluaran.jumlah) as harga_modal_total'),
            DB::raw('SUM(tb_bahanbaku.harga_jual * tb_detailpengeluaran.jumlah) as harga_jual_total'),
            DB::raw('SUM((tb_bahanbaku.harga_jual - tb_bahanbaku.harga_modal) * tb_detailpengeluaran.jumlah) as laba'),
            DB::raw('MAX(tb_pengeluaran.created_at) as created_at')
        )
        ->groupBy(
            'tb_bahanbaku.id_bahanbaku',
            'tb_bahanbaku.nama_bahan'
        )
        ->orderBy('created_at', $direction)
        ->get();

    return \Excel::download(
        new \App\Exports\PenjualanExport($data),
        'Laporan_Penjualan_' . ($bulan ?: 'SemuaBulan') . '_' . $tahun . '.xlsx'
    );
}

public function exportPengeluaranPDF(Request $request)
{
    $tahun = $request->get('tahun', date('Y'));
    $bulan = $request->get('bulan');

    $data = DB::table('tb_pemasukan')
        ->join('tb_bahanbaku', 'tb_pemasukan.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
        ->select(
            'tb_pemasukan.id_pemasukan',
            'tb_bahanbaku.nama_bahan',
            'tb_pemasukan.jumlah',
            'tb_bahanbaku.harga_modal',
            DB::raw('tb_bahanbaku.harga_modal * tb_pemasukan.jumlah as total_pengeluaran'),
            'tb_pemasukan.created_at'
        )
        ->whereYear('tb_pemasukan.created_at', $tahun)
        ->when($bulan, fn($q) => $q->whereMonth('tb_pemasukan.created_at', $bulan))
        ->get();

    $pdf = Pdf::loadView('exports.pengeluaran_pdf', [
            'pengeluaran' => $data,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);

    return $pdf->download("Laporan_PengeluaranDC_{$bulan}_{$tahun}.pdf");
}

public function exportPengeluaranExcel(Request $request)
{
    $tahun = $request->get('tahun', date('Y'));
    $bulan = $request->get('bulan');

    $data = DB::table('tb_pemasukan')
        ->join('tb_bahanbaku', 'tb_pemasukan.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
        ->select(
            'tb_pemasukan.id_pemasukan',
            'tb_bahanbaku.nama_bahan',
            'tb_pemasukan.jumlah',
            'tb_bahanbaku.harga_modal',
            DB::raw('tb_bahanbaku.harga_modal * tb_pemasukan.jumlah as total_pengeluaran'),
            'tb_pemasukan.created_at'
        )
        ->whereYear('tb_pemasukan.created_at', $tahun)
        ->when($bulan, fn($q) => $q->whereMonth('tb_pemasukan.created_at', $bulan))
        ->get();

    // Kirim semua 3 argumen ke konstruktor Export
    return Excel::download(
        new \App\Exports\PengeluaranExport($data, $bulan, $tahun),
        "Laporan_PengeluaranDC_{$bulan}_{$tahun}.xlsx"
    );
}

public function tabelPenjualan(Request $request)
{
    $bulan = $request->input('bulan');
    $tahun = $request->input('tahun', date('Y'));
    $direction = $request->input('direction', 'desc');


    $query = DB::table('tb_pengeluaran')
        ->join('tb_detailpengeluaran', 'tb_pengeluaran.id_pengeluaran', '=', 'tb_detailpengeluaran.id_pengeluaran')
        ->join('tb_bahanbaku', 'tb_detailpengeluaran.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
        ->whereMonth('tb_pengeluaran.created_at', $bulan ?? Carbon::now()->month)
        ->whereYear('tb_pengeluaran.created_at', $tahun);

    if (!empty($bulan)) {
        $query->whereMonth('tb_pengeluaran.created_at', $bulan);
    }

    $transaksiDetail = $query->select(
            'tb_bahanbaku.id_bahanbaku',
            'tb_bahanbaku.nama_bahan',
            DB::raw('SUM(tb_detailpengeluaran.jumlah) as jumlah'),
            DB::raw('SUM(tb_bahanbaku.harga_modal * tb_detailpengeluaran.jumlah) as harga_modal_total'),
            DB::raw('SUM(tb_bahanbaku.harga_jual * tb_detailpengeluaran.jumlah) as harga_jual_total'),
            DB::raw('SUM((tb_bahanbaku.harga_jual - tb_bahanbaku.harga_modal) * tb_detailpengeluaran.jumlah) as laba'),
            DB::raw('MAX(tb_pengeluaran.created_at) as created_at')
        )
        ->groupBy(
            'tb_bahanbaku.id_bahanbaku',
            'tb_bahanbaku.nama_bahan'
        )
        ->orderBy('created_at', $direction)
        ->get();

    return view('gudang.tabelpenjualan', [
        'transaksiDetail' => $transaksiDetail,
        'bulanSekarang' => (int)($bulan ?: date('m')),
        'tahunSekarang' => (int)$tahun,
        'direction' => $direction,
    ]);
}

public function tabelPaket(Request $request)
{
    $paket = DB::table('tb_paket')
        ->join('tb_detailpaket', 'tb_paket.id_paket', '=', 'tb_detailpaket.id_paket')
        ->join('tb_bahanbaku', 'tb_detailpaket.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
        ->select(
            'tb_paket.id_paket',
            'tb_paket.nama_paket',
            'tb_paket.harga',
            'tb_paket.gambar_paket',
            DB::raw("GROUP_CONCAT(tb_bahanbaku.nama_bahan SEPARATOR ', ') as bahan"),
            DB::raw("GROUP_CONCAT(tb_bahanbaku.id_bahanbaku SEPARATOR ', ') as id_bahanbaku"),
            DB::raw("GROUP_CONCAT(tb_bahanbaku.satuan SEPARATOR ', ') as satuan"),
            DB::raw("GROUP_CONCAT(tb_detailpaket.jumlah SEPARATOR ', ') as jumlah")
        )
        ->groupBy('tb_paket.id_paket', 'tb_paket.nama_paket', 'tb_paket.harga', 'tb_paket.gambar_paket')
        ->get();


    $bahanbaku = DB::table('tb_bahanbaku')
        ->select()
        ->get();

    return view('gudang.tabelpaket', [
        'paket' => $paket,
        'bahanbaku' => $bahanbaku,
    ]);
}

    public function tambahPaket(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'id_bahanbaku' => 'required|array|min:1',
            'id_bahanbaku.*' => 'required|string|exists:tb_bahanbaku,id_bahanbaku',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'required|numeric|min:1',
            'harga' => 'required',
            'gambar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama_paket.required' => 'Nama paket wajib diisi.',
            'id_bahanbaku.*.required' => 'Bahan baku wajib dipilih.',
            'id_bahanbaku.*.exists' => 'Bahan baku tidak valid.',
            'jumlah.*.required' => 'Jumlah wajib diisi.',
            'jumlah.*.numeric' => 'Jumlah harus berupa angka.',
            'jumlah.*.min' => 'Jumlah minimal 1.',
            'harga.min' => 'Harga minimal Rp. 1000.',
            'gambar.required' => 'Masukkan gambar untuk paket',
        ]);


        DB::beginTransaction();

        try {
            $harga = str_replace(['Rp', '.', ' '], '', $request->harga);

            $lastPaket = DB::table('tb_paket')
                ->orderBy('id_paket', 'desc')
                ->first();

            if ($lastPaket) {
                $lastNumber = (int) substr($lastPaket->id_paket, 1);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $namaGambar = null;

            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $namaGambar = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/paket'), $namaGambar);
            }

            $idPaket = 'P' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

            DB::table('tb_paket')->insert([
                'id_paket'    => $idPaket,
                'nama_paket'  => $request->nama_paket,
                'harga'       => $harga,
                'gambar_paket' => $namaGambar,
            ]);

            foreach ($request->id_bahanbaku as $i => $idBahan) {
                if (!empty($idBahan) && !empty($request->jumlah[$i])) {
                    $lastDetailpaket = DB::table('tb_detailpaket')
                        ->orderBy('id_detailpaket', 'desc')
                        ->first();

                    if ($lastDetailpaket) {
                        $lastNumber = (int) substr($lastDetailpaket->id_detailpaket, 2);
                        $newNumber = $lastNumber + 1;
                    } else {
                        $newNumber = 1;
                    }

                    $idDetailpaket = 'DP' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
                    DB::table('tb_detailpaket')->insert([
                        'id_detailpaket' => $idDetailpaket,
                        'id_paket'      => $idPaket,
                        'id_bahanbaku'  => $idBahan,
                        'jumlah'        => $request->jumlah[$i],
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Paket berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error tambah paket', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'request' => $request->all()
            ]);

            return redirect()->back()->with('error', 'Paket gagal ditambahkan. Error: ' . $e->getMessage());
        }
    }

    public function updatePaket(Request $request)
    {
        $request->validate([
            'id_paket' => 'required|exists:tb_paket,id_paket',
            'nama_paket' => 'required|string|max:255',
            'id_bahanbaku' => 'required|array|min:1',
            'id_bahanbaku.*' => 'required|string|exists:tb_bahanbaku,id_bahanbaku',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'required|numeric|min:1',
            'harga' => 'required',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::beginTransaction();

        try {

            $harga = preg_replace('/[^0-9]/', '', $request->harga);

            $paketLama = DB::table('tb_paket')
                ->where('id_paket', $request->id_paket)
                ->first();

            // gunakan gambar lama jika tidak upload baru
            $namaGambar = $paketLama->gambar_paket;

            if ($request->hasFile('gambar')) {

                // hapus gambar lama
                if (
                    $paketLama->gambar_paket &&
                    file_exists(public_path('uploads/paket/' . $paketLama->gambar_paket))
                ) {
                    unlink(public_path('uploads/paket/' . $paketLama->gambar_paket));
                }

                $file = $request->file('gambar');

                $namaGambar = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $file->move(
                    public_path('uploads/paket'),
                    $namaGambar
                );
            }

            DB::table('tb_paket')
                ->where('id_paket', $request->id_paket)
                ->update([
                    'nama_paket'   => $request->nama_paket,
                    'harga'        => $harga,
                    'gambar_paket' => $namaGambar,
                ]);

            DB::table('tb_detailpaket')
                ->where('id_paket', $request->id_paket)
                ->delete();

            $lastDetail = DB::table('tb_detailpaket')
                ->orderBy('id_detailpaket', 'desc')
                ->first();

            $newNumberDetail = $lastDetail
                ? (int) substr($lastDetail->id_detailpaket, 2)
                : 0;

            foreach ($request->id_bahanbaku as $i => $idBahan) {

                $newNumberDetail++;

                $idDetail = 'DP' . str_pad($newNumberDetail, 3, '0', STR_PAD_LEFT);

                DB::table('tb_detailpaket')->insert([
                    'id_detailpaket' => $idDetail,
                    'id_paket' => $request->id_paket,
                    'id_bahanbaku' => $idBahan,
                    'jumlah' => $request->jumlah[$i],
                ]);
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Paket berhasil diupdate.');
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Error update paket', [
                'message' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return redirect()->back()
                ->with('error', 'Paket gagal diupdate.');
        }
    }

    public function deletePaket($id)
    {
        DB::beginTransaction();

        try {
            $paket = DB::table('tb_paket')
                ->where('id_paket', $id)
                ->first();

            if (!$paket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data paket tidak ditemukan.'
                ], 404);
            }

            Log::info('Cek gambar paket', [
                'id_paket' => $id,
                'gambar_paket' => $paket->gambar_paket ?? null,
                'path' => public_path('uploads/paket/' . ($paket->gambar_paket ?? '')),
                'exists' => file_exists(public_path('uploads/paket/' . ($paket->gambar_paket ?? ''))),
            ]);

            if ($paket->gambar_paket && file_exists(public_path('uploads/paket/' . $paket->gambar_paket))) {
                unlink(public_path('uploads/paket/' . $paket->gambar_paket));
            }

            DB::table('tb_detailpaket')
                ->where('id_paket', $id)
                ->delete();

            DB::table('tb_paket')
                ->where('id_paket', $id)
                ->delete();

            DB::commit();

            return response()->json([
                'success' => true
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function tabelPengeluaran(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', date('Y'));

        $query = DB::table('tb_pemasukan')
            ->join('tb_bahanbaku', 'tb_pemasukan.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
            ->whereYear('tb_pemasukan.created_at', $tahun);

        if (!empty($bulan)) {
            $query->whereMonth('tb_pemasukan.created_at', $bulan);
        }

        $pengeluaran = $query->select(
            'tb_pemasukan.id_pemasukan',
            'tb_bahanbaku.nama_bahan',
            'tb_pemasukan.jumlah',
            'tb_bahanbaku.harga_modal',
            DB::raw('tb_bahanbaku.harga_modal * tb_pemasukan.jumlah as total_pengeluaran'),
            'tb_pemasukan.created_at'
        )->orderBy('tb_pemasukan.created_at', 'desc')->get();

        return view('gudang.tabelpengeluaran', [
            'pengeluaran' => $pengeluaran,
            'bulanSekarang' => (int)($bulan ?: date('m')),
            'tahunSekarang' => (int)$tahun,
        ]);
    }

    public function exportStokPDF()
    {
        $bahan = DB::table('tb_bahanbaku')
            ->leftJoin('tb_laporanstok', function ($join) {
                $join->on('tb_bahanbaku.id_bahanbaku', '=', 'tb_laporanstok.id_bahanbaku')
                    ->whereMonth('tb_laporanstok.tanggal', now()->month)
                    ->whereYear('tb_laporanstok.tanggal', now()->year);
            })
            ->select(
                'tb_bahanbaku.nama_bahan',
                'tb_bahanbaku.id_bahanbaku',
                'tb_bahanbaku.stok as stok_terkini',
                'tb_laporanstok.stok_awal',
                'tb_laporanstok.barang_masuk',
                'tb_laporanstok.barang_keluar',
                'tb_laporanstok.stok_akhir'
            )
            ->get();

        // HARUS pakai loadView agar Blade dirender jadi HTML
        $pdf = Pdf::loadView('exports.stok_pdf', compact('bahan'));
        return $pdf->download('Laporan_Stok_BahanBaku.pdf');
    }

    public function exportStokExcel()
    {
        $bahan = DB::table('tb_bahanbaku')
            ->leftJoin('tb_laporanstok', function ($join) {
                $join->on('tb_bahanbaku.id_bahanbaku', '=', 'tb_laporanstok.id_bahanbaku')
                    ->whereMonth('tb_laporanstok.tanggal', now()->month)
                    ->whereYear('tb_laporanstok.tanggal', now()->year);
            })
            ->select(
                'tb_bahanbaku.nama_bahan',
                'tb_bahanbaku.id_bahanbaku',
                'tb_bahanbaku.stok as stok_terkini',
                'tb_laporanstok.stok_awal',
                'tb_laporanstok.barang_masuk',
                'tb_laporanstok.barang_keluar',
                'tb_laporanstok.stok_akhir'
            )
            ->get();

        return Excel::download(new class($bahan) implements \Maatwebsite\Excel\Concerns\FromView {
            private $bahan;
            public function __construct($bahan) { $this->bahan = $bahan; }
            public function view(): \Illuminate\Contracts\View\View {
                // HARUS pakai view() di sini juga
                return view('exports.stok_excel', ['bahan' => $this->bahan]);
            }
        }, 'Laporan_Stok_BahanBaku.xlsx');
    }
    
    public function exportRiwayatMasukPDF(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;

        $keywordMasuk = $request->input('keyword_masuk');
        $keywordKeluar = $request->input('keyword_keluar');

        // Riwayat Pemasukan
        $riwayatmasuk = DB::table('tb_transaksi')
            ->join('tb_pemasukan', 'tb_pemasukan.id_transaksi', '=', 'tb_transaksi.id_transaksi')
            ->join('tb_bahanbaku', 'tb_pemasukan.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
            ->leftJoin('tb_supplier', 'tb_bahanbaku.id_supplier', '=', 'tb_supplier.id_supplier')
            ->select(
                'tb_transaksi.id_transaksi',
                'tb_transaksi.tanggal_transaksi',
                DB::raw('GROUP_CONCAT(tb_bahanbaku.nama_bahan SEPARATOR ", ") as nama_bahan'),
                DB::raw('GROUP_CONCAT(tb_pemasukan.jumlah SEPARATOR ", ") as jumlah'),
                DB::raw('GROUP_CONCAT(tb_pemasukan.harga SEPARATOR ", ") as harga'),
                DB::raw('GROUP_CONCAT(tb_bahanbaku.satuan SEPARATOR ", ") as satuan'),
                'tb_supplier.nama_supplier',
                'tb_transaksi.total',
                DB::raw('MAX(tb_pemasukan.created_at) as created_at'),
                DB::raw('MAX(tb_pemasukan.updated_at) as updated_at')
            )
            ->where('tb_transaksi.jenis_transaksi', '=', 'Pemasukan')
            ->when($keywordMasuk, function ($query, $keywordMasuk) {
                $query->where(function ($q) use ($keywordMasuk) {
                    $q->where('tb_bahanbaku.nama_bahan', 'like', "%$keywordMasuk%")
                      ->orWhere('tb_supplier.nama_supplier', 'like', "%$keywordMasuk%");
                });
            })
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('tb_transaksi.tanggal_transaksi', [$fromDate, $toDate]);
            })
            ->groupBy('tb_transaksi.id_transaksi', 'tb_transaksi.tanggal_transaksi', 'tb_supplier.nama_supplier', 'tb_transaksi.total')
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('exports.riwayatmasuk_pdf', compact('riwayatmasuk', 'from', 'to'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Pemasukan_' . now()->format('Ymd_His') . '.pdf');
    }

    public function exportRiwayatMasukExcel(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $keywordMasuk = $request->input('keyword_masuk');

        return Excel::download(
            new RiwayatMasukExport($from, $to, $keywordMasuk),
            'Laporan_Pemasukan_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function exportRiwayatKeluarPDF(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;

        $keywordMasuk = $request->input('keyword_masuk');
        $keywordKeluar = $request->input('keyword_keluar');

        $riwayatkeluar = DB::table('tb_transaksi')
        ->join('tb_pengeluaran', 'tb_pengeluaran.id_transaksi', '=', 'tb_transaksi.id_transaksi')
        ->leftJoin('tb_detailpengeluaran', 'tb_pengeluaran.id_pengeluaran', '=', 'tb_detailpengeluaran.id_pengeluaran')
        ->leftJoin('tb_bahanbaku', 'tb_detailpengeluaran.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
        ->leftJoin('tb_franchise', 'tb_pengeluaran.id_franchise', '=', 'tb_franchise.id_franchise')
        ->select(
            'tb_transaksi.id_transaksi',
            'tb_transaksi.tanggal_transaksi',
            DB::raw('GROUP_CONCAT(tb_bahanbaku.nama_bahan SEPARATOR ", ") as nama_bahan'),
            DB::raw('GROUP_CONCAT(tb_pengeluaran.jumlah SEPARATOR ", ") as jumlah'),
            DB::raw('GROUP_CONCAT(tb_pengeluaran.harga SEPARATOR ", ") as harga'),
            DB::raw('GROUP_CONCAT(tb_bahanbaku.satuan SEPARATOR ", ") as satuan'),
            DB::raw('GROUP_CONCAT(tb_franchise.nama_franchise SEPARATOR ", ") as nama_franchise'),
            DB::raw('GROUP_CONCAT(tb_franchise.alamat_usaha SEPARATOR ", ") as alamat_usaha'),
            'tb_transaksi.total',
            DB::raw('MAX(tb_pengeluaran.created_at) as created_at'),
            DB::raw('MAX(tb_pengeluaran.updated_at) as updated_at')
        )
        ->where('tb_transaksi.jenis_transaksi', '=', 'Pengeluaran')
        ->when($keywordKeluar, function ($query, $keywordKeluar) {
            $query->where(function ($q) use ($keywordKeluar) {
                $q->where('tb_bahanbaku.nama_bahan', 'like', "%$keywordKeluar%")
                  ->orWhere('tb_franchise.nama_franchise', 'like', "%$keywordKeluar%");
            });
        })
        ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
            $query->whereBetween('tb_transaksi.tanggal_transaksi', [$fromDate, $toDate]);
        })
        ->groupBy(
            'tb_transaksi.id_transaksi',
            'tb_transaksi.tanggal_transaksi',
            'tb_transaksi.total'
        )
        ->orderBy('created_at', 'desc')
        ->get();

        $pdf = Pdf::loadView('exports.riwayatkeluar_pdf', compact('riwayatkeluar', 'from', 'to'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Pengeluaran_' . now()->format('Ymd_His') . '.pdf');
    }

    public function exportRiwayatKeluarExcel(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $keywordKeluar = $request->keyword_keluar;

        return Excel::download(
            new RiwayatKeluarExport($from, $to, $keywordKeluar),
            'Laporan_Pengeluaran_' . now()->format('Ymd_His') . '.xlsx'
        );
    }
    
    public function editbahan($id_bahan, $id_supplier)
    {
        $bahan = DB::table('tb_bahanbaku')
            ->leftJoin('tb_supplier', 'tb_bahanbaku.id_supplier', '=', 'tb_supplier.id_supplier')
            ->where('tb_bahanbaku.id_bahanbaku', $id_bahan)
            ->where('tb_bahanbaku.id_supplier', $id_supplier)
            ->select('tb_bahanbaku.*', 'tb_supplier.nama_supplier')
            ->first();
            
        if (!$bahan) {
            return redirect()->route('gudang.datasupplier')->with('error', 'Bahan tidak ditemukan.');
        }

        return view('gudang.editbahan', compact('bahan'));
    }
    
    public function updatebahan(Request $request, $id_bahan, $id_supplier)
    {
        Request()->validate([
            'nama_bahan' => 'required',
            'jenis_bahan' => 'required',
            'harga_modal' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|numeric',
            'satuan' => 'required',
        ]);
        
        $data = [
            'id_bahanbaku' => $id_bahan,
            'id_supplier' => $id_supplier,
            'nama_bahan' => $request->nama_bahan,
            'jenis_bahan' => $request->jenis_bahan,
            'harga_modal' => $request->harga_modal,
            'harga_jual' => $request->harga_jual,
            'stok' => $request->stok,
            'satuan' => $request->satuan,
        ];
        
        DB::table('tb_bahanbaku')
            ->where('id_bahanbaku', $id_bahan)
            ->where('id_supplier', $id_supplier)
            ->update($data);

        return redirect()->route('gudang.datasupplier')->with('success', 'Bahan Baku berhasil diperbarui!');
    }




    }