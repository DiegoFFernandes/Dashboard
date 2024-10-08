<?php

namespace App\Http\Controllers\Admin\Cobranca;

use App\Http\Controllers\Controller;
use App\Models\AcompanhamentoPneu;
use App\Models\AreaComercial;
use App\Models\BloqueioPedido;
use App\Models\RegiaoComercial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\DataTables as DataTablesDataTables;
use Yajra\DataTables\Facades\DataTables;

class BloqueioPedidosController extends Controller
{
    public $request, $bloqueio, $regiao, $area, $acompanha, $user;

    public function __construct(
        Request $request,
        BloqueioPedido $bloqueio,
        RegiaoComercial $regiao,
        AreaComercial $area,
        AcompanhamentoPneu $acompanha,
    ) {
        $this->request = $request;
        $this->bloqueio = $bloqueio;
        $this->regiao = $regiao;
        $this->area = $area;
        $this->acompanha = $acompanha;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index()
    {
        $title_page   = 'Pedidos Bloqueados';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();
        if ($this->user->hasRole('coordenador')) {
            //Criar condição caso o usuario for gerente mais não estiver associado no painel
            $find = $this->area->findAreaUser($this->user->id);
            $array = json_decode($find, true);
            if (empty($array)) {
                return Redirect::route('admin.dashboard')->with('warning', 'Usuario com permissão  de coordenador mais sem vinculo com área, fale com o Administrador do sistema!');
            }
        } elseif (!$this->user->hasRole('admin|gerencia')) {
            $regiaoUsuario = $this->regiao->regiaoPorUsuario($this->user->id);
            foreach ($regiaoUsuario as $r) {
                $cd_regiao[] = $r->cd_regiaocomercial;
            }
            //verifica se o usuario tem permissão mais ainda nao foi associado região para ele e retorna com mensagem!
            if (empty($cd_regiao)) {
                return redirect()->back()->with('warning', 'Usuario com permissão mais sem vinculo com região, fale com o Administrador do sistema!');
            }
        }

        return view('admin.cobranca.bloqueio-pedidos', compact('title_page', 'user_auth', 'uri'));
    }
    public function getBloqueioPedido()
    {
        if ($this->user->hasRole('admin')) {
            $cd_regiao = "";
        } elseif ($this->user->hasRole('coordenador|gerencia')) {
            //Criar condição caso o usuario for gerente mais não estiver associado no painel
            $area = $this->area->findAreaUser($this->user->id);

            $cd_area = [];
            foreach ($area as $a) {
                $cd_area[] = $a['cd_areacomercial'];
            }
            $cd_area = implode(",", $cd_area);

            $regiao = $this->regiao->regiaoArea($cd_area);
            foreach ($regiao as $r) {
                $cd_regiao[] = $r->CD_REGIAOCOMERCIAL;
            }
            //serialize a informação vinda do banco e faz o implode dos valores separados por (;)
            $cd_regiao = implode(",", $cd_regiao);
        } else {
            $regiaoUsuario = $this->regiao->regiaoPorUsuario($this->user->id);
            foreach ($regiaoUsuario as $r) {
                $cd_regiao[] = $r->cd_regiaocomercial;
            }
            //serialize a informação vinda do banco e faz o implode dos valores separados por (;)
            $cd_regiao = implode(",", $cd_regiao);
        }

        $bloqueio = $this->bloqueio->BloqueioPedido($cd_regiao);

        return DataTables::of($bloqueio)
            ->addColumn('action', function ($b) {
                return '<a href="https://api.whatsapp.com/send?phone=+5541985227055&text=
                Olá,%20meu%20pedido%20' . $b->PEDIDO . ',%20cliente%20' . $b->CLIENTE . '%20está%20bloqueado%20com%20motivo%20'
                    . $b->MOTIVO . '%20poderiam%20verificar?" id="ver-itens" class="btn btn-success btn-sm">
                Avisar Whats</a>';
            })
            ->addColumn('status_cliente', ' ')
            ->addColumn('status_scpc', ' ')
            ->addColumn('status_pedido', ' ')
            ->editColumn('status_cliente', function ($row) {
                return $row->ST_ATIVA && BloqueioPedido::STATUS_CLIENTE[$row->ST_ATIVA] ? BloqueioPedido::STATUS_CLIENTE[$row->ST_ATIVA] : 'none';
            })
            ->editColumn('status_scpc', function ($row) {
                return $row->ST_SCPC && BloqueioPedido::STATUS_SCPC[$row->ST_SCPC] ? BloqueioPedido::STATUS_SCPC[$row->ST_SCPC] : 'none';
            })
            ->editColumn('status_pedido', function ($row) {
                return $row->STPEDIDO && BloqueioPedido::STATUS_PEDIDO[$row->STPEDIDO] ? BloqueioPedido::STATUS_PEDIDO[$row->STPEDIDO] : 'none';
            })
            ->make();
    }
    public function getPedidoAcompanhar()
    {
        if ($this->user->hasRole('admin')) {
            $cd_regiao = "";
        } elseif ($this->user->hasRole('coordenador|gerencia')) {
            $find = $this->area->findAreaUser($this->user->id);
            $area = json_decode($find, true);
            if (empty($area)) {
                return Redirect::route('admin.dashboard')->with('warning', 'Usuario com permissão de coordenador mais sem vinculo com área, fale com o Administrador do sistema!');
            }
            $cd_area = [];
            foreach ($area as $a) {
                $cd_area[] = $a['cd_areacomercial'];
            }
            $cd_area = implode(",", $cd_area);

            $regiao = $this->regiao->regiaoArea($cd_area);

            $cd_regiao = [];
            foreach ($regiao as $r) {
                $cd_regiao[] = $r->CD_REGIAOCOMERCIAL;
            }
            //serialize a informação vinda do banco e faz o implode dos valores separados por (;)
            $cd_regiao = implode(",", $cd_regiao);
        } else {
            $regiaoUsuario = $this->regiao->regiaoPorUsuario($this->user->id);
            foreach ($regiaoUsuario as $r) {
                $cd_regiao[] = $r->cd_regiaocomercial;
            }
            //serialize a informação vinda do banco e faz o implode dos valores separados por (;)
            $cd_regiao = implode(",", $cd_regiao);
        }

        $pedidos = $this->acompanha->ListPedidoPneu($cd_regiao);
        return DataTables::of($pedidos)
            
            ->addColumn('actions', function ($d) {
                return '<button class="details-control fa fa-plus-circle" aria-hidden="true"></button>';
            })
            ->rawColumns(['actions'])
            ->setRowClass(function ($p) {
                if ($p->STPEDIDO == "ATENDIDO        ") {
                    return 'bg-green';
                } elseif ($p->STPEDIDO == "EM PRODUCAO     ") {
                    return 'bg-yellow';
                } elseif ($p->STPEDIDO == "BLOQUEADO       ") {
                    return 'bg-red';
                }
            })
            ->make();
    }
    public function getItemPedidoAcompanhar()
    {
        $itempedidos = $this->acompanha->ItemPedidoPneu($this->request->id);
        return DataTables::of($itempedidos)
            ->addColumn('details_item_pedido_url', function ($i) {
                return route('get-detalhe-item-pedido', $i->ID);
            })
            ->make();
    }

    public function getDetalheItemPedidoAcompanhar($nrordem)
    {
        // return $nrordem;
        $detalhe_ordem = $this->acompanha->BuscaSetores($nrordem);
        return DataTables::of($detalhe_ordem)
            ->addColumn('entrada', function ($d) {
                return \Carbon\Carbon::createFromFormat('Y-m-d', $d->O_DT_ENTRADA)
                    ->format('d/m/Y') . ' ' . $d->O_HR_ENTRADA;
            })
            ->addColumn('saida', function ($d) {
                return \Carbon\Carbon::createFromFormat('Y-m-d', $d->O_DT_SAIDA)
                    ->format('d/m/Y') . ' ' . $d->O_HR_SAIDA;
            })
            ->make();
    }
}
