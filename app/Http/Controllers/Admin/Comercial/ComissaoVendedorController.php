<?php

namespace App\Http\Controllers\Admin\Comercial;

use App\Http\Controllers\Controller;
use App\Models\AreaComercial;
use App\Models\ComissaoVendedor;
use App\Models\Empresa;
use App\Models\RegiaoComercial;
use App\Models\Vendedores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ComissaoVendedorController extends Controller
{
    public $empresa, $request, $user, $regiao, $area, $vendedor, $comissao;

    public function __construct(
        Request $request,
        Empresa $empresa,
        RegiaoComercial $regiao,
        AreaComercial $area,
        Vendedores $vendedor,
        ComissaoVendedor $comissao

    ) {
        $this->empresa  = $empresa;
        $this->request = $request;
        $this->regiao = $regiao;
        $this->area = $area;
        $this->vendedor = $vendedor;
        $this->comissao = $comissao;


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
        $allEmpresas = 1;

        return view('admin.comercial.comissao-vendedor', compact(
            'title_page',
            'user_auth',
            'uri',
            'empresas',
            'area',
            'regiao',
            'vendedor',
            'allEmpresas'
        ));
    }
    public function listComissaoLiquidacao()
    {       
        $dt_inicio = $this->request->data_ini;
        $dt_fim = $this->request->data_fim;
        $validate = self::_validate();

        // return $validate->errors();
        // return $validate->validated();
        $data = $this->comissao->listComissaoLiquidacao($validate->validated(), $dt_inicio, $dt_fim);

        return DataTables::of($data)->make(true);
    }

    public function _validate()
    {
        return Validator::make(
            $this->request->all(),
            [
                'cd_empresa' => 'required|integer',                
                'cd_vendedor' => 'integer'
            ]
        );
    }
}
