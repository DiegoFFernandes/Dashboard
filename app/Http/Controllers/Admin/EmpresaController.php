<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmpresaController extends Controller
{

    public function __construct(Empresa $empresa)
    {
        $this->empresa = $empresa;
        //return $this->connection = Auth::user()->conexao;
    }
    public function index()
    {
        //return Auth::user()->conexao ;
        return $this->empresa->empresa();
    }
    public function getEmpresaFiscal()
    {
        $empresas = $this->empresa->EmpresaFiscalAll() ;

        return response()->json($empresas);
    }
}
