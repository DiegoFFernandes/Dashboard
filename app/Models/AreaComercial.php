<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AreaComercial extends Model
{
    use HasFactory;
    protected $table = 'AREACOMERCIAL';
    protected $connection;

    public function __construct()
    {
        $this->connection = 'Sempre setar o banco firebird com SetConnet';
    }
    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }
    public function areaAll(){
        $query = "select * from areacomercial";

        return DB::connection($this->setConnet())->select($query);
    }
}
