<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
 public function dashboard()
 {
  if (Auth::check() === true) {
   $user_auth = Auth::user();

   return view('admin.index', compact('user_auth'));
  }
  return redirect()->route('admin.login');
 }

 public function showLoginForm()
 {
  if (Auth::check() === true) {
   $user_auth = Auth::user();
   return view('admin.index', compact('user_auth'));
  }
  return view('auth.login');
 }

 public function Login(Request $request)
 {
  $credencials = [
   'email'    => $request->email,
   'password' => $request->password,
  ];

  if (Auth::attempt($credencials)) {

   return redirect()->route('admin.dashborad');
  }
  return redirect()->back()->withInput()->withErrors(['Os dados informados são invalidos!']);

 }

 public function logout()
 {
  Auth::logout();
  return redirect()->route('admin.dashborad');
 }

}
