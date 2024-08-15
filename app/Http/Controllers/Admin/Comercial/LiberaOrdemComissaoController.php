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

        $localizacao = Helper::VerifyRegion($this->user->conexao);
        $dados = $this->libera->listPneusOrdensBloqueadas($id, $localizacao);

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
            if (min($item['PC_DESCONTO']) > 10) {
                $pedidos_gerencia[] = $item['PEDIDO'];
            }else{
                $pedidos_coordenador[] = $item['PEDIDO'];
            }
        }

        if ($this->user->hasRole('admin|gerencia')) {
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

        $data = $this->libera->listOrdensBloqueadas($cd_regiao, $pedidos, $localizacao);

        return DataTables::of($data)
            // ->addColumn('dsbloqueiopneu', function($d){
            //     $dsbloqueio = $d->DSBLOQUEIO;
            //     $linhas = explode("\n", $dsbloqueio);
            //     $limiteUltrapassado = [];
            //     $duplicatasAtraso = [];
            //     $pessoa = [];
            //     $pneus = [];

            //     foreach ($linhas as $linha) {
            //         // Remover espaços em branco no início e no final da linha
            //         $linha = trim($linha);

            //         if ($linha !== "") {
            //             if (strpos($linha, "Ultrapassou o Limite de Crédito") !== false) {
            //                 // Linha indicando que o limite de crédito foi ultrapassado
            //                 $limiteUltrapassado[] = $linha;
            //             } elseif (strpos($linha, "Duplicatas com Atraso acima do Limite") !== false){
            //                 $duplicatasAtraso[] = $linha;
            //             }
            //             elseif (strpos($linha, "Pessoa.:") !== false){
            //                 $pessoa[] = $linha;
            //             }

            //             else {
            //                 // Linha contendo informações do pneu
            //                 $pneus[] = $linha;
            //             }
            //         }
            //     }  
            //     return $pneus;
            // })
            ->make(true);
    }
    public function getListPneusOrdemBloqueadas($id)
    {
        $localizacao = Helper::VerifyRegion($this->user->conexao);
        // return $this->user->empresa;
        $data = $this->libera->listPneusOrdensBloqueadas($id, $localizacao);

        // dd($data);
        return DataTables::of($data)->make(true);
    }
    public function saveLiberaPedido()
    {
        $pedido = $this->pedido->verifyIfExists($this->request->pedido);
        $pedido ? "True" : "False";

        // $localizacao = Helper::VerifyRegion($this->user->conexao);
        $data = $this->libera->listOrdensBloqueadas("", $this->request->pedido);
        $data[0]->DSLIBERACAO = $data[0]->DSLIBERACAO . ' / (Liberado pelo Dash - ' . $this->user->name . ') Obs: ' . $this->request->liberacao;

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
