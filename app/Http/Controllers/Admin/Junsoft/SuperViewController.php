<?php

namespace App\Http\Controllers\Admin\Junsoft;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SuperViewController extends Controller
{
    public $user, $request, $empresas, $view;

    public function __construct(
        Request $request,
        Empresa $empresa,
        View $view,

    ) {
        $this->request = $request;
        $this->empresas = $empresa;
        $this->view = $view;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function coletaRede()
    {
        $title_page   = '000 - Coletas Rede';
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();



        return view('admin.superview.coleta-rede', compact(
            'uri',
            'user_auth',
            'title_page'
        ));
    }
    public function listColetas()
    {
        if ($this->request->id == 3) {
            $data = $this->view->coletas($this->request->id);
            $somaMeses = [];
            $nomeMes = [];
            $qtdMes = [];
            foreach ($data as $d) {
                $mes = $d['DS_MES'];
                $qtde = (int)$d['QTDE'];

                if (isset($somaMeses[$mes])) {
                    $somaMeses[$mes] += $qtde;
                } else {
                    $nomeMes[] = $d['DS_MES'];   //traz o meses agrupado                
                    $somaMeses[$mes] = $qtde;
                }
            }
            foreach ($somaMeses as $s) {
                $qtdMes[] = $s;
            }

            return response()->json(['labels' => $nomeMes, 'qtd' => $qtdMes]);
        } elseif ($this->request->id == 6) {
            $data = $this->view->pedidosNaoIniciados();
        } else {
            $data = $this->view->coletas($this->request->id);
        }

        return DataTables::of($data)->make(true);
    }
    public function pedidoNaoIniciadosProd() {}
}
