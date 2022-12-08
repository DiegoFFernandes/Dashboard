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
        'cd_barras'        
    ];

    public function maquinaAll(){
       return EtapaMaquina::select(
                'etapa_maquinas.id', 
                'etapa_maquinas.cd_empresa',  
                 DB::raw("concat(etapasproducaopneus.dsetapaempresa, ' - ' , maquinas.ds_maquina, ' - ' , etapa_maquinas.cd_seq_maq) ds_maquina"),    
                'etapa_maquinas.cd_barras')
        ->join('etapasproducaopneus', 'etapasproducaopneus.cd_etapa', 'etapa_maquinas.cd_etapa_producao')
        ->join('maquinas', 'maquinas.id', 'etapa_maquinas.cd_maquina')
        ->get();


    }
}
