<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class M_survey
{
    public function survey()
    {
        return DB::table('tb_survey')->get();
    }

    public function datacalon()
    {
        return DB::table('tb_calonmitra')->get();
    }

    public function buatlaporan($id_calon)
    {
        return DB::table('tb_calonmitra')->where('id_calon', $id_calon)->first();
    }

    public static function allData()
    {
        return DB::table('tb_survey')->get();
    }

    public static function dataAkun()
    {
       return DB::table('tb_calonmitra')->where('status', 'Lokasi Survey')->get();
    }

    public static function getById($id)
    {
        return DB::table('tb_survey')->where('id_survey', $id)->first();
    }

}
