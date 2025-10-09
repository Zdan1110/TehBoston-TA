<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'tb_transaksi';

    protected $primaryKey = 'id_transaksi';
    public $incrementing = false;
    protected $keyType = 'string';

    
    public $timestamps = false; 

    /**
     * 
     *
     * @var array<int
     */
    protected $fillable = [
        'id_transaksi',
        'transaksi',
        'pemasukan',
        'pengeluaran',
        'supplier',
        'keterangan',
    ];

    public static function allData()
    {
        return DB::table('tb_transaksi')->get();
    }
}