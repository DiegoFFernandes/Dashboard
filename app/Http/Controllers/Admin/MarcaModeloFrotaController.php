<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\FrotaVeiculos;
use App\Models\MarcaModeloFrota;
use App\Models\MarcaVeiculo;
use App\Models\ModeloVeiculo;
use App\Models\MotoristaVeiculo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MarcaModeloFrotaController extends Controller
{

  public function __construct(
    Request $request,
    Empresa $empresa,
    FrotaVeiculos $frotaveiculo,
    ModeloVeiculo $modelo,
    MarcaVeiculo $marca,
    MarcaModeloFrota $marcamodelo,
    MotoristaVeiculo $motoristaveiculo,
  ) 
  {
    $this->empresa      = $empresa;
    $this->resposta     = $request;
    $this->frotaveiculo = $frotaveiculo;
    $this->modelo       = $modelo;
    $this->marca        = $marca;
    $this->marcamodelo  = $marcamodelo;
    $this->motoristaveiculo = $motoristaveiculo;

    $this->middleware(function ($request, $next) {
      $this->user = Auth::user();
      return $next($request);
    });
  }

  public function index()
  {
    $title_page    = 'Vincular Marca/Modelo/Frota';
    $user_auth     = $this->user;
    $uri           = $this->resposta->route()->uri();
    $marcas        = $this->marca->marcaAll();
    $modelos       = $this->modelo->modeloAll();
    $frotaveiculos = $this->frotaveiculo->frotaAll();

    return view(
      'admin.veiculo.marca-modelo-veiculo.index',
      compact('title_page', 'user_auth', 'uri', 'marcas', 'modelos', 'frotaveiculos')
    );
  }

  public function getMarcaModelos(Request $request)
  {
    $frota = 0;
    $data = $this->marcamodelo->MarcaModeloAll($frota);
    return DataTables::of($data)
      ->addColumn('Actions', function ($data) {
        return '<button type="button" class="btn btn-success btn-sm" id="getEditMarcaModeloData" data-id="' . $data->id . '">Editar</button>
            <button type="button" data-id="' . $data->id . '" data-toggle="modal" data-target="#DeleteMarcaModeloVeiculo" class="btn btn-danger btn-sm" id="getDeleteId">Excluir</button>';
      })
      ->rawColumns(['Actions'])
      ->make(true);
  }

  public function store(Request $request)
  {
    $request['cd_usuario'] = $this->user->id;
    $validator = $this->_validator($request);
    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()->all()]);
    }

    if ($this->marcamodelo->verifyIfExists($request['cd_marca'], $request['cd_modelo'])) {
      return response()->json(['alert' => 'Marca e Modelo associados já existem!']);
    }

    $this->marcamodelo->storeData($request->all());

    return response()->json(['success' => 'Marca e Modelo associados com sucesso']);
  }

  public function edit(Request $request, $id)
  {
    $data = $this->marcamodelo->findData($id);
    return response()->json($data);
  }

  public function update(Request $request, $id)
  {
    $request['cd_usuario'] = $this->user->id;
    $validator = $this->_validator($request);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()->all()]);
    }

    if ($this->marcamodelo->verifyIfExists($request['cd_marca'], $request['cd_modelo'])) {
      return response()->json(['alert' => 'Marca e Modelo associados já existem!']);
    }

    $this->marcamodelo->updateData($id, $request->all());

    return response()->json(['success' => 'Marca e Modelo atualizados com sucesso']);
  }

  public function destroy($id)
  {
    if ($this->motoristaveiculo->findMarcaModelo($id)) {
      return response()->json(['errors' => 'Esse item está vinculado ao cadastro de motorista e veiculos, não pode ser excluido!']);
    }
    $this->marcamodelo->destroyData($id);
    return response()->json(['success' => 'Excluido com sucesso!']);
  }

  public function _validator(Request $request)
  {
    return Validator::make(
      $request->all(),
      [
        'cd_marca'  => 'integer|required',
        'cd_modelo' => 'integer|required',
        'cd_frota'  => 'integer|required',
      ],
      [
        'cd_marca.required' => 'Marca deve ser preenchida',
        'cd_marca.integer'   => 'Id marca deve ser inteiro',
        'cd_modelo.required' => 'Modelo deve ser preenchida',
        'cd_modelo.integer'  => 'Id modelo deve ser inteiro',
        'cd_frota.required'  => 'Frota deve ser preenchida',
        'cd_frota.integer'   => 'Id frota deve ser inteiro',
      ]
    );
  }
}
