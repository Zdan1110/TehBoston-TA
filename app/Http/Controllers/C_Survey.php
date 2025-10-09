<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\M_survey;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\CalonMitraController; 
use Illuminate\Support\Facades\Mail;
use App\Mail\KirimNotifikasiCalon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


class C_Survey extends Controller
{

    public function __construct()
    {
        $this->M_survey = new M_survey();
    }  
    
    public function index(Request $request)
    {
        $query = DB::table('tb_survey');

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
        }

        $survey = $query->paginate(10);

        return view('survey.datasurvey', compact('survey'));
    }


public function indexadmin()
{
    // Ambil semua data dari tb_survey
    $surveySemua = DB::table('tb_survey')->get();

    $calon = [];
    $franchise = [];

    foreach ($surveySemua as $s) {
        // Jika dari calon mitra
        if ($s->id_calon) {
            $mitra = DB::table('tb_calonmitra')
                ->where('id_calon', $s->id_calon)
                ->first();

            $calon[] = (object) [
                'id_survey'    => $s->id_survey,
                'id_calon'     => $s->id_calon,
                'id_akun'      => $s->id_akun,
                'nama_lengkap' => $s->nama_lengkap,
                'panjang'      => $s->panjang,
                'lebar'        => $s->lebar,
                'total_luas'   => $s->total_luas,
                'foto'         => $s->foto,
                'catatan'      => $s->catatan,
                'status'       => $mitra->status ?? 'Tidak diketahui',
                'sumber'       => 'calonmitra',
                'created_at'   => $s->created_at, 
                'updated_at'   => $s->updated_at
            ];
        }
        // Jika dari franchise baru
        elseif ($s->id_franchisebaru) {
            $fr = DB::table('tb_franchisebaru')
                ->where('id_franchisebaru', $s->id_franchisebaru)
                ->first();

            $franchise[] = (object) [
                'id_survey'    => $s->id_survey,
                'id_calon'     => $s->id_franchisebaru, // tetap pakai id_calon untuk kompatibilitas
                'id_akun'      => $s->id_akun,
                'nama_lengkap' => $s->nama_lengkap,
                'panjang'      => $s->panjang,
                'lebar'        => $s->lebar,
                'total_luas'   => $s->total_luas,
                'foto'         => $s->foto,
                'catatan'      => $s->catatan,
                'status'       => $fr->status ?? 'Tidak diketahui',
                'sumber'       => 'franchisebaru', // ✅ Tambahkan sumber
                'created_at'   => $s->created_at, // ✅ Tambahkan ini juga
                'updated_at'   => $s->updated_at
            ];
        }
    }

    // Gabungkan data calon dan franchise
    $survey = collect($calon)->merge($franchise);

    return view('admin.datasurvey', compact('survey'));
}

  public function indexowner()
{
    // Ambil semua data dari tb_survey
    $surveySemua = DB::table('tb_survey')->get();

    $calon = [];
    $franchise = [];

    foreach ($surveySemua as $s) {
        if ($s->id_calon) {
            $mitra = DB::table('tb_calonmitra')
                ->where('id_calon', $s->id_calon)
                ->first();

            $calon[] = (object) [
                'id_survey' => $s->id_survey,
                'id_calon' => $s->id_calon,
                'id_akun' => $s->id_akun,
                'nama_lengkap' => $s->nama_lengkap,
                'panjang' => $s->panjang,
                'lebar' => $s->lebar,
                'total_luas' => $s->total_luas,
                'foto' => $s->foto,
                'catatan' => $s->catatan,
                'status' => $mitra->status ?? 'Tidak diketahui',
                'created_at'   => $s->created_at, 
                'updated_at'   => $s->updated_at
            ];
        } elseif ($s->id_franchisebaru) {
            $fr = DB::table('tb_franchisebaru')
                ->where('id_franchisebaru', $s->id_franchisebaru)
                ->first();

            $franchise[] = (object) [
                'id_survey' => $s->id_survey,
                'id_calon' => $s->id_franchisebaru,
                'id_akun' => $s->id_akun,
                'nama_lengkap' => $s->nama_lengkap,
                'panjang' => $s->panjang,
                'lebar' => $s->lebar,
                'total_luas' => $s->total_luas,
                'foto' => $s->foto,
                'catatan' => $s->catatan,
                'status' => $fr->status ?? 'Tidak diketahui',
                'created_at'   => $s->created_at, // ✅ Tambahkan ini juga
                'updated_at'   => $s->updated_at
            ];
        }
    }

    // Gabungkan data
    $survey = collect($calon)->merge($franchise);

    return view('owner.datasurvey', compact('survey'));
}


