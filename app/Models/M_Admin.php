<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class M_Admin extends Model
{
    public function datacalon()
    {
        return DB::table('tb_calonmitra')->get();
    }

    public function datacalonpindah($id_calonmitra)
    {
        return DB::table('tb_calonmitra')->where('id_calon', $id_calonmitra)->first();
    }

    public function datafranchisepindah($id_franchisebaru)
    {
        return DB::table('tb_franchisebaru')->where('id_franchisebaru', $id_franchisebaru)->first();
    }

    public function dataakun()
    {
        return DB::table('tb_akun')->where('type_akun', 'user')->get();
    }

    public function dataakunadmin($id_akun)
    {
        return DB::table('tb_akun')->where('type_akun', 'admin')->first();
    }

    public function dataproduk()
    {
        $rows = DB::table('tb_produk')
            ->leftJoin('tb_detailproduk', 'tb_produk.id_produk', '=', 'tb_detailproduk.id_produk')
            ->leftJoin('tb_bahanbaku', 'tb_detailproduk.id_bahanbaku', '=', 'tb_bahanbaku.id_bahanbaku')
            ->select(
                'tb_produk.*',
                'tb_bahanbaku.nama_bahan',
                'tb_detailproduk.jumlah'
            )
            ->orderBy('tb_produk.id_produk')
            ->get();

        $produk = [];

        foreach ($rows as $row) {

            if (!isset($produk[$row->id_produk])) {

                $produk[$row->id_produk] = (object) [
                    'id_produk' => $row->id_produk,
                    'nama_produk' => $row->nama_produk,
                    'hpp' => $row->hpp,
                    'harga' => $row->harga,
                    'gambar_produk' => $row->gambar_produk,
                    'bahan' => []
                ];
            }

            if ($row->nama_bahan) {
                $produk[$row->id_produk]->bahan[] = [
                    'nama_bahan' => $row->nama_bahan,
                    'jumlah' => $row->jumlah
                ];
            }
        }

        return collect($produk)->values();
    }

    public function datafranchisebaru()
    {
        return DB::table('tb_franchisebaru')->get();
    }

    public function detailDatacalon($id_calon)
    {
        return DB::table('tb_calonmitra')->where('id_calon', $id_calon)->first();
    }

    public function datamitra($id_akun)
    {
        return DB::table('tb_mitra')->where('id_akun', $id_akun)->get();
    }

    public function datamitrafoto($id_akun)
    {
        return DB::table('tb_mitra')->where('id_akun', $id_akun)->first();
    }

    public function datafranchise()
    {
        return DB::table('tb_franchise')->get();
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
    
    public function detailDatafranchise($id_franchise)
    {
        return DB::table('tb_franchise')->where('id_franchise', $id_franchise)->first();
    }
    
    public function editDataproduk($id_produk, $data)
    {
        DB::table('tb_produk')->where('id_produk', $id_produk)->update($data);
    }

    public function editDatafranchise($id_franchise, $data)
    {
        DB::table('tb_franchise')->where('id_franchise', $id_franchise)->update($data);
    }

    public function deleteDatafranchise($id_franchise)
    {
        DB::table('tb_franchise')->where('id_franchise', $id_franchise)->delete();
    }

    public function deleteDataproduk($id_produk)
    {
        DB::table('tb_produk')->where('id_produk', $id_produk)->delete();
    }

    public function deleteDatafranchisebaru($id_franchisebaru)
    {
        DB::table('tb_franchisebaru')->where('id_franchisebaru', $id_franchisebaru)->delete();
    }

    public function addData($data)
    {
        DB::table('tb_produk')->insert($data);
    }


}
