<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\FrotaVeiculos;
use App\Models\MarcaVeiculo;
use App\Models\ModeloVeiculo;
use App\Models\MotoristaVeiculo;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VeiculoController extends Controller
{
 public function __construct(Request $request, Empresa $empresa)
 {
  $this->empresa  = $empresa;
  $this->resposta = $request;
  $this->middleware(function ($request, $next) {
   $this->user = Auth::user();
   return $next($request);
  });
 }
 function list(Request $request) {
  $title_page = 'Lista de Motoristas';
  $user_auth  = $this->user;
  $uri        = $this->resposta->route()->uri();

  $sql = "
  SELECT motorista_veiculos.id, pessoas.name, motorista_veiculos.placa, motorista_veiculos.cor, marcaveiculos.descricao as dsmarca, 
  modeloveiculos.descricao as dsmodelo, motorista_veiculos.ano, tipoveiculo.descricao as dstipo, frotaveiculos.descricao as dsfrota
  FROM motorista_veiculos
  inner join pessoas on (pessoas.id = motorista_veiculos.cd_pessoa)
  inner join marcaveiculos on (marcaveiculos.cd_marca = motorista_veiculos.cd_marca)
  inner join modeloveiculos on (modeloveiculos.id = motorista_veiculos.cd_modelo)
  inner join tipoveiculo on (tipoveiculo.id = motorista_veiculos.cd_tipoveiculo)
  inner join frotaveiculos on (frotaveiculos.id = marcaveiculos.cd_frotaveiculos)
  group by motorista_veiculos.id, motorista_veiculos.cd_marca, marcaveiculos.descricao, frotaveiculos.descricao";

  $motoristas = DB::select($sql);

  return view('admin.veiculo.listar-motorista-veiculos', compact('user_auth', 'uri', 'motoristas'));
 }
 public function edit($id)
 {
  $motorista  = MotoristaVeiculo::findOrFail($id);
  $title_page = 'Editar Motorista';
  $user_auth  = $this->user;
  $uri        = $this->resposta->route()->uri();

  $veiculo = MotoristaVeiculo::select('motorista_veiculos.cd_empresa', 'motorista_veiculos.id', 'pessoas.id as cd_pessoa', 'pessoas.name as pessoa', 'motorista_veiculos.placa', 'marcaveiculos.id as cd_marca',
   'marcaveiculos.descricao as marca', 'modeloveiculos.id as cd_modelo', 'modeloveiculos.descricao as modelo', 'motorista_veiculos.ano as ano', 'motorista_veiculos.cor as cor',
   'frotaveiculos.id as cd_frota', 'frotaveiculos.descricao as frota', 'tipoveiculo.id as cd_tipoveiculo', 'tipoveiculo.descricao as tipoveiculo', 'motorista_veiculos.ativo')
   ->join('pessoas', 'pessoas.id', 'motorista_veiculos.cd_pessoa')
   ->join('marcaveiculos', 'marcaveiculos.cd_marca', 'motorista_veiculos.cd_marca')
   ->join('frotaveiculos', 'frotaveiculos.id', 'marcaveiculos.cd_frotaveiculos')
   ->join('modeloveiculos', function ($join) {
    $join->on('modeloveiculos.id_marca', '=', 'marcaveiculos.id');    
   })
   ->join('tipoveiculo', 'tipoveiculo.id', 'motorista_veiculos.cd_tipoveiculo')
   ->where('motorista_veiculos.id', $id)
   ->get();

  $frotaveiculos = FrotaVeiculos::select('id', 'descricao')->get();

  $marcas = MarcaVeiculo::select('cd_marca', 'descricao')
   ->where('cd_marca', 0)
   ->groupBy('cd_marca', 'descricao')
   ->get();

  $modelos = ModeloVeiculo::select('id', 'descricao')
   ->where('id', 0)
   ->get();

  $empresas = $this->empresa->empresa();

  $motoristas = Pessoa::select('id', 'name')->get();

  return view('admin.veiculo.motorista-veiculo',
   compact('user_auth', 'uri', 'motorista', 'title_page', 'veiculo', 'frotaveiculos', 'marcas', 'modelos', 'empresas', 'motoristas'));

 }
 public function create()
 {
  $title_page = 'Cadastrar Motorista/Veiculo';
  $user_auth  = $this->user;
  $uri        = $this->resposta->route()->uri();

  $frotaveiculos = FrotaVeiculos::select('id', 'descricao')->get();

  $marcas = MarcaVeiculo::select('cd_marca', 'descricao')
   ->where('cd_marca', 0)
   ->groupBy('cd_marca', 'descricao')
   ->get();

  $modelos = ModeloVeiculo::select('id', 'descricao')
   ->where('id', 0)
   ->get();

  $motoristas = Pessoa::select('id', 'name')->get();

  $empresas = $this->empresa->empresa();

  return view('admin.veiculo.motorista-veiculo',
   compact('user_auth', 'uri', 'title_page', 'frotaveiculos', 'marcas', 'modelos', 'motoristas', 'empresas'));
 }
 public function loadMarcas(Request $request)
 {
  $dataForm = $request->all();
  $frotaId  = 2;

  $marcas = MarcaVeiculo::select('id', 'cd_marca', 'cd_frotaveiculos', 'descricao')
   ->where('cd_frotaveiculos', $frotaId)->get();

  return view('admin.veiculo.marca_ajax', compact('marcas'));

 }

 public function loadModelos(Request $request)
 {
  //$dataForm = $request->id_marca;
  // $frotaId  = $dataForm['id_frotaveiculo'];
  $id_marca = $request->id_marca;
  $cd_frotaveiculos = $request->id_frotaveiculo;

  $modelos = ModeloVeiculo::select('id', 'descricao')
   ->where('cd_frotaveiculos', $cd_frotaveiculos)
   ->where('cd_marca', $id_marca)
   ->get();

  return view('admin.veiculo.modelo_ajax', compact('modelos'));
 }

 public function save(Request $request)
 {
  $placa_exists = MotoristaVeiculo::where('placa', $request->placa)->exists();
  if ($placa_exists) {
   return redirect()->route('admin.cadastrar.motorista.veiculos')->with('warning', 'A placa ' . $request->placa . ' já está cadastrada com outro motorista!');
  }
  $request['cor'] = strtoupper($request->cor);
  $request['cd_usuario'] = $this->user->id;
  $motorista             = $this->_validate($request);
  $motorista             = MotoristaVeiculo::create($motorista);
  return redirect()->route('admin.cadastrar.motorista.veiculos')->with('status', 'Motorista vinculado com veiculo!');

 }

 public function update(Request $request)
 {
  $motorista = MotoristaVeiculo::findOrFail($request->id);
  $this->_validate($request);
  $request['cor'] = strtoupper($request->cor);
  $request['cd_usuario'] = $this->user->id;
  $motorista->fill($request->all());
  $status = $motorista->save();

  if ($status == 1) {
   return redirect()->route('admin.listar.motorista.veiculos')->with('status', 'Motorista/Veiculo atualizado com sucesso!');
  } else {
   return redirect()->route('admin.listar.motorista.veiculos')->with('status', 'Houve algum erro');
  }

 }

 public function _validate(Request $request)
 {
  return $request->validate(
   [
    'cd_empresa'     => 'required', 'integer:1,2,3,21,22',
    'cd_pessoa'      => 'integer|required',
    'placa'          => 'required|max:8',
    'ativo'          => 'required|string:S,N',
    'cd_tipoveiculo' => 'integer|required',
    'ano'            => 'integer|required',
    'cor'            => 'string|required',
    'cd_marca'       => 'integer|required',
    'cd_modelo'      => 'integer|required',
    'cd_usuario'     => 'integer',

   ],
   [
    'id_empresa.required'     => 'Por favor informe uma empresa.',
    'id_pessoa.required'      => 'Por favor informe um motorista!',
    'id_pessoa.integer'       => 'Motorista deve ter um código valido!',
    'placa.required'          => 'Por favor informe uma placa',
    'placa.max'               => 'Placa deve ser no maximo até 7 caracteres',
    'ativa.required'          => 'Por favor informe se está ativo ou desativado',
    'ativa.string'            => 'Ativo deve ser *S* para sim e *N* para não ',
    'id_tipoveiculo.required' => 'Por favor informe o tipo do veiculo',
    'ano.required'            => 'Por favor informe o ano do veiculo',
    'ano.integer'             => 'Ano deve ser um numero inteiro',
    'cor.required'            => 'Por favor informe a cor do veiculo ',
    'cor.string'              => 'A descrição Cor deve ser um palavra',
    'cd_marca.required'       => 'Por favor informe a marca do veiculo ',
    'cd_marca.integer'        => 'Marca deve ter um numero inteiro',
    'id_modelo.required'      => 'Por favor informe o modelo do veiculo',
    'cd_modelo.integer'       => 'Modelo deve ser informado',
    'cd_modelo.required'      => 'Modelo deve ser informado',
   ]
  );
 }

}