public function index2()
{
    // Ambil data dari tb_calonmitra
    $calon = DB::table('tb_calonmitra')
        ->leftJoin('tb_survey', 'tb_calonmitra.id_calon', '=', 'tb_survey.id_calon')
        ->select(
            'tb_calonmitra.id_calon',
            'tb_calonmitra.nama_lengkap',
            'tb_calonmitra.alamat_usaha',
            'tb_calonmitra.no_hp',
            'tb_calonmitra.status',
            'tb_survey.id_survey',
            DB::raw("'calonmitra' as sumber")
        )
        ->where('tb_calonmitra.status', 'Survey Lokasi')
        ->get();

    // Ambil data dari tb_franchisebaru
    $franchiseData = DB::table('tb_franchisebaru')
        ->where('status', 'Survey Lokasi')
        ->get();

    $franchise = [];

    foreach ($franchiseData as $f) {
        // Ambil nama_lengkap dari tb_mitra secara terpisah
        $mitra = DB::table('tb_mitra')->where('id_mitra', $f->id_mitra)->first();

        $survey = DB::table('tb_survey')
            ->where('id_franchisebaru', $f->id_franchisebaru)
            ->first();


        $franchise[] = (object) [
            'id_calon' => $f->id_franchisebaru,
            'nama_lengkap' => $mitra->nama_lengkap ?? '-',
            'alamat_usaha' => $f->lokasi_usaha,
            'no_hp' => '-',
            'status' => $f->status,
            'id_survey' => $survey->id_survey ?? null, // ini yang digunakan di Blade
            'sumber' => 'franchisebaru'
        ];

    }

    // Gabungkan data
    $combined = collect($calon)->merge($franchise);

    return view('survey.v_tabelcalon', ['survey' => $combined]);
}


