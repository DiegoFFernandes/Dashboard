<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Procedimento extends Model
{
    use HasFactory;
    protected $table = 'procedimentos';
    protected $fillable = [
        'id_setor',
        'title',
        'description',
        'path',
        'status',
        'id_user_create'
    ];
    protected $casts = [
        'criado'  => 'date:d-m-Y',

    ];
    public function storeData($input, $file)
    {
        $procedimento = new Procedimento;
        return $procedimento::create([
            'id_setor' => $input['setor'],
            'title' => $input['title'],
            'description' => $input['description'],
            'path' => $file['file']->store('procedimentos'),
            'status' => 'A',
            'id_user_create' => Auth::user()->id
        ]);
    }
    public function listData($status, $setor)
    {
        return Procedimento::select(
            'procedimentos.id',
            'procedimentos.id_setor',
            'setors.nm_setor',
            'procedimentos.title',
            'procedimentos.description',
            'procedimentos.path',
            'i.path as path2',
            'procedimentos.id_user_create',
            'users.name',
            DB::raw("CASE procedimentos.status 
                                WHEN 'A' THEN 'Aguardando' 
                                WHEN 'P' THEN 'Em andamento' 
                                WHEN 'L' THEN 'Liberado' 
                                WHEN 'R' THEN 'Reprovado'
                                WHEN 'N' THEN 'Reanalise' 
                                END status"),
            'procedimentos.created_at as criado',
            'procedimento_public.status as public'
        )
            ->join('setors', 'setors.id', 'procedimentos.id_setor')
            ->join('users', 'users.id', 'procedimentos.id_user_create')
            ->leftJoin('procedimento_public', 'procedimento_public.id_procedimento', 'procedimentos.id')
            ->leftJoin('item_files as i', 'i.id_item', 'procedimentos.id')
            ->when($status == 'pub', function ($q) use ($setor) {
                if ($setor == 'all') {
                    return $q->where('procedimento_public.status', 'P');
                } else {
                    return $q->where('procedimento_public.status', 'P')
                        ->where('procedimentos.id_setor', $setor);
                }
            }, function ($q) use ($status) {
                return $q->whereIn('procedimentos.status', $status);
            })
            ->get();
    }
    public function updateData($input)
    {
        $procedimento = Procedimento::find($input['id_procedimento']);

        return Procedimento::where('id', $input['id_procedimento'])
            ->update(
                [
                    'id_setor' => $input['setor'],
                    'title' => $input['title'],
                    'description' => $input['description'],
                    'path' => $input['file']->store('procedimentos'),
                    'id_user_create' => Auth::user()->id
                ]
            );
    }
    public function updateIfReproved($input)
    {
        return Procedimento::where('id', $input)->update(['status' => 'N']);
    }
    public function storeUpdateFileEdit()
    {
    }
    public function countProcedimentos()
    {
        return Procedimento::select('procedimentos.id_setor', 's.nm_setor', DB::raw('count(*) as qtd'))
            ->join('setors as s', 's.id', 'procedimentos.id_setor')
            ->join('procedimento_public as p', 'p.id_procedimento', 'procedimentos.id')
            ->where('p.status', 'P')
            ->groupBy('procedimentos.id_setor', 's.nm_setor')
            ->orderBy('s.nm_setor')
            ->get();
    }
}
