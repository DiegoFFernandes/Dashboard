<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AcompanhaOrdemController extends Controller
{
 public $user;
 public $resposta;

 public function __construct(Request $request)
 {
  $this->resposta = $request;
  $this->middleware(function ($request, $next) {
   $this->user = Auth::user();
   return $next($request);
  });

 }

 public function index(Request $request)
 {
  $uri  = $this->resposta->route()->uri();
  $user = $this->user;
  $codigo_barras = $request->codigo_barras;
  
  

  return view('admin.producao.acompanha-ordem', compact('user', 'uri', 'codigo_barras'));
 }

 public function statusOrdem(Request $request)
 {
  $uri      = $this->resposta->route()->uri();
  $user     = $this->user;
  $nr_ordem = $request->nr_ordem;
  $bindings = [$nr_ordem];

  if ($nr_ordem >= 9999999999) {
    $sem_info = 0;
    return view('admin.producao.acompanha-ordem', compact('user', 'uri', 'sem_info', 'nr_ordem'));
   }

  $sql_etapas      =
   "SELECT *
   FROM RETORNA_ACOMPANHAMENTOPNEU (:bindings) R
   ORDER BY CAST(R.O_DT_ENTRADA||' '||R.O_HR_ENTRADA AS DOM_TIMESTAMP)";

  $status_etapas = DB::connection('firebird')->select($sql_etapas, $bindings);

  if ($status_etapas === []) {
   $sem_info = $status_etapas;
   return view('admin.producao.acompanha-ordem', compact('user', 'uri', 'sem_info', 'nr_ordem'));
  }

  $sql_info_pneu = "
  Select IPP.idpedidopneu PEDIDO, OPR.id ORDEM, PP.idpessoa ID, P.NM_PESSOA CLIENTE, SP.dsservico SERVICO,
  MP.dsmodelo||' - '||M.dsmarca as MODELO, MD.dsmedidapneu MEDIDA,
  PN.nrserie SERIE, PN.nrfogo FOGO, PN.nrdot DOT, LOPR.idmontagemlotepcp LOTE, CLR.dscontrolelotepcp DSLOTE
  FROM ITEMPEDIDOPNEU IPP
  INNER JOIN PEDIDOPNEU PP ON (PP.ID = IPP.idpedidopneu)
  INNER JOIN PNEU PN on (PN.ID = IPP.idpneu)
  INNER JOIN PESSOA P ON (P.cd_pessoa = PP.idpessoa)
  INNER JOIN servicopneu SP ON (SP.id = IPP.idservicopneu)
  INNER JOIN ORDEMPRODUCAORECAP OPR ON ( OPR.iditempedidopneu = IPP.id)
  INNER JOIN MODELOPNEU MP ON ( MP.id = PN.idmodelopneu)
  INNER JOIN MARCAPNEU M ON (M.id = MP.idmarcapneu)
  INNER JOIN MEDIDAPNEU MD ON (MD.id = PN.idmedidapneu)
  LEFT JOIN LOTEPCPORDEMPRODUCAORECAP LOPR ON (LOPR.idordemproducao = OPR.ID)
  LEFT JOIN MONTAGEMLOTEPCPRECAP MLP ON (MLP.id = LOPR.idmontagemlotepcp)
  LEFT JOIN controlelotepcprecap CLR ON (CLR.id = MLP.idcontrolelotepcprecap)
  where OPR.id = :bindings";

 $info_pneu = DB::connection('firebird')->select($sql_info_pneu, $bindings);

  return view('admin.producao.acompanha-ordem', compact('user', 'uri', 'status_etapas', 'info_pneu'));
 }

 
}
