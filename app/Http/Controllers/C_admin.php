<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\M_Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use App\Charts\BostonAdminChart;
use App\Mail\KirimNotifikasi;
use App\Mail\KirimNotifikasiBerlangganan;
use Illuminate\Support\Facades\Mail;
use App\Models\NotifikasiAdmin;
use App\Models\Transaksi;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class C_admin extends Controller
{
    public function __construct()
    {
        $this->M_Admin = new M_Admin();
        $this->Transaksi = new \App\Models\Transaksi();
    }

    public function index(Request $request, BostonAdminChart $chart)
    {
        $jumlahPendaftar = DB::table('tb_calonmitra')
                            ->where('status', 'Review Dokumen')
                            ->count();

        $jumlahFranchise = DB::table('tb_franchise')
                            ->count();

        $totalditerima = DB::table('tb_calonmitra')
                            ->where('status', 'Diterima')
                            ->count();
        
        $topFranchise = DB::table('tb_penjualan')
            ->join('tb_franchise', 'tb_penjualan.id_franchise', '=', 'tb_franchise.id_franchise')
            ->select('tb_penjualan.id_franchise', 'tb_franchise.nama_franchise', DB::raw('SUM(tb_penjualan.harga) as total_penjualan'))
            ->groupBy('tb_penjualan.id_franchise', 'tb_franchise.nama_franchise')
            ->orderByDesc('total_penjualan')
            ->first();

        $pendapatan = $totalditerima * 28000000;

        $bulanAwal = $request->input('bulan_awal');     
        $bulanAkhir = $request->input('bulan_akhir');
                        
        $chart = $chart->build($bulanAwal, $bulanAkhir);
        
        $daftarbaru = DB::table('tb_calonmitra')
            ->where('status', 'Review Dokumen')
            ->get();

        $transaksi = $this->Transaksi->allData();

        // Ambil tahun dari data penjualan (otomatis)
        $tahunList = DB::table('tb_penjualan')
            ->selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');
                        

            $bulan = $request->bulan;
            $tahun = $request->tahun;
        
            $query = DB::table('tb_detailpenjualan as dp')
                ->join('tb_penjualan as pj', 'dp.id_penjualan', '=', 'pj.id_penjualan')
                ->join('tb_franchise as f', 'pj.id_franchise', '=', 'f.id_franchise')
                ->select(
                    'pj.id_franchise',
                    'f.nama_franchise',
                    'f.alamat_usaha',
                    'dp.nama_produk',
                    DB::raw('SUM(dp.jumlah) as total_terjual')
                )
                ->groupBy('pj.id_franchise', 'f.nama_franchise', 'f.alamat_usaha', 'dp.nama_produk');
        
            // Tambahkan filter bulan dan tahun
            if ($request->bulan) {
                $query->whereMonth('pj.tanggal', $request->bulan);
            }
            if ($request->tahun) {
                $query->whereYear('pj.tanggal', $request->tahun);
            }
        
            $allData = $query->get();
        
            // Ambil produk terlaris per franchise
            $bestSellers = $allData->groupBy('id_franchise')->map(function ($group) {
                return $group->sortByDesc('total_terjual')->first();
            })->values();

        return view('admin.v_dashboard', compact(
            'daftarbaru', 'chart', 'tahunList', 'bulanAkhir', 'bulanAwal', 
            'jumlahPendaftar', 'jumlahFranchise', 'totalditerima', 
            'pendapatan', 'topFranchise', 'transaksi', 'bestSellers'
        ));
    }

    // Fungsi mencatat notifikasi
    private function tambahNotifikasi($pesan)
    {
        DB::table('notifikasi_admin')->insert([
            'pesan' => $pesan,
            'dibuat_pada' => now()
        ]);
    }

    

    public function data()
    {
        $admin = [
            'admin' => $this->M_Admin->allData()
        ];
        return view('admin.v_tabelcalon', $admin);
    }

    public function indexprofile()
    {
        $user = Session::get('user');
        if (!$user || !isset($user['id_akun'])) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        $id_akun = Session::get('user')['id_akun'];

        $mitra = [
            'mitra' => $this->M_Admin->datamitra($id_akun)
        ];

        $admin = [
            'admin' => $this->M_Admin->dataakun($id_akun)
        ];

        $foto = [
            'foto' => $this->M_Admin->datamitrafoto($id_akun)
        ];
        return view('v_profileakun', $mitra, $foto);
    }

     public function indexprofileadmin()
    {
        $id_akun = Session::get('user')['id_akun'];

        $admin = DB::table('tb_akun')->where('type_akun', 'admin')->first(); // pakai first(), bukan get()


        return view('admin.v_profileakun', compact('admin'));
    }


    public function indexfranchise()
    {
        $user = Session::get('user');
        if (!$user || !isset($user['id_akun'])) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        $id_akun = Session::get('user')['id_akun'];

        // Cek apakah user sudah punya mitra
        $id_mitra = DB::table('tb_mitra')
                        ->select('id_mitra')
                        ->where('id_akun', $id_akun)
                        ->first();

        // Jika belum daftar mitra, redirect kembali dengan notifikasi
        if (!$id_mitra) {
            return redirect()->back()->with('error', 'Silahkan daftar mitra dulu atau cek status pendaftaran dulu ya');
        }

        // Ambil data franchise berdasarkan id_mitra
        $franchise = DB::table('tb_franchise')
                        ->where('id_mitra', $id_mitra->id_mitra)
                        ->get();

        // Ambil satu data foto
        $foto = DB::table('tb_franchise')
                    ->select('lokasi_usaha')
                    ->where('id_mitra', $id_mitra->id_mitra)
                    ->get();

        // Ambil profil mitra
        $profile = DB::table('tb_mitra')
                        ->where('id_akun', $id_akun)
                        ->first();

        return view('v_franchisee', compact('franchise', 'foto', 'profile'));
    }

    public function index1(Request $request)
    {
        $search = $request->input('search');
        $dataCalon = $this->M_Admin->datacalon();
        if ($search) {
            $dataCalon = collect($dataCalon)->filter(function ($item) use ($search) {
                return stripos($item->id_calon, $search) !== false||
                        stripos($item->nama_lengkap, $search) !== false;;
            });
        }
        $admin = [
            'admin' => $dataCalon
        ];

        return view('admin.v_tabelcalon', $admin);
    }

    public function index2(Request $request)
    {
        $search = $request->input('search');
        $dataAkun = $this->M_Admin->dataakun();

        if ($search) {
            $dataAkun = collect($dataAkun)->filter(function ($item) use ($search) {
                return stripos($item->username, $search) !== false||
                        stripos($item->nama, $search) !== false;
            });
        }

        $admin = [
            'admin' => $dataAkun
        ];

        return view('admin.v_tabelakun', $admin);
    }

    public function index3(Request $request)
    {
        $search = $request->input('search');
        $dataProduk = $this->M_Admin->dataproduk();

        if ($search) {
            $dataProduk = collect($dataProduk)->filter(function ($item) use ($search) {
                return stripos($item->nama_produk, $search) !== false;
            });
        }

        $admin = [
            'admin' => $dataProduk
        ];
        return view('admin.v_tabelproduk', $admin);
    }


    public function index4(Request $request)
    {
        $search = $request->input('search');
        $dataFranchise = $this->M_Admin->datafranchisebaru();

        if ($search) {
            $dataFranchise = collect($dataFranchise)->filter(function ($item) use ($search) {
                return stripos($item->nama_franchise, $search) !== false;
            });
        }

        $admin = [
            'admin' => $dataFranchise
        ];
        return view('admin.v_tabelfranchisebaru', $admin);
    }
    
    public function indexqr()
    {
        $qrs = DB::table('tb_qr')->orderBy('created_at', 'desc')->get();
        return view('admin.dataqr', compact('qrs'));
    }


    public function tabelfranchise(Request $request)
    {
        $search = $request->input('search');
        $dataFranchise = DB::table('tb_franchise')->get();

        if ($search) {
            $dataFranchise = collect($dataFranchise)->filter(function ($item) use ($search) {
                return stripos($item->nama_franchise, $search) !== false;
            });
        }

        return view('admin.v_tabelfranchise', ['admin' => $dataFranchise]);
    }


   public function deletecalon($id_calon)
{
    $admin = $this->M_Admin->detailDatacalon($id_calon);

    // Hapus file KTP kalau ada
    if (!empty($admin->ktp)) {
        $filePath = public_path('uploads/ktp/' . $admin->ktp);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }

    // Hapus file pas photo kalau ada
    if (!empty($admin->pas_photo)) {
        $filePath = public_path('uploads/photo/' . $admin->pas_photo);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }

    // Hapus file lokasi usaha kalau ada
    if (!empty($admin->lokasi_usaha)) {
        $filePath = public_path('uploads/lokasi/' . $admin->lokasi_usaha);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }

    // Hapus data dari database
    $this->M_Admin->deleteDatacalon($id_calon);

    // Tambahkan notifikasi
    $this->tambahNotifikasi("Data calon mitra '{$admin->nama_lengkap}' telah dihapus.");

    return redirect()->route('admincalon')->with('success', 'Data berhasil dihapus');
}
    public function deleteakun($id_akun)
    {
        $this->M_Admin->deleteDataakun($id_akun);
        $this->tambahNotifikasi("Akun dengan ID '{$id_akun}' telah dihapus.");

        return redirect()->route('adminakun')->with('success', 'Akun berhasil dihapus');
    }

    public function deletefranchisebaruq($id_franchisebaru)
    {
        $this->M_Admin->deleteDatafranchisebaru($id_franchisebaru);
        $this->tambahNotifikasi("Franchise baru dengan ID '{$id_franchisebaru}' telah dihapus.");
        return redirect()->route('adminfranchisebaru')->with('success', 'Akun berhasil dihapus');
    }

    public function tambahproduk()
    {
        return view('admin.v_tambahproduk');
    }

    public function insertproduk(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:25',
            'hpp' => 'required|integer',
            'harga' => 'required|integer',
            'gambar_produk' => 'required|file|mimes:jpg,jpeg,png,pdf',
        ]);            
        
        $lastProduk = DB::table('tb_produk')
            ->select('id_produk')
            ->orderByDesc('id_produk')
            ->first();

        $idProduk = $lastProduk ? 
            'P' . str_pad((int) substr($lastProduk->id_produk, 1) + 1, 4, '0', STR_PAD_LEFT) : 
            'P0001';

        $fileproduk = $request->file('gambar_produk');
        $fileNameproduk = $request->nama_produk . '.' . $fileproduk->extension();
        $fileproduk->move(public_path('uploads/produk'), $fileNameproduk);

        $dataproduk = [
            'id_produk' => $idProduk,
            'nama_produk' => $request->nama_produk,
            'hpp' => $request->hpp,
            'harga' => $request->harga,
            'gambar_produk' => $fileNameproduk,
        ];

        try {
            DB::table('tb_produk')->insert($dataproduk);
            $this->tambahNotifikasi("Produk '{$request->nama_produk}' berhasil ditambahkan.");
            Log::info('Data produk berhasil disimpan.', $dataproduk);
            return redirect('/admin/tabelproduk')->with('success', 'Tambah Produk Berhasil!.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan produk: ' . $e->getMessage());
            return redirect('/admin/produk/add')->with('error', 'Terjadi kesalahan tambah produk. Silakan coba lagi.');
        }
    }

    public function editfranchise($id_franchise)
    {
        $franchise = $this->M_Admin->detailDatafranchise($id_franchise);

        if (!$franchise) {
            return redirect()->route('adminfranchise')->with('error', 'Franchise tidak ditemukan.');
        }

        return view('admin.v_editfranchise', compact('franchise'));
    }

    public function updatefranchise($id_franchise)
    {
    try {
        Request()->validate([
            'id_franchise' => 'required',
            'nama_franchise' => 'required',
            'provinsi_usaha' => 'required',
            'kota_usaha' => 'required',
            'kelurahan_usaha' => 'required',
            'kecamatan_usaha' => 'required',
            'alamat_usaha' => 'required',
            'kode_pos' => 'required',
            'titik_koordinat' => 'required',
            'lokasi_usaha' => 'file|mimes:jpg,jpeg,png,pdf',
        ]);

        $data = [
            'id_franchise' => Request()->id_franchise,
            'nama_franchise' => Request()->nama_franchise,
            'provinsi_usaha' => Request()->provinsi_usaha,
            'kota_usaha' => Request()->kota_usaha,
            'kelurahan_usaha' => Request()->kelurahan_usaha,
            'kecamatan_usaha' => Request()->kecamatan_usaha,
            'alamat_usaha' => Request()->alamat_usaha,
            'kode_pos' => Request()->kode_pos,
            'titik_koordinat' => Request()->titik_koordinat,
        ];

        if (Request()->file('lokasi_usaha')) {
            $filelokasi = Request()->file('lokasi_usaha');
            $fileNamelokasi = Request()->id_franchise . '.' . $filelokasi->extension();
            $filelokasi->move(public_path('uploads/lokasi'), $fileNamelokasi);
            $data['lokasi_usaha'] = $fileNamelokasi;
        }

        $this->M_Admin->editDatafranchise($id_franchise, $data);
        $this->tambahNotifikasi("Franchise '{$data['nama_franchise']}' berhasil diperbarui.");
        
        return redirect()->route('adminfranchise')->with('pesan', 'Data berhasil diperbarui!');
    } catch (\Exception $e) {
        // Log error ke laravel.log
        Log::error("Gagal update franchise {$id_franchise}: " . $e->getMessage(), [
            'franchise_id' => $id_franchise,
            'user' => session('user'),
            'trace' => $e->getTraceAsString(),
        ]);

        return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui franchise. Coba lagi nanti.');
    }
    }

    public function editproduk($id_produk)
    {
        $produk = $this->M_Admin->detailDataproduk($id_produk);

        if (!$produk) {
            return redirect()->route('adminproduk')->with('error', 'Produk tidak ditemukan.');
        }

        return view('admin.v_editproduk', compact('produk'));
    }


    public function updateproduk($id_produk)
    {
        Request()->validate([
            'id_produk' => 'required',
            'nama_produk' => 'required|min:5|max:20',
            'hpp' => 'required|integer',
            'harga' => 'required|integer',
            'gambar_produk' => 'file|mimes:jpg,jpeg,png,pdf',
        ]);
    
        $data = [
            'id_produk' => Request()->id_produk,
            'nama_produk' => Request()->nama_produk,
            'hpp' => Request()->hpp,
            'harga' => Request()->harga,
        ];

        if (Request()->file('gambar_produk')) {
            $fileproduk = Request()->file('gambar_produk');
            $fileNameproduk = Request()->nama_produk . '.' . $fileproduk->extension();
            $fileproduk->move(public_path('uploads/produk'), $fileNameproduk);
            $data['gambar_produk'] = $fileNameproduk;
        }

        $this->M_Admin->editDataproduk($id_produk, $data);
        $this->tambahNotifikasi("Produk '{$data['nama_produk']}' berhasil diperbarui.");
        return redirect()->route('adminproduk')->with('pesan', 'Data berhasil diperbarui!');
    }

    public function deletefranchise($id_franchise)
{
    // Hapus semua kasir yang terkait dengan franchise ini
    DB::table('tb_kasir')->where('id_franchise', $id_franchise)->delete();

    // Ambil data franchise untuk notifikasi
    $franchise = $this->M_Admin->detailDatafranchise($id_franchise);
    if ($franchise) {
        $this->tambahNotifikasi("Franchise '{$franchise->nama_franchise}' berhasil dihapus.");
    }

    // Hapus franchise
    $this->M_Admin->deleteDatafranchise($id_franchise);

    return redirect()->route('adminfranchise')->with('success', 'Franchise dan kasir terkait berhasil dihapus');
}


    public function deleteproduk($id_produk)
    {
        $produk = $this->M_Admin->detailDataproduk($id_produk);
        if ($produk) {
            $this->tambahNotifikasi("Produk '{$produk->nama_produk}' berhasil dihapus.");
        }
        $this->M_Admin->deleteDataproduk($id_produk);
        return redirect()->route('adminproduk')->with('success', 'Produk Berhasil Dihapus');
    }

    public function deletefranchisebaru($id_franchisebaru)
    {
        $this->M_Admin->deleteDatafranchisebaru($id_franchisebaru);
        $this->tambahNotifikasi("Franchise baru dengan ID '{$id_franchisebaru}' telah dihapus.");
        return redirect()->route('adminfranchisebaru')->with('success', 'Franchise Baru Berhasil Dihapus');
    }

    // Update status untuk franchise baru
    public function updateStatus(Request $request, $id_franchisebaru)
    {
        $request->validate([
            'status' => 'required|in:Review Dokumen,Survey Lokasi,Pembayaran,Pembuatan Booth,Diterima,Aktif,Ditolak'
        ]);
        
        try {
            DB::table('tb_franchisebaru')
                ->where('id_franchisebaru', $id_franchisebaru)
                ->update(['status' => $request->status]);

            $nama_lengkap = DB::table('tb_franchisebaru')
                                ->leftJoin('tb_mitra', 'tb_franchisebaru.id_mitra', '=', 'tb_mitra.id_mitra')
                                ->where('id_franchisebaru', $id_franchisebaru)
                                ->select('tb_mitra.nama_lengkap')
                                ->first();
                    
            $email = DB::table('tb_franchisebaru')
                        ->leftJoin('tb_mitra', 'tb_franchisebaru.id_mitra', '=', 'tb_mitra.id_mitra')
                        ->leftJoin('tb_akun', 'tb_mitra.id_akun', '=', 'tb_akun.id_akun')
                        ->where('id_franchisebaru', $id_franchisebaru)
                        ->select('tb_akun.email')
                        ->first();
                                

            // Hanya lakukan proses ini jika status Diterima
            if ($request->status == 'Diterima') {
                $ubah = $this->M_Admin->datafranchisepindah($id_franchisebaru);
                $id_akun = Session::get('user')['id_akun'];

                // Generate ID franchise
                $lastFranchise = DB::table('tb_franchise')
                    ->select('id_franchise')
                    ->orderByDesc('id_franchise')
                    ->first();
    
                $idFranchise = $lastFranchise ? 
                    'F' . str_pad((int) substr($lastFranchise->id_franchise, 1) + 1, 4, '0', STR_PAD_LEFT) : 
                    'F0001';

                // Masukkan ke tb_franchise
                DB::table('tb_franchise')->insert([
                    'id_franchise' => $idFranchise,
                    'id_mitra' => $ubah->id_mitra,
                    'nama_franchise' => $ubah->nama_franchise,
                    'provinsi_usaha' => $ubah->provinsi_usaha,
                    'kota_usaha' => $ubah->kota_usaha,
                    'kelurahan_usaha' => $ubah->kelurahan_usaha,
                    'kecamatan_usaha' => $ubah->kecamatan_usaha,
                    'alamat_usaha' => $ubah->alamat_usaha,
                    'kode_pos' => $ubah->kode_pos,
                    'titik_koordinat' => $ubah->titik_koordinat,
                    'lokasi_usaha' => $ubah->lokasi_usaha,
                ]);

                // Generate ID kasir
                $lastKasir = DB::table('tb_kasir')
                    ->select('id_kasir')
                    ->orderByDesc('id_kasir')
                    ->first();

                $idKasir = $lastKasir ? 
                    'K' . str_pad((int) substr($lastKasir->id_kasir, 1) + 1, 4, '0', STR_PAD_LEFT) : 
                    'K0001';

                // Generate ID akun
                $lastAkun = DB::table('tb_akun')
                    ->select('id_akun')
                    ->orderByDesc('id_akun')
                    ->first();

                $idAkun = $lastAkun ? 
                    'A' . str_pad((int) substr($lastAkun->id_akun, 1) + 1, 4, '0', STR_PAD_LEFT) : 
                    'A0001';

                // Simpan ke tb_akun
                DB::table('tb_akun')->insert([
                    'id_akun' => $idAkun,
                    'username' => $idKasir,
                    'password' => bcrypt($idKasir),
                    'type_akun' => 'kasir',
                ]);

                // Simpan ke tb_kasir
                DB::table('tb_kasir')->insert([
                    'id_kasir' => $idKasir,
                    'id_akun' => $idAkun,
                    'id_franchise' => $idFranchise
                ]);
            }

            $this->tambahNotifikasi("Status franchise ID '{$id_franchisebaru}' diperbarui menjadi '{$request->status}'.");
            return back()->with('success', 'Status berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update status: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui status.');
        }
    }

    // Update status untuk calon mitra
    public function updateStatusCalonMitra(Request $request, $id_calon)
    {
        $request->validate([
            'status' => 'required|in:Review Dokumen,Survey Lokasi,Pembayaran,Pembuatan Booth,Diterima,Aktif, Ditolak'
        ]);
        
        try {
            DB::table('tb_calonmitra')
                ->where('id_calon', $id_calon)
                ->update(['status' => $request->status]);
        
                $nama_lengkap = DB::table('tb_calonmitra')
                                    ->where('id_calon', $id_calon)
                                    ->select('tb_calonmitra.nama_lengkap')
                                    ->first();
                        
                $email = DB::table('tb_calonmitra')
                            ->leftJoin('tb_akun', 'tb_calonmitra.id_akun', '=', 'tb_akun.id_akun')
                            ->where('id_calonmitra', $id_calon)
                            ->select('tb_akun.email')
                            ->first();
        
            // Jika status Diterima, buat data mitra
            if ($request->status == 'Diterima') {
                $calon = DB::table('tb_calonmitra')
                    ->where('id_calon', $id_calon)
                    ->first();

                // Cek apakah sudah ada di tb_mitra
                $mitra = DB::table('tb_mitra')
                    ->where('id_akun', $calon->id_akun)
                    ->first();

                if (!$mitra) {
                    // Generate ID mitra
                    $lastMitra = DB::table('tb_mitra')
                        ->select('id_mitra')
                        ->orderByDesc('id_mitra')
                        ->first();

                    $idMitra = $lastMitra ? 
                        'M' . str_pad((int) substr($lastMitra->id_mitra, 1) + 1, 4, '0', STR_PAD_LEFT) : 
                        'M0001';

                    // Insert ke tb_mitra
                    DB::table('tb_mitra')->insert([
                        'id_mitra' => $idMitra,
                        'id_akun' => $calon->id_akun,
                        'nama_mitra' => $calon->nama_lengkap,
                        'alamat_mitra' => $calon->alamat,
                        'no_telepon' => $calon->no_telepon,
                        'email' => $calon->email,
                        'status' => 'Aktif',
                    ]);
                }
            }

            $this->tambahNotifikasi("Status calon mitra ID '{$id_calon}' diperbarui menjadi '{$request->status}'.");

            return back()->with('success', 'Status berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update status calon mitra: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui status calon mitra.');
        }
    }

    public function getNotifikasi()
    {
        $notifikasi = NotifikasiAdmin::orderBy('dibuat_pada', 'desc')->take(10)->get();
        return response()->json($notifikasi);
    }
    
        public function updateAkses(Request $request, $id_franchise)
    {
        $request->validate([
            'akses' => 'required'
        ]);
        
        try {
            DB::table('tb_franchise')
                ->where('id_franchise', $id_franchise)
                ->update([
                    'langganan' => $request->akses
                ]);

                $nama_franchise = DB::table('tb_franchise')
                ->where('id_franchise', $id_franchise)
                ->select('tb_franchise.nama_franchise')
                ->first();
    
                $email = DB::table('tb_franchise')
                            ->leftJoin('tb_mitra', 'tb_franchise.id_mitra', '=', 'tb_mitra.id_mitra')
                            ->leftJoin('tb_akun', 'tb_mitra.id_akun', '=', 'tb_akun.id_akun')
                            ->where('tb_franchise.id_franchise', $id_franchise)
                            ->select('tb_akun.email')
                            ->first();
    
            return back()->with('success', 'Status berlangganan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update status berlangganan: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui status berlangganan.');
        }
    }
    
        public function qrcode(Request $request)
    {
        $request->validate([
            'id_qr' => 'required|unique:tb_qr,id_qr',
            'url'   => 'required|url',
        ]);

        // Secret key rahasia (jangan ditaruh di code, taruh di .env)
        $secret = env('QR_SECRET_KEY', 'mySecretKey123');

        // Buat hash sederhana (misalnya sha256)
        $hash = hash_hmac('sha256', $request->id_qr, $secret);

        // Data QR berisi id + hash
        $data = $request->id_qr . '|' . $hash;

        // Nama file QR disimpan sesuai ID
        $fileName = $request->id_qr . '.png';
        $filePath = public_path('uploads/qrcode/' . $fileName);

        // generate dan simpan sebagai PNG binary
        QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->color(0, 0, 0)               // Warna QR hitam
            ->backgroundColor(255, 255, 255, 0)
            ->generate($data, $filePath);

        // Simpan ke database
        DB::table('tb_qr')->insert([
            'id_qr'     => $request->id_qr,
            'url'       => $request->url,
            'qr_img'    => $fileName,
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);

        return redirect()->back()->with('success', 'QR Code berhasil dibuat dan disimpan.');
    }

    public function redirectToUrl($data)
    {
        $secret = env('QR_SECRET_KEY', 'mySecretKey123');
    
        // Pisahkan data "id_qr|hash"
        [$id_qr, $hash] = explode('|', $data);
    
        // Hitung ulang hash
        $validHash = hash_hmac('sha256', $id_qr, $secret);
    
        // Validasi hash
        if (!hash_equals($validHash, $hash)) {
            return abort(403, 'QR Code tidak valid atau dipalsukan.');
        }
    
        // Cari data QR di DB
        $qr = DB::table('tb_qr')->where('id_qr', $id_qr)->first();
        if (!$qr) {
            return abort(404, 'QR Code tidak ditemukan.');
        }
    
        // Kalau valid, redirect ke URL
        return redirect()->away($qr->url);
    }

    public function updateqr(Request $request, $id_qr)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        DB::table('tb_qr')->where('id_qr', $id_qr)->update([
            'url' => $request->url,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'URL QR Code berhasil diupdate!');
    }

    public function deleteqr($id_qr)
    {
        try {
            // Ambil data qr untuk cek nama file
            $qr = DB::table('tb_qr')->where('id_qr', $id_qr)->first();

            if ($qr) {
                // Hapus file gambar jika ada
                $filePath = public_path('uploads/qrcode/' . $qr->qr_img);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }

                // Hapus data dari database
                DB::table('tb_qr')->where('id_qr', $id_qr)->delete();

                return redirect()->back()->with('success', 'Data QR berhasil dihapus beserta gambarnya');
            }

            return redirect()->back()->with('error', 'QR tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus QR: ' . $e->getMessage());
        }
    }
}