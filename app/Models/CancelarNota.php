<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CancelarNota extends Model
{
    use HasFactory;

    protected $fillable = [
        'cd_empresa',
        'nr_lancamento',
        'nr_nota',
        'nr_cnpjcpf',
        'nm_pessoa',
        'motivo',
        'cd_requerente',
        'cd_autorizador',
        'observacao',
    ];
    public $timestamps = true;

    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }
    
    public function SearchNota($nr_nota, $cd_empresa)
    {
        $query = "select first 1 n.cd_empresa, n.nr_lancamento, n.cd_pessoa, p.nm_pessoa, p.nr_cnpjcpf, n.nr_notafiscal
        from nota n
        inner join pessoa p on (p.cd_pessoa = n.cd_pessoa)
        where n.nr_notafiscal = $nr_nota and n.cd_empresa = $cd_empresa";
        return DB::connection($this->setConnet())->select($query);
    }

    public function store($input)
    {
        try {
            CancelarNota::updateOrInsert(
                [
                    'cd_empresa' => $input['cd_empresa'],
                    'nr_lancamento' => $input['nr_lancamento'],
                    'nr_nota'       => $input['nr_nota'],
                ],
                [
                    'cd_empresa'    => $input['cd_empresa'],
                    'nr_lancamento' => $input['nr_lancamento'],
                    'nr_nota'       => $input['nr_nota'],
                    'nr_cnpjcpf'    => $input['nr_cnpjcpf'],
                    'nm_pessoa'     => $input['nm_pessoa'],
                    'motivo'        => $input['motivo'],
                    'cd_requerente' => $input['cd_requerente'],
                    'observacao'    => $input['observacao'],
                    "created_at"    =>  \Carbon\Carbon::now(), # new \Datetime()
                    "updated_at"    => \Carbon\Carbon::now(),  # new \Datetime()
                ]
            );
        } catch (\Throwable $th) {
            return 0;
        }
        return 1;
    }
    public function list($cd_requerente)
    {
        return CancelarNota::select(
            'cancelar_notas.cd_empresa',
            'cancelar_notas.nr_nota',
            'cancelar_notas.nr_cnpjcpf',
            'cancelar_notas.nm_pessoa',
            'cancelar_notas.motivo',
            'cancelar_notas.created_at'
        )
            ->where('cd_requerente', $cd_requerente)
            ->get();
    }
    public function listAll()
    {
        return CancelarNota::select(
            'cancelar_notas.cd_empresa',
            'cancelar_notas.nr_nota',
            'cancelar_notas.nr_cnpjcpf',
            'cancelar_notas.nm_pessoa',
            'cancelar_notas.motivo',
            'users.name',
            'cancelar_notas.observacao',
            'cancelar_notas.created_at'
        )
            ->join('users', 'users.id', 'cancelar_notas.cd_requerente')
            ->get();
    }
}
