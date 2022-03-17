<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use App\Models\MovimentoVeiculo;
use App\Models\Vendedores;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{

    public function __construct(
        Request $request,
        MovimentoVeiculo $movimento,
        Vendedores $vendedor
    ) {
        $this->resposta = $request;
        $this->movimento = $movimento;
        $this->vendedor = $vendedor;
    }

    public function dashboard()
    {        
        if (Auth::check() === true) {
            $vendedor = $this->vendedor->qtdVendedores();
            $user_auth = Auth::user();
            $uri       = $this->resposta->route()->uri();
            return view('admin.index', compact('user_auth', 'uri', 'vendedor'));
        }

        return redirect()->route('admin.login');
    }

    public function showLoginForm()
    {
        if (Auth::check() === true) {
            $vendedor = $this->vendedor->qtdVendedores();
            $user_auth = Auth::user();
            $uri       = $this->resposta->route()->uri();
            return view('admin.index', compact('user_auth', 'uri'));
        }
        return view('auth.login');
    }

    public function Login(Request $request)
    {
        $credencials = [
            'email'    => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credencials, $request->has('remember'))) {
            $this->authenticated($request->password);
            return redirect()->route('admin.dashborad');
        }
        return redirect()->back()->withInput()->withErrors(['Os dados informados são invalidos!']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.dashborad');
    }

    protected function authenticated($password)
    {
        Auth::logoutOtherDevices($password);
        return redirect()->intended();
    }
}
