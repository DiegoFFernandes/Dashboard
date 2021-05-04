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
   $emp = 1;

  $result_escareacao = $this->esc_prod->executores($emp);

  $chart = $this->CarregaVariavel($result_escareacao);

  return view('admin.producao.produtividade-executores', compact('chart'));
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
    'backgroundColor' => '#3FBF3F',
   ]);
  $chart->dataset('Ontem', 'bar', $ontem)
   ->options([
    'backgroundColor' => '#3F3FBF',
   ]);
  $chart->dataset('Anteontem', 'bar', $anteontem)
   ->options([
    'backgroundColor' => '#C43535',
   ]);
  $chart->dataset('Media Dia', 'line', $media)
   ->options([
    'color' => '#F0E118',
   ]);
  return $chart;
 }
}
