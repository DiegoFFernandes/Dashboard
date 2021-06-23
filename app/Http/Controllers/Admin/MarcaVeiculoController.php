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
 public function __construct(Request $request, Empresa $empresa, FrotaVeiculos $frotaveiculo, MarcaVeiculo $marca)
 {
  $this->empresa      = $empresa;
  $this->resposta     = $request;
  $this->frotaveiculo = $frotaveiculo;
  $this->marca        = $marca;
  $this->middleware(function ($request, $next) {
   $this->user = Auth::user();
   return $next($request);
  });
 }

 public function create()
 {
  $title_page = 'Cadastrar Marca';
  $user_auth  = $this->user;
  $uri        = $this->resposta->route()->uri();
  $marcas     = $this->marca->marcaAll();

  $frotaveiculos = $this->frotaveiculo->frotaveiculosAll();
  return view('admin.veiculo.marca-veiculo.cadastrar', compact('title_page', 'user_auth', 'uri', 'frotaveiculos', 'marcas'));
 }
 public function save(Request $request)
 {
  if (MarcaVeiculo::where('descricao', $request->descricao)
   ->where('cd_frotaveiculos', $request->cd_frotaveiculos)
   ->exists()) {
   return redirect()->route('marca-veiculo.listar')->with('warning', 'Essa descricao da marca já existe!');
  }

  $marca  = $this->_validate($request);
  $status = MarcaVeiculo::create($marca);
  return redirect()->route('marca-veiculo.listar')->with('status', 'Marca criada com sucesso!');

 }
 public function delete(Request $request)
 {

  MarcaVeiculo::findOrFail($request->cd_marca);
  $delete = MarcaVeiculo::where('id', $request->cd_marca)->delete();
  if ($delete) {
   return redirect()->route('marca-veiculo.listar')->with('status', 'Marca excluida com sucesso!');
  } else {
   return redirect()->route('marca-veiculo.listar')->with('warning', 'Ops, aconteceu algum erro. Contatar administrador do sistema!');
  }

 }
 public function update(Request $request)
 {
  if (MarcaVeiculo::where('descricao', $request->descricao)
   ->where('cd_frotaveiculos', $request->cd_frotaveiculos)
   ->exists()) {
   return redirect()->route('marca-veiculo.listar')->with('warning', 'Essa descricao da marca já existe!');
  }
  $marca              = MarcaVeiculo::findOrFail($request->id);
  $data               = $this->_validate($request);
  $data['cd_usuario'] = $this->user->id;
  $marca->fill($data);

  $status = $marca->save();

  if ($status) {
   return redirect()->route('marca-veiculo.listar')->with('status', 'Marca alterada com sucesso!');
  } else {
   return redirect()->route('marca-veiculo.listar')->with('warning', 'Houve algum erro ao atualizar!');
  }

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