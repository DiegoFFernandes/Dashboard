<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ExpedicaoController extends Controller
{
 public function index()
 {
  $nr_ordem = 387;
  $bindings = [387];
  $sql      = 
       "SELECT *
        FROM RETORNA_DETALHESPRODUCAORECAP(:bindings) OPP
        ORDER BY OPP.O_IDORDEMPRODUCAORECAP, OPP.O_ORDER, OPP.O_DTINICIO ";

  $result = DB::connection('firebird')->select($sql, $bindings);

  dd($result);
 }
}
