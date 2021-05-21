<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
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
  $uri       = $this->resposta->route()->uri();
  $user_auth = $this->user;
  $data      = User::select('users.id', 'users.name', 'users.email', 'users.empresa', 'users.created_at')
   ->leftJoin('model_has_permissions', 'model_has_permissions.permission_id', '=', 'users.id')
   ->groupBy('users.id', 'users.name', 'users.email', 'users.empresa', 'users.created_at')
   ->orderBy('id')->get();
   

  return view('admin.usuarios.permission', compact('uri', 'user_auth', 'data'));
 }
 public function edit(){

 }

 public function update(){

 }
 public function delete(){
     

 }

}
