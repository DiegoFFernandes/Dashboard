<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Ticket extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'date:d/m/Y',
        'updated_at'  => 'date:d/m/Y',

    ];

    protected $fillable = [
        'cd_empresa',
        'urgencia',
        'cd_barras_etapa_maq',
        'tp_problema',
        'maq_parada',
        'observacao',
        'id_user', 
        'status'
    ];

    public function storeData($input)
    {
        $ticket = new Ticket();

        return $ticket::create([
            'cd_empresa' => $input['cd_empresa'],
            'urgencia' => $input['urgencia'],
            'cd_barras_etapa_maq' => $input['cd_maq'],
            'tp_problema' => $input['tp_chamado'],
            'maq_parada' => $input['mq_parada'],
            'observacao' => $input['observacao'],
            'id_user' => Auth::user()->id,
            'status' => $input['status']
        ]);
    }

    public function ListTickets($user, $input, $wpp)
    {
        return Ticket::select(
            'tickets.id',
            'tickets.cd_empresa as empresa',
            DB::raw("case tickets.urgencia 
                when 'B' then 'Baixa'
                when 'M' then 'Média'
                when 'A' then 'Alta'
            end as prioridade"),
            DB::raw("concat(epp.dsetapaempresa, ' - ' , m.ds_maquina, ' - ' ,em.cd_seq_maq ) maquina"),
            DB::raw("case tickets.tp_problema 
                when 'M' then 'Mecanico'
                when 'E' then 'Eletrico'
                when 'P' then 'Pneumatica'
                when 'O' then 'Outro'
            end tp_problema"),
            DB::raw("case tickets.maq_parada 
                when 'S' then 'Sim'
                when 'N' then 'Não'       
            end parada"),
            'tickets.id_user',
            'tickets.observacao',
            'u.name',
            DB::raw("case tickets.status 
                when 'P' then 'Pendente'
                when 'A' then 'Andamento'  
                when 'F' then 'Finalizado' 
                when 'R' then 'Reaberto'       
            end status"),
            'tickets.created_at'
        )
            ->join('etapa_maquinas as em', 'em.cd_barras', 'tickets.cd_barras_etapa_maq')
            ->join('etapasproducaopneus as epp', 'epp.cd_etapa', 'em.cd_etapa_producao')
            ->join('maquinas as m', 'm.id', 'em.cd_maquina')
            ->join('users as u', 'u.id', 'tickets.id_user')
            ->when($user == 'ver-todos-chamados', function ($q) {
                return;
            }, function ($q) {
                return $q->where('tickets.id_user', Auth::user()->id);
            })
            ->when($wpp == 'sim', function ($q) use ($input) {
                return $q->where('tickets.id', $input['id']);
            }, function ($q) {
                return;
            })
            ->get();
    }
}