public function index3($id_calon, Request $request)
{
    $sumber = $request->input('sumber', 'calonmitra');

    if ($sumber == 'franchisebaru') {
        $data = DB::table('tb_franchisebaru')
            ->leftJoin('tb_mitra', 'tb_franchisebaru.id_mitra', '=', 'tb_mitra.id_mitra')
            ->where('tb_franchisebaru.id_franchisebaru', $id_calon)
            ->select(
                'tb_franchisebaru.id_franchisebaru', // <-- ini ditambahkan
                'tb_franchisebaru.id_franchisebaru as id_calon', // biar kompatibel
                'tb_mitra.nama_lengkap',
                'tb_franchisebaru.alamat_usaha',
                'tb_mitra.no_hp',
                'tb_franchisebaru.status',
                'tb_franchisebaru.lokasi_usaha'
            )
            ->first();

    } else {
        $data = DB::table('tb_calonmitra')
            ->where('id_calon', $id_calon)
            ->select('id_calon', 'nama_lengkap', 'alamat_usaha', 'no_hp', 'status', 'lokasi_usaha')
            ->first();
    }

    if (!$data) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }

    return view('survey.v_laporansurvey', compact('data'));
}


    public function destroy($id_survey)
    {
        $data = DB::table('tb_survey')->where('id_survey', $id_survey)->first();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        DB::table('tb_survey')->where('id_survey', $id_survey)->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    public function laporansurvey(Request $request, $id_calon)
{
     
    $request->validate([
        'panjang' => 'required',
        'lebar' => 'required',
        'total_luas' => 'required',
        'foto' => 'required|file|mimes:jpg,jpeg,png,pdf',
        'catatan' => 'required',
    ]);

    // Buat ID survey baru
    $lastsurvey = DB::table('tb_survey')->select('id_survey')->orderByDesc('id_survey')->first();
    $idsurvey = $lastsurvey
        ? 'S' . str_pad((int) substr($lastsurvey->id_survey, 1) + 1, 4, '0', STR_PAD_LEFT)
        : 'S0001';

    // Upload file
    $filesurvey = $request->file('foto');
    $fileNamesurvey = $idsurvey . '.' . $filesurvey->extension();
    $filesurvey->move(public_path('uploads/survey'), $fileNamesurvey);

    // Deteksi sumber: franchisebaru atau calonmitra
    $sumber = $request->input('sumber', 'calonmitra');
    if ($sumber === 'franchisebaru') {
        $datacalon = DB::table('tb_franchisebaru')
            ->join('tb_mitra', 'tb_franchisebaru.id_mitra', '=', 'tb_mitra.id_mitra')
            ->where('tb_franchisebaru.id_franchisebaru', $id_calon)
            ->select('tb_franchisebaru.id_franchisebaru', 'tb_mitra.nama_lengkap')
            ->first();

    } else {
        $datacalon = DB::table('tb_calonmitra')
            ->where('id_calon', $id_calon)
            ->select('nama_lengkap')
            ->first();
    }

    if (!$datacalon) {
        return redirect()->back()->with('error', 'Data calon tidak ditemukan.');
    }

    // Simpan ke tb_survey
    $datasurvey = [
        'id_survey'        => $idsurvey,
        'id_calon'         => $sumber === 'calonmitra' ? $id_calon : null,
        'id_franchisebaru' => $sumber === 'franchisebaru' ? $id_calon : null,
        'id_akun'          => $request->id_akun,
        'nama_lengkap'     => $request->nama_lengkap,
        'panjang'          => $request->panjang,
        'lebar'            => $request->lebar,
        'total_luas'       => $request->total_luas,
        'foto'             => $fileNamesurvey,
        'catatan'          => $request->catatan,
    ];

    try {
        DB::table('tb_survey')->insert($datasurvey);
        Log::info('Data survey berhasil disimpan.', $datasurvey);

        return redirect('/survey/datasurvey')->with('success', 'Buat Laporan Berhasil!.');
    } catch (\Exception $e) {
        Log::error('Gagal membuat laporan: ' . $e->getMessage());

        return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat laporan. Silakan coba lagi.');
    }
}


    public function indexprofile()
    {
        $user = Auth::user();
        return view('survey.profile', compact('user'));
    }

    public function updateprofile(Request $request)
    {
        $user = Auth::user();
        
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:tb_akun,email,'.$user->id_akun.',id_akun',
            'no_hp' => 'nullable|string|max:20',
            'username' => 'required|string|max:255|unique:tb_akun,username,'.$user->id_akun.',id_akun',
            'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['nama', 'email', 'no_hp', 'username']);

        // Handle file upload
        if ($request->hasFile('foto_profile')) {
    // Hapus foto lama jika ada
            if ($user->foto_profile && file_exists(public_path('uploads/survey/profile/' . $user->foto_profile))) {
                unlink(public_path('uploads/survey/profile/' . $user->foto_profile));
            }

            $file = $request->file('foto_profile');
            $fileName = 'profile_' . $user->id_akun . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/survey/profile'), $fileName);
            $data['foto_profile'] = $fileName;
        }



        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function edit($id)
    {
        $survey = M_survey::getById($id);

        if (!$survey) {
            abort(404);
        }

        return view('survey.edit', compact('survey'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'panjang' => 'required|numeric',
            'lebar' => 'required|numeric',
            'catatan' => 'required|string',
            'foto' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $survey = DB::table('tb_survey')->where('id_survey', $id)->first();

        if (!$survey) {
            abort(404);
        }

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'panjang' => $request->panjang,
            'lebar' => $request->lebar,
            'total_luas' => $request->panjang * $request->lebar,
            'catatan' => $request->catatan,
        ];

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/survey'), $filename);
            $data['foto'] = $filename;
        }

        DB::table('tb_survey')->where('id_survey', $id)->update($data);

        return redirect()->route('datasurvey')->with('success', 'Data survey berhasil diperbarui.');
    }

    public function accCalon($id_calon, $sumber)
{
    if ($sumber === 'franchisebaru') {
        $data = DB::table('tb_franchisebaru')
            ->join('tb_mitra', 'tb_franchisebaru.id_mitra', '=', 'tb_mitra.id_mitra')
            ->join('tb_akun', 'tb_mitra.id_akun', '=', 'tb_akun.id_akun')
            ->where('tb_franchisebaru.id_franchisebaru', $id_calon)
            ->select('tb_franchisebaru.*', 'tb_akun.email', 'tb_mitra.nama_lengkap')
            ->first();

        if (!$data) {
            return redirect()->back()->with('error', 'Franchise baru tidak ditemukan.');
        }

        DB::table('tb_franchisebaru')->where('id_franchisebaru', $id_calon)->update(['status' => 'Pembayaran']);
    } else {
        $data = DB::table('tb_calonmitra')
            ->join('tb_akun', 'tb_calonmitra.id_akun', '=', 'tb_akun.id_akun')
            ->where('tb_calonmitra.id_calon', $id_calon)
            ->select('tb_calonmitra.*', 'tb_akun.email')
            ->first();

        if (!$data) {
            return redirect()->back()->with('error', 'Calon mitra tidak ditemukan.');
        }

        DB::table('tb_calonmitra')->where('id_calon', $id_calon)->update(['status' => 'Pembayaran']);
    }

    Mail::to($data->email)->send(new KirimNotifikasiCalon($data->nama_lengkap, 'Pembayaran'));

    return redirect()->back()->with('success', 'Status berhasil diubah menjadi Pembayaran.');
}

    
}
