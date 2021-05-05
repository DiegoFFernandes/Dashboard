<?php

namespace App\Http\Controllers\admin;

use App\Charts\ProdutividadeExecutadoresChart;
use App\Http\Controllers\Controller;
use App\Models\Produtividade;

class ProdutividadeController extends Controller
{
 protected $escareacao;

 public function __construct(Produtividade $prod)
 {
  $this->esc_prod = $prod;
 }

 public function index()
 {
  $emp              = 1;
  $escareacao       = 'ESCAREACAOPNEU';
  $raspa            = 'RASPAGEMPNEU';
  $exame_inicial    = 'EXAMEINICIAL';
  $preparacao_banda = 'PREPARACAOBANDAPNEU';

  $result_escareacao = $this->esc_prod->executores($emp, $escareacao);
  $chart_escareacao  = $this->CarregaVariavel($result_escareacao);

  $result_raspagem = $this->esc_prod->executores($emp, $raspa);
  $chart_raspagem  = $this->CarregaVariavel($result_raspagem);

  $result_exame_inicial = $this->esc_prod->executores($emp, $exame_inicial);
  $chart_exame_inicial  = $this->CarregaVariavel($result_exame_inicial);

  $result_preparacaobanda = $this->esc_prod->executores($emp, $preparacao_banda);
  $chart_prep_banda  = $this->CarregaVariavel($result_preparacaobanda);

  return view('admin.producao.produtividade-executores',
   compact('chart_escareacao', 'chart_raspagem', 'chart_exame_inicial', 'chart_prep_banda'));
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
    'show'      => true,
    'backgroundColor' => '#3FBF3F',
   ],
   
  );
  $chart->dataset('Ontem', 'bar', $ontem)
   ->options([
    'show'      => true,
    'backgroundColor' => '#3F3FBF',
   ]);
  $chart->dataset('Anteontem', 'bar', $anteontem)
   ->options([
    'show'      => true,
    'backgroundColor' => '#C43535',
   ]);
  $chart->dataset('Media Dia', 'line', $media)
   ->options([
    'show'      => true,
    'color' => '#F0E118',
   ]);
  return $chart;
 }
}
