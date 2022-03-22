<?php

namespace App\Http\Controllers\Auth;

use App\Charts\ProducaoChart;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use App\Models\MovimentoVeiculo;
use App\Models\Producao;
use App\Models\Vendedores;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

    public function __construct(
        Request $request,
        MovimentoVeiculo $movimento,
        Vendedores $vendedor,
        Producao $producao
    ) {
        $this->resposta = $request;
        $this->movimento = $movimento;
        $this->vendedor = $vendedor;
        $this->producao = $producao;
    }

    public function dashboard()
    {
        if (Auth::check() === true) {
            $vendedor = $this->vendedor->qtdVendedores();
            $user_auth = Auth::user();
            $uri       = $this->resposta->route()->uri();

            $recapMounth = array_reverse($this->producao->recapMounth());
                    
            foreach ($recapMounth as $r) {
                $mes[] = $r->MES_NOME . ' - ' . $r->ANO;
                $qtd[] = $r->QTDE;
                $meta[] = 10000;
            }

            // Criando um grafico
            $chart = new ProducaoChart;
            $chart->labels($mes);
            $chart->dataset('Recap', 'bar', $qtd)->options([                
                'fill' => 'true',
                'borderColor' => '#51C1C0',
                'backgroundColor' => '#D1F2EB',
                'borderWidth' => 2,
                'color' => '#666'
            ]);
            $chart->dataset('Meta', 'line', $meta)->options([
                'fill' => 'true',
                'borderColor' => '#f39c12',
                'borderWidth' => 1,                
            ]);
            
            return view('admin.index', compact(
                'user_auth',
                'uri',
                'vendedor',
                'chart',
                'recapMounth'
            ));
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
        Session::flush();
        return redirect()->route('admin.dashborad');
    }

    protected function authenticated($password)
    {
        Auth::logoutOtherDevices($password);
        return redirect()->intended();
    }
}
