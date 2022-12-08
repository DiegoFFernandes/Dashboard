<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketAcompanhamento extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'date:d/m/Y',
        'updated_at'  => 'date:d/m/Y',

    ];

    protected $fillable = [
        'cd_ticket',
        'cd_user_create',
        'cd_user_resp',
        'message',
        'type'
    ];

    public function storeData($input)
    {
        $ticket = new TicketAcompanhamento();

        if ($input['cd_user_resp'] == '') {
            $ticket =  $ticket::create([
                'cd_ticket' => $input['id'],
                'cd_user_create' => $input['id_user'],
                'message' => $input['observacao'],
                'type' => $input['type']
            ]);
        } else {
            $ticket =  $ticket::create([
                'cd_ticket' => $input['id'],
                'cd_user_create' => $input['id_user'],
                'cd_user_resp' => $input['cd_user_resp'],
                'message' => $input['observacao'],
                'type' => $input['type']
            ]);
        }

        return $ticket;
    }
    public function listAcompanhamentoTickets($idTicket){
        return TicketAcompanhamento::select('ticket_acompanhamentos.cd_ticket',
        'ticket_acompanhamentos.cd_user_create',
        'user_criador.name as nm_criador',
        'ticket_acompanhamentos.cd_user_resp', 
        'user_resp.name as nm_resp',
        'ticket_acompanhamentos.message',
        'ticket_acompanhamentos.type', 
        'ticket_acompanhamentos.created_at')
        ->leftJoin('users as user_criador', 'user_criador.id', 'ticket_acompanhamentos.cd_user_create')
        ->leftJoin('users as user_resp', 'user_resp.id', 'ticket_acompanhamentos.cd_user_resp')
        ->where('ticket_acompanhamentos.cd_ticket', $idTicket)
        // ->where('id_user_create', $input['id_user_create'])
        // ->where('id_user_approver', $input['id_user_approver'])
        ->get();
    }
}
