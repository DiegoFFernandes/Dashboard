<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
 public function __construct(Empresa $empresa)
 {
  $this->empresa = $empresa;
 }
 public function index()
 {
  $users    = User::all();
  $empresas = $this->empresa->empresa();

  return view('admin.usuarios.usuarios', compact('users', 'empresas'));
 }

 public function create(Request $request)
 {
  $user = $this->_validade($request);

  User::create($user);

  return redirect()->route('admin.usuarios.listar')->with('status', 'Usuário criado com sucesso!');

 }

 public function edit(Request $request)
 {

  $users    = User::all();
  $user_id  = User::findOrFail($request->id);
  $empresas = $this->empresa->empresa();

  return view('admin.usuarios.usuarios', compact('user_id', 'users', 'empresas'));
 }

 public function update(Request $request)
 {
  $user = User::findOrFail($request->id);

  $data             = $this->_validade($request);
  $data['name']     = $request->name;
  $data['email']    = $request->email;
  $data['password'] = $request->password;
  $data['empresa']  = $request->empresa;

  $user->fill($data);
  $status = $user->save();
  if ($status == 1) {
   return redirect()->route('admin.usuarios.listar')->with('status', 'Usuário atualizado com sucesso!');
  } else {
   return redirect()->route('admin.usuarios.listar')->with('status', 'Ouve algum erro!');
  }
 }
 public function delete($id){
   $user = User::findOrFail($id);
   $user->delete();
   return redirect()->route('admin.usuarios.listar')->with('status', 'Usuário deletado com sucesso!');
 }

 public function _validade(Request $request)
 {
  return $request->validate(
   [
    'id'       => 'integer',
    'name'     => 'required|max:255',
    'email'    => 'required|email',
    'password' => 'required', 'alpha_num',
    'empresa'  => 'required', 'integer:1,2,3,21,22',
   ],
   ['name.required'    => 'Por favor informe um nome.',
    'email.required'    => 'Por favor informe um email.',
    'password.required' => 'Por favor informe uma senha.',
    'empresa.required'  => 'Por favor informe uma empresa valida.',
   ]
  );

 }
}
