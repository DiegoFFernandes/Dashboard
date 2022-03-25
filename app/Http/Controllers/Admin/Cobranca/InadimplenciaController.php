<?php

namespace App\Http\Controllers\Admin\Cobranca;

use Illuminate\Http\Request;
use App\Models\Inadimplencia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        $dividaAll = $this->inadimplencia->dividaAll();
        $vvencer = 0;
        foreach ($dividaAll as $d) {
            // condição que o Silvio estava usando no power bi, perguntar para ver do que se trata
            // if($d->CD_COBRADOR <> 1 && $d->CD_COBRADOR <> 6 ){
            //     $soma_vencidos += $d->VL_SALDO;
            // } 
            $vvencer += $d->VL_SALDO;
        }
        return view('admin.cobranca.inadimplentes', compact(
            'title_page',
            'user_auth',
            'uri',
            'vvencer'
        ));
        

    }
}
