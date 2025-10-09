<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Strukdc extends Model
{
    protected $table = 'tb_transaksi';
    protected $primaryKey = 'id_transaksi';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_transaksi', 
        'tanggal_transaksi', 
        'jenis_transaksi', 
        'total', 
        'struk'
    ];
}
