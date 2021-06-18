<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortariaController extends Controller
{
 public function __construct(Request $request)
 {
  $this->resposta = $request;
  $this->middleware(function ($request, $next) {
   $this->user = Auth::user();
   return $next($request);
  });
 }
 public function index()
 {
  $title_page = 'Entrada Veiculo';
  $user_auth  = $this->user;
  $uri        = $this->resposta->route()->uri();
  return view('admin.portaria.entrada', compact('user_auth', 'uri', 'title_page'));
 }

 public function create(Request $request)
 {
  return $request;
 }



 public function editar(Request $request){

 }
}
