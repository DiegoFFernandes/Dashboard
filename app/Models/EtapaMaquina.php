<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EtapaMaquina extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'cd_empresa',
        'cd_etapa_producao',
        'cd_maquina',
        'cd_seq_maq',
        'cd_barras',
        'created_at',
        'updated_at'
    ];

    public function maquinaAll()
    {
        return EtapaMaquina::select(
            'etapa_maquinas.id',
            'etapa_maquinas.cd_empresa',
            DB::raw("concat(etapasproducaopneus.dsetapaempresa, ' - ' , maquinas.ds_maquina, ' - ' , etapa_maquinas.cd_seq_maq) ds_maquina"),
            'etapa_maquinas.cd_barras'
        )
            ->join('etapasproducaopneus', 'etapasproducaopneus.cd_etapa', 'etapa_maquinas.cd_etapa_producao')
            ->join('maquinas', 'maquinas.id', 'etapa_maquinas.cd_maquina')
            ->get();
    }
    public function StoreData($input)
    {
        $cd_barras = $input['empresa'] . $input['etapa'] . $input['seq_maquina'];

        $maquina = new EtapaMaquina;

        return $maquina::firstOrCreate([
            'cd_empresa' => $input['empresa'],
            'cd_etapa_producao' => $input['etapa'],
            'cd_maquina' => $input['maquina'],
            'cd_seq_maq' => $input['seq_maquina'],
            'cd_barras' => $cd_barras
        ]);
    }
    public function UpdateDate($input)
    {
        $cd_barras = $input['empresa'] . $input['etapa'] . $input['seq_maquina'];

        
        $maquina = new EtapaMaquina();
        $maquina->cd_empresa = $input['empresa'];
        $maquina->cd_etapa_producao = $input['etapa'];
        $maquina->cd_maquina = $input['maquina'];
        $maquina->cd_seq_maq = $input['seq_maquina'];
        $maquina->cd_barras = $cd_barras;
        return $maquina->update();
    }
}
