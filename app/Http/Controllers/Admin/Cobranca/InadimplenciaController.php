<?php

namespace App\Http\Controllers\Admin\Cobranca;

use Illuminate\Http\Request;
use App\Models\Inadimplencia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Yajra\DataTables\Facades\DataTables;

class InadimplenciaController extends Controller
{
    public function __construct(
        Request $request,
        Inadimplencia $inadimplencia
    ) {
        $this->request = $request;
        $this->inadimplencia = $inadimplencia;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        $title_page   = 'Inadimplência';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();

        $vv = $this->vl_saldo($this->inadimplencia->dividaAll(0));

        $vvencer = $vv['avencer'];
        $vvencer60 = $vv['vvencer60'];
        $vvencer120 = $vv['vvencer120'];
        $vvtotal = $vvencer60 + $vvencer120;
        // $vvencer = $this->dividaMes($this->inadimplencia->dividaAll(0));        
        // $tvvencer = $this->inadimplencia->dividaAll(60);
        // $vvencer60  = $this->dividaMes($tvvencer);
        // $vvencer120 = $this->dividaMes($this->inadimplencia->dividaAll(120));
        // $vvtotal = $vvencer120 + $vvencer60;
        $porcent120 = $vvencer120 / $vvtotal * 100;
        $vchequedesc = $this->cheque($this->inadimplencia->chequeAll('desc'), 0);
        $vchequepre = $this->cheque($this->inadimplencia->chequeAll('pre'), 5);
        $dpdescontada = $this->cheque($this->inadimplencia->dpDescontadas(), 9);
        // $dt60 = Config::get('constants.options.dt60days');
        // $dt120 = Config::get('constants.options.dt120days');

        return view('admin.cobranca.inadimplentes', compact(
            'title_page',
            'user_auth',
            'uri',
            'vvencer60',
            'vvencer120',
            'vvtotal',
            'porcent120',
            'vvencer',
            'vchequepre',
            'vchequedesc',
            'dpdescontada',
            'vv'

        ));
    }
    public function vl_saldo($prazos)
    {
        $vvencer = 0;
        $vvencer60 = 0;
        $vvencer120 = 0;
        foreach ($prazos as $p) {
            $vvencer += $p->AVENCER;
            $vvencer60 += $p->VENCIDO60;
            $vvencer120 += $p->VENCIDO120;
        }
        return array(
            "avencer" => $vvencer,
            "vvencer60" => $vvencer60,
            "vvencer120" => $vvencer120
        );
    }

    public function cheque($chequeAll, $tipo_conta)
    {
        $valor = 0;
        if ($tipo_conta == 5) {
            foreach ($chequeAll as $c) {
                if ($c->CD_TIPOCONTA == 5) {
                    $valor += $c->VL_SALDO;
                }
            }
        } elseif ($tipo_conta == 9) {
            foreach ($chequeAll as $c) {
                $valor += $c->VL_SALDO;
            }
        } else {
            foreach ($chequeAll as $c) {
                $valor += $c->VL_SALDO;
            }
        }
        return $valor;
    }
    public function getVencer()
    {
        $vv = $this->inadimplencia->dividaAll(0);
        return DataTables::of($vv)
            ->addColumn('details_url', function ($v) {
                return route('get-details-vencer', $v->CD_AREACOMERCIAL);
            })
            ->addColumn('porcent', function ($v) {
                return number_format((($v->VENCIDO120 + $v->VENCIDO60) * 100) / ($v->VENCIDO120 + $v->VENCIDO60 + $v->AVENCER), 2, ',', '.');
            })
            ->make(true);
    }
    public function getDetailsArea($id)
    {
        $details = $this->inadimplencia->DetailsArea($id);
        return Datatables::of($details)
            ->addColumn('details_area_url', function ($v) {
                return route('get-details-area-vencer', $v->CD_REGIAOCOMERCIAL);
            })
            ->addColumn('porcent', function ($v) {
                return number_format((($v->VENCIDO120 + $v->VENCIDO60) * 100) / ($v->VENCIDO120 + $v->VENCIDO60 + $v->AVENCER), 2, ',', '.');
            })
            ->make(true);
    }
    public function getDetailsRegiao($id)
    {
        $details = $this->inadimplencia->DetailRegiao($id);
        return Datatables::of($details)
            ->addColumn('porcent', function ($v) {
                return number_format((($v->VENCIDO120 + $v->VENCIDO60) * 100) / ($v->VENCIDO120 + $v->VENCIDO60 + $v->AVENCER), 2, ',', '.');
            })
            ->make(true);
    }
}
