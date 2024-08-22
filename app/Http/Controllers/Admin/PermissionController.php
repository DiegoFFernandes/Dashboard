<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use PowerbiHelper;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
  public $user, $resposta;
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
    $uri       = $this->resposta->route()->uri();
    $user_auth = $this->user;
    $data      = User::select('users.id', 'users.name', 'users.email', 'users.empresa', 'users.created_at')
      ->join('model_has_permissions', 'model_has_permissions.model_id', '=', 'users.id')
      ->groupBy('users.id', 'users.name', 'users.email', 'users.empresa', 'users.created_at')
      ->orderBy('id')->get();

    return view('admin.usuarios.permission', compact('uri', 'user_auth', 'data'));
  }
  public function edit(Request $request)
  {
    $title           = 'Edição de Permissão de usuários';
    $exploder        = explode('/', $this->resposta->route()->uri());
    $uri             = ucfirst($exploder[1]);
    $user_auth       = $this->user;
    $userId          = User::findOrFail($request->id);
    $permissions     = Permission::all()->pluck('name');
    $userPermissions = $userId->getPermissionNames();
    $array1          = json_decode(json_encode($permissions), true);
    $array2          = json_decode(json_encode($userPermissions), true);
    $all_permissions = array_diff($array1, $array2);

    return view('admin.usuarios.user-permission', compact(
      'uri',
      'user_auth',
      'userId',
      'permissions',
      'title',
      'userPermissions',
      'all_permissions'
    ));
  }
  public function save(Request $request)
  {
    $user           = User::findOrFail($request->id);
    $userPermission = $request->permissions;
    $user->syncPermissions($userPermission);
    return redirect()->route('admin.usuarios.permission')->with('status', 'Nova permissão usuário adicionada com sucesso!');
  }

  public function update(Request $request)
  {
    $user           = User::findorFail($request->id);
    $userPermission = $request->permissions;
    $user->syncPermissions($userPermission);
    return redirect()->route('admin.usuarios.permission')->with('status', 'Nova permissão do usuário atualizado com sucesso!');
  }
  public function create()
  {
    $all_permissions = Permission::all()->pluck('name');
    $title           = 'Nova permissão usuário';
    $user_auth       = $this->user;

    $users = User::select('users.id', 'users.name', 'users.email', 'users.empresa', 'users.created_at')
      ->leftJoin('model_has_permissions', 'model_has_permissions.model_id', '=', 'users.id')
      ->whereNull('model_has_permissions.model_id')
      ->groupBy('users.id', 'users.name')
      ->orderBy('id')->get();

    $exploder = explode('/', $this->resposta->route()->uri());
    $uri      = ucfirst($exploder[1]);

    return view('admin.usuarios.user-permission', compact(
      'uri',
      'users',
      'all_permissions',
      'title',
      'user_auth'
    ));
  }
  public function delete($id)
  {
    DB::table("model_has_permissions")->where('model_id', $id)->delete();
    return redirect()->route('admin.usuarios.permission')
      ->with('status', 'Permissões usuario deletadas com successo');
  }

  public function updatePermissionPowerBi()
  {
    $office360token = PowerbiHelper::getOffice360AccessToken();

    $datasetID = env('DATASET_ID_REDE');

    $url = "https://api.powerbi.com/v1.0/myorg/datasets/%s/refreshes";
    $url = sprintf($url,   $datasetID);

    $headers = [
      'Content-Type' => 'application/json',
      'Authorization' => $office360token->token_type . ' ' . $office360token->access_token,
    ];
    $body = [
      "type" => "Full",
      "objects" => [
        [
          "table" => "dRLS"
        ]
      ]
    ];
    $response = Http::withHeaders($headers)->post($url, $body);


    // Verificando a resposta da API
    // Verifica se a requisição foi bem-sucedida
    if ($response->successful()) {
      return Redirect::route('admin.usuarios.permission')->with(['info' => 'Atualizando permissões, aguarde processamento no servidor!']);
    }

    // Se a resposta contiver um erro, trata conforme necessário
    if ($response->clientError()) {
      $responseData = $response->json();

      if (isset($responseData['error']['message']) && $responseData['error']['code'] === 'InvalidRequest') {

        return Redirect::route('admin.usuarios.permission')->with(['warning' => 'Já existe uma solicitação de atualização em andamento. Tente novamente mais tarde.']);
      }
      return Redirect::route('admin.usuarios.permission')->with(['warning' => 'Erro ao atualizar as permissões do Power BI.']);     
      
    }
  }
}
