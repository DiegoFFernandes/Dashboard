<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MarcaPneu extends Model
{
    use HasFactory;

    public function __construct()
    {
        $this->connection = 'mysql';
    }
    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }
    public function MarcaAll(){

        $query = "select cd_marca, ds_marca from marca";

        return DB::connection('firebird_rede')->select($query);
        
    }
}
