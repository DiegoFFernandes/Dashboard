<?php

namespace App\Http\Controllers\Admin\Comercial;

use App\Exports\PedidoPneuLaudoAssociadoRecusadoExport;
use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\PedidoPneu;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ComercialController extends Controller
{
    public $empresa, $request, $user, $pneu, $pedido;

    public function __construct(
        Request $request,
        Empresa $empresa,
        PedidoPneu $pedido

    ) {
        $this->empresa  = $empresa;
        $this->request = $request;
        $this->pedido = $pedido;


        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        $title_page   = 'Indicadores';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();

        return view('admin.diretoria.index', compact('title_page', 'user_auth', 'uri'));
    }

    public function ivoComercialNorte()
    {
        $title_page   = 'Rede Ivorecap - Norte';
        $user_auth    = $this->user;

        $uri         = $this->request->route()->uri();

        return view('admin.comercial.comercial-norte', compact('title_page', 'user_auth', 'uri'));
    }

    public function ivoComercialSul()
    {
        $title_page   = 'Rede Ivorecap - Sul';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();

        return view('admin.comercial.comercial-sul', compact('title_page', 'user_auth', 'uri'));
    }

    public function ivoDiretoriaNorte()
    {
        $title_page   = 'Rede Ivorecap - Norte';
        $user_auth    = $this->user;
        $exploder = explode('/', $this->request->route()->uri());
        $uri          = $exploder[0] . '/' . $exploder[1];
        return view('admin.diretoria.diretoria-norte', compact('title_page', 'user_auth', 'uri'));
    }

    public function ivoDiretoriaRedeFluxo()
    {
        $title_page   = 'Rede Fluxo Caixa';
        $user_auth    = $this->user;
        $exploder = explode('/', $this->request->route()->uri());
        $uri          = $exploder[0] . '/' . $exploder[1];
        return view('admin.diretoria.diretoria-fluxo', compact('title_page', 'user_auth', 'uri'));
    }

    public function ivoDiretoriaSul()
    {

        $title_page   = 'Rede Ivorecap - Sul';
        $user_auth    = $this->user;
        $exploder = explode('/', $this->request->route()->uri());
        $uri          = $exploder[0] . '/' . $exploder[1];

        return view('admin.diretoria.diretoria-sul', compact('title_page', 'user_auth', 'uri'));
    }
    public function ivoDiretoriaRede()
    {
        $title_page   = 'Rede Ivorecap - Rede';
        $user_auth    = $this->user;
        $exploder = explode('/', $this->request->route()->uri());
        $uri          = $exploder[0] . '/' . $exploder[1];;

        return view('admin.diretoria.diretoria-rede', compact('title_page', 'user_auth', 'uri'));
    }

    public function updateStatusPedidoPneuLaudo()
    {
        $data = $this->pedido->listPedidoLaudoRecusado();

        if (empty($data)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nenhum pedido encontrado para atualização.'
            ], 404);
        }

        $results = []; // Array para armazenar os resultados do processamento

        foreach ($data as $d) {
            try {
                $success = $this->pedido->updateStatuPedidoLaudoRecusado($d['IDEMPRESA'], $d['NR_PEDIDO']);

                if ($success) {
                    $results[] = [
                        'empresa' => $d['IDEMPRESA'],
                        'pedido' => $d['NR_PEDIDO'],
                        'pessoa' => $d['NM_PESSOA'],
                        'status' => 'Atualizado'
                    ];
                } else {
                    $results[] = [
                        'empresa' => $d['IDEMPRESA'],
                        'pedido' => $d['NR_PEDIDO'],
                        'pessoa' => $d['NM_PESSOA'],
                        'status' => 'Erro ao Atualizar'
                    ];
                }
            } catch (\Exception $e) {
                // Caso ocorra algum erro, captura a exceção
                $results[] = [
                    'empresa' => $d['IDEMPRESA'],
                    'pedido' => $d['NR_PEDIDO'],
                    'pessoa' => $d['NM_PESSOA'],
                    'status' => "Erro ao atualizar o pedido {$d['NR_PEDIDO']}: " . $e->getMessage(),
                ];
            }
        }

        // Salvar os dados na sessão
        session()->put('export_results', $results);

        // Redirecionar para a página de download
        return redirect()->route('admin.dashboard');
        
        // return Excel::download(new PedidoPneuLaudoAssociadoRecusadoExport($results), 'resultados.xlsx');

    }
}
