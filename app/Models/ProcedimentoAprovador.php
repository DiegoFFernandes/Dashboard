<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class ProcedimentoAprovador extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_procedimento',
        'id_user',
        'aprovado',
    ];
    protected $casts = [
        'updated_at'  => 'date:d/m/Y',
        
    ];

    public function create($id_procedimento, $user)
    {
        $procAprov = new ProcedimentoAprovador;
        $procAprov->id_procedimento = $id_procedimento;
        $procAprov->id_user = $user;
        $procAprov->aprovado = 'A';
        return $procAprov->save();
    }
    public function listData($role)
    {
        return ProcedimentoAprovador::select(
            'procedimento_aprovadors.id_procedimento',
            'procedimento_aprovadors.id_user',
            DB::raw("CASE procedimento_aprovadors.aprovado 
                                WHEN 'A' THEN 'Aguardando' 
                                WHEN 'N' THEN 'Reanalise' 
                                else  procedimento_aprovadors.aprovado
                                END aprovado"),
            'procedimentos.id_setor',
            'setors.nm_setor',
            'procedimentos.title',
            'procedimentos.description',
            'procedimentos.path',
            'procedimentos.id_user_create',
            'procedimentos.status',
            'users.name'
        )
            ->join('procedimentos', 'procedimentos.id', 'procedimento_aprovadors.id_procedimento')
            ->join('setors', 'setors.id', 'procedimentos.id_setor')
            ->join('users', 'users.id', 'procedimento_aprovadors.id_user')
            ->when($role == '1', function ($q) {
                return;
            }, function ($q) {
                return $q->where('users.id', Auth::user()->id);
            })
            ->whereIn('procedimento_aprovadors.aprovado', ['A', 'N'])

            ->get();
    }
    public function updateData($input)
    {
        $aprovador = ProcedimentoAprovador::where('id_procedimento', $input['id'])
            ->where('id_user', $input['user'])
            ->firstOrFail();
        if ($input['status'] == 'R') {
            $aprovador->aprovado = $input['status'];
        }
        $aprovador->aprovado = $input['status'];
        return $aprovador->save();
    }
    public function updateIfReproved($input)
    {
        return ProcedimentoAprovador::where('id_procedimento', $input)->update(['aprovado' => 'N']);
    }
    public function verifyIfReleased($input)
    {
        return ProcedimentoAprovador::select('aprovado')
            ->where('id_procedimento', $input)
            ->groupBy('id_procedimento', 'aprovado')->get();
    }
    public function updateIfCreateLargerDays()
    {
        return ProcedimentoAprovador::where('aprovado', '<>', 'L')
            ->whereDate('created_at', '<=',  Config::get('constants.options.dt10days'))
            ->update(['aprovado' => 'L']);
    }
    public function outStandingApprover($id)
    {
        return ProcedimentoAprovador::select(
            'procedimento_aprovadors.id_procedimento',
            'users.name',
            DB::raw("CASE procedimento_aprovadors.aprovado 
                            WHEN 'L' THEN 'LIBERADO'
                            WHEN 'A' THEN 'AGUARDANDO'
                            END status"),
            'procedimento_aprovadors.updated_at'
        )
            ->join('users', 'users.id', 'procedimento_aprovadors.id_user')
            ->where('id_procedimento', $id)->get();
    }
}
