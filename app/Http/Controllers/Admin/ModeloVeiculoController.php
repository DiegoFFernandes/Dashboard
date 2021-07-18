<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\FrotaVeiculos;
use App\Models\ModeloVeiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModeloVeiculoController extends Controller
{
 public function __construct(Request $request, Empresa $empresa, FrotaVeiculos $frotaveiculo, ModeloVeiculo $modelo)
 {
  $this->empresa      = $empresa;
  $this->resposta     = $request;
  $this->frotaveiculo = $frotaveiculo;
  $this->modelo       = $modelo;  
  $this->middleware(function ($request, $next) {
   $this->user = Auth::user();
   return $next($request);
  });
 }
 public function create()
 {
  $title_page = 'Cadastrar Modelo';
  $user_auth  = $this->user;
  $uri        = $this->resposta->route()->uri();
  $modelos    = $this->modelo->modeloAll();

  return view('admin.veiculo.modelo-veiculo.modelo', compact('title_page', 'user_auth', 'uri', 'modelos'));
 }
 public function save(Request $request)
 {
  if (ModeloVeiculo::where('descricao', $request->descricao)->exists()) {
   return redirect()->route('modelo-veiculo')->with('warning', 'Essa descricao da modelo já existe!');
  }

  $modelo               = $this->_validate($request);
  $modelo['descricao']  = strtoupper($request->descricao);
  $modelo['cd_usuario'] = $this->user->id;

  $status = ModeloVeiculo::create($modelo);
  if ($status) {
   return redirect()->route('modelo-veiculo')->with('status', 'Modelo criado com sucesso!');
  } else {
   return redirect()->route('modelo-veiculo')->with('status', 'Houve algum erro contate administrador do sistema');
  }

 }
 public function delete(Request $request)
 {   
  ModeloVeiculo::findOrFail($request->cd_modelo);
  $delete = ModeloVeiculo::where('id', $request->cd_modelo)->delete();
  if ($delete) {
   return redirect()->route('modelo-veiculo')->with('status', 'Modelo excluido com sucesso!');
  } else {
   return redirect()->route('modelo-veiculo')->with('warning', 'Ops, aconteceu algum erro. Contatar administrador do sistema!');
  }

 }
 public function update(Request $request)
 {
  //  return $request->all();
  if (ModeloVeiculo::where('descricao', $request->descricao)   
   ->exists()) {
   return redirect()->route('modelo-veiculo')->with('warning', 'Essa descricao da modelo já existe!');
  }

  $modelo              = ModeloVeiculo::findOrFail($request->id);
  $data               = $this->_validate($request);
  $data['descricao'] = strtoupper($request->descricao);
  $data['cd_usuario'] = $this->user->id;
  $modelo->fill($data);

  $status = $modelo->save();

  if ($status) {
   return redirect()->route('modelo-veiculo')->with('status', 'Modelo alterada com sucesso!');
  } else {
   return redirect()->route('modelo-veiculo')->with('warning', 'Houve algum erro ao atualizar!');
  }

 }
 public function _validate(Request $request)
 {
  return $request->validate(
   [
    'descricao' => 'string|required',
   ],
   [
    'descricao.required' => 'Descrição modelo deve ser preenchida',
    'descricao.string'   => 'Descrição modelo deve ser palavra']
  );
 }
}
