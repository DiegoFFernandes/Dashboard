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
  $uri           = $this->resposta->route()->uri();
  $user_auth          = $this->user;
  $codigo_barras = $request->codigo_barras;

  return view('admin.producao.acompanha-ordem', compact('user_auth', 'uri', 'codigo_barras'));
 }

 public function statusOrdem(Request $request)
 {
  $uri      = $this->resposta->route()->uri();
  $user_auth     = $this->user;
  $nr_ordem = $request->nr_ordem;

  /* verifica se a ordem e maior que o limite //VALIDAR ISSO COM UMA FUNÇÃO TA NA GAMBIS se sim redireciona */
  if ($nr_ordem >= 9999999999) {
   $sem_info = 0;
   return view('admin.producao.acompanha-ordem', compact('user_auth', 'uri', 'sem_info', 'nr_ordem'));
  }

  /*Com a informação do usuario, consulta no banco e pega o ID do itempedidopneu*/
  $sql_idpneu       = "select IDITEMPEDIDOPNEU from ordemproducaorecap where id = ?";
  $iditempedidopneu = DB::connection('firebird_campina')->select($sql_idpneu, [$nr_ordem]);

  /*Verifica se a consulta do banco e um array vazio //VALIDAR ISSO COM UMA FUNÇÃO TA NA GAMBIS se sim redireciona */
  if ($nr_ordem >= 9999999999 || $iditempedidopneu === []) {
    $sem_info = 0;
    return view('admin.producao.acompanha-ordem', compact('user_auth', 'uri', 'sem_info', 'nr_ordem'));
  }

  /*Consulta no Banco através do IDPEDIDOPNEU buscando os setores que a ordem passou RRC016*/
  $sql_etapas =
   "SELECT *
   FROM RETORNA_ACOMPANHAMENTOPNEU (?) R
   ORDER BY CAST(R.O_DT_ENTRADA||' '||R.O_HR_ENTRADA AS DOM_TIMESTAMP)";

  $status_etapas = DB::connection('firebird_campina')->select($sql_etapas, [$iditempedidopneu[0]->IDITEMPEDIDOPNEU]);

  /*Verifica se a consulta do banco e um array vazio //VALIDAR ISSO COM UMA FUNÇÃO TA NA GAMBIS se sim redireciona */
  if ($status_etapas === []) {
   $sem_info = $status_etapas;
   return view('admin.producao.acompanha-ordem', compact('user_auth', 'uri', 'sem_info', 'nr_ordem'));
  }
  /* Consulta informações da ORDEM */
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
  where OPR.id = ?";

  $info_pneu = DB::connection('firebird_campina')->select($sql_info_pneu, [$nr_ordem]);

  return view('admin.producao.acompanha-ordem', compact('user_auth', 'uri', 'status_etapas', 'info_pneu'));
 }

 public function layout()
 {
  return view('site.producao.layout');
 }

 public function validaDados($nr_ordem, $iditempedidopneu)
 {
  $uri  = $this->resposta->route()->uri();
  $user = $this->user;
  if ($nr_ordem >= 9999999999 || $iditempedidopneu === []) {
   $sem_info = 0;
   return view('admin.producao.acompanha-ordem', compact('user_auth', 'uri', 'sem_info', 'nr_ordem'));
  }
 }
}
