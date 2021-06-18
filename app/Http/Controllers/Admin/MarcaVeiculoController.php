<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\FrotaVeiculos;
use App\Models\MarcaVeiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarcaVeiculoController extends Controller
{
 public function __construct(Request $request, Empresa $empresa, FrotaVeiculos $frotaveiculo)
 {
  $this->empresa      = $empresa;
  $this->resposta     = $request;
  $this->frotaveiculo = $frotaveiculo;
  $this->middleware(function ($request, $next) {
   $this->user = Auth::user();
   return $next($request);
  });
 }

 public function create()
 {
  $title_page = 'Marca';
  $user_auth  = $this->user;
  $uri        = $this->resposta->route()->uri();

  $frotaveiculos = $this->frotaveiculo->frotaveiculosAll();
  return view('admin.veiculo.marca-veiculo.cadastrar', compact('title_page', 'user_auth', 'uri', 'frotaveiculos'));
 }
 public function save(Request $request)
 {
     if(MarcaVeiculo::where('descricao', $request->descricao)->exists()){
        return redirect()->route('marca-veiculo.cadastrar')->with('warning', 'Essa descricao da marca já existe');
     }
     
     return $request->all();

 }
 public function _validate(Request $request)
 {
  return $request->validate(
   [
    'cd_marca'         => 'integer|required',
    'descricao'        => 'string|required',
    'cd_frotaveiculos' => 'integer|required',

   ],
   [
    'cd_marca.required'         => 'Marca deve ser preenchida.',
    'cd_marca.integer'          => 'Número marca deve ser inteiro!',
    'descricao.required'        => 'Descrição marca deve ser preenchida',
    'descricao.string'          => 'Descrição marca deve ser palavra',
    'cd_frotaveiculos.integer'  => 'Número frota deve ser inteiro',
    'cd_frotaveiculos.required' => 'Frota deve ser preenchida',

   ]
  );
 }
}
