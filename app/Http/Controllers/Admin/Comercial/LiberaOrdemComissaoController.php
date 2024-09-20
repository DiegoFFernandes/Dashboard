<?php

namespace App\Http\Controllers\Admin\Comercial;

use App\Http\Controllers\Controller;
use App\Models\AreaComercial;
use App\Models\Empresa;
use App\Models\LiberaOrdemComercial;
use App\Models\PedidoPneu;
use App\Models\RegiaoComercial;
use App\Models\User;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class LiberaOrdemComissaoController extends Controller
{
    public $request, $user, $empresa, $libera, $regiao, $area, $pedido;

    public function __construct(
        Request $request,
        Empresa $empresa,
        LiberaOrdemComercial $libera,
        RegiaoComercial $regiao,
        AreaComercial $area,
        User $user,
        PedidoPneu $pedidopneu,

    ) {
        $this->request = $request;
        $this->libera = $libera;
        $this->empresa = $empresa;
        $this->regiao = $regiao;
        $this->area = $area;
        $this->pedido = $pedidopneu;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index()
    {
        $title_page   = 'Liberação Ordens Bloqueadas';
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();

        return view('admin.comercial.libera-ordem', compact(
            'title_page',
            'user_auth',
            'uri'
        ));
    }
    public function getListOrdemBloqueadas()
    {
        $id = 0;
        $pedidos_coordenador = [];
        $pedidos_gerencia = [];

        // $localizacao = Helper::VerifyRegion($this->user->conexao);
        $dados = $this->libera->listPneusOrdensBloqueadas($id);

        $result = [];

        foreach ($dados as $item) {
            $pedido = $item->PEDIDO;
            $pc_desconto = $item->PC_DESCONTO;

            if (!isset($result[$pedido])) {
                $result[$pedido] = [
                    'PEDIDO' => $pedido,
                    'PC_DESCONTO' => []
                ];
            }

            $result[$pedido]['PC_DESCONTO'][] = $pc_desconto;
        }

        $result = array_values($result);

        $pedidos_gerencia = [];
        $pedidos_coordenador = [];

        foreach ($result as $item) {
            $pedidos_gerencia[] = $item['PEDIDO'];
        }

        if ($this->user->hasRole('admin|gerencia|controladoria')) {
            $pedidos = array_unique($pedidos_gerencia);
            //Serealize os pedidos separando em (,)
            $pedidos = implode(",", $pedidos);
            $cd_regiao = "";
        } elseif ($this->user->hasRole('coordenador')) {
            //Criar condição caso o usuario for gerente mais não estiver associado no painel
            $find = $this->area->findAreaUser($this->user->id);
            $regiao = $this->regiao->regiaoArea($find[0]->cd_areacomercial);
            foreach ($regiao as $r) {
                $cd_regiao[] = $r->CD_REGIAOCOMERCIAL;
            }
            //serialize a informação vinda do banco e faz o implode dos valores separados por (;)
            $cd_regiao = implode(",", $cd_regiao);
            $pedidos = array_unique($pedidos_coordenador);
            //Serealize os pedidos separando em (,)
            $pedidos = implode(",", $pedidos);
        }

       $data = $this->libera->listOrdensBloqueadas($cd_regiao, $pedidos);

        return DataTables::of($data)
            ->addColumn('actions', function($d){
                return '<button class="details-control fa fa-plus-circle" aria-hidden="true"></button>
                <button class="details-down fa fa-arrow-down" aria-hidden="true"></button>';               
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
    public function getListPneusOrdemBloqueadas($id)
    {
        $localizacao = Helper::VerifyRegion($this->user->conexao);
        // return $this->user->empresa;
        $data = $this->libera->listPneusOrdensBloqueadas($id, $localizacao);

        return DataTables::of($data)->make(true);
    }
    public function saveLiberaPedido()
    {
        $pedido = $this->pedido->verifyIfExists($this->request->pedido);
        $pedido ? "True" : "False";

        // return $this->request->pneus;

        // $localizacao = Helper::VerifyRegion($this->user->conexao);
        $data = $this->libera->listOrdensBloqueadas("", $this->request->pedido);

        $data[0]->DSLIBERACAO = $data[0]->DSLIBERACAO . ' / (Dash - ' . $this->user->name . ') Obs: ' . $this->request->liberacao;

        foreach($this->request->pneus as $pneu){            
          $this->libera->updateValueItempedidoPneu($pneu);
        }       
        if ($data[0]->TP_BLOQUEIO == "C") //Se bloqueio for igual a Comercial
        {
            $update = $this->pedido->updateData($data[0], $stpedido = 'N', $tpbloqueio = '');
            if ($update) {
                return response()->json(['success' => 'Ordem Liberada com sucesso!']);
            } else {
                return response()->json(['errors' => 'Houve algum erro favor contactar TI!']);
            }
        } elseif ($data[0]->TP_BLOQUEIO == "A") {
            $update = $this->pedido->updateData($data[0], $stpedido = "B", $tpbloqueio = "F");
            if ($update) {
                return response()->json(['success' => 'Ordem Liberada com sucesso, mas ainda falta liberação de credito!']);
            } else {
                return response()->json(['error' => 'Houve algum erro favor contactar TI!']);
            }
        }
    }
    public function CancelaOrdem()
    {
    }
}
