<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class M_owner extends Model
{
    public function allData()
    {
        return DB::table('tb_calonmitra')->get();
    }

    public function dataakun()
    {
        return DB::table('tb_akun')->where('type_akun', '!=', 'owner')->get();
    }

    public function dataproduk()
    {
        return DB::table('tb_produk')->get();
    }

    public function detailDatacalon($id_calon)
    {
        return DB::table('tb_calonmitra')->where('id_calon', $id_calon)->first();
    }

    public function deleteDatacalon($id_calon)
    {
        DB::table('tb_calonmitra')->where('id_calon', $id_calon)->delete();
    }

    public function detailDataakun($id_akun)
    {
        return DB::table('tb_akun')->where('id_akun', $id_akun)->first();
    }

    public function deleteDataakun($id_akun)
    {
        DB::table('tb_akun')->where('id_akun', $id_akun)->delete();
    }

    public function detailDataproduk($id_produk)
    {
        return DB::table('tb_produk')->where('id_produk', $id_produk)->first();
    }
    
    public function editDataproduk($id_produk, $data)
    {
        DB::table('tb_produk')->where('id_produk', $id_produk)->update($data);
    }

    public function deleteDataproduk($id_produk)
    {
        DB::table('tb_produk')->where('id_produk', $id_produk)->delete();
    }

    public function addData($data)
    {
        DB::table('tb_produk')->insert($data);
    }

    public function datafranchisebaru()
    {
        return DB::table('tb_franchisebaru')->get();
    }
}
