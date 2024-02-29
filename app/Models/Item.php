<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    use HasFactory;
    protected $table = 'itens';
    public $timestamps = true;

    public function __construct()
    {
        $this->connection = 'mysql';
    }
    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }
    public function ImportaItemJunsoft($cd_marca)
    {
        $query = "select
                    case i.cd_subgrupo
                    when 101 then i.cd_codbarraemb
                    when 308 then i.cd_codigobarra
                    when 103 then i.cd_codigobarra
                    end cd_codbarraemb,
                    i.cd_item,
                    cast((i.ds_item) as varchar(100) character set utf8) ds_item, 
                    i.ps_liquido,
                    i.sg_unidmed,
                    i.cd_subgrupo,
                    i.cd_marca,
                    i.dt_registro,
                    i.st_ativo
                from item i
                where i.cd_marca = $cd_marca
                and i.tp_item in ('I','S','P')
                and i.cd_grupo in (1,3)
                and i.cd_subgrupo in (101,308,103)
                and i.st_ativo = 'S'
                --and i.cd_item in ('3107','1001904')";
        $itens = DB::connection('firebird_rede')->select($query);

        $status = $this->InsertItem($itens);
        if ($status == 1) {
            return 1;
        } else {
            return $status;
        }
    }
    public function InsertItem($itens)
    {
        $this->connection = 'mysql';
        foreach ($itens as $i) {
            try {
                Item::updateOrInsert(
                    ['cd_item' => $i->CD_ITEM],
                    [
                        'cd_codbarraemb' => $i->CD_CODBARRAEMB,
                        'cd_item' => $i->CD_ITEM,
                        'ds_item' => $i->DS_ITEM,
                        'ps_liquido' => $i->PS_LIQUIDO,
                        'sg_unidmed' => $i->SG_UNIDMED,
                        'cd_subgrupo' => $i->CD_SUBGRUPO,
                        'cd_marca' => $i->CD_MARCA,
                        'st_ativo' => $i->ST_ATIVO,
                        'cd_usuario' => Auth::user()->id,
                        'created_at' => $i->DT_REGISTRO,
                        'updated_at' => Carbon::now()->format('Y-m-d H:m:s')
                    ]
                );
            } catch (\Illuminate\Database\QueryException $ex) {
                return ($ex->errorInfo[2]);
                return "Houve algum erro ao importar os itens";
            }
        }
        return 1;
    }
    public function ItemFind($cd_barra)
    {
        return Item::where('cd_codbarraemb', $cd_barra)->firstOr(function () {
            return 0;
        });
    }
    public function FindProdutoAll($term){
        $this->connection = 'mysql';

        return Item::where('ds_item','like', '%'.$term.'%')->where('st_ativo', 'S')
		->limit(10)->get();
    }
}
