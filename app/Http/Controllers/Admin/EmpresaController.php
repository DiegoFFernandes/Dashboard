<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AreaComercial;
use Illuminate\Http\Request;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmpresaController extends Controller
{
    public $empresa, $area;

    public function __construct(Empresa $empresa, AreaComercial $area)
    {
        $this->empresa = $empresa;

        $this->area = $area;

        //return $this->connection = Auth::user()->conexao;
    }
    public function index()
    {
        return Auth::user()->conexao ;
        return $this->empresa->empresa();
    }
    public function getEmpresaFiscal()
    {
        $empresas = $this->empresa->EmpresaFiscalAll() ;

        return response()->json($empresas);
    }
    public function ImportVendedor(){

       return $this->area->ImportaVendedor();
        
    }
}
