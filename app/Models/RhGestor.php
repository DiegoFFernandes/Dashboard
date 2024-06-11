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
        'comp', 'cd_indicador', 'cd_area_lotacao', 'valor'
    ];
    protected $table = "rhgestor_indicador_financeiro";

    public function store($input)
    {
        try {
            $record = RhGestor::firstOrCreate(
                [
                    'comp' => $input['Competencia'],
                    'cd_indicador' => $input['CodIndicador'],
                    'cd_area_lotacao' => $input['DsLotacaoArea']
                ],
                [
                    'valor' => $input['Valor'],
                    "created_at"    =>  \Carbon\Carbon::now(), # new \Datetime()
                    "updated_at"    => \Carbon\Carbon::now(),  # new \Datetime()
                ]
            );

            if (!$record->wasRecentlyCreates) {
                $record->update([
                    'valor' => $input['Valor'],
                    "updated_at"    => \Carbon\Carbon::now(),
                ]);
            }            
        } catch (Exception $e) {
           throw $e;
        }
    }
}
