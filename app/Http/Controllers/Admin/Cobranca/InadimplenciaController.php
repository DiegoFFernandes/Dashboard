<?php

namespace App\Http\Controllers\Admin\Cobranca;

use Illuminate\Http\Request;
use App\Models\Inadimplencia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

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

        $vvencer = $this->dividaMes($this->inadimplencia->dividaAll(0));
        $vvencer60  = $this->dividaMes($this->inadimplencia->dividaAll(60));
        $vvencer120 = $this->dividaMes($this->inadimplencia->dividaAll(120));
        $vvtotal = $vvencer120 + $vvencer60;
        $porcent120 = number_format($vvencer120 / $vvtotal * 100, 2, ",", ".");

        // $dt60 = Config::get('constants.options.dt60days');
        // $dt120 = Config::get('constants.options.dt120days');

        return view('admin.cobranca.inadimplentes', compact(
            'title_page',
            'user_auth',
            'uri',
            'vvencer120',
            'vvtotal',
            'porcent120',
            'vvencer'
        ));
    }
    public function dividaMes($dividaAll)
    {
        $valor = 0;
        foreach ($dividaAll as $d) {
            // condição que o Silvio estava usando no power bi, perguntar para ver do que se trata            
            // if($d->CD_COBRADOR <> 1 && $d->CD_COBRADOR <> 6 && $d->DT_VENCIMENTO <= $dt120){                
            //         $vvencer120 += $d->VL_SALDO;                            
            // }elseif($d->CD_COBRADOR <> 1 && $d->CD_COBRADOR <> 6 && $d->DT_VENCIMENTO <= $dt60){
            //     $vvencer60 += $d->VL_SALDO;  
            // }
            if ($d->CD_COBRADOR <> 1 && $d->CD_COBRADOR <> 6) {
                $valor += $d->VL_SALDO;
            }
        }
        return $valor;
    }
}
