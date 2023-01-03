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
    public function ListTickets($user, $input, $wpp, $status)
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
            ->when($status <> 0, function ($q) use ($status) {
                return $q->whereIn('tickets.status', $status['status']);
            })
            ->get();
    }
    public function ListTpProblem($status, $inicio, $fim, $empresa)
    {
        // return $status;
        return Ticket::select(
            'tickets.cd_empresa',
            'tickets.cd_barras_etapa_maq as cd_barras',
            DB::raw("concat(epp.dsetapaempresa,' - ',m.ds_maquina,' - ',em.cd_seq_maq) as ds_maquina"),
            DB::raw("count(tickets.maq_parada) as qtd")
        )
            ->join('etapa_maquinas as em', 'em.cd_barras', 'tickets.cd_barras_etapa_maq')
            ->join('etapasproducaopneus as epp', 'epp.cd_etapa', 'em.cd_etapa_producao')
            ->join('maquinas as m', 'm.id', 'em.cd_maquina')
            ->whereIn('tickets.maq_parada', $status)
            ->when($inicio <> 0, function ($q) use ($inicio, $fim) {
                return $q->whereBetween('tickets.created_at', [$inicio, $fim]);
            })
            ->when($empresa <> 0, function ($q) use ($empresa) {
                return $q->where('tickets.cd_empresa', $empresa);
            })
            ->groupBy('cd_empresa', 'cd_barras', 'epp.dsetapaempresa', 'ds_maquina', 'cd_seq_maq')
            ->orderBy('qtd', 'desc')
            ->get();
    }
    public function timeTickets($inicio, $fim, $empresa)
    {
        return Ticket::select(
            'tickets.cd_empresa',
            'tickets.id',
            DB::raw('timestampdiff(DAY, created_at, updated_at) as dias'),
            DB::raw('timestampdiff(HOUR, created_at + INTERVAL TIMESTAMPDIFF(DAY, created_at, updated_at) DAY, updated_at) as horas'),
            DB::raw('timestampdiff(MINUTE, created_at + INTERVAL TIMESTAMPDIFF(HOUR, created_at, updated_at) HOUR, updated_at) as minutos')
        )
            ->whereIn('tickets.status', ['F', 'A'])
            ->when($inicio <> 0, function ($q) use ($inicio, $fim) {
                return $q->whereBetween('tickets.created_at', [$inicio, $fim]);
            })
            ->when($empresa <> 0, function ($q) use ($empresa) {
                return $q->where('tickets.cd_empresa', $empresa);
            })
            ->get();
    }
    public function averageTickets($inicio, $fim, $empresa)
    {
        return Ticket::select(
            'tickets.cd_empresa',
            DB::raw('format((sum(timestampdiff(DAY, created_at, updated_at))*24*60 +
            sum(timestampdiff(HOUR, created_at + INTERVAL TIMESTAMPDIFF(DAY, created_at, updated_at) DAY, updated_at))*60 +
            sum(timestampdiff(MINUTE, created_at + INTERVAL TIMESTAMPDIFF(HOUR, created_at, updated_at) HOUR, updated_at))) / count(*),2) as espera_media'),
            DB::raw("case tickets.status 
                when 'P' then 'Pendente'
                when 'A' then 'Andamento'  
                when 'F' then 'Finalizado' 
                when 'R' then 'Reaberto'       
            end status"),
        )
            ->when($inicio <> 0, function ($q) use ($inicio, $fim) {
                return $q->whereBetween('tickets.created_at', [$inicio, $fim]);
            })
            ->when($empresa <> 0, function ($q) use ($empresa) {
                return $q->where('tickets.cd_empresa', $empresa);
            })
            ->groupBy('cd_empresa', 'tickets.status')
            ->get();
    }
}
