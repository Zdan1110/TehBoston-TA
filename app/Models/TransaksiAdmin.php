<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiAdmin extends Model
{
    use HasFactory;

    protected $table = 'tb_transaksiadmin';   // nama tabel
    protected $primaryKey = 'id_transaksi';   // primary key custom
    public $incrementing = false;             // karena bukan auto increment (TD0001)
    protected $keyType = 'string';            // karena primary key berupa string
    protected $fillable = ['id_transaksi', 'transaksi', 'jenis', 'jumlah', 'keterangan'];
}
