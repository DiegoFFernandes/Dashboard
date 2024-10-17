<?php

namespace App\Http\Controllers\Admin\Compras;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\SolicitacaoCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SolicitacaoCompraController extends Controller
{
    public $request, $user, $empresa, $solicitacao;

    public function __construct(
        Request $request,
        Empresa $empresa,
        SolicitacaoCompra $solicitacao,
    ) {
        $this->empresa = $empresa;
        $this->request = $request;
        $this->solicitacao = $solicitacao;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function liberaPedidoCompras()
    {
        $title_page   = 'Liberação de Pedido de Compra';
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();

        return view('admin.compras.libera-pedido-compra', compact(
            'title_page',
            'user_auth',
            'uri'

        ));
    }

    public function listPedidosCompraBloqueadas()
    {
        $status = $this->request->st_visto;
        $data = $this->solicitacao->SolicitacoesCompraBloqueadas($status);

        return DataTables::of($data)
            ->addColumn('actions', function ($d) {
                return '<button class="details-solicitacao fa fa-align-justify btn-open" aria-hidden="true"></button>
                        <button class="details-motivo fa fa-commenting-o btn-open" aria-hidden="true"></button>
                                                
                        ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function listPedidosCompraBlqueadasItem()
    {

        $nr_solicitacao = $this->request->nr_solicitacao;
        $cd_empresa = $this->request->cd_empresa;

        $data = $this->solicitacao->SolicitacoesCompraBloqueadasItem($nr_solicitacao, $cd_empresa);

        return DataTables::of($data)->make(true);
    }

    public function updateStatusComprasBloqueadas()
    {
        $data = $this->request->all();

        foreach ($data['pedidos'] as $c) {
            $this->solicitacao->updateStatusSolicitacoesCompraBloqueadas(
                $c['cd_empresa'],
                $c['nr_solicitacao'],
                $c['status'],
                mb_convert_encoding($c['ds_observacao'] . ' / (Portal - ' . $this->user->name . ') Motivo: ' . $data['ds_liberacao'], 'ISO-8859-1', 'UTF-8')

            );
            $status = $c['status'];
        }
        if ($status == 'S') {
            return response()->json(['warning' => 'Contas ainda esta bloqueada, movidas para bloqueadas pendentes!']);
        } else {
            return response()->json(['success' => 'Contas liberadas com sucesso!']);
        }
    }
}
