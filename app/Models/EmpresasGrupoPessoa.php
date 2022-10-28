<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EmpresasGrupoPessoa extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_user',
        'nr_cnpjcpf'
    ];

    public function EmpresaGrupoAll()
    {
        return EmpresasGrupoPessoa::where('id_user', Auth::user()->id)->get();
    }
    public function verifyIfExists($cnpj)
    {
        return EmpresasGrupoPessoa::where('nr_cnpjcpf', $cnpj)->exists();
    }
    public function PessoaUserAll()
    {
        return EmpresasGrupoPessoa::select('empresas_grupo_pessoas.id','empresas_grupo_pessoas.id_user','users.name', 'empresas_grupo_pessoas.nr_cnpjcpf')
        ->join('users', 'empresas_grupo_pessoas.id_user', 'users.id')
        ->get();
        ;
    }
}
