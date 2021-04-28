<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PneusLotePcpController extends Controller
{
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
  $user          = $this->user;
  $bindings      = ["nr_lote" => $request->nr_lote];
  
  $sql_pneu_lote = "
    select IPP.idpedidopneu PEDIDO, OPR.id ORDEM, PP.idpessoa ID, P.NM_PESSOA CLIENTE, SP.dsservico SERVICO,
    MP.dsmodelo||' - '||M.dsmarca as MODELO, MD.dsmedidapneu MEDIDA,
    PN.nrserie SERIE, PN.nrfogo FOGO, PN.nrdot DOT, LOPR.idmontagemlotepcp LOTE, CLR.dscontrolelotepcp DSLOTE
    FROM ITEMPEDIDOPNEU IPP
    INNER JOIN PEDIDOPNEU PP ON (PP.ID = IPP.idpedidopneu)
    INNER JOIN PNEU PN on (PN.ID = IPP.idpneu)
    INNER JOIN PESSOA P ON (P.cd_pessoa = PP.idpessoa)
    INNER JOIN ORDEMPRODUCAORECAP OPR ON ( OPR.iditempedidopneu = IPP.id)
    INNER JOIN servicopneu SP ON (SP.id = IPP.idservicopneu)
    INNER JOIN MODELOPNEU MP ON ( MP.id = PN.idmodelopneu)
    INNER JOIN MARCAPNEU M ON (M.id = MP.idmarcapneu)
    INNER JOIN MEDIDAPNEU MD ON (MD.id = PN.idmedidapneu)
    LEFT JOIN LOTEPCPORDEMPRODUCAORECAP LOPR ON (LOPR.idordemproducao = OPR.ID)
    LEFT JOIN MONTAGEMLOTEPCPRECAP MLP ON (MLP.id = LOPR.idmontagemlotepcp)
    LEFT JOIN controlelotepcprecap CLR ON (CLR.id = MLP.idcontrolelotepcprecap)
    where LOPR.idmontagemlotepcp = :nr_lote
        AND OPR.stexamefinal NOT IN ('A', 'R')";

  $pneus_lote = DB::connection('firebird')->select($sql_pneu_lote, $bindings);
  
  return view('admin.pcp.pneus-lote-pcp', compact('uri', 'user', 'pneus_lote'));
 }
}
