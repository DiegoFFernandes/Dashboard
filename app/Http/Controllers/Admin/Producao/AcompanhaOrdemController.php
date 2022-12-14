<?php

//13582
namespace App\Http\Controllers\Admin\Producao;

use App\Http\Controllers\Controller;
use App\Models\AcompanhamentoPneu;
use App\Models\EtapasProducaoPneu;
use App\Models\OrdemProducaoRecap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class AcompanhaOrdemController extends Controller
{
  public $user;
  public $resposta;

  public function __construct(
    Request $request,
    AcompanhamentoPneu $acompanha,
    EtapasProducaoPneu $etapa,
    OrdemProducaoRecap $ordem
  ) {
    $this->request = $request;
    $this->acompanha = $acompanha;
    $this->etapas = $etapa;
    $this->ordem = $ordem;
    $this->middleware(function ($request, $next) {
      $this->user = Auth::user();
      return $next($request);
    });
  }

  public function index(Request $request)
  {
    $exploder  = explode('/', $this->request->route()->uri());
    $uri = $exploder[0] . "/" . $exploder[1];
    $user_auth = $this->user;
    $codigo_barras = $request->codigo_barras;
    $etapas = $this->etapas->all();

    return view('admin.producao.acompanha-ordem', compact(
      'user_auth',
      'uri',
      'codigo_barras',
      'etapas'
    ));
  }

  public function statusOrdem(Request $request)
  {
    $exploder = explode('/', $this->request->route()->uri());
    $uri      = $exploder[0] . "/" . $exploder[1];
    $user_auth     = $this->user;
    $etapas = $this->etapas->all();
    $nr_ordem = $request->nr_ordem;

    /* verifica se a ordem e maior que o limite //VALIDAR ISSO COM UMA FUNÇÃO TA NA GAMBIS se sim redireciona */
    if ($nr_ordem >= 9999999999) {
      $sem_info = 0;
      return view('admin.producao.acompanha-ordem', compact('user_auth', 'uri', 'sem_info', 'nr_ordem', 'etapas'));
    }

    /*Com a informação do usuario, consulta no banco e pega o ID do itempedidopneu*/
    $iditempedidopneu = $this->acompanha->IdOrdemProducao($nr_ordem);
    //var_dump($iditempedidopneu[0]->IDITEMPEDIDOPNEU);
    /*Verifica se a consulta do banco e um array vazio //VALIDAR ISSO COM UMA FUNÇÃO TA NA GAMBIS se sim redireciona */
    if (empty($iditempedidopneu[0]->IDITEMPEDIDOPNEU)) {
      $sem_info = 0;
      return view('admin.producao.acompanha-ordem', compact('user_auth', 'uri', 'sem_info', 'nr_ordem', 'etapas'));
    }
    /*Consulta no Banco através do IDPEDIDOPNEU buscando os setores que a ordem passou RRC016*/
    $status_etapas = $this->acompanha->BuscaSetores($iditempedidopneu[0]->IDITEMPEDIDOPNEU);

    /*Verifica se a consulta do banco e um array vazio //VALIDAR ISSO COM UMA FUNÇÃO TA NA GAMBIS se sim redireciona */
    if ($status_etapas === []) {
      $sem_info = $status_etapas;
      return view('admin.producao.acompanha-ordem', compact('user_auth', 'uri', 'sem_info', 'nr_ordem', 'etapas'));
    }
    /* Consulta informações da ORDEM */
    $info_pneu = $this->acompanha->showDataPneus($nr_ordem);
    return view('admin.producao.acompanha-ordem', compact('user_auth', 'uri', 'status_etapas', 'info_pneu', 'etapas'));
  }

  public function layout()
  {
    return view('site.producao.layout');
  }

  public function validaDados($nr_ordem, $iditempedidopneu)
  {
    $uri  = $this->request->route()->uri();
    $etapas = $this->etapas->all();
    $user = $this->user;
    if ($nr_ordem >= 9999999999 || $iditempedidopneu === []) {
      $sem_info = 0;
      return view('admin.producao.acompanha-ordem', compact('user_auth', 'uri', 'sem_info', 'nr_ordem', 'etapas'));
    }
  }
  public function unlockOrder()
  {
    $ordem = Crypt::decrypt($this->request->nr_ordem);
    $unlock = $this->ordem->UnlockOrdem($ordem);
    if($unlock){      
      return redirect()->route('admin.producao.acompanha.ordem')->with('status', 'Ordem '.$ordem.' Desbloqueada com sucesso!');      
    }
  }
}
