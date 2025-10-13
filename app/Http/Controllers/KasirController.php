<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\BostonKasirChart;
use App\Models\M_Kasir;
use App\Models\penjualan; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class KasirController extends Controller
{
    protected $kasir;

    public function __construct()
    {
        $this->kasir = new M_Kasir(); // Inisialisasi model kasir
    }

    public function index(Request $request, BostonKasirChart $chart)
    {
        $bulanAwal = $request->input('bulan_awal'); // format: 2025-04
        $bulanAkhir = $request->input('bulan_akhir');
        $user = Session::get('user');
        if (!$user || !isset($user['id_akun'])) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        $id_akun = $user['id_akun'];
        $type_akun = Session::get('user')['type_akun'];
        if ($type_akun == 'kasir')
        {
        // Get id_franchise for the logged-in kasir
        $idFranchise = DB::table('tb_kasir')
            ->join('tb_franchise', 'tb_kasir.id_franchise', '=', 'tb_franchise.id_franchise')
            ->where('tb_kasir.id_akun', $id_akun)
            ->select('tb_franchise.id_franchise')
            ->first();

        // Check if idFranchise exists before proceeding
        if (!$idFranchise) {
            Log::warning("Kasir with id_akun: {$id_akun} not linked to any franchise.");
            return redirect('/')->with('error', 'Akun kasir tidak terhubung dengan franchise.');
        }

        $currentFranchiseId = $idFranchise->id_franchise;
        } elseif ($type_akun == 'user')
        {
            $currentFranchiseId = Session::get('user')['id_franchise'];
        }
        $pendapatanperhari = DB::table('tb_penjualan')
            ->select('id_franchise', DB::raw('SUM(harga) as total_pendapatan'))
            ->whereDate('tanggal', Carbon::today())
            ->where('id_franchise', $currentFranchiseId)
            ->groupBy('id_franchise')
            ->orderBy('id_franchise', 'asc')
            ->first();

        $pendapatanbulanini = DB::table('tb_penjualan')
            ->select('id_franchise', DB::raw('SUM(harga) as total_pendapatan'))
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->where('id_franchise', $currentFranchiseId)
            ->groupBy('id_franchise')
            ->orderBy('id_franchise', 'asc')
            ->first();

        $jumlahpelangganperhari = DB::table('tb_penjualan')
            ->select('id_franchise', DB::raw('COUNT(*) as total_transaksi'))
            ->whereDate('tanggal', Carbon::today())
            ->where('id_franchise', $currentFranchiseId)
            ->groupBy('id_franchise')
            ->orderBy('id_franchise', 'asc')
            ->first();

        $chart = $chart->build($bulanAwal, $bulanAkhir);

        $tahunList = DB::table('tb_penjualan')
            ->selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // --- START: MODIFIED CODE TO INCLUDE PELANGGAN ---
        $penjualan = DB::table('tb_detailpenjualan as dp')
            ->join('tb_penjualan as p', 'dp.id_penjualan', '=', 'p.id_penjualan')
            ->select(
                'p.id_penjualan',
                'p.pelanggan',     // <-- Added this line to select the customer name
                'dp.nama_produk',
                'dp.jumlah',
                'dp.harga',
                'p.tanggal'
            )
            ->where('p.id_franchise', $currentFranchiseId)
            ->orderBy('p.tanggal', 'desc')
            ->limit(5)
            ->get();
        
            // Produk terjual terbanyak
            $produkTerjual = DB::table('tb_detailpenjualan as dp')
            ->join('tb_penjualan as p', 'dp.id_penjualan', '=', 'p.id_penjualan')
            ->select('dp.nama_produk', DB::raw('SUM(dp.jumlah) as total_terjual'))
            ->where('p.id_franchise', $currentFranchiseId)
            ->groupBy('dp.nama_produk')
            ->orderByDesc('total_terjual')
            ->limit(8) // tampilkan 8 produk terbanyak
            ->get();

        



        return view('kasir.v_dashkasir', compact('chart', 'tahunList', 'bulanAkhir', 'bulanAwal', 'pendapatanperhari', 'pendapatanbulanini', 'jumlahpelangganperhari', 'penjualan','produkTerjual'));
    }

    public function kasir()
    {
        $model = new M_Kasir();
        $kasir = $model->allData();
        $user = Session::get('user');
        if (!$user || !isset($user['id_akun'])) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        $id_akun = Session::get('user')['id_akun'];
        $type_akun = Session::get('user')['type_akun'];
        
        if ($type_akun == 'kasir')
        {
        $idFranchise = DB::table('tb_kasir')
            ->join('tb_franchise', 'tb_kasir.id_franchise', '=', 'tb_franchise.id_franchise')
            ->where('tb_kasir.id_akun', $id_akun)
            ->select('tb_franchise.id_franchise')
            ->first();
        $id_franchise = $idFranchise->id_franchise;
        $riwayat = $model->DataHarian($id_franchise);

        $bestSellers = DB::table('tb_detailpenjualan')
            ->select('nama_produk', DB::raw('SUM(jumlah) as total_terjual'))
            ->groupBy('nama_produk')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->pluck('nama_produk')
            ->first();
        } elseif ($type_akun == 'user')
        {
            $id_franchise = Session::get('user')['id_franchise'];
            $riwayat = $model->DataHarian($id_franchise);

            $bestSellers = DB::table('tb_detailpenjualan')
                ->select('nama_produk', DB::raw('SUM(jumlah) as total_terjual'))
                ->groupBy('nama_produk')
                ->orderByDesc('total_terjual')
                ->limit(5)
                ->pluck('nama_produk')
                ->first();
        }


        return view('kasir.v_kasir', compact('kasir', 'riwayat', 'bestSellers'));
       
    }
public function laporan(Request $request)
{
    $bulan = $request->input('bulan');
    $tahun = $request->input('tahun');

    // default bulan & tahun realtime (saat ini) kalau kosong
    if (!$bulan) {
        $bulan = date('m');
    }
    if (!$tahun) {
        $tahun = date('Y');
    }

    $model = new M_Kasir();
    $user = Session::get('user');
    if (!$user || !isset($user['id_akun'])) {
        return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
    }
    $id_akun = Session::get('user')['id_akun'];
    $type_akun = Session::get('user')['type_akun'];

    if ($type_akun == 'kasir')
    {
    $idFranchise = DB::table('tb_kasir')
        ->join('tb_franchise', 'tb_kasir.id_franchise', '=', 'tb_franchise.id_franchise')
        ->where('tb_kasir.id_akun', $id_akun)
        ->select('tb_franchise.id_franchise')
        ->first();

    $id_franchise = $idFranchise->id_franchise;
    } elseif ($type_akun == 'user')
    {
        $id_franchise = Session::get('user')['id_franchise'];
    }

    // pakai filter bulan & tahun
    $penjualan = $model->DataLaporanFilterBulanTahun($id_franchise, $bulan, $tahun);

    return view('kasir.v_pelaporan', compact('penjualan', 'bulan', 'tahun', 'type_akun'));
}

    public function checkout(Request $request)
    {
        try {
            DB::beginTransaction();
            $type_akun = Session::get('user')['type_akun'];

            if ($type_akun == 'kasir')
            {
                $lastPenjualan = DB::table('tb_penjualan')
                    ->select('id_penjualan')
                    ->orderByDesc('id_penjualan')
                    ->first();

                $idPenjualan = $lastPenjualan
                    ? 'T' . str_pad((int) substr($lastPenjualan->id_penjualan, 1) + 1, 4, '0', STR_PAD_LEFT)
                    : 'T0001';

                $id_akun = Session::get('user')['id_akun'];
                $idFranchise = DB::table('tb_kasir')
                    ->join('tb_franchise', 'tb_kasir.id_franchise', '=', 'tb_franchise.id_franchise')
                    ->where('tb_kasir.id_akun', $id_akun)
                    ->select('tb_franchise.id_franchise')
                    ->first();

                DB::table('tb_penjualan')->insert([
                    'id_penjualan' => $idPenjualan,
                    'id_franchise' => $idFranchise->id_franchise,
                    'pelanggan' => $request->kode, // This value is inserted here
                    'harga' => $request->total,
                    'tanggal' => Carbon::now(),
                ]);

                $lastDetail = DB::table('tb_detailpenjualan')
                    ->select('id_detailpenjualan')
                    ->orderByDesc('id_detailpenjualan')
                    ->first();

                $lastNumber = $lastDetail ? (int) substr($lastDetail->id_detailpenjualan, -4) : 0;

                foreach ($request->pesanan as $index => $item) {
                    $newNumber = $lastNumber + $index + 1;
                    $idDetail = 'DT' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

                    DB::table('tb_detailpenjualan')->insert([
                        'id_detailpenjualan' => $idDetail,
                        'id_penjualan' => $idPenjualan,
                        'nama_produk' => $item['nama'],
                        'harga' => $item['harga'] * $item['jumlah'],
                        'jumlah' => $item['jumlah'],
                    ]);
                }
            } elseif ($type_akun == 'user')
            {
                $lastPenjualan = DB::table('tb_penjualan')
                    ->select('id_penjualan')
                    ->orderByDesc('id_penjualan')
                    ->first();

                $idPenjualan = $lastPenjualan
                    ? 'T' . str_pad((int) substr($lastPenjualan->id_penjualan, 1) + 1, 4, '0', STR_PAD_LEFT)
                    : 'T0001';

                $id_akun = Session::get('user')['id_akun'];
                $idFranchise = Session::get('user')['id_franchise'];

                DB::table('tb_penjualan')->insert([
                    'id_penjualan' => $idPenjualan,
                    'id_franchise' => $idFranchise,
                    'pelanggan' => $request->kode, // This value is inserted here
                    'harga' => $request->total,
                    'tanggal' => Carbon::now(),
                ]);

                $lastDetail = DB::table('tb_detailpenjualan')
                    ->select('id_detailpenjualan')
                    ->orderByDesc('id_detailpenjualan')
                    ->first();

                $lastNumber = $lastDetail ? (int) substr($lastDetail->id_detailpenjualan, -4) : 0;

                foreach ($request->pesanan as $index => $item) {
                    $newNumber = $lastNumber + $index + 1;
                    $idDetail = 'DT' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

                    DB::table('tb_detailpenjualan')->insert([
                        'id_detailpenjualan' => $idDetail,
                        'id_penjualan' => $idPenjualan,
                        'nama_produk' => $item['nama'],
                        'harga' => $item['harga'] * $item['jumlah'],
                        'jumlah' => $item['jumlah'],
                    ]);
                }
            }

            DB::commit();
            Log::info('kasir Baru disimpan', ['id_penjualan' => $idPenjualan]);
            return response()->json([
                'success' => true,
                'redirect' => route('printkasir', ['id_penjualan' => $idPenjualan])
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Checkout failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Checkout gagal: ' . $e->getMessage() // tampilkan error
            ]);
        }
    }

    public function destroy($id)
    {
        DB::table('tb_detailpenjualan')->where('id_penjualan', $id)->delete();
        DB::table('tb_penjualan')->where('id_penjualan', $id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    public function editakun($id_akun)
    {
        $user = Session::get('user');
        if (!$user || !isset($user['id_akun'])) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        $id_akun = $this->kasir->getAkunById($id_akun);
        
        $akun = DB::table('tb_kasir')
            ->leftJoin('tb_akun', 'tb_kasir.id_akun', '=', 'tb_akun.id_akun')
            ->where('tb_akun.id_akun', $id_akun->id_akun)
            ->select('tb_akun.*')
            ->first();
        
        if (!$akun) {
            abort(404);
        }
        
        return view('kasir.v_editakun', compact('akun'));
    }

    public function updateakunkasir($id_akun)
    {
        Request()->validate([
            'username' => 'required|min:5|max:20',
            'password' => 'nullable|min:6|max:100',
        ]);

        $data = [
            'username' => Request()->username,
        ];

        if (Request()->filled('password')) {
            $data['password'] = bcrypt(Request()->password);
        }

        $this->kasir->updateAkun($id_akun, $data);
        $idakun = Session::get('user')['id_akun'];

        return redirect()->route('editakun', ['id_akun' => $idakun])->with('pesan', 'Akun kasir berhasil diperbarui!');
    }

    public function print($id_penjualan)
    {
        $datadetail = DB::table('tb_penjualan')
                    ->leftJoin('tb_detailpenjualan', 'tb_penjualan.id_penjualan', '=', 'tb_detailpenjualan.id_penjualan')
                    ->where('tb_penjualan.id_penjualan', $id_penjualan)
                    ->select('tb_detailpenjualan.*')
                    ->get();
        
        $data = DB::table('tb_penjualan')
                ->where('tb_penjualan.id_penjualan', $id_penjualan)
                ->first();
        return view('kasir.v_print', compact('data', 'datadetail'));
    }

    public function stokFranchise()
    {
        // 1. Ambil data user dari session manual (bukan dari auth())
        $user = Session::get('user');

        if (!$user || !isset($user['id_akun'])) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        $type_akun = Session::get('user')['type_akun'];

        if ($type_akun == 'kasir')
        {
            // 2. Ambil data kasir dari tb_kasir berdasarkan id_akun
            $kasir = DB::table('tb_kasir')->where('id_akun', $user['id_akun'])->first();
            if (!$kasir) {
                return abort(403, 'Data kasir tidak ditemukan.');
            }
        

            // 3. Ambil franchise dari id_franchise kasir
            $franchise = DB::table('tb_franchise')->where('id_franchise', $kasir->id_franchise)->first();
            if (!$franchise) {
                return abort(403, 'Franchise tidak ditemukan.');
            }
            
            // 4. Ambil stok bahan baku dari franchise
            $stok = DB::table('tb_stokfranchise')
            ->join('tb_bahanbaku', 'tb_stokfranchise.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
            ->where('tb_stokfranchise.id_franchise', $kasir->id_franchise)
            ->select(
                'tb_stokfranchise.*',
                'tb_bahanbaku.nama_bahan',
                'tb_bahanbaku.jenis_bahan',
                'tb_bahanbaku.satuan'
                )
                ->get();
            $bahanbaku = DB::table('tb_bahanbaku')->get();
            return view('kasir.stokbahan', compact('stok', 'franchise', 'kasir', 'bahanbaku', 'type_akun'));
        } elseif ($type_akun == 'user')
        {
            $id_franchise = Session::get('user')['id_franchise'];      

            // 3. Ambil franchise dari id_franchise kasir
            $franchise = DB::table('tb_franchise')->where('id_franchise', $id_franchise)->first();
            if (!$franchise) {
                return abort(403, 'Franchise tidak ditemukan.');
            }
            
            // 4. Ambil stok bahan baku dari franchise
            $stok = DB::table('tb_stokfranchise')
            ->join('tb_bahanbaku', 'tb_stokfranchise.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
            ->where('tb_stokfranchise.id_franchise', $id_franchise)
            ->select(
                'tb_stokfranchise.*',
                'tb_bahanbaku.nama_bahan',
                'tb_bahanbaku.jenis_bahan',
                'tb_bahanbaku.satuan'
                )
                ->get();
            $bahanbaku = DB::table('tb_bahanbaku')->get();
            return view('kasir.stokbahan', compact('stok', 'franchise', 'bahanbaku', 'type_akun'));
        }

    }

    public function tambahstok(Request $request)
    {
        $request->validate([
            'id_bahanbaku' => 'required',
            'stok' => 'required|numeric',
        ]);
    
        try {
            $id_akun = Session::get('user')['id_akun'];
            $id_franchise = Session::get('user')['id_franchise'];
    
            $data = DB::table('tb_stokfranchise')
                ->where('id_franchise', $id_franchise)
                ->where('id_bahanbaku', $request->id_bahanbaku)
                ->exists(); // lebih efisien
    
            if (!$data) {
                $lastCalon = DB::table('tb_stokfranchise')
                    ->select('id_stokfranchise')
                    ->orderByDesc('id_stokfranchise')
                    ->first();
    
                if ($lastCalon) {
                    $lastNumcalon = (int) substr($lastCalon->id_stokfranchise, 2);
                    $idStokfranchise = 'SF' . str_pad($lastNumcalon + 1, 4, '0', STR_PAD_LEFT);
                } else {
                    $idStokfranchise = 'SF0001';
                }
    
                DB::table('tb_stokfranchise')->insert([
                    'id_stokfranchise' => $idStokfranchise,
                    'id_franchise' => $id_franchise,
                    'id_bahanbaku' => $request->id_bahanbaku,
                    'stok' => $request->stok,
                ]);
    
                Log::info('Stok baru berhasil ditambahkan', [
                    'id_stokfranchise' => $idStokfranchise,
                    'id_franchise' => $id_franchise,
                    'id_bahanbaku' => $request->id_bahanbaku,
                    'stok' => $request->stok,
                ]);
            } else {
                DB::table('tb_stokfranchise')
                    ->where('id_franchise', $id_franchise)
                    ->where('id_bahanbaku', $request->id_bahanbaku)
                    ->update([
                        'stok' => DB::raw("stok + {$request->stok}")
                    ]);
    
                Log::info('Stok berhasil diupdate', [
                    'id_franchise' => $id_franchise,
                    'id_bahanbaku' => $request->id_bahanbaku,
                    'stok_ditambah' => $request->stok,
                ]);
            }
            return back()->with('success', 'Stok berhasil diproses!');
        } catch (\Exception $e) {
            Log::error('Gagal memproses stok', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Terjadi kesalahan saat memproses stok!');
        }
    }

    public function editstok($id_stokfranchise)
    {
        $stokfranchise = DB::table('tb_stokfranchise')
            ->leftJoin('tb_bahanbaku', 'tb_stokfranchise.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
            ->where('id_stokfranchise', $id_stokfranchise)
            ->select('tb_stokfranchise.stok', 'tb_stokfranchise.id_stokfranchise', 'tb_bahanbaku.nama_bahan', 'tb_bahanbaku.satuan')
            ->first();

        if (!$stokfranchise) {
            return redirect()->route('stok.franchise')->with('error', 'Bahan tidak ditemukan.');
        }

        return view('kasir.edit', compact('stokfranchise'));
    }

    public function updateStok($id_stokfranchise)
    {
        Request()->validate([
            'stok' => 'required|numeric',
        ]);
        DB::table('tb_stokfranchise')
            ->where('id_stokfranchise', $id_stokfranchise)
            ->update([
                'stok' => Request()->stok
            ]);

        return redirect()->route('stok.franchise')->with('success', 'Stok berhasil diperbarui!');
    }

    public function destroyStok($id_stokfranchise)
    {
        DB::table('tb_stokfranchise')->where('id_stokfranchise', $id_stokfranchise)->delete();
        return redirect()->back()->with('success', 'Stok berhasil dihapus.');
    }

}