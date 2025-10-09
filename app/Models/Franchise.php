<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Franchise extends Model
{
    protected $table = 'tb_franchise';
    protected $primaryKey = 'id_franchise';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_franchise', 'id_mitra', 'nama_franchise', 'provinsi_usaha', 'kota_usaha',
        'kelurahan_usaha', 'kecamatan_usaha', 'alamat_usaha', 'kode_pos',
        'titik_koordinat', 'lokasi_usaha'
    ];
}
