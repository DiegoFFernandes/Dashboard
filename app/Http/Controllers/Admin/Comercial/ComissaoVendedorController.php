<?php

namespace App\Http\Controllers\Admin\Comercial;

use App\Http\Controllers\Controller;
use App\Models\AreaComercial;
use App\Models\Empresa;
use App\Models\RegiaoComercial;
use App\Models\Vendedores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ComissaoVendedorController extends Controller
{
    public $empresa, $request, $user, $regiao, $area, $vendedor;

    public function __construct(
        Request $request,
        Empresa $empresa,
        RegiaoComercial $regiao,
        AreaComercial $area,
        Vendedores $vendedor

    ) {
        $this->empresa  = $empresa;
        $this->request = $request;
        $this->regiao = $regiao;
        $this->area = $area;
        $this->vendedor = $vendedor;


        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function comissaoLiquidacao()
    {
        $title_page   = 'Comissao Vendedores';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();
        $empresas =  $this->empresa->EmpresaFiscalAll();
        $area = $this->area->areaAll();
        $regiao = $this->regiao->regiaoAll();
        $vendedor = $this->vendedor->listVendedoresAll();

        return view('admin.comercial.comissao-vendedor', compact(
            'title_page',
            'user_auth',
            'uri',
            'empresas',
            'area',
            'regiao',
            'vendedor'
        ));
    }
    public function listComissaoLiquidacao(){
        $data = $this->empresa->EmpresaFiscalAll();

        return DataTables::of($data)->make(true);
    }
}
