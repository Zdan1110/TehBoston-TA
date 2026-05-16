<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalonMitra;
use App\Models\M_Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Mail\KirimNotifikasiCalon;
use Illuminate\Support\Facades\Mail;


class CalonMitraController extends Controller
{

    public function __construct()
    {
        $this->M_Admin = new M_Admin();
    }

    public function indexdaftar()
    {
        $user = Session::get('user');
        if (!$user || !isset($user['id_akun'])) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        return view('daftarmitra');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'   => 'required|string',
            'no_ktp'         => 'required|string|unique:tb_calonmitra,no_ktp',
            'provinsi'       => 'required|string',
            'kota'           => 'required|string',
            'kelurahan'      => 'required|string',
            'ktp'            => 'required|file|mimes:jpg,jpeg,png,pdf',
            'no_hp'          => 'required|string',
            'alamat_lengkap' => 'required|string',
            'provinsi_usaha' => 'required|string',
            'kota_usaha'     => 'required|string',
            'kelurahan_usaha'=> 'required|string',
            'kecamatan_usaha'=> 'required|string',
            'alamat_usaha'   => 'required|string',
            'kode_pos'       => 'required|string',
            'titik_koordinat'=> 'required|string',
            'lokasi_usaha'   => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ]);

            if (Auth::check()) {
                $id_akun = Auth::user()->id_akun;
            }
        
            
            $lastCalon = DB::table('tb_calonmitra')
            ->select('id_calon')
            ->orderByDesc('id_calon')
            ->first();

            if ($lastCalon) {
            $lastNumcalon = (int) substr($lastCalon->id_calon, 1);
            $idCalon = 'C' . str_pad($lastNumcalon + 1, 4, '0', STR_PAD_LEFT);
            } else {
            $idCalon = 'C0001';
            }

            $filektp = Request()->file('ktp');
            $fileNamektp = Request()->no_ktp . '.' . $filektp->extension();
            $filektp->move(public_path('uploads/ktp'), $fileNamektp);

            $filelokasi = Request()->file('lokasi_usaha');
            $fileNamelokasi = $idCalon . '.' . $filelokasi->extension();
            $filelokasi->move(public_path('uploads/lokasi'), $fileNamelokasi);

            // Simpan Data ke Database
            $datacalon = [
                'id_calon' => $idCalon,
                'id_akun' => $id_akun,
                'nama_lengkap' => $request->nama_lengkap,
                'no_ktp' => $request->no_ktp,
                'provinsi' => $request->provinsi,
                'kota' => $request->kota,
                'kelurahan' => $request->kelurahan,
                'ktp' => $fileNamektp,
                'no_hp' => $request->no_hp,
                'alamat_lengkap' => $request->alamat_lengkap,
                'provinsi_usaha' => $request->provinsi_usaha,
                'kota_usaha' => $request->kota_usaha,
                'kelurahan_usaha' => $request->kelurahan_usaha,
                'kecamatan_usaha' => $request->kecamatan_usaha,
                'alamat_usaha' => $request->alamat_usaha,
                'kode_pos' => $request->kode_pos,
                'titik_koordinat' => $request->titik_koordinat,
                'lokasi_usaha' => $fileNamelokasi,
                'status' => 'Review Dokumen',
            ];

            try {
                DB::table('tb_calonmitra')->insert($datacalon);
                Log::info('Data calon mitra berhasil disimpan.', $datacalon);
                return redirect('/qrcode')->with('success', 'Pendaftaran berhasil!.');
            } catch (\Exception $e) {
                Log::error('Gagal menyimpan data calon mitra: ' . $e->getMessage());
                return redirect('/home')->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
            }
            
        }

        
    
        public function index() {
            $admin = CalonMitra::all(); // ambil semua data
            return view('admin.v_tabelcalon', compact('admin'));
        }
        
        public function updateStatus(Request $request, $id_calonmitra)
{
    $request->validate([
        'status' => 'required'
    ]);

    DB::beginTransaction();
    try {
        // Update status calon mitra
        DB::table('tb_calonmitra')
            ->where('id_calon', $id_calonmitra)
            ->update([
                'status' => $request->status
            ]);

        // Ambil data calon mitra
        $ubah = $this->M_Admin->datacalonpindah($id_calonmitra);

        // Kalau status diterima, baru lakukan pemindahan data
        if ($request->status == 'Diterima' && $ubah) {

            $id_akun = $ubah->id_akun;
            $nama_franchise = $ubah->nama_lengkap;

            // Generate ID Mitra
            $lastMitra = DB::table('tb_mitra')->select('id_mitra')->orderByDesc('id_mitra')->first();
            $idMitra = $lastMitra 
                ? 'M' . str_pad((int) substr($lastMitra->id_mitra, 1) + 1, 4, '0', STR_PAD_LEFT)
                : 'M0001';

            DB::table('tb_mitra')->insert([
                'id_mitra' => $idMitra,
                'id_akun' => $id_akun,
                'nama_lengkap' => $ubah->nama_lengkap,
                'no_ktp' => $ubah->no_ktp,
                'provinsi' => $ubah->provinsi,
                'kota' => $ubah->kota,
                'kelurahan' => $ubah->kelurahan,
                'ktp' => $ubah->ktp,
                'no_hp' => $ubah->no_hp,
                'alamat_lengkap' => $ubah->alamat_lengkap,
            ]);

            // Generate ID Franchise
            $lastFranchise = DB::table('tb_franchise')->select('id_franchise')->orderByDesc('id_franchise')->first();
            $idFranchise = $lastFranchise
                ? 'F' . str_pad((int) substr($lastFranchise->id_franchise, 1) + 1, 4, '0', STR_PAD_LEFT)
                : 'F0001';

            DB::table('tb_franchise')->insert([
                'id_franchise' => $idFranchise,
                'id_mitra' => $idMitra,
                'nama_franchise' => $nama_franchise,
                'provinsi_usaha' => $ubah->provinsi_usaha,
                'kota_usaha' => $ubah->kota_usaha,
                'kelurahan_usaha' => $ubah->kelurahan_usaha,
                'kecamatan_usaha' => $ubah->kecamatan_usaha,
                'alamat_usaha' => $ubah->alamat_usaha,
                'kode_pos' => $ubah->kode_pos,
                'titik_koordinat' => $ubah->titik_koordinat,
                'lokasi_usaha' => $ubah->lokasi_usaha,
            ]);

            // Generate akun kasir
            $lastAkun = DB::table('tb_akun')->select('id_akun')->orderByDesc('id_akun')->first();
            $idAkun = $lastAkun 
                ? 'A' . str_pad((int) substr($lastAkun->id_akun, 1) + 1, 4, '0', STR_PAD_LEFT)
                : 'A0001';

            $lastKasir = DB::table('tb_kasir')->select('id_kasir')->orderByDesc('id_kasir')->first();
            $idKasir = $lastKasir 
                ? 'K' . str_pad((int) substr($lastKasir->id_kasir, 1) + 1, 4, '0', STR_PAD_LEFT)
                : 'K0001';

            DB::table('tb_akun')->insert([
                'id_akun' => $idAkun,
                'username' => $idKasir,
                'password' => bcrypt($idKasir),
                'type_akun' => 'kasir',
            ]);

            DB::table('tb_kasir')->insert([
                'id_kasir' => $idKasir,
                'id_akun' => $idAkun,
                'id_franchise' => $idFranchise,
            ]);
        }

        DB::commit();

        // Setelah semua data berhasil disimpan, baru kirim email
        $nama_lengkap = DB::table('tb_calonmitra')
            ->where('id_calon', $id_calonmitra)
            ->value('nama_lengkap');

        $email = DB::table('tb_calonmitra')
            ->leftJoin('tb_akun', 'tb_calonmitra.id_akun', '=', 'tb_akun.id_akun')
            ->where('tb_calonmitra.id_calon', $id_calonmitra)
            ->value('tb_akun.email');

        if ($nama_lengkap && $email) {
            try {
                Mail::to($email)->send(new KirimNotifikasiCalon($nama_lengkap, $request->status));
            } catch (\Exception $mailEx) {
                Log::warning('Gagal mengirim email ke ' . $email . ': ' . $mailEx->getMessage());
                // biarkan saja, tidak mengganggu proses
            }
        }

        return back()->with('success', 'Status berhasil diperbarui.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Gagal update status: ' . $e->getMessage());
        return back()->with('error', 'Gagal memperbarui status.');
    }
}


        public function status()
        {
            $user = Session::get('user');
            if (!$user || !isset($user['id_akun'])) {
                return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
            }
            $id_akun = Session::get('user')['id_akun'];
            $idcalon = DB::table('tb_calonmitra')
                        ->where('id_akun', $id_akun)
                        ->first();

            // Cek apakah calon mitra ditemukan
            if (!$idcalon) {
                return back()->with('error', 'Anda belum daftar kemitraan, silahkan daftar dulu ya.');
            }

            if ($idcalon->status !== 'Diterima') {
                return view('status', ['calon' => $idcalon]);
            } else {
                $idfranchisebaru = DB::table('tb_franchisebaru')
                                    ->leftJoin('tb_mitra', 'tb_franchisebaru.id_mitra', '=', 'tb_mitra.id_mitra')
                                    ->where('tb_mitra.id_akun', $id_akun)
                                    ->select('tb_franchisebaru.*')
                                    ->get();
                return view('v_preview', compact('idfranchisebaru'));
            }
        }

        public function qrcode()
        {   
            $id_akun = Session::get('user')['id_akun'];
            $idcalon = DB::table('tb_calonmitra')
                        ->where('id_akun', $id_akun)
                        ->first();
                        
            if (!$idcalon) {
                return back()->with('error', 'Data calon mitra tidak ditemukan!');
            }

            return view('v_qrcode', compact('idcalon', 'id_akun'));
        }

        public function qrcodefranchise($id_franchisebaru)
        {   
            $idcalon = DB::table('tb_franchisebaru')
                        ->where('id_franchisebaru', $id_franchisebaru)
                        ->first();
                        
            if (!$idcalon) {
                return back()->with('error', 'Data tidak ditemukan!');
            }

            return view('v_qrcodefranchise', compact('idcalon'));
        }

        public function downloadQrCodefranchise($id_franchisebaru)
        {
            $idcalon = DB::table('tb_franchisebaru')
                        ->where('id_franchisebaru', $id_franchisebaru)
                        ->first();
                        
            if (!$idcalon) {
                return back()->with('error', 'Data calon mitra tidak ditemukan!');
            }
            $qrdata = $idcalon->id_franchisebaru;
            $image = QrCode::format('png')
                        ->size(400)
                        ->margin(2)
                        ->generate($qrdata);

            return response($image)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'attachment; filename="qrcode.png"');
        }
        
        public function indextambahfranchise()
        {
            return view('v_tambahfranchise');
        }

        public function tambahfranchise(Request $request)
        {
            $request->validate([
                'provinsi_usaha' => 'required',
                'kota_usaha'     => 'required',
                'kelurahan_usaha'=> 'required',
                'kecamatan_usaha'=> 'required',
                'alamat_usaha'   => 'required',
                'kode_pos'       => 'required',
                'titik_koordinat'=> 'required',
                'lokasi_usaha'   => 'file|mimes:jpg,jpeg,png,pdf',
            ]);
        
            $id_akun = Auth::user()->id_akun;
            $lastFBaru = DB::table('tb_franchisebaru')->orderByDesc('id_franchisebaru')->first();
            $idFBaru = $lastFBaru
                ? 'FB' . str_pad(((int)substr($lastFBaru->id_franchisebaru, 2)) + 1, 4, '0', STR_PAD_LEFT)
                : 'FB0001';
        
            $fileNamelokasi = null;
            if ($filelokasi = $request->file('lokasi_usaha')) {
                $fileNamelokasi = $idFBaru . '.' . $filelokasi->extension();
                $filelokasi->move(public_path('uploads/lokasi'), $fileNamelokasi);
            }
        
            $mitra = DB::table('tb_mitra')->where('id_akun', $id_akun)->first();
            if (!$mitra) {
                return redirect()->back()->with('error', 'User belum terdaftar sebagai mitra.');
            }
        
            $data = [
                'id_franchisebaru' => $idFBaru,
                'id_mitra'         => $mitra->id_mitra,
                'nama_franchise'   => $mitra->nama_lengkap,
                'provinsi_usaha'   => $request->provinsi_usaha,
                'kota_usaha'       => $request->kota_usaha,
                'kelurahan_usaha'  => $request->kelurahan_usaha,
                'kecamatan_usaha'  => $request->kecamatan_usaha,
                'alamat_usaha'     => $request->alamat_usaha,
                'kode_pos'         => $request->kode_pos,
                'titik_koordinat'  => $request->titik_koordinat,
                'lokasi_usaha'     => $fileNamelokasi,
                'status'           => 'Review Dokumen',
            ];
            try {
                DB::table('tb_franchisebaru')->insert($data);
                Log::info('Franchise Baru disimpan', $data);
                return redirect()->route('qrcode.franchise', ['id_franchisebaru' => $idFBaru])->with('success', 'Pendaftaran franchise berhasil!');
            } catch (\Exception $e) {
                Log::error('Gagal simpan franchise baru: '.$e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan. Silakan coba lagi.');
            }
        }
        
        public function uploadtransaksi(Request $request, $id_calon)
        {
            $request->validate([
                'via_pembayaran' => 'required',
                'bukti' => 'required|file|mimes:jpg,jpeg,png,pdf'
            ]);

            if (!$request->via_pembayaran){
                return back()->with('error', 'Tolong isi via pembayaran!');
            }

            if (!$request->bukti){
                return back()->with('error', 'Tolong isi Bukti pembayaran!');
            }
            $fileNamelokasi = null;
            if ($filelokasi = $request->file('bukti')) {
                $fileNamelokasi = $id_calon . '.' . $filelokasi->extension();
                $filelokasi->move(public_path('uploads/bukti'), $fileNamelokasi);
            }
            $data = [
                'id_calon' => $id_calon,
                'via_pembayaran' => $request->via_pembayaran,
                'bukti' => $fileNamelokasi,
            ];

            DB::table('tb_calonmitra')->where('id_calon', $id_calon)->update($data);
            return redirect('/home')->with('success', 'Upload bukti pembayaran berhasil!.');
        }
        
        public function uploadtransaksifranchise(Request $request, $id_franchisebaru)
        {
            $request->validate([
                'via_pembayaran' => 'required',
                'bukti' => 'required|file|mimes:jpg,jpeg,png,pdf'
            ]);

            if (!$request->via_pembayaran){
                return back()->with('error', 'Tolong isi via pembayaran!');
            }

            if (!$request->bukti){
                return back()->with('error', 'Tolong isi Bukti pembayaran!');
            }
            $fileNamelokasi = null;
            if ($filelokasi = $request->file('bukti')) {
                $fileNamelokasi = $id_franchisebaru . '.' . $filelokasi->extension();
                $filelokasi->move(public_path('uploads/bukti'), $fileNamelokasi);
            }
            $data = [
                'id_franchisebaru' => $id_franchisebaru,
                'via_pembayaran' => $request->via_pembayaran,
                'bukti' => $fileNamelokasi,
            ];
            try {
                DB::table('tb_franchisebaru')->where('id_franchisebaru', $id_franchisebaru)->update($data);
                Log::info('Bukti pembayaran disimpan disimpan', $data);
                return redirect('/home')->with('success', 'Upload Bukti pembayaran berhasil!');
            } catch (\Exception $e) {
                Log::error('Gagal simpan bukti pembayaran: '.$e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan. Silakan coba lagi.');
            }
        }
}