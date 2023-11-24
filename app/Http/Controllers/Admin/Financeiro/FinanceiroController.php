<?php

namespace App\Http\Controllers\Admin\Financeiro;

use App\Exports\ConciliacaoFinanceiraExport;
use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Financeiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Facades\Excel;

class FinanceiroController extends Controller
{
    public $request, $financeiro, $user, $empresa;
    public function __construct(
        Request $request,
        Financeiro $financeiro, 
        Empresa $empresa,
    ) {
        $this->empresa = $empresa;
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
        $empresas =  $this->empresa->EmpresaFiscalAll();
        

        return view('admin.financeiro.index', compact(
            'title_page',
            'user_auth',
            'uri',
            'empresas'
            
        ));
    }
    public function getConciliacao()
    {
        ini_set('max_execution_time', 1500);        
        
        $cd_empresa = $this->request->cd_empresa;
        $dt_ini = $this->request->dt_inicio;
        $dt_fim = $this->request->dt_fim;  
        $nm_empresa = $this->request->nm_empresa;    
        
        $this->financeiro->Conciliacao($cd_empresa, $dt_ini, $dt_fim);
        $myFile = Excel::raw(new ConciliacaoFinanceiraExport(
            $cd_empresa,
            $dt_ini,
            $dt_fim
        ), 'Xlsx');

        $response = array(
            'name' => $nm_empresa."-Conciliacao.xlsx", 
            'file' => "data:application/vnd.ms-excel;base64,".base64_encode($myFile)
        );
        return response()->json($response);
    }
}
