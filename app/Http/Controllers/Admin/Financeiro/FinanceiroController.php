<?php

namespace App\Http\Controllers\Admin\Financeiro;

use App\Exports\ConciliacaoFinanceiraExport;
use App\Http\Controllers\Controller;
use App\Models\Financeiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Facades\Excel;

class FinanceiroController extends Controller
{
    public function __construct(
        Request $request,
        Financeiro $financeiro
    ) {
        $this->request = $request;
        $this->financeiro = $financeiro;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        $title_page   = 'Fechamento Mensal - Empresas';
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();
        

        return view('admin.financeiro.index', compact(
            'title_page',
            'user_auth',
            'uri',
            
        ));
    }
    public function getConciliacao()
    {
        $cd_empresa = $this->request->cd_empresa;
        $dt_ini = $this->request->dt_inicio;
        $dt_fim = $this->request->dt_fim;      
        
        // return $this->financeiro->Conciliacao($cd_empresa, $dt_ini, $dt_fim);
        return Excel::download(new ConciliacaoFinanceiraExport(
            $cd_empresa,
            $dt_ini,
            $dt_fim
        ), 'conciliacao.xlsx');
    }
}
