<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
 public function __construct(Request $request)
 {
  $this->resposta = $request;
  $this->middleware(function ($request, $next) {
   $this->user = Auth::user();
   return $next($request);
  });
 }
 public function index(Request $request)
 {
  $uri = $this->resposta->route()->uri();

  $data = User::select('users.id', 'users.name', 'users.email', 'users.empresa', 'users.created_at')
   ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
   ->groupBy('users.id', 'users.name', 'users.email', 'users.empresa', 'users.created_at')
   ->orderBy('id')->get();
  $user_auth = $this->user;

  return view('admin.usuarios.roles', compact('data', 'user_auth', 'uri'));
 }
 public function edit(Request $request)
 {
  $title     = 'Edição de função de usuários';
  $exploder  = explode('/', $this->resposta->route()->uri());
  $uri       = ucfirst($exploder[1]);
  $user_auth      = $this->user;
  $userId    = User::findOrFail($request->id);
  $roles     = Role::all()->pluck('name');
  $userRoles = $userId->getRoleNames();
  $array1    = json_decode(json_encode($roles), true);
  $array2    = json_decode(json_encode($userRoles), true);
  $all_roles = array_diff($array1, $array2);

  return view('admin.usuarios.user-role', compact(
   'uri', 'user_auth', 'userId', 'roles', 'title', 'userRoles', 'all_roles'
  ));

 }
 public function update(Request $request)
 {
  $user     = User::findOrFail($request->id);
  $userRole = $request->roles;
  //$user->assignRole($userRole);
  $user->syncRoles($userRole);

  return redirect()->route('admin.usuarios.role')->with('status', 'Nova função Usuário atualizado com sucesso!');

 }
 public function create()
 {
  $all_roles = Role::all()->pluck('name');
  $title     = 'Nova função do usuario';
  $user_auth = $this->user;

  $users = User::select('users.id', 'users.name', 'users.email', 'users.empresa', 'users.created_at')
   ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
   ->whereNull('model_has_roles.model_id')
   ->groupBy('users.id', 'users.name')
   ->orderBy('id')->get();
    
  $exploder = explode('/', $this->resposta->route()->uri());
  $uri      = ucfirst($exploder[1]);

  return view('admin.usuarios.user-role', compact(
   'uri', 'users', 'all_roles', 'title', 'user_auth'
  ));
 }
 public function save(Request $request)
 {
     $user = User::findOrFail($request->id);
     $userRole = $request->roles;
     $user->syncRoles($userRole);
     return redirect()->route('admin.usuarios.role')->with('status', 'Nova função usuário criada  com sucesso!');
 }

 public function delete(Request $request)
 {
  return $user = User::findOrFail($request->id);
 }
}
