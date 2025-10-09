<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalonMitra extends Model
{
    use HasFactory;

    protected $table = 'tb_calonmitra'; // Pastikan tabel benar
    protected $primaryKey = 'id_calon';
    public $incrementing = false; // karena id bukan auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'id_calon',
        'id_akun',
        'nama_lengkap',
        'no_ktp',
        'provinsi',
        'kota',
        'kelurahan',
        'kecamatan',
        'ktp',
        'no_hp',
        'alamat_lengkap',
        'pas_photo',

        // Data Lokasi Usaha
        'provinsi_usaha',
        'kota_usaha',
        'kelurahan_usaha',
        'kecamatan_usaha',
        'alamat_usaha',
        'kode_pos',
        'titik_koordinat',
        'lokasi_usaha',
        'status',
    ];


}
