<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pcp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LotePcpController extends Controller
{
 public function __construct(Request $request, Pcp $pcp)
 {
  $this->request = $request;
  $this->pcp = $pcp;

  $this->middleware(function ($request, $next) {
   $this->user = Auth::user();
   return $next($request);
  });
 }
 public function index()
 {
  $uri  = $this->request->route()->uri();
  $user_auth = $this->user;
  $resultados = $this->pcp->QtdLotePcp();
  $pneus = $this->pcp->PneusLotePcp();

  return view('admin.pcp.lote-pcp', compact('resultados', 'pneus', 'user_auth', 'uri'));
 }
}
