<?php

namespace App\Http\Controllers\Admin\Cobranca;

use App\Charts\AgendaPessoaChart;
use App\Http\Controllers\Controller;
use App\Models\AgendaPessoa;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class CobrancaController extends Controller
{
    public function __construct(
        Request $request,
        Empresa $empresa,
        AgendaPessoa $agenda

    ) {
        $this->empresa  = $empresa;
        $this->resposta = $request;
        $this->agenda = $agenda;
        $this->p_dia = '1';
        $this->atual_dia = date("d");

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        $p_dia = $this->p_dia;
        $dia_atual = $this->atual_dia;
        $operadores = $this->agenda->Operadores();

        $meses = $this->agenda->AgendaOperador3Meses();

        $clientesNovos = $this->agenda->CadastroNovos();
        $chartClienteNovos = $this->CarregaVariavel($clientesNovos);
        $chart = $this->CarregaVariavel($meses);
        $agenda = $this->agenda->AgendaOperadorMes($operadores);
        $clientesNovosDia = $this->agenda->ClientesNovosMes($operadores);

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
            'clientesNovosDia'
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
        return $cdusuario;
    }
    public function AgendaData(Request $request)
    {
        $dt = $request->dt;
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
        $data = $this->agenda->AgendaMes($datai, $dataf);

        $html = '<table id="table-agenda-mes" class="table">
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
        //return response()->json($data);
    }
}
