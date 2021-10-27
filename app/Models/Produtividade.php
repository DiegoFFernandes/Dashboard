<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Produtividade extends Model
{
   use HasFactory;

   public function __construct()
   {
      $this->connection = 'Sempre setar o banco firebird com SetConnet';
      $this->p_dia = date("m/01/Y");
   }

   public function setConnet()
   {
      return $this->connection = Auth::user()->conexao;
   }

   public function executores($emp, $setor)
   {
      $banco = $this->setConnet();
      $sql = "
  SELECT substring(X.NMEXECUTOR from 1 for position(' ', X.NMEXECUTOR)-1) NMEXECUTOR, SUM(X.HOJE) HOJE, SUM(X.ONTEM) ONTEM, SUM(X.ANTEONTEM) ANTEONTEM
  FROM (
     SELECT E.NMEXECUTOR, COUNT(I.ID) HOJE, 0 ONTEM, 0 ANTEONTEM
     FROM " . $setor . " I
     INNER JOIN EXECUTORETAPA E ON (I.IDEXECUTOR = E.ID)
     INNER JOIN ORDEMPRODUCAORECAP OPR ON (OPR.ID = I.IDORDEMPRODUCAORECAP)
     INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
     INNER JOIN PEDIDOPNEU PP ON (PP.ID = IPP.IDPEDIDOPNEU)
     WHERE CAST(I.DTFIM AS DATE) = CURRENT_DATE
      AND I.ST_ETAPA = 'F'
      AND PP.IDEMPRESA = " . $emp . "
     GROUP BY  E.NMEXECUTOR
     HAVING COUNT(I.ID) > 5

  UNION ALL

     SELECT E.NMEXECUTOR, 0 HOJE, COUNT(I.ID) ONTEM, 0 ANTEONTEM
     FROM " . $setor . " I
     INNER JOIN EXECUTORETAPA E ON (I.IDEXECUTOR = E.ID)
     INNER JOIN ORDEMPRODUCAORECAP OPR ON (OPR.ID = I.IDORDEMPRODUCAORECAP)
     INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
     INNER JOIN PEDIDOPNEU PP ON (PP.ID = IPP.IDPEDIDOPNEU)
    WHERE CAST(I.DTFIM AS DATE) = CURRENT_DATE-1
      AND I.ST_ETAPA = 'F'
      AND PP.IDEMPRESA = " . $emp . "
     GROUP BY  E.NMEXECUTOR
     HAVING COUNT(I.ID) > 5

  UNION ALL

     SELECT E.NMEXECUTOR, 0 HOJE, 0 ONTEM, COUNT(I.ID) ANTEONTEM
     FROM " . $setor . " I
     INNER JOIN EXECUTORETAPA E ON (I.IDEXECUTOR = E.ID)
     INNER JOIN ORDEMPRODUCAORECAP OPR ON (OPR.ID = I.IDORDEMPRODUCAORECAP)
     INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
     INNER JOIN PEDIDOPNEU PP ON (PP.ID = IPP.IDPEDIDOPNEU)
     WHERE CAST(I.DTFIM AS DATE) = CURRENT_DATE-2
      AND I.ST_ETAPA = 'F'
      AND PP.IDEMPRESA = " . $emp . "
     GROUP BY  E.NMEXECUTOR
     HAVING COUNT(I.ID) > 5
  ) X
  GROUP BY X.NMEXECUTOR
  ORDER BY HOJE DESC
        ";

      $array_vazio = [
         (object)['NMEXECUTOR' => 'SEM INFORMAÇÃO', 'HOJE' => 0, 'ONTEM' => 0, 'ANTEONTEM' => 0]

      ];
      $resultado = DB::connection($banco)->select($sql);

      if ($resultado === []) {
         return $resultado = [
            (object)['NMEXECUTOR' => 'SEM INFORMAÇÃO', 'HOJE' => 0, 'ONTEM' => 0, 'ANTEONTEM' => 0]

         ];
      }
      return $resultado;
   }
}
