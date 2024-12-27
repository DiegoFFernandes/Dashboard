<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdiantamentoDespesas extends Model
{
    use HasFactory;
    protected $table = 'adiantamento_despesas';
    protected $fillable = [
        'cd_user',
        'tp_despesa',
        'vl_consumido',
        'dt_despesa',
        'ds_observacao'
    ];
    protected $casts = [
        'dt_despesa' => 'datetime:d/m/Y'
    ];

    public function storeDespesasConsumidas($input)
    {
        return AdiantamentoDespesas::create([
            'cd_user' => Auth::user()->id,
            'tp_despesa' => $input['tp_despesa'],
            'dt_despesa' => $input['dt_despesa'],
            'vl_consumido' => $input['vl_consumido'],
            'ds_observacao' => $input['ds_observacao'],

        ]);
    }

    public function updateData($input)
    {
        AdiantamentoDespesas::where('cd_adiantamento', $input['cd_comprovante'])->update([
            'tp_despesa' => $input['tp_despesa'],
            'dt_despesa' => $input['dt_despesa'],
            'vl_consumido' => $input['vl_consumido'],
            'ds_observacao' => $input['ds_observacao'],
            'updated_at' => now(),
        ]);
    }

    public function SaldoUtilizado()
    {

        $id = Auth::user()->id;

        return AdiantamentoDespesas::where('cd_user', $id)->where('st_visto', 'N')->sum('vl_consumido');
    }

    public function listData($user)
    {

        $id = Auth::user()->id;
        return AdiantamentoDespesas::select(
            'adiantamento_despesas.cd_adiantamento',
            'users.name',
            'adiantamento_despesas.vl_consumido',
            'adiantamento_despesas.dt_despesa',
            DB::raw("case 
                        when adiantamento_despesas.tp_despesa = 'C' then 'Combustivel'
                        when adiantamento_despesas.tp_despesa = 'A' then 'Alimentação'
                        when adiantamento_despesas.tp_despesa = 'H' then 'Hospedagem'
                        when adiantamento_despesas.tp_despesa = 'P' then 'Pedágio'
                        when adiantamento_despesas.tp_despesa = 'O' then 'Outros'
                    end tp_despesa"),
            'adiantamento_despesas.tp_despesa as despesa',
            DB::raw("case 
                        when adiantamento_despesas.st_visto = 'N' then 'Não'
                        when adiantamento_despesas.st_visto = 'S' then 'Sim'                 
                    end st_visto"),
            'adiantamento_despesas.st_visto as visto',
            'adiantamento_despesas.ds_observacao'
        )
            ->join('users', 'users.id', 'adiantamento_despesas.cd_user')
            ->when($user == '0', function ($q) use ($id) {
                $q->where('adiantamento_despesas.cd_user', $id);
            })->get();
    }

    public function DestroyData($id)
    {
        PictureComprovanteDespesa::where('cd_adiantamento', $id)->delete();
        return AdiantamentoDespesas::where('cd_adiantamento', $id)->delete();
    }

    public function updateVisto($id)
    {
        return AdiantamentoDespesas::where('cd_adiantamento', $id)->update(['st_visto' => 'S']);
    }
}
