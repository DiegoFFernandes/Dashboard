<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ModeloPneu extends Model
{
    use HasFactory;  
    protected $connection;

    public function __construct()
    {
        $this->connection = 'mysql';
    }

    // Essa consulta tras as informação do Mysql
    public function list(){
        
        $this->connection = 'mysql';
       return ModeloPneu::all();
    } 

    public function setConnet($cd_empresa)
    {
        if ($cd_empresa == 'SUL') {
            return $this->connection = 'firebird_campina';
        }
        return $this->connection = 'firebird_paranavai';
    }
    // Essa consulta tras as informações do Firebird
    public function listModeloPneu(){
        $query = "
            SELECT
                MO.id,
                MO.idmarcapneu,
                cast(MA.dsmarca||' - '||MO.dsmodelo as varchar(200) character set utf8) ds_MODELO
            FROM MODELOPNEU MO
            LEFT JOIN marcapneu MA ON (MA.id = MO.idmarcapneu)
            WHERE MO.st_ativo = 'S' AND MA.st_ativo = 'S'";
        return DB::connection($this->setConnet('SUL'))->select($query);
    }
    
    // public function ImportaItemJunsoft($cd_marca)
    // {
    //     $query = "";

    //     $itens = DB::connection($this->setConnet('SUL'))->select($query);

    //     $status = $this->InsertItem($itens);
    //     if ($status == 1) {
    //         return 1;
    //     } else {
    //         return $status;
    //     }
    // }
    // public function InsertItem($itens)
    // {
    //     $this->connection = 'mysql';
    //     foreach ($itens as $i) {
    //         try {
    //             Item::updateOrInsert(
    //                 ['cd_item' => $i->CD_ITEM],
    //                 [
    //                     'cd_codbarraemb' => $i->CD_CODBARRAEMB,
    //                     'cd_item' => $i->CD_ITEM,
    //                     'ds_item' => $i->DS_ITEM,
    //                     'ps_liquido' => $i->PS_LIQUIDO,
    //                     'sg_unidmed' => $i->SG_UNIDMED,
    //                     'cd_subgrupo' => $i->CD_SUBGRUPO,
    //                     'cd_marca' => $i->CD_MARCA,
    //                     'cd_usuario' => Auth::user()->id,
    //                     'created_at' => $i->DT_REGISTRO
    //                 ]
    //             );
    //         } catch (\Illuminate\Database\QueryException $ex) {
    //             return ($ex->errorInfo[2]);
    //             return "Houve algum erro ao importar os itens";
    //         }
    //     }
    //     return 1;
    // }
    

    


}
