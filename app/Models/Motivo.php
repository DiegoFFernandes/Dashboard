<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Motivo extends Model
{
    use HasFactory;
    protected $table = 'motivo';
    public $timestamps = true;


    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }

    public function motivoAll()
    {
        $query = "select m.cd_motivo, cast(m.ds_motivo as varchar(100) character set utf8) ds_motivo, m.tp_motivo, m.dt_registro
                    from motivo m
                    where m.tp_motivo='C'";
        return DB::connection($this->setConnet())->select($query);
    }
}
