<?php

namespace App\Http\Controllers\Admin\Cobranca;

use App\Http\Controllers\Controller;
use App\Models\AgendaPessoa;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class CobrancaController extends Controller
{
    public function __construct(
        Request $request,
        Empresa $empresa,
        AgendaPessoa $agenda

    ) {
        $this->empresa  = $empresa;
        $this->resposta = $request;
        $this->agenda = $agenda;
        $this->p_dia = '1';
        $this->atual_dia = date("d");

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        $p_dia = $this->p_dia;
        $dia_atual = $this->atual_dia;        
        $operadores = $this->agenda->Operadores();     
       
        $meses = $this->agenda->AgendaOperador3Meses();       
        //dd(date('d'));  
        $agenda = $this->agenda->AgendaOperadorMes($operadores);  
                   
        $title_page   = 'Agenda';
        $user_auth    = $this->user;
        $uri         = $this->resposta->route()->uri();
        
        return view('admin.cobranca.index', compact('title_page', 'user_auth', 'uri', 'agenda', 'operadores', 'meses'));
    }
}
