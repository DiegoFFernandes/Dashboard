<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TentativaEnvio extends Model
{
    use HasFactory;

    protected $table = 'tentativas_envio';

    protected $fillable = [
        'CD_EMPRESA',
        'NR_DOCUMENTO',
        'NR_TENTATIVAS'
    ];
    public function StoreDataTentativas($cd_empresa, $nr_documento){
        $tentativas = new TentativaEnvio;

        return $tentativas::firstOrCreate(
            [
                'CD_EMPRESA' => $cd_empresa,
                'NR_DOCUMENTO' => $nr_documento               
            ],
            [
                'CD_EMPRESA' => $cd_empresa,
                'NR_DOCUMENTO' => $nr_documento,
                'NR_TENTATIVAS' => 0,
                'created_at' => \Carbon\Carbon::now()
            ]
        );
    }

    public function searchTentativas($cd_empresa, $nr_documento){
        return TentativaEnvio::where('CD_EMPRESA', $cd_empresa)->where('NR_DOCUMENTO', $nr_documento)->first();
    }
    

    public function UpdateTentativas($cd_empresa, $nr_documento, $tentativas)
    {
        return TentativaEnvio::where('CD_EMPRESA', $cd_empresa)
            ->where('NR_DOCUMENTO', $nr_documento)
            ->update([
            'NR_TENTATIVAS' => $tentativas,
            'updated_at' => \Carbon\Carbon::now()
        ]);
    }
    
}
