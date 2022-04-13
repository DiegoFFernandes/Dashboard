<?php

namespace App\Http\Controllers\Admin\Cobranca;

use App\Http\Controllers\Controller;
use App\Models\BloqueioPedido;
use App\Models\RegiaoComercial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class BloqueioPedidosController extends Controller
{
    public function __construct(
        Request $request,
        BloqueioPedido $bloqueio,
        RegiaoComercial $regiao,
    ) {
        $this->request = $request;
        $this->bloqueio = $bloqueio;
        $this->regiao = $regiao;

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
        if (!$this->user->hasRole('admin|gerencia|coordenador')) {
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
        if ($this->user->hasRole('admin|gerencia|coordenador')) {
            $cd_regiao = "";
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
            ->make();
    }
    
}
