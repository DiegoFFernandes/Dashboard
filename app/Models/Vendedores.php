<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Vendedores extends Model
{
    use HasFactory;

    public function __construct()
    {
        $this->connection = 'Sempre setar o banco firebird com SetConnet';
    }

    public function setConnet()
    {                
        return $this->connection = Auth::user()->conexao;
    }
    public function qtdVendedores(){
        $query = "SELECT COUNT(*) as qtd FROM VENDEDOR V WHERE V.ST_ATIVO = 'S'";
        $key = 'vendedor';
        return Cache::remember($key, now()->addMinutes(180), function() use ($query){
            return DB::connection($this->setConnet())->select($query);
        });
    }
}
