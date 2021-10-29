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

        $chart = $this->CarregaVariavel($meses);

        $agenda = $this->agenda->AgendaOperadorMes($operadores);


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
            'chart'
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
}
