<?php

namespace App\Models;

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
        $query = "select i.cd_codbarraemb, i.cd_item, cast((i.ds_item) as varchar(100) character set utf8) ds_item, 
            i.ps_liquido, i.sg_unidmed, i.cd_subgrupo, i.cd_marca, i.dt_registro
                    from item i
                    where i.cd_marca = $cd_marca
                    and i.tp_item = 'I'
                    and i.cd_grupo = 1
                    --and i.cd_item = 1002289";
        $itens = DB::connection($this->setConnet())->select($query);

        return $this->InsertItem($itens);
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
                        'cd_usuario' => Auth::user()->id,
                        'created_at' => $i->DT_REGISTRO
                    ]
                );
            } catch (\Illuminate\Database\QueryException $ex) {
                die("Houve um erro ao importar os produtos da Junsoft, favor contatar desenvolvimento!");
            }
        }

        return "Items importados com sucesso!";
    }
}
