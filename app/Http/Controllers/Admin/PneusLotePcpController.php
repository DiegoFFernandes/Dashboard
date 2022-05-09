<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pcp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PneusLotePcpController extends Controller
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

  public function index($nr_ordem)
  {
    $uri       = $this->request->route()->uri();
    $user_auth = $this->user;    
    $pneus_lote = $this->pcp->ItemLotePcp($nr_ordem);
    return view('admin.pcp.pneus-lote-pcp', compact('uri', 'user_auth', 'pneus_lote'));
  }
}
