<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ParmSistema extends Model
{
    use HasFactory;

    public function UpdateLicencaColeta(){
        $query = 'update parmsistema p set p.nrlicencacoleta = 210';
        DB::connection('firebird_campina')->select($query);
        return DB::connection('firebird_paranavai')->select($query);
    }
}
