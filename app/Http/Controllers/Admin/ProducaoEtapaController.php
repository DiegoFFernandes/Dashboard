<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Producao;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProducaoEtapaController extends Controller
{
        public $user;
        public $request;

        public function __construct(
                Request $request,
                Producao $producao,
                Empresa $empresa
        ) {
                $this->request = $request;
                $this->producao = $producao;
                $this->empresa  = $empresa;
                $this->middleware(function ($request, $next) {
                        $this->user = Auth::user();
                        return $next($request);
                });
        }

        public function index(Request $request)
        {
                $current_date = date('m-d-Y');
                if ($_POST) {
                        $date     = $request->date;
                        $bindings = [
                                'data_Iexini'   => $date . ' 00:00:00', 'data_Fexini'   => $date . ' 23:59:59',
                                'data_Iraspa'   => $date . ' 00:00:00', 'data_Fraspa'   => $date . ' 23:59:59',
                                'data_Ibanda'   => $date . ' 00:00:00', 'data_Fbanda'   => $date . ' 23:59:59',
                                'data_Iescar'   => $date . ' 00:00:00', 'data_Fescar'   => $date . ' 23:59:59',
                                'data_Icobert'  => $date . ' 00:00:00', 'data_Fcobert'  => $date . ' 23:59:59',
                                'data_Ivulc'    => $date . ' 00:00:00', 'data_Fvulc'    => $date . ' 23:59:59',
                                'data_Iexfin'   => $date . ' 00:00:00', 'data_Fexfin'   => $date . ' 23:59:59',
                                'data_Imanchao' => $date . ' 00:00:00', 'data_Fmanchao' => $date . ' 23:59:59',
                                'data_Icola'    => $date . ' 00:00:00', 'data_Fcola'    => $date . ' 23:59:59',
                                'data_Iconser'  => $date . ' 00:00:00', 'data_Fconser'  => $date . ' 23:59:59',
                                'data_Iextru'   => $date . ' 00:00:00', 'data_Fextru'   => $date . ' 23:59:59',
                                'data_Imonta'   => $date . ' 00:00:00', 'data_Fmonta'   => $date . ' 23:59:59',
                                'data_Ienvel'   => $date . ' 00:00:00', 'data_Fenvel'   => $date . ' 23:59:59',
                                'data_Idesen'   => $date . ' 00:00:00', 'data_Fdesen'   => $date . ' 23:59:59',
                                'data_Iuti'     => $date . ' 00:00:00', 'data_Futi'     => $date . ' 23:59:59',
                                'data_IAz'      => $date . ' 00:00:00', 'data_FAz'      => $date . ' 23:59:59',
                        ];
                } else {
                        $bindings = [
                                'data_Iexini'   => $current_date . ' 00:00:00', 'data_Fexini'   => $current_date . ' 23:59:59',
                                'data_Iraspa'   => $current_date . ' 00:00:00', 'data_Fraspa'   => $current_date . ' 23:59:59',
                                'data_Ibanda'   => $current_date . ' 00:00:00', 'data_Fbanda'   => $current_date . ' 23:59:59',
                                'data_Iescar'   => $current_date . ' 00:00:00', 'data_Fescar'   => $current_date . ' 23:59:59',
                                'data_Icobert'  => $current_date . ' 00:00:00', 'data_Fcobert'  => $current_date . ' 23:59:59',
                                'data_Ivulc'    => $current_date . ' 00:00:00', 'data_Fvulc'    => $current_date . ' 23:59:59',
                                'data_Iexfin'   => $current_date . ' 00:00:00', 'data_Fexfin'   => $current_date . ' 23:59:59',
                                'data_Imanchao' => $current_date . ' 00:00:00', 'data_Fmanchao' => $current_date . ' 23:59:59',
                                'data_Icola'    => $current_date . ' 00:00:00', 'data_Fcola'    => $current_date . ' 23:59:59',
                                'data_Iconser'  => $current_date . ' 00:00:00', 'data_Fconser'  => $current_date . ' 23:59:59',
                                'data_Iextru'   => $current_date . ' 00:00:00', 'data_Fextru'   => $current_date . ' 23:59:59',
                                'data_Imonta'   => $current_date . ' 00:00:00', 'data_Fmonta'   => $current_date . ' 23:59:59',
                                'data_Ienvel'   => $current_date . ' 00:00:00', 'data_Fenvel'   => $current_date . ' 23:59:59',
                                'data_Idesen'   => $current_date . ' 00:00:00', 'data_Fdesen'   => $current_date . ' 23:59:59',
                                'data_Iuti'     => $current_date . ' 00:00:00', 'data_Futi'     => $current_date . ' 23:59:59',
                                'data_IAz'      => $current_date . ' 00:00:00', 'data_FAz'      => $current_date . ' 23:59:59',
                        ];
                }

                $uri  = $this->request->route()->uri();
                $user_auth = $this->user;
                $sql  = "SELECT
                                SUM(X.NR_EXINI) NR_EXINI,
                                SUM(X.NR_RASPA) NR_RASPA,
                                SUM(X.NR_BANDA) NR_BANDA,
                                SUM(X.NR_ESCAR) NR_ESCAR,
                                SUM(X.NR_COBERT) NR_COBERT,
                                SUM(X.NR_VULCA) NR_VULCA,
                                SUM(X.NR_EXFIN) NR_EXFIN,
                                SUM(X.NR_MACHAO) NR_MACHAO,
                                SUM(X.NR_COLA) NR_COLA,
                                SUM(X.NR_CONSER) NR_CONSER,
                                SUM(X.NR_EXTRU) NR_EXTRU,
                                SUM(X.NR_MONTA) NR_MONTA,
                                SUM(X.NR_ENVEL) NR_ENVEL,
                                SUM(X.NR_DESENV) NR_DESENV,
                                SUM(X.NR_UTI) NR_UTI,
                                SUM(X.NR_AZ) NR_AZ
                                FROM (

                                SELECT
                                        COUNT(OPR.ID) NR_EXINI,
                                        NULL NR_RASPA,
                                        NULL NR_BANDA,
                                        NULL NR_ESCAR,
                                        NULL NR_COBERT,
                                        NULL NR_VULCA,
                                        NULL NR_EXFIN,
                                        NULL NR_MACHAO,
                                        NULL NR_COLA,
                                        NULL NR_CONSER,
                                        NULL NR_EXTRU,
                                        NULL NR_MONTA,
                                        NULL NR_ENVEL,
                                        NULL NR_DESENV,
                                        NULL NR_UTI,
                                        NULL NR_AZ
                                FROM ORDEMPRODUCAORECAP OPR
                                INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
                                INNER JOIN PEDIDOPNEU P ON (P.ID = IPP.IDPEDIDOPNEU)
                                INNER JOIN ITEM II ON (II.CD_ITEM = IPP.IDSERVICOPNEU)
                                INNER JOIN EXAMEINICIAL EI ON (EI.IDORDEMPRODUCAORECAP = OPR.ID)
                                WHERE P.idempresa IN (1,2,3)
                                AND OPR.STORDEM <> 'C'
                                AND P.STPEDIDO <> 'C'
                                AND EI.ST_ETAPA = 'F'
                                AND EI.ID NOT IN (SELECT IDEXAMEINICIAL FROM MOTIVORECUSAEXAME)
                                AND EI.DTFIM between :data_Iexini and :data_Fexini

                                UNION ALL

                                SELECT
                                        NULL NR_EXINI,
                                        COUNT(OPR.ID) NR_RASPA,
                                        NULL NR_BANDA,
                                        NULL NR_ESCAR,
                                        NULL NR_COBERT,
                                        NULL NR_VULCA,
                                        NULL NR_EXFIN,
                                        NULL NR_MACHAO,
                                        NULL NR_COLA,
                                        NULL NR_CONSER,
                                        NULL NR_EXTRU,
                                        NULL NR_MONTA,
                                        NULL NR_ENVEL,
                                        NULL NR_DESENV,
                                        NULL NR_UTI,
                                        NULL NR_AZ
                                FROM ORDEMPRODUCAORECAP OPR
                                INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
                                INNER JOIN PEDIDOPNEU P ON (P.ID = IPP.IDPEDIDOPNEU)
                                INNER JOIN RASPAGEMPNEU R ON (R.IDORDEMPRODUCAORECAP = OPR.ID)
                                INNER JOIN ITEM II ON (II.CD_ITEM = IPP.IDSERVICOPNEU)
                                INNER JOIN EXAMEINICIAL EI ON(EI.IDORDEMPRODUCAORECAP = OPR.ID)
                                WHERE P.IDEMPRESA IN (1,2,3)
                                AND OPR.STORDEM <> 'C'
                                AND P.STPEDIDO <> 'C'
                                AND R.ST_ETAPA = 'F'
                                AND EI.ID NOT IN (SELECT IDEXAMEINICIAL FROM MOTIVORECUSAEXAME)
                                AND R.DTFIM between :data_Iraspa and :data_Fraspa

                                UNION ALL

                                SELECT
                                        NULL NR_EXINI,
                                        NULL NR_RASPA,
                                        COUNT(OPR.ID) NR_BANDA,
                                        NULL NR_ESCAR,
                                        NULL NR_COBERT,
                                        NULL NR_VULCA,
                                        NULL NR_EXFIN,
                                        NULL NR_MACHAO,
                                        NULL NR_COLA,
                                        NULL NR_CONSER,
                                        NULL NR_EXTRU,
                                        NULL NR_MONTA,
                                        NULL NR_ENVEL,
                                        NULL NR_DESENV,
                                        NULL NR_UTI,
                                        NULL NR_AZ
                                FROM ORDEMPRODUCAORECAP OPR
                                INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
                                INNER JOIN PEDIDOPNEU P ON (P.ID = IPP.IDPEDIDOPNEU)
                                INNER JOIN PREPARACAOBANDAPNEU B ON (B.IDORDEMPRODUCAORECAP = OPR.ID)
                                INNER JOIN ITEM II ON (II.CD_ITEM = IPP.IDSERVICOPNEU)
                                WHERE P.IDEMPRESA IN (1,2,3)
                                AND OPR.STORDEM <> 'C'
                                AND P.STPEDIDO <> 'C'
                                AND B.ST_ETAPA = 'F'
                                AND B.DTFIM between :data_Ibanda and :data_Fbanda

                                UNION ALL

                                SELECT
                                        NULL NR_EXINI,
                                        NULL NR_RASPA,
                                        NULL NR_BANDA,
                                        COUNT(OPR.ID) NR_ESCAR,
                                        NULL NR_COBERT,
                                        NULL NR_VULCA,
                                        NULL NR_EXFIN,
                                        NULL NR_MACHAO,
                                        NULL NR_COLA,
                                        NULL NR_CONSER,
                                        NULL NR_EXTRU,
                                        NULL NR_MONTA,
                                        NULL NR_ENVEL,
                                        NULL NR_DESENV,
                                        NULL NR_UTI,
                                        NULL NR_AZ
                                FROM ORDEMPRODUCAORECAP OPR
                                INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
                                INNER JOIN PEDIDOPNEU P ON (P.ID = IPP.IDPEDIDOPNEU)
                                INNER JOIN ESCAREACAOPNEU E ON (E.IDORDEMPRODUCAORECAP = OPR.ID)
                                INNER JOIN ITEM II ON (II.CD_ITEM = IPP.IDSERVICOPNEU)
                                INNER JOIN EXAMEINICIAL EI ON(EI.IDORDEMPRODUCAORECAP = OPR.ID)
                                WHERE P.IDEMPRESA IN (1,2,3)
                                AND OPR.STORDEM <> 'C'
                                AND P.STPEDIDO <> 'C'
                                AND E.ST_ETAPA = 'F'
                                AND EI.ID NOT IN (SELECT IDEXAMEINICIAL FROM MOTIVORECUSAEXAME)
                                AND E.DTFIM between :data_Iescar and :data_Fescar

                                UNION ALL

                                SELECT
                                        NULL NR_EXINI,
                                        NULL NR_RASPA,
                                        NULL NR_BANDA,
                                        NULL NR_ESCAR,
                                        COUNT(OPR.ID) NR_COBERT,
                                        NULL NR_VULCA,
                                        NULL NR_EXFIN,
                                        NULL NR_MACHAO,
                                        NULL NR_COLA,
                                        NULL NR_CONSER,
                                        NULL NR_EXTRU,
                                        NULL NR_MONTA,
                                        NULL NR_ENVEL,
                                        NULL NR_DESENV,
                                        NULL NR_UTI,
                                        NULL NR_AZ
                                FROM ORDEMPRODUCAORECAP OPR
                                INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
                                INNER JOIN PEDIDOPNEU P ON (P.ID = IPP.IDPEDIDOPNEU)
                                INNER JOIN EMBORRACHAMENTO EM ON (EM.IDORDEMPRODUCAORECAP = OPR.ID)
                                INNER JOIN ITEM II ON (II.CD_ITEM = IPP.IDSERVICOPNEU)
                                INNER JOIN EXAMEINICIAL EI ON(EI.IDORDEMPRODUCAORECAP = OPR.ID)
                                WHERE P.IDEMPRESA IN (1,2,3)
                                AND OPR.STORDEM <> 'C'
                                AND P.STPEDIDO <> 'C'
                                AND EM.ST_ETAPA = 'F'
                                AND EI.ID NOT IN (SELECT IDEXAMEINICIAL FROM MOTIVORECUSAEXAME)
                                AND EM.DTFIM between :data_Icobert and :data_Fcobert


                                UNION ALL

                                SELECT
                                        NULL NR_EXINI,
                                        NULL NR_RASPA,
                                        NULL NR_BANDA,
                                        NULL NR_ESCAR,
                                        NULL NR_COBERT,
                                        COUNT(OPR.ID) NR_VULCA,
                                        NULL NR_EXFIN,
                                        NULL NR_MACHAO,
                                        NULL NR_COLA,
                                        NULL NR_CONSER,
                                        NULL NR_EXTRU,
                                        NULL NR_MONTA,
                                        NULL NR_ENVEL,
                                        NULL NR_DESENV,
                                        NULL NR_UTI,
                                        NULL NR_AZ
                                FROM ORDEMPRODUCAORECAP OPR
                                INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
                                INNER JOIN PEDIDOPNEU P ON (P.ID = IPP.IDPEDIDOPNEU)
                                INNER JOIN VULCANIZACAO V ON (V.IDORDEMPRODUCAORECAP = OPR.ID)
                                INNER JOIN ITEM II ON (II.CD_ITEM = IPP.IDSERVICOPNEU)
                                WHERE P.IDEMPRESA IN (1,2,3)
                                AND OPR.STORDEM <> 'C'
                                AND P.STPEDIDO <> 'C'
                                AND V.ST_ETAPA = 'F'
                                AND V.DTFIM between :data_Ivulc and :data_Fvulc

                                UNION ALL

                                SELECT
                                        NULL NR_EXINI,
                                        NULL NR_RASPA,
                                        NULL NR_BANDA,
                                        NULL NR_ESCAR,
                                        NULL NR_COBERT,
                                        NULL NR_VULCA,
                                        COUNT(OPR.ID) NR_EXFIN,
                                        NULL NR_MACHAO,
                                        NULL NR_COLA,
                                        NULL NR_CONSER,
                                        NULL NR_EXTRU,
                                        NULL NR_MONTA,
                                        NULL NR_ENVEL,
                                        NULL NR_DESENV,
                                        NULL NR_UTI,
                                        NULL NR_AZ
                                FROM ORDEMPRODUCAORECAP OPR
                                INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
                                INNER JOIN PEDIDOPNEU P ON (P.ID = IPP.IDPEDIDOPNEU)
                                INNER JOIN EXAMEFINALPNEU EF ON (EF.IDORDEMPRODUCAORECAP = OPR.ID)
                                INNER JOIN ITEM II ON (II.CD_ITEM = IPP.IDSERVICOPNEU)
                                INNER JOIN EXAMEINICIAL EI ON(EI.IDORDEMPRODUCAORECAP = OPR.ID)
                                WHERE P.IDEMPRESA IN (1,2,3)
                                AND OPR.STORDEM <> 'C'
                                AND P.STPEDIDO <> 'C'
                                AND EF.ST_ETAPA = 'F'
                                AND EI.ID NOT IN (SELECT IDEXAMEINICIAL FROM MOTIVORECUSAEXAME)
                                AND EF.DTFIM between :data_Iexfin and :data_Fexfin

                                UNION ALL

                                SELECT
                                        NULL NR_EXINI,
                                        NULL NR_RASPA,
                                        NULL NR_BANDA,
                                        NULL NR_ESCAR,
                                        NULL NR_COBERT,
                                        NULL NR_VULCA,
                                        NULL NR_EXFIN,
                                        COUNT(OPR.ID) NR_MACHAO,
                                        NULL NR_COLA,
                                        NULL NR_CONSER,
                                        NULL NR_EXTRU,
                                        NULL NR_MONTA,
                                        NULL NR_ENVEL,
                                        NULL NR_DESENV,
                                        NULL NR_UTI,
                                        NULL NR_AZ
                                FROM ORDEMPRODUCAORECAP OPR
                                INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
                                INNER JOIN PEDIDOPNEU P ON (P.ID = IPP.IDPEDIDOPNEU)
                                INNER JOIN LIMPEZAMANCHAO LM ON (LM.IDORDEMPRODUCAORECAP = OPR.ID)
                                INNER JOIN ITEM II ON (II.CD_ITEM = IPP.IDSERVICOPNEU)
                                INNER JOIN EXAMEINICIAL EI ON(EI.IDORDEMPRODUCAORECAP = OPR.ID)
                                WHERE P.IDEMPRESA IN (1,2,3)
                                AND OPR.STORDEM <> 'C'
                                AND P.STPEDIDO <> 'C'
                                AND LM.ST_ETAPA = 'F'
                                AND EI.ID NOT IN (SELECT IDEXAMEINICIAL FROM MOTIVORECUSAEXAME)
                                AND LM.DTFIM between :data_Imanchao and :data_Fmanchao

                                UNION ALL

                                SELECT
                                        NULL NR_EXINI,
                                        NULL NR_RASPA,
                                        NULL NR_BANDA,
                                        NULL NR_ESCAR,
                                        NULL NR_COBERT,
                                        NULL NR_VULCA,
                                        NULL NR_EXFIN,
                                        NULL NR_MACHAO,
                                        COUNT(OPR.ID) NR_COLA,
                                        NULL NR_CONSER,
                                        NULL NR_EXTRU,
                                        NULL NR_MONTA,
                                        NULL NR_ENVEL,
                                        NULL NR_DESENV,
                                        NULL NR_UTI,
                                        NULL NR_AZ
                                FROM ORDEMPRODUCAORECAP OPR
                                INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
                                INNER JOIN PEDIDOPNEU P ON (P.ID = IPP.IDPEDIDOPNEU)
                                INNER JOIN APLICACAOCOLAPNEU AC ON (AC.IDORDEMPRODUCAORECAP = OPR.ID)
                                INNER JOIN ITEM II ON (II.CD_ITEM = IPP.IDSERVICOPNEU)
                                WHERE P.IDEMPRESA IN (1,2,3)
                                AND OPR.STORDEM <> 'C'
                                AND P.STPEDIDO <> 'C'
                                AND AC.ST_ETAPA = 'F'
                                AND AC.DTFIM between :data_Icola and :data_Fcola

                                UNION ALL

                                SELECT
                                        NULL NR_EXINI,
                                        NULL NR_RASPA,
                                        NULL NR_BANDA,
                                        NULL NR_ESCAR,
                                        NULL NR_COBERT,
                                        NULL NR_VULCA,
                                        NULL NR_EXFIN,
                                        NULL NR_MACHAO,
                                        NULL NR_COLA,
                                        COUNT(OPR.ID) NR_CONSER,
                                        NULL NR_EXTRU,
                                        NULL NR_MONTA,
                                        NULL NR_ENVEL,
                                        NULL NR_DESENV,
                                        NULL NR_UTI,
                                        NULL NR_AZ
                                FROM ORDEMPRODUCAORECAP OPR
                                INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
                                INNER JOIN PEDIDOPNEU P ON (P.ID = IPP.IDPEDIDOPNEU)
                                INNER JOIN APLICCONSERTOPNEU AM ON (AM.IDORDEMPRODUCAORECAP = OPR.ID)
                                INNER JOIN ITEM II ON (II.CD_ITEM = IPP.IDSERVICOPNEU)
                                INNER JOIN EXAMEINICIAL EI ON(EI.IDORDEMPRODUCAORECAP = OPR.ID)
                                WHERE P.IDEMPRESA IN (1,2,3)
                                AND OPR.STORDEM <> 'C'
                                AND P.STPEDIDO <> 'C'
                                AND AM.ST_ETAPA = 'F'
                                AND EI.ID NOT IN (SELECT IDEXAMEINICIAL FROM MOTIVORECUSAEXAME)
                                AND AM.DTFIM between :data_Iconser and :data_Fconser

                                UNION ALL

                                SELECT
                                        NULL NR_EXINI,
                                        NULL NR_RASPA,
                                        NULL NR_BANDA,
                                        NULL NR_ESCAR,
                                        NULL NR_COBERT,
                                        NULL NR_VULCA,
                                        NULL NR_EXFIN,
                                        NULL NR_MACHAO,
                                        NULL NR_COLA,
                                        NULL NR_CONSER,
                                        COUNT(OPR.ID) NR_EXTRU,
                                        NULL NR_MONTA,
                                        NULL NR_ENVEL,
                                        NULL NR_DESENV,
                                        NULL NR_UTI,
                                        NULL NR_AZ
                                FROM ORDEMPRODUCAORECAP OPR
                                INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
                                INNER JOIN PEDIDOPNEU P ON (P.ID = IPP.IDPEDIDOPNEU)
                                INNER JOIN EXTRUSORAPNEU EX ON (EX.IDORDEMPRODUCAORECAP = OPR.ID)
                                INNER JOIN ITEM II ON (II.CD_ITEM = IPP.IDSERVICOPNEU)
                                WHERE P.IDEMPRESA IN (1,2,3)
                                AND OPR.STORDEM <> 'C'
                                AND P.STPEDIDO <> 'C'
                                AND EX.ST_ETAPA = 'F'
                                AND EX.DTFIM between :data_Iextru and :data_Fextru

                                UNION ALL

                                SELECT
                                        NULL NR_EXINI,
                                        NULL NR_RASPA,
                                        NULL NR_BANDA,
                                        NULL NR_ESCAR,
                                        NULL NR_COBERT,
                                        NULL NR_VULCA,
                                        NULL NR_EXFIN,
                                        NULL NR_MACHAO,
                                        NULL NR_COLA,
                                        NULL NR_CONSER,
                                        NULL NR_EXTRU,
                                        COUNT(OPR.ID) NR_MONTA,
                                        NULL NR_ENVEL,
                                        NULL NR_DESENV,
                                        NULL NR_UTI,
                                        NULL NR_AZ
                                FROM ORDEMPRODUCAORECAP OPR
                                INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
                                INNER JOIN PEDIDOPNEU P ON (P.ID = IPP.IDPEDIDOPNEU)
                                INNER JOIN MONTAGEMRECAP MO ON (MO.IDORDEMPRODUCAORECAP = OPR.ID)
                                INNER JOIN ITEM II ON (II.CD_ITEM = IPP.IDSERVICOPNEU)
                                WHERE P.IDEMPRESA IN (1,2,3)
                                AND OPR.STORDEM <> 'C'
                                AND P.STPEDIDO <> 'C'
                                AND MO.ST_ETAPA = 'F'
                                AND MO.DTFIM between :data_Imonta and :data_Fmonta

                                UNION ALL

                                SELECT
                                        NULL NR_EXINI,
                                        NULL NR_RASPA,
                                        NULL NR_BANDA,
                                        NULL NR_ESCAR,
                                        NULL NR_COBERT,
                                        NULL NR_VULCA,
                                        NULL NR_EXFIN,
                                        NULL NR_MACHAO,
                                        NULL NR_COLA,
                                        NULL NR_CONSER,
                                        NULL NR_EXTRU,
                                        NULL NR_MONTA,
                                        COUNT(OPR.ID) NR_ENVEL,
                                        NULL NR_DESENV,
                                        NULL NR_UTI,
                                        NULL NR_AZ
                                FROM ORDEMPRODUCAORECAP OPR
                                INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
                                INNER JOIN PEDIDOPNEU P ON (P.ID = IPP.IDPEDIDOPNEU)
                                INNER JOIN ENVELOPAMENTO EP ON (EP.IDORDEMPRODUCAORECAP = OPR.ID)
                                INNER JOIN ITEM II ON (II.CD_ITEM = IPP.IDSERVICOPNEU)
                                WHERE P.IDEMPRESA IN (1,2,3)
                                AND OPR.STORDEM <> 'C'
                                AND P.STPEDIDO <> 'C'
                                AND EP.ST_ETAPA = 'F'
                                AND EP.DTFIM between :data_Ienvel and :data_Fenvel

                                UNION ALL

                                SELECT
                                        NULL NR_EXINI,
                                        NULL NR_RASPA,
                                        NULL NR_BANDA,
                                        NULL NR_ESCAR,
                                        NULL NR_COBERT,
                                        NULL NR_VULCA,
                                        NULL NR_EXFIN,
                                        NULL NR_MACHAO,
                                        NULL NR_COLA,
                                        NULL NR_CONSER,
                                        NULL NR_EXTRU,
                                        NULL NR_MONTA,
                                        NULL NR_ENVEL,
                                        COUNT(OPR.ID) NR_DESENV,
                                        NULL NR_UTI,
                                        NULL NR_AZ
                                FROM ORDEMPRODUCAORECAP OPR
                                INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
                                INNER JOIN PEDIDOPNEU P ON (P.ID = IPP.IDPEDIDOPNEU)
                                INNER JOIN DESENVELOPAMENTO DP ON (DP.IDORDEMPRODUCAORECAP = OPR.ID)
                                INNER JOIN ITEM II ON (II.CD_ITEM = IPP.IDSERVICOPNEU)
                                WHERE P.IDEMPRESA IN (1,2,3)
                                AND OPR.STORDEM <> 'C'
                                AND P.STPEDIDO <> 'C'
                                AND DP.ST_ETAPA = 'F'
                                AND DP.DTFIM between :data_Idesen and :data_Fdesen

                                UNION ALL

                                SELECT
                                        NULL NR_EXINI,
                                        NULL NR_RASPA,
                                        NULL NR_BANDA,
                                        NULL NR_ESCAR,
                                        NULL NR_COBERT,
                                        NULL NR_VULCA,
                                        NULL NR_EXFIN,
                                        NULL NR_MACHAO,
                                        NULL NR_COLA,
                                        NULL NR_CONSER,
                                        NULL NR_EXTRU,
                                        NULL NR_MONTA,
                                        NULL NR_ENVEL,
                                        NULL NR_DESENV,
                                        COUNT(OPR.ID) NR_UTI,
                                        NULL NR_AZ
                                FROM ORDEMPRODUCAORECAP OPR
                                INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
                                INNER JOIN PEDIDOPNEU P ON (P.ID = IPP.IDPEDIDOPNEU)
                                INNER JOIN ACABAMENTOPNEU EA ON (EA.IDORDEMPRODUCAORECAP = OPR.ID)
                                INNER JOIN ITEM II ON (II.CD_ITEM = IPP.IDSERVICOPNEU)
                                WHERE P.IDEMPRESA IN (1,2,3)
                                AND OPR.STORDEM <> 'C'
                                AND P.STPEDIDO <> 'C'
                                AND EA.ST_ETAPA = 'F'
                                AND EA.DTFIM between :data_Iuti and :data_Futi

                                UNION ALL

                                SELECT
                                        NULL NR_EXINI,
                                        NULL NR_RASPA,
                                        NULL NR_BANDA,
                                        NULL NR_ESCAR,
                                        NULL NR_COBERT,
                                        NULL NR_VULCA,
                                        NULL NR_EXFIN,
                                        NULL NR_MACHAO,
                                        NULL NR_COLA,
                                        NULL NR_CONSER,
                                        NULL NR_EXTRU,
                                        NULL NR_MONTA,
                                        NULL NR_ENVEL,
                                        NULL NR_DESENV,
                                        NULL NR_EXTRAUT,
                                        COUNT(OPR.ID) NR_AZ
                                FROM ORDEMPRODUCAORECAP OPR
                                INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
                                INNER JOIN PEDIDOPNEU P ON (P.ID = IPP.IDPEDIDOPNEU)
                                INNER JOIN EXTRUSORAAUTOPNEU EA ON (EA.IDORDEMPRODUCAORECAP = OPR.ID)
                                INNER JOIN ITEM II ON (II.CD_ITEM = IPP.IDSERVICOPNEU)
                                WHERE P.IDEMPRESA IN (1,2,3)
                                AND OPR.STORDEM <> 'C'
                                AND P.STPEDIDO <> 'C'
                                AND EA.ST_ETAPA = 'F'
                                AND EA.DTFIM between :data_IAz and :data_FAz
                                ) X
                                ";

                $etapas = DB::connection('firebird_campina')->select($sql, $bindings);

                return view('admin.pcp.etapas', compact('user_auth', 'uri', 'etapas'));
        }
        public function trocaServico()
        {
                $uri       = $this->request->route()->uri();
                $empresas = $this->empresa->EmpresaFiscal(Helper::VerifyRegion($this->user->conexao));
                $user_auth = $this->user;

                return view('admin.producao.troca-servico', compact(
                        'empresas',
                        'uri',
                        'user_auth',
                ));
        }
        public function getChangeService()
        {
                if ($this->request->i == "A") {
                        $dt_ini = date('m-d-Y', strtotime('-1 days')) . " 00:00";
                        $dt_fim = date('m-d-Y') . " 23:59";
                        $empresa = 3;
                } else {
                        $empresa = Empresa::where('cd_empresa', $this->request->cdempresa)->firstOrFail();
                        $dt_ini = $this->request->dtini;
                        $dt_fim = $this->request->dtfim;
                        $empresa = $empresa->cd_empresa;
                }
                $data = $this->producao->trocaServico($dt_ini, $dt_fim, $empresa);
                return DataTables::of($data)
                        ->addColumn('actions', function ($data) {
                                return '<button class="btn btn-default" data-id="' . $data->ORDEM . '" id="view-grid-change"><i class="fa fa-eye" aria-hidden="true"></i></button>';
                        })
                        ->rawColumns(['actions'])
                        ->make(true);
        }
        public function getChangeServiceOrdem()
        {
                $this->request->validate([
                        'ordem' => 'integer',
                ]);
                $grid_ordem =  $this->producao->GridChandeServiceOrdem($this->request->ordem);
                $html = '<table class="table table-bordered" style="font-size: 12px" style="width:100%" id="table-change-ordem">
                                <thead>
                                    <tr>
                                        <th>Ordem</th>
                                        <th>Etapa</th>
                                        <th>Servico</th>
                                        <th>Executor</th>
                                        <th>Autorizador</th>
                                        <th>Alterado</th>
                                    </tr>
                                </thead>
                                <tbody>';
                foreach ($grid_ordem as $g) {
                        $html .= '<tr>';
                        $html .= '<td>' . $g->ORDEM . '</td>';
                        $html .= '<td>' . $g->DSETAPAEMPRESA . '</td>';
                        $html .= '<td>' . $g->SERVTROCA . '</td>';
                        $html .= '<td>' . $g->EXECUTOR . '</td>';
                        $html .= '<td>' . $g->AUTORIZADOR . '</td>';
                        $html .= '<td>' . $g->DTREGISTRO . '</td>';
                        $html .= '</tr>';
                }
                $html .= "</tbody></table>";

                return response()->json($html);
        }
}
