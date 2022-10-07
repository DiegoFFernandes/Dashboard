<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Editor\Fields\Select;

class TipoPessoa extends Model
{
    use HasFactory;
    protected $table = 'tipopessoa';
    protected $connection;

    public function __construct()
    {
        $this->connection = 'mysql';
    }

    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }

    public function tipoPessoaAll(){
        $query = "SELECT tp.cd_tipopessoa, cast(tp.ds_tipopessoa as varchar(60) character set utf8) ds_tipopessoa
                  FROM tipopessoa tp
                  WHERE tp.cd_tipopessoa not in (0,2,4,6,7,8,9)
                  ";

        return DB::connection($this->setConnet())->select($query);
    }

}
