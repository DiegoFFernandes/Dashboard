<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaLotacao extends Model
{
    use HasFactory;

    protected $fillable = [
        'cd_area',
        'ds_area',
        'cd_empresa'
    ];
    protected $table = 'area_lotacao';

    public function StoreAreaLotacao($input)
    {
        $record = AreaLotacao::firstOrCreate(
            [
                'cd_area' => $input[0]
            ],
            [
                'cd_area' => $input[0],
                'ds_area' => $input[1],
                'cd_empresa' => 999,
                "created_at"    =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at"    => \Carbon\Carbon::now(),  # new \Datetime()
            ]
        );        

        return $record;
    }
}
