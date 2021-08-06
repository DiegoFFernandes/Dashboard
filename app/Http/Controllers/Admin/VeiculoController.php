<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\FrotaVeiculos;
use App\Models\MarcaModeloFrota;
use App\Models\MarcaVeiculo;
use App\Models\ModeloVeiculo;
use App\Models\MotoristaVeiculo;
use App\Models\Pessoa;
use App\Models\TipoVeiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class VeiculoController extends Controller
{
  public function __construct(
    Request $request,
    Empresa $empresa,
    MotoristaVeiculo $motoristaVeiculo,
    Pessoa $pessoa,
    MarcaModeloFrota $marcamodelo,
    TipoVeiculo $tipoveiculo
  ) {
    $this->empresa  = $empresa;
    $this->resposta = $request;
    $this->pessoas = $pessoa;
    $this->marcas = $marcamodelo;
    $this->motoristaveiculos = $motoristaVeiculo;
    $this->tipoveiculos = $tipoveiculo;
    $this->middleware(function ($request, $next) {
      $this->user = Auth::user();
      return $next($request);
    });
  }

  public function index(Request $request)
  {
    $title_page   = 'Lista de Motoristas/Veiculos';
    $user_auth    = $this->user;
    $uri         = $this->resposta->route()->uri();
    $empresas     = $this->empresa->empresa();
    $pessoas      = $this->pessoas->PessoasAll();
    $marcas       = $this->marcas->MarcaModeloDsAll();
    $tipoveiculos = $this->tipoveiculos->TipoVeiculoAll();
    // return $this->motoristaveiculos->findData(1);
    return view(
      'admin.veiculo.motorista-veiculo.index',
      compact('user_auth', 'uri', 'title_page', 'empresas', 'pessoas', 'marcas', 'tipoveiculos')
    );
  }

  public function getMotoristaVeiculos()
  {
    $data  = $this->motoristaveiculos->MotoristaVeiculoAll();
    return DataTables::of($data)
      ->addColumn('Actions', function ($data) {
        return '<button type="button" class="btn btn-success btn-sm" id="getEditMotoristaVeiculo" data-id="' . $data->id . '">Editar</button>
            <button type="button" data-id="' . $data->id . '" data-toggle="modal" data-target="#DeleteMotoristaVeiculo" class="btn btn-danger btn-sm" id="getDeleteId">Excluir</button>';
      })
      ->rawColumns(['Actions'])
      ->make(true);
  }

  public function store(Request $request)
  {
    $request['ativo'] = 'S';
    $request['cd_usuario'] = $this->user->id;

    $validator = $this->_validator($request);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()->all()]);
    }
    
    if ($this->motoristaveiculos->verifyIfExists($request['cd_pessoa'], $request['placa'])) {
      return response()->json(['alert' => 'Motorista já está associado a placa, <strong>'. $request['placa'] .' </strong>, favor verificar!']);
    }

    $this->motoristaveiculos->storeData($request->all());
    return response()->json(['success' => 'Motorista associado ao vinculo com sucesso!']);
  }

  public function edit($id)
  {
    $data =  $this->motoristaveiculos->findData($id);
    return response()->json($data);
  }

  public function update(Request $request, $id)
  {
    $validator = $this->_validator($request); 
   
    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()->all()]);
    }

    $request['cor'] = strtoupper($request->cor);
    $request['cd_usuario'] = $this->user->id;   


    $this->motoristaveiculos->updateData($id, $request->all());

    return response()->json(['success' => 'Motorista e veiculo atualizados com sucesso']);
  }

  public function destroy($id)
  {
    $this->motoristaveiculos->destroyData($id);
    return response()->json(['success' => 'Id '.$id.', excluido com sucesso!']);
  }

  public function _validator(Request $request)
  {
    return Validator::make(
      $request->all(),
      [
        'cd_empresa'          => 'required', 'integer:1,2,3,21,22',
        'cd_pessoa'           => 'integer|required|not_in:0',
        'placa'               => 'required|max:8',
        'cor'                 => 'string|required',
        'cd_marcamodelofrota' => 'integer|required|not_in:0',
        'ano'                 => 'integer|required',
        'cd_tipoveiculo'      => 'integer|required|not_in:0',
      ],
      [
        'cd_empresa.required'     => 'Por favor informe uma empresa.',
        'cd_empresa.integer'      => 'Empresa deve ter um código valido',

        'cd_pessoa.required'      => 'Por favor informe um motorista!',
        'cd_pessoa.integer'       => 'Motorista deve ter um código valido!',

        'placa.required'          => 'Por favor informe uma placa',
        'placa.max'               => 'Placa deve ser no maximo até 7 caracteres',

        'cor.required'            => 'Por favor informe a cor do veiculo ',
        'cor.string'              => 'A descrição Cor deve ser um palavra',

        'ano.required'            => 'Por favor informe o ano do veiculo',
        'ano.integer'             => 'Ano deve ser um numero inteiro',

        'cd_tipoveiculo.required' => 'Por favor informe o tipo do veiculo',
        'cd_tipoveiculo.integer'  => 'Tipo do veiculo deve ser inteiro',
      ]
    );
  }
}
