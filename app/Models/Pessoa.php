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

    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }
    public function FindPessoaJunsoftAll($search)
    {
        $query = "select first 10 p.cd_pessoa||' - '||cast(p.nm_pessoa as varchar(100) character set utf8) nm_pessoa, p.nr_cnpjcpf
                    from pessoa p
                    where p.st_ativa = 'S'
                        --and p.cd_tipopessoa in (1,3)
                        and p.nm_pessoa like '%$search%'";
        return DB::connection($this->setConnet())->select($query);
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
        $this->connection = 'mysql';
        return Pessoa::where('cpf', $cpf)
            ->exists();
    }
    public function storeData($input)
    {
        $pessoa = new Pessoa;
        $pessoa->setConnection('mysql');

        return $pessoa::create([
            'cd_empresa' => $input['cd_empresa'],
            'name' => $input['name'],
            'cpf' => $input['cpf'],
            'cd_email' => $input['cd_email'],
            'endereco' => $input['endereco'],
            'numero' => $input['numero'],
            'phone' => $input['phone'],
            'cd_usuario' => $input['cd_usuario']
        ]);
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
    public function QtdClientesFormaPagamento($dti, $dtf)
    {
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
