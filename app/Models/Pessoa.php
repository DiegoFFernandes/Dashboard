<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class Pessoa extends Model
{
    use HasFactory;
    protected $table = 'pessoas';
    protected $fillable = [
        'cd_empresa',
        'name',
        'cpf',
        'cd_email',
        'phone',
        'endereco',
        'numero',
        'cd_usuario'
    ];

    public function __construct()
    {
        $this->connection = 'Sempre setar o banco firebird com SetConnet';
        $this->p_dia = date("m/01/Y");
        $this->dti30dias = Config::get('constants.options.dti30dias');
        $this->dtf30dias = Config::get('constants.options.dtf30dias');
        $this->dti60dias = Config::get('constants.options.dti60dias');
        $this->dtf60dias = Config::get('constants.options.dtf60dias');
        $this->dti90dias = Config::get('constants.options.dti90dias');
        $this->dtf90dias = Config::get('constants.options.dtf90dias');
    }
    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }
    public function PessoasAll()
    {
        return Pessoa::select(
            'pessoas.id',
            'pessoas.cd_empresa',
            'pessoas.name',
            'pessoas.phone',
            'pessoas.cpf',
            'pessoas.cd_email',
            'emails.email',
            'pessoas.endereco',
            'pessoas.numero'
        )
            ->join('emails', 'emails.id', 'pessoas.cd_email')
            ->orderBy('pessoas.id')
            ->get();
    }
    public function verifyIfExists($cpf)
    {
        return Pessoa::where('cpf', $cpf)
            ->exists();
    }
    public function storeData($input)
    {
        return Pessoa::create($input);
    }
    public function updateData($id, $input)
    {
        return Pessoa::find($id)->update($input);
    }
    public function destroyData($id)
    {
        return Pessoa::find($id)->delete();
    }
    public function findEmail($inputEmail)
    {
        return Pessoa::where('cd_email', $inputEmail)->exists();
    }
    public function QtdClientesNovosMes($dti, $dtf)
    {
        $query = "select count(*) as qtd
                    from pessoa p
                    inner join usuario u on (u.cd_usuario = p.cd_usuariocad)
                    where p.dt_cadastro between '$dti' and '$dtf'
                    and p.cd_usuariocad not in ('ti02', 'ti04')
                    and u.cd_emprpadrao = 3";

        return DB::connection($this->setConnet())->select($query);
    }
    public function QtdClientesFormaPagamento($dti, $dtf){
        $query = "select ep.cd_formapagto, count(*) qtd
                    from enderecopessoa ep
                    inner join pessoa p on (p.cd_pessoa = ep.cd_pessoa)
                    inner join usuario u on (u.cd_usuario = p.cd_usuariocad)
                    where p.dt_cadastro between '$dti' and '$dtf'
                    and p.cd_usuariocad not in ('ti02', 'ti04')
                    and u.cd_emprpadrao = 3
                    group by ep.cd_formapagto";

        return DB::connection($this->setConnet())->select($query);
    }
}
