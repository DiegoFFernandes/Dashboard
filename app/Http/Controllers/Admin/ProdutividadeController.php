<?php

namespace App\Http\Controllers\admin;

use App\Charts\ProdutividadeExecutadoresChart;
use App\Http\Controllers\Controller;
use App\Models\Produtividade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdutividadeController extends Controller
{
  protected $escareacao;

  public function __construct(Produtividade $prod, Request $request)
  {
    $this->produtividade = $prod;
    $this->resposta      = $request;
    $this->middleware(function ($request, $next) {
      $this->user = Auth::user();
      return $next($request);
    });
  }

  public function index()
  {

    $exploder  = explode('/', $this->resposta->route()->uri());
    $uri       = ucfirst($exploder[2]);
    $user_auth = $this->user;
    $emp       = 3;

    if ($uri == "Quadrante-1") {

      $exame_inicial    = 'EXAMEINICIAL';
      $raspa            = 'RASPAGEMPNEU';
      $escareacao       = 'ESCAREACAOPNEU';
      $preparacao_banda = 'PREPARACAOBANDAPNEU';
      $setor            = ['setor1' => 'Exame Inicial', 'setor2' => 'Raspagem', 'setor3' => 'Escareação', 'setor4' => 'Preparação Banda'];

      $result_escareacao = $this->produtividade->executores($emp, $escareacao);
      $chart_setor1      = $this->CarregaVariavel($result_escareacao);

      $result_raspagem = $this->produtividade->executores($emp, $raspa);
      $chart_setor2    = $this->CarregaVariavel($result_raspagem);

      $result_exame_inicial = $this->produtividade->executores($emp, $exame_inicial);
      $chart_setor3         = $this->CarregaVariavel($result_exame_inicial);

      $result_preparacaobanda = $this->produtividade->executores($emp, $preparacao_banda);
      $chart_setor4           = $this->CarregaVariavel($result_preparacaobanda);

      return view(
        'admin.producao.produtividade-executores',
        compact('chart_setor1', 'chart_setor2', 'chart_setor3', 'chart_setor4', 'setor', 'user_auth', 'uri')
      );
    } elseif ($uri == "Quadrante-2") {

      $limpezamanchao = 'LIMPEZAMANCHAO';
      $aplicacaocola  = 'APLICACAOCOLAPNEU';

      $setor = ['setor1' => 'Exame Inicial', 'setor2' => 'Raspagem', 'setor3' => 'Escareação', 'setor4' => 'Preparação Banda'];

      return view('admin.producao.produtividade');
    }
  }

  public function CarregaVariavel($resultados)
  {
    foreach ($resultados as $key => $setor) {
      $keys[]            = $setor->NMEXECUTOR;
      $value_hoje[]      = $setor->HOJE;
      $value_ontem[]     = $setor->ONTEM;
      $value_anteontem[] = $setor->ANTEONTEM;
      $mediaDia[]        = 100;
    }
    $chart = $this->GeraCharts($keys, $value_hoje, $value_ontem, $value_anteontem, $mediaDia);
    return $chart;
  }

  public function GeraCharts($keys, $hoje, $ontem, $anteontem, $media)
  {
    $chart = new ProdutividadeExecutadoresChart;
    $chart->labels($keys);
    $chart->dataset('Hoje', 'bar', $hoje)
      ->options([
          'show'            => true,
          'backgroundColor' => '#3FBF3F',
        ],
      );
    $chart->dataset('Ontem', 'bar', $ontem)
      ->options([
        'show'            => true,
        'backgroundColor' => '#3F3FBF',
      ]);
    $chart->dataset('Anteontem', 'bar', $anteontem)
      ->options([
        'show'            => true,
        'backgroundColor' => '#C43535',
      ]);
    $chart->dataset('Media Dia', 'line', $media)
      ->options([
        'show'  => true,
        'color' => '#F0E118',
      ]);
    return $chart;
  }
}
