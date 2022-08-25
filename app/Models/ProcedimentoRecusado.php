<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcedimentoRecusado extends Model
{
    use HasFactory;
    protected $table = 'procedimento_recusados';
    protected $fillable = [
        'id_procedimento', 
        'id_user_create',
        'id_user_approver', 
        'message',
        'type'
    ];

    public function storeData($procedimento, $aprovador ){
        // return ProcedimentoRecusado::where('', '')->where()->exists();
        return ProcedimentoRecusado::create([
            'id_procedimento' => $procedimento['id'],
            'id_user_create' => $procedimento['id_user_create'],
            'id_user_approver' => $aprovador['user'], 
            'message' => $aprovador['description_recusa'],
            'type' => 'A'
        ]);
    }
    public function listaData($id){
        return ProcedimentoRecusado::select('procedimento_recusados.id_procedimento', 
        'procedimento_recusados.id_user_create', 'user_create.name as nm_create', 
        'id_user_approver', 'user_approver.name as nm_approver', 
        'procedimento_recusados.message', 'procedimento_recusados.type')
        ->join('users as user_approver', 'user_approver.id', 'procedimento_recusados.id_user_approver')
        ->join('users as user_create', 'user_create.id', 'procedimento_recusados.id_user_create')
        ->where('id_procedimento', $id)
        ->where('type', 'A')
        ->get();
    }
    public function listDataReproved($input){
        return ProcedimentoRecusado::select('procedimento_recusados.id_procedimento', 
        'procedimento_recusados.id_user_create', 'user_create.name as nm_create', 
        'id_user_approver', 'user_approver.name as nm_approver', 
        'procedimento_recusados.message', 'procedimento_recusados.type', 'procedimento_recusados.message', 'procedimento_recusados.created_at')
        ->join('users as user_approver', 'user_approver.id', 'procedimento_recusados.id_user_approver')
        ->join('users as user_create', 'user_create.id', 'procedimento_recusados.id_user_create')
        ->where('id_procedimento', $input['id_procedimento'])
        ->where('id_user_create', $input['id_user_create'])
        ->where('id_user_approver', $input['id_user_approver'])
        ->get();
    }

}
