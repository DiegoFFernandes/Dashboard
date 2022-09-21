<?php

namespace App\Models;

use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgendaEnvio extends Model
{
    use HasFactory;
    protected $table = 'AGENDAPESSOA';
    protected $connection;

    public function __construct()
    {
        $this->connection = 'Sempre setar o banco firebird com SetConnet';
    }

    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }

    public function searchSend($request)
    {
        //return $request->cd_number;
        $query = "select ae.nr_contexto,
                    cast(ce.ds_contexto as varchar(200) character set utf8) ds_contexto,
                    cast(ae.ds_assunto as varchar(200) character set utf8) ds_assunto,
                    cast(ae.ds_mensagem as varchar(3200) character set utf8) ds_mensagem, 
                    cast(ce.ds_contexto as varchar(200) character set utf8) ds_contexto, ae.nr_agenda, ae.nr_envio, ae.cd_pessoa, p.nm_pessoa,
        ae.bi_anexorelat, ae.dt_envio
        from agendaenvio ae
        inner join pessoa p on (p.cd_pessoa = ae.cd_pessoa)
        inner join contextoemail ce on (ce.nr_contexto = ae.nr_contexto)                
                where ae.ds_mensagem like '%$request->cd_number%' 
                " . (($request->cd_pessoa != 0) ? "and ae.cd_pessoa = $request->cd_pessoa" : "") . "
                " . (($request->nm_pessoa != 0) ? "and p.nm_pessoa like '%$request->nm_pessoa%'" : "") . "
                " . (($request->cpf_cnpj != 0) ? "and p.nr_cnpjcpf = '$request->cpf_cnpj'" : "") . "
                " . (($request->inicio_data != 0) ? "and ae.dt_envio between '$request->inicio_data' and '$request->fim_data'" : "") . "
                " . (($request->ds_email != 0) ? "and ae.ds_emaildest like '%$request->ds_email%'" : "") . "    
                " . (($request->nr_contexto != 0) ? "and ae.nr_contexto = $request->nr_contexto" : "");

        return DB::connection($this->setConnet())->select($query);
    }

    public function contextoEmail()
    {
        $query = "select ce.nr_contexto, cast(ce.ds_contexto as varchar(200) character set utf8) ds_contexto, ce.st_ativo
        from contextoemail ce
        where ce.st_ativo = 'S'
            and ce.tp_envio = 'E'
            --and ce.nr_contexto in (1,4,5,6,7,3,2,10,11,12,13,14,33,32,37,41)
        order by ce.ds_contexto";
        return DB::connection($this->setConnet())->select($query);
    }

    public function verEmail($nr_envio)
    {
        $query = "select 
                    cast(ce.ds_contexto as varchar(200) character set utf8) ds_contexto,
                    cast(ae.ds_assunto as varchar(200) character set utf8) ds_assunto,
                    cast(ae.ds_mensagem as varchar(3200) character set utf8) ds_mensagem, 
                    ae.ds_emailrem, ae.ds_emaildest, ae.dt_envio
        from agendaenvio ae
        inner join pessoa p on (p.cd_pessoa = ae.cd_pessoa)
        inner join contextoemail ce on (ce.nr_contexto = ae.nr_contexto)                
                where ae.nr_envio = $nr_envio";

        return DB::connection($this->setConnet())->select($query);
    }
}
