<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required', 
            'password' => 'required',
        ]);

        $user = DB::table('tb_akun')
            ->where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();
        

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Username atau Email tidak ditemukan');
        }

        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->withInput()->with('error', 'Password salah');
        }
        
        if ($user->type_akun == 'kasir'){
            $statuslangganan = DB::table('tb_kasir')
                ->leftJoin('tb_franchise', 'tb_kasir.id_franchise', '=', 'tb_franchise.id_franchise')
                ->where('tb_kasir.id_akun', $user->id_akun)
                ->select('tb_franchise.langganan')
                ->first();
                
            if ($statuslangganan->langganan == 'Tidak Berlangganan'){
                return back()->with('error', 'Silahkan Berlangganan Terlebih Dahulu Untuk Menggunakan Fitur Kasir!');
            }
        }

        Session::put('user', [
            'id_akun' => $user->id_akun,
            'username' => $user->username,
            'email' => $user->email,
            'nama' => $user->nama,
            'type_akun' => $user->type_akun
        ]);

        if (Auth::attempt(['username' => $user->username, 'password' => $request->password])) {
            $request->session()->regenerate();
        }

        if ($user->type_akun === 'admin') {
            return redirect('/admin')->with('success', 'Login berhasil sebagai Admin');
        } else if ($user->type_akun === 'owner') {
            return redirect('/owner')->with('success', 'Login berhasil sebagai Owner');
        } else if ($user->type_akun === 'user') {
            return redirect('/home')->with('success', 'Login berhasil!');
        } else if ($user->type_akun === 'kasir') {
            return redirect('/kasir')->with('success', 'Login berhasil sebagai Kasir');
        } else if ($user->type_akun === 'survey') {
            return redirect('/survey/datasurvey')->with('success', 'Login berhasil sebagai Surveyor');
        } else if ($user->type_akun === 'gudang') {
            return redirect('/gudang')->with('success', 'Login berhasil sebagai DC');
        } else if ($user->type_akun === 'kasirdc') {
            return redirect('/gudang')->with('success', 'Login berhasil sebagai Kasir DC');
        } else if ($user->type_akun === 'auditor') {
            return redirect('/admin/dataqr')->with('success', 'Login berhasil sebagai Auditor');
        }
    }

    public function logout()
    {
        Session::forget('user');
        return redirect('/')->with('success', 'Logout berhasil');
    }

public function loginkasir($id_franchise)
{
    $kasir = DB::table('tb_kasir')
        ->join('tb_akun', 'tb_kasir.id_akun', '=', 'tb_akun.id_akun')
        ->where('tb_kasir.id_franchise', $id_franchise)
        ->select('tb_akun.*') 
        ->first();

    if (!$kasir) {
        return back()->with('error', 'Kasir untuk franchise ini tidak ditemukan');
    }

    Session::put('user', array_merge(session('user', []), [
        'id_franchise' => $id_franchise
    ]));

    return redirect('/dashkasir')->with('success', 'Login berhasil sebagai Kasir');
}

}
