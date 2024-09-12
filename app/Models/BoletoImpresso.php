<?php

namespace App\Models;

use Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BoletoImpresso extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'boletoimpresso';

    protected $fillable = [
        'CD_EMPRESA',
    ];


    public function __construct()
    {
        $this->connection = 'mysql';
    }
    public function setConnet($cd_empresa)
    {
        if ($cd_empresa == 'SUL') {
            return $this->connection = 'firebird_campina';
        }
        return $this->connection = 'firebird_paranavai';
    }

    

    
    
}
