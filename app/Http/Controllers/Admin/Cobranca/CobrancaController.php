<?php

namespace App\Http\Controllers\Admin\Cobranca;

use App\Charts\AgendaPessoaChart;
use App\Http\Controllers\Controller;
use App\Models\AgendaEnvio;
use App\Models\AgendaPessoa;
use App\Models\Empresa;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class CobrancaController extends Controller
{
    public function __construct(
        Request $request,
        Empresa $empresa,
        AgendaPessoa $agenda,
        AgendaEnvio $envio,
        Pessoa $pessoa

    ) {
        $this->empresa  = $empresa;
        $this->resposta = $request;
        $this->agenda = $agenda;
        $this->p_dia = '1';
        $this->atual_dia = date("d");
        $this->pessoa = $pessoa;
        $this->envio = $envio;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        //Data inicial e Final do Mes
        $dti = Config::get('constants.options.dti');
        $dtf = Config::get('constants.options.dtf');

        $operadores = $this->agenda->Operadores();
        $meses = $this->agenda->AgendaOperador3Meses();
        $clientesNovos = $this->agenda->CadastroNovos();
        $chartClienteNovos = $this->CarregaVariavel($clientesNovos);
        $chart = $this->CarregaVariavel($meses);
        $agenda = $this->agenda->AgendaOperadorMes($operadores);
        $clientesNovosDia = $this->agenda->ClientesNovos3Mes($operadores);
        $qtdClientesNovosMes = $this->pessoa->QtdClientesNovosMes($dti, $dtf);
        $qtdClientesFormaPagamento  = $this->pessoa->QtdClientesFormaPagamento($dti, $dtf);

        $title_page   = 'Agenda';
        $user_auth    = $this->user;
        $uri         = $this->resposta->route()->uri();

        return view('admin.cobranca.index', compact(
            'title_page',
            'user_auth',
            'uri',
            'agenda',
            'operadores',
            'meses',
            'chart',
            'clientesNovos',
            'chartClienteNovos',
            'clientesNovosDia',
            'qtdClientesNovosMes',
            'qtdClientesFormaPagamento'
        ));
    }
    public function CarregaVariavel($meses)
    {
        foreach ($meses as $m) {
            $keys[] = $m->NM_USUARIO;
            $mes1[] = $m->MES1;
            $mes2[] = $m->MES2;
            $mes3[] = $m->MES3;
        }

        return $this->GeraCharts($keys, $mes1, $mes2, $mes3);
    }
    public function GeraCharts($keys, $mes1, $mes2, $mes3)
    {
        $chart = new AgendaPessoaChart();
        $chart->labels($keys);
        $chart->displaylegend(true);
        $chart->height(180);

        $chart->dataset(Config::get('constants.meses.nMes30'), 'bar', $mes1)
            ->color("rgb(255, 99, 132)")
            ->backgroundcolor("rgb(255, 99, 132)")
            ->fill(false)
            ->linetension(0.1)
            ->dashed([5]);
        $chart->dataset(Config::get('constants.meses.nMes60'), 'bar', $mes2)
            ->options([
                'show'            => true,
                'backgroundColor' => '#D1F2EB',
                'displaylegend' => false
            ]);
        $chart->dataset(Config::get('constants.meses.nMes90'), 'bar', $mes3)->options([
            'show'            => true,
            'backgroundColor' => '#D5DBDB',
        ]);

        return $chart;
    }
    public function DetalheAgenda($cdusuario, $dt)
    {
        $title_page   = 'Detalhes Agenda';
        $user_auth    = $this->user;
        $exploder = explode('/', $this->resposta->route()->uri());
        $uri       = ucfirst($exploder[1]);

        $dt  = date('m-d-Y', strtotime($dt));
        $detalhes = $this->agenda->Detalhe($cdusuario, $dt);

        return view('admin.cobranca.detalhe-agenda', compact('detalhes', 'user_auth', 'uri'));
    }
    public function ClientesNovos($cdusuario, $dt)
    {
        $title_page   = 'Agenda';
        $user_auth    = $this->user;
        $exploder = explode('/', $this->resposta->route()->uri());
        $detalhesOperador = $this->agenda->DetalheCadastroClienteOperador($dt, $cdusuario);
        $uri       = ucfirst($exploder[1]);

        return view('admin.cobranca.detalhe-clientes-novos', compact('title_page', 'user_auth', 'uri', 'detalhesOperador'));
    }
    public function ClientesNovosMes(Request $request)
    {
        $dt = $this->VerificaData($request->dt);
        $datai = $dt[0];
        $dataf = $dt[1];
        $data = $this->agenda->ClientesNovosMes($datai, $dataf);

        foreach ($data as $d) {
            $labels[] = $d->NM_USUARIO;
            $mes[] = $d->MES;
        }
        $chartest = new AgendaPessoaChart();
        $chartest->labels(1, 2, 3, 4);
        $chartest->dataset('My dataset 1', 'line', [1, 2, 3, 4]);

        $html = '<table id="table-clientes-novos-mes" class="table table-striped">
                    <thead>
                    <tr>
                        <th style="width:100px">Cód. Usuario</th>
                        <th style="width:100px">Nome</th>
                        <th>Quantidade</th>
                    </tr>
                    </thead>
                <tbody>';
        foreach ($data as $d) {
            $html .= '
                <tr>
                    <td>' . $d->CD_USUARIO . '</td>
                    <td>' . $d->NM_USUARIO . '</td>
                    <td>' . $d->MES . '</td>
                </tr>';
        }
        $html .= '</tbody>';
        // return $html;
        return response()->json(['html' => $html, 'labels' => $labels, 'qtd' => $mes]);
    }
    public function AgendaData(Request $request)
    {
        $dt = $this->VerificaData($request->dt);
        $datai = $dt[0];
        $dataf = $dt[1];
        $data = $this->agenda->AgendaMes($datai, $dataf);
        $html = '<table id="table-agenda-mes" class="table table-striped">
                <thead>
                <tr>
                    <th style="width:100px">Cód. Usuario</th>
                    <th style="width:100px">Nome</th>
                    <th>Quantidade</th>
                </tr>
                </thead>
                <tbody>';
        foreach ($data as $d) {
            $html .= '
                <tr>
                    <td>' . $d->CD_USUARIO . '</td>
                    <td>' . $d->NM_USUARIO . '</td>
                    <td>' . $d->MES . '</td>
                </tr>';
        }
        $html .= '</tbody>';
        return $html;
    }
    private function VerificaData($dt)
    {
        if ($dt == 120) {
            $datai = Config::get('constants.options.dti120dias');
            $dataf = Config::get('constants.options.dtf120dias');
        } elseif ($dt == 150) {
            $datai = Config::get('constants.options.dti150dias');
            $dataf = Config::get('constants.options.dtf150dias');
        } elseif ($dt == 180) {
            $datai = Config::get('constants.options.dti180dias');
            $dataf = Config::get('constants.options.dtf180dias');
        } elseif ($dt == 210) {
            $datai = Config::get('constants.options.dti210dias');
            $dataf = Config::get('constants.options.dtf210dias');
        } elseif ($dt == 240) {
            $datai = Config::get('constants.options.dti240dias');
            $dataf = Config::get('constants.options.dtf240dias');
        } elseif ($dt == 270) {
            $datai = Config::get('constants.options.dti270dias');
            $dataf = Config::get('constants.options.dtf270dias');
        } elseif ($dt == 300) {
            $datai = Config::get('constants.options.dti300dias');
            $dataf = Config::get('constants.options.dtf300dias');
        }

        return array($datai, $dataf);
    }
    public function testeChart()
    {
        $api = url(route('cobranca.chart-api'));
        $chart = new AgendaPessoaChart;
        $chart->labels(['One', 'Two', 'Three', 'Four']);
        // $chart->labels(['One', 'Two', 'Three', 'Four'])->load($api);
        $chart->dataset('My dataset', 'line', [1, 2, 3, 4]);

        return view('admin.cobranca.teste', compact('chart'));
    }
    public function chartLineAjax(Request $request)
    {
        $chart = new AgendaPessoaChart;

        $chart->dataset('New User Register Chart', 'line', [1, 2, 3, 4])
            ->options([
                'fill' => 'true',
                'borderColor' => '#51C1C0'
            ]);

        return $chart->api();
    }
    public function searchEnvio()
    {        
        $title_page  = 'Agenda';
        $user_auth   = $this->user;
        $uri         = $this->resposta->route()->uri();
        $contexto    = $this->envio->contextoEmail();
        // return $search = $this->envio->searchSend(7369, 201);

        return view("admin.cobranca.search-follow-up", compact('uri', 'contexto'));
    }
    public function getSearchEnvio(Request $request)
    {
        $search = $this->envio->searchSend($request);

        //return $search[0]->DS_MENSAGEM;
        
        $html = '<table id="table-search" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>                    
                            <th>Descrição</th>
                            <th>Nr Envio</th>
                            <th>Nr Agenda</th>
                            <th>Cd Pessoa</th>
                            <th>Nome</th>
                            <th>Dt Envio</th>
                            <th>Anexo</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach ($search as $s) {
            $exploder        = explode('\\', $s->BI_ANEXORELAT);
            $anexo = is_null($s->BI_ANEXORELAT) ? 'não existe' : '<a href="file:///\\172.29.0.2/'.$exploder[2].'/'.$exploder[3].'/'.$exploder[4].'/'.$exploder[5].'" class="btn btn-primary">Anexo</a>';
            $email = '<button class="btn btn-default ver-email" data-id="'.$s->NR_ENVIO.'" aria-hidden="true"> Ver E-mail </button>';
            //var_dump($exploder);
            $html .= '
                    <tr>                    
                        <td>' . $s->DS_CONTEXTO . '</td>
                        <td>' . $s->NR_ENVIO . '</td>
                        <td>' . $s->NR_AGENDA . '</td>
                        <td>' . $s->CD_PESSOA . '</td>
                        <td>' . $s->NM_PESSOA . '</td>
                        <td>' . $s->DT_ENVIO . '</td>
                        <td>' . $anexo . '</td>
                        <td>' . $email. '</td>
                    </tr>';
        }
        $html .= '</tbody>
                ';
        return $html;
    }
    public function getEmailEnvio($nr_envio){
        $email = $this->envio->verEmail($nr_envio);
        return $email;
    }
}
