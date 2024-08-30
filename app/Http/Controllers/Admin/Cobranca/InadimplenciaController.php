<?php

namespace App\Http\Controllers\Admin\Cobranca;

use Illuminate\Http\Request;
use App\Models\Inadimplencia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Yajra\DataTables\Facades\DataTables;

use function PHPUnit\Framework\isNull;

class InadimplenciaController extends Controller
{
    public $request, $user, $inadimplencia, $banco;

    public function __construct(
        Request $request,
        Inadimplencia $inadimplencia
    ) {
        $this->request = $request;
        $this->inadimplencia = $inadimplencia;

        if (isset($this->request->rede)) {
            $this->banco = $this->request->rede;
        } else {
            $this->banco = 0;
        }

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
        $atevvencer60 = $vv['atevencido60'];
        $atevvencer120 = $vv['atevencido120'];
        $maisvencido120 = $vv['maisvencido120'];

        $vvtotal = $atevvencer60 + $atevvencer120 + $maisvencido120;
        // $vvencer = $this->dividaMes($this->inadimplencia->dividaAll(0));        
        // $tvvencer = $this->inadimplencia->dividaAll(60);
        // $vvencer60  = $this->dividaMes($tvvencer);
        // $vvencer120 = $this->dividaMes($this->inadimplencia->dividaAll(120));
        // $vvtotal = $vvencer120 + $vvencer60;
        $porcent120 = ($atevvencer120 + $maisvencido120) / $vvtotal * 100;
        $vchequedesc = $this->cheque($this->inadimplencia->chequeAll('desc', 0), 0);
        $vchequepre = $this->cheque($this->inadimplencia->chequeAll('pre', 0), 5);
        $dpdescontada = $this->cheque($this->inadimplencia->dpDescontadas(0), 9);
        // $dt60 = Config::get('constants.options.dt60days');
        // $dt120 = Config::get('constants.options.dt120days');

        return view('admin.cobranca.inadimplentes', compact(
            'title_page',
            'user_auth',
            'uri',
            'atevvencer60',
            'atevvencer120',
            'maisvencido120',
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
        $atevencido60 = 0;
        $atevencido120 = 0;
        $maisvencido120 = 0;

        foreach ($prazos as $p) {
            $vvencer += $p->AVENCER;
            $atevencido60 += $p->ATEVENCIDO60;
            $atevencido120 += $p->ATEVENCIDO120;
            $maisvencido120 += $p->MAISVENCIDO120;
        }
        return array(
            "avencer" => $vvencer,
            "atevencido60" => $atevencido60,
            "atevencido120" => $atevencido120,
            "maisvencido120" => $maisvencido120
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
        $vv = $this->inadimplencia->dividaAll($this->banco);
        $banco = $this->banco;
        return DataTables::of($vv)
            ->addColumn('details_url', function ($v) use ($banco) {
                return route('get-details-vencer', [$v->CD_AREACOMERCIAL, 'rede' => $banco]);
            })
            ->addColumn('porcent', function ($v) {
                return number_format((($v->ATEVENCIDO60 + $v->ATEVENCIDO120 + $v->MAISVENCIDO120) * 100) / ($v->ATEVENCIDO60 + $v->ATEVENCIDO120 + $v->MAISVENCIDO120 + $v->AVENCER), 2, ',', '.');
            })
            ->make(true);
    }
    public function getDetailsArea($id)
    {               
        $details = $this->inadimplencia->DetailsArea($id, $this->banco);
        $banco = $this->banco;
        return Datatables::of($details)
            ->addColumn('details_area_url', function ($v) use ($banco) {
                return route('get-details-area-vencer', [$v->CD_REGIAOCOMERCIAL, 'rede' => $banco]);
            })
            ->addColumn('porcent', function ($v) {
                return number_format((($v->ATEVENCIDO60 + $v->ATEVENCIDO120 + $v->MAISVENCIDO120) * 100) / ($v->ATEVENCIDO60 + $v->ATEVENCIDO120 + $v->MAISVENCIDO120 + $v->AVENCER), 2, ',', '.');
            })
            ->make(true);
    }
    public function getDetailsRegiao($id)
    {        
        $details = $this->inadimplencia->DetailRegiao($id, $this->banco);        
        return Datatables::of($details)
            ->addColumn('porcent', function ($v) {
                return number_format((($v->ATEVENCIDO60 + $v->ATEVENCIDO120 + $v->MAISVENCIDO120) * 100) / ($v->ATEVENCIDO60 + $v->ATEVENCIDO120 + $v->MAISVENCIDO120 + $v->AVENCER), 2, ',', '.');
            })
            ->make(true);
    }
}
