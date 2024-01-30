<?php

namespace App\Http\Controllers\admin\Producao;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\EtapasProducaoPneu;
use App\Models\ExecutorEtapa;
use App\Models\MetaOperador;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MetaOperadorController extends Controller
{
    public $request, $empresa, $executor, $etapas, $meta, $user;

    public function __construct(
        Request $request,
        Empresa $empresa,
        ExecutorEtapa $executor,
        EtapasProducaoPneu $etapa,
        MetaOperador $meta
    ) {
        $this->request = $request;
        $this->empresa = $empresa;
        $this->executor = $executor;
        $this->etapas = $etapa;
        $this->meta = $meta;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            //$this->localizacao =  Helper::VerifyRegion(Auth::user()->conexao);
            return $next($request);
        });
    }
    public function index()
    {

        try {
            $etapa = EtapasProducaoPneu::findOrFail($this->request->cd_etapa);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Etapa não encontrado!']);
        }

        $meta_operador = $this->meta->MetaOperadorSetor($this->request->cd_executor, $etapa); 

        if(empty($meta_operador))  {
            return response()->json(['error' => 'Não existe meta de Operador no setor indicado!']);
        }  
        return response()->json([
            'nm_executor' => $meta_operador[0]->NMEXECUTOR,
            'hoje' => $meta_operador[0]->DIAATUAL.'/'.$meta_operador[0]->META,
            'ontem' => $meta_operador[0]->ONTEM.'/'.$meta_operador[0]->META,
            'anteontem' => $meta_operador[0]->ANTEONTEM.'/'.$meta_operador[0]->META,
        ]);
    }
}
