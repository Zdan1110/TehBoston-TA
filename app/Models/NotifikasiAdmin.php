<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotifikasiAdmin extends Model
{
    protected $table = 'notifikasi_admin';
    public $timestamps = false;

    protected $fillable = [
        'judul', 'pesan', 'dibaca', 'dibuat_pada'
    ];
}
