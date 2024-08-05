<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Stmt\TryCatch;

class RhGestor extends Model
{
    use HasFactory;

    protected $fillable = [
        'comp', 'cd_indicador', 'cd_area_lotacao', 'valor', 'cpf', 'ds_lotacao_empresa'
    ];
    protected $table = "rhgestor_indicador_financeiro";

    public function store($input)
    {
        try {
            $record = RhGestor::firstOrCreate(
                [
                    'comp' => $input['Competencia'],
                    'cd_indicador' => $input['CodIndicador'],
                    'cpf' => $input['cpf'],
                    'ds_lotacao_empresa' => $input['DsLotacao_Empresa'],
                    'cd_area_lotacao' => $input['DsLotacao_Area']
                ],
                [
                    'valor' => $input['valor'],
                    "created_at"    =>  \Carbon\Carbon::now() # new \Datetime()                    
                ]
            );

            if (!$record->wasRecentlyCreates) {
                $record->update([
                    'valor' => $input['valor'],
                    "updated_at"    => \Carbon\Carbon::now(),
                ]);
            }            
        } catch (Exception $e) {
           throw $e;
        }
    }
}
