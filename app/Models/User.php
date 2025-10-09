<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'tb_akun'; // Sesuaikan dengan tabel di database
    protected $primaryKey = 'id_akun';
    public $incrementing = false; // karena id bukan auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'id_akun',
        'nama',
        'email',
        'no_hp',
        'username',
        'password',
        'type_akun'
    ];
}
