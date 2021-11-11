<?php

namespace App\Http\Controllers\Admin\Cobranca;

use App\Charts\AgendaPessoaChart;
use App\Http\Controllers\Controller;
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
        Pessoa $pessoa

    ) {
        $this->empresa  = $empresa;
        $this->resposta = $request;
        $this->agenda = $agenda;
        $this->p_dia = '1';
        $this->atual_dia = date("d");
        $this->pessoa = $pessoa;

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
        $uri       = ucfirst($exploder[1]);

        return view('admin.cobranca.detalhe-clientes-novos', compact('title_page', 'user_auth', 'uri'));
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
}
