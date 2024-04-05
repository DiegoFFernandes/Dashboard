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
        $query = "select first 10 p.cd_pessoa id, 
                    cast(p.nm_pessoa as varchar(100) character set utf8) nm_pessoa, p.nr_cnpjcpf, 
                    p.ds_email, tp.cd_tipopessoa, tp.ds_tipopessoa, ep.nr_celular
                    from pessoa p
                    inner join tipopessoa tp on (tp.cd_tipopessoa = p.cd_tipopessoa)
                    inner join enderecopessoa ep on (ep.cd_pessoa = p.cd_pessoa)
                    where p.st_ativa = 'S'
                        --and p.cd_tipopessoa in (1,3)
                        and p.nm_pessoa like '%$search%'";
        return DB::connection('firebird_rede')->select($query);
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
                    --and u.cd_emprpadrao = 3
                    --and p.cd_tipopessoa = 1";

        return DB::connection($this->setConnet())->select($query);
    }
    public function QtdClientesFormaPagamento($dti, $dtf)
    {
        $query = "select ep.cd_formapagto, cast(fp.ds_formapagto as varchar(100) character set utf8) ds_formapagto, count(*) qtd
                    from enderecopessoa ep
                    inner join pessoa p on (p.cd_pessoa = ep.cd_pessoa)
                    inner join usuario u on (u.cd_usuario = p.cd_usuariocad)
                    inner join formapagto fp on (fp.cd_formapagto = ep.cd_formapagto)
                    where p.dt_cadastro between '$dti' and '$dtf'
                    and p.cd_usuariocad not in ('ti02', 'ti04')
                    --and u.cd_emprpadrao = 3
                    --and p.cd_tipopessoa = 1
                    group by ep.cd_formapagto, fp.ds_formapagto";

        return DB::connection($this->setConnet())->select($query);
    }
    public function listClientFormPgto($fp, $dti, $dtf)
    {
        $query = "select cast(p.cd_pessoa||' - '|| p.nm_pessoa as varchar(100) character set utf8) nm_pessoa, p.nr_cnpjcpf, p.cd_nmusuariocad, p.dt_cadastro
                    from enderecopessoa ep
                    inner join pessoa p on (p.cd_pessoa = ep.cd_pessoa)
                    inner join usuario u on (u.cd_usuario = p.cd_usuariocad)
                    where p.dt_cadastro between '$dti' and '$dtf'
                    and p.cd_usuariocad not in ('ti02', 'ti04')
                    --and u.cd_emprpadrao = 3
                    --and p.cd_tipopessoa in (1,3)
                    and ep.cd_formapagto = '$fp'
                    group by p.cd_pessoa, p.nm_pessoa, p.nr_cnpjcpf, p.cd_nmusuariocad, p.dt_cadastro";

        return DB::connection($this->setConnet())->select($query);
    }
    public function findTipoPessoaVencimento()
    {
        $query = "SELECT
        --C.CD_EMPRESA,
        C.CD_PESSOA,        
        CAST(P.NM_PESSOA AS VARCHAR(100) CHARACTER SET UTF8) NM_PESSOA,
        --C.NR_DOCUMENTO || '/' || C.NR_PARCELA NR_DOCUMENTO,
        --C.DT_VENCIMENTO,
        --C.CD_COBRADOR,
        --CD.VL_CREDITOACUM,
        SUM(C.VL_SALDO) VL_TOTAL,
        SUM(IIF(C.DT_VENCIMENTO < CURRENT_DATE - 5, C.VL_SALDO, 0)) VL_VENCIDO
        --C.CD_FORMAPAGTO,
        --C.cd_serie,
        --TC.CD_TIPOCONTA
        --P.CD_TIPOPESSOA
    
    FROM CONTAS C
    INNER JOIN PESSOA P ON (P.CD_PESSOA = C.CD_PESSOA)
          --INNER JOIN tipopessoa TP ON (TP.cd_tipopessoa = P.cd_tipopessoa)
    
    INNER JOIN TIPOCONTA TC ON (TC.CD_TIPOCONTA = C.CD_TIPOCONTA)   
    WHERE C.ST_CONTAS IN ('T', 'P') AND
          C.ST_INCOBRAVEL = 'N'
          --AND C.CD_EMPRESA IN (1003)
          AND
          TC.CD_TIPOCONTA IN (2, 5, 10, 12, 16, 17, 23, 28, 32, 37,
                              38, 60, 70, 71, 95, 99, 100, 1004, 1005, 1010,
                              1012)
          --AND (C.CD_COBRADOR <> 6 OR (C.CD_COBRADOR IS NULL))
          AND
          P.CD_TIPOPESSOA IN (4, 1, 3, 6, 7, 11, 14, 9, 13) AND
          C.CD_PESSOA NOT IN (1, 1000001, 1000005, 1000009, 1000010, 1003389, 1008922, 1013289, 1011617, 1014318,
                              1014319, 1018122, 1019426, 2017816, 2000001, 2000005, 2000364, 2002893, 2017209, 2017814,
                              2017815, 2017818, 2018986, 2020860, 1036590, 1036787, 1036788, 1103336) AND
          C.DT_VENCIMENTO <= CURRENT_DATE-366
         -- AND C.CD_PESSOA = 1006272
    GROUP BY
        --C.CD_EMPRESA,
        NM_PESSOA,
        C.CD_PESSOA
        --P.NM_PESSOA,
        --NR_DOCUMENTO,
        --C.DT_VENCIMENTO,
        --C.CD_FORMAPAGTO,
        --C.CD_COBRADOR,
        --C.cd_serie,
        --TP.ds_tipopessoa,
        --TC.CD_TIPOCONTA
        --P.CD_TIPOPESSOA
        --, CD.VL_CREDITOACUM
    ";
        return DB::connection('firebird_rede')->select($query);
    }

    public function UpdateTipoPessoa($pessoas)
    {
        foreach ($pessoas as $p) {
            $query = 'update pessoa p set p.cd_tipopessoa = 10 where p.cd_pessoa = ' . $p->CD_PESSOA . '';
            DB::connection('firebird_rede')->select($query);
        }
        return true;
    }
}
