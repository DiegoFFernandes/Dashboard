<?php

namespace App\Http\Controllers\Admin\Producao;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Epi;
use App\Models\EpiEtapasProducao;
use App\Models\EpisEtapasExecutores;
use App\Models\EtapasProducaoPneu;
use App\Models\ExecutorEtapa;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Yajra\DataTables\Facades\DataTables;

class EpisController extends Controller
{
    public function __construct(
        Request $request,
        Empresa $empresa,
        ExecutorEtapa $executor,
        EtapasProducaoPneu $etapa,
        EpiEtapasProducao $epiEtapa,
        EpisEtapasExecutores $epiexecutor,
        Epi $epis,
    ) {
        $this->request = $request;
        $this->empresa = $empresa;
        $this->executor = $executor;
        $this->etapas = $etapa;
        $this->epi_etapa = $epiEtapa;
        $this->epis = $epis;
        $this->epiexecutor = $epiexecutor;       
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->localizacao =  Helper::VerifyRegion(Auth::user()->conexao);
            return $next($request);
        });
    }
    public function index()
    {
        $title_page   = 'Controle de Epis';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();
        $epis = $this->epis->all();        
        $etapas = $this->etapas->all();        
        // $empresas = $empresas = $this->empresa->EmpresaFiscal($localizacao);
        $executor = $this->executor->ListExecutor($this->localizacao);        

        return view('admin.producao.controle-epi', compact(
            'title_page',
            'user_auth',
            'uri',
            'executor',
            'etapas',
            'epis'
        ));
    }
    public function searchSetores()
    {
        $epis =  $this->epi_etapa->SearchEpisEtapas($this->request->idetapa);
        $html = '<div class="form-group" id="epis-etapa">
                    <label for="executor">Epis da Etapa:</label>';
        foreach ($epis as $e) {
            $html .=  '<div class="checkbox">
                        <label><input type="checkbox" name="epis[]" value="' . $e->id . '">' . $e->ds_epi . '</label>
                   </div>';
        }
        $html .= '</div>';
        return response()->json(['html' => $html]);
    }
    public function SaveEpiSetorOperador()
    {

        $executor = ExecutorEtapa::findOrFail($this->request->executor);
        $etapa = EtapasProducaoPneu::findOrFail($this->request->etapa);
        $episEtapa = $this->epi_etapa->SearchEpisEtapas($this->request->etapa);

        $existe = $this->epiexecutor->VerifyIfExists($executor->id, $etapa->id, date('Y-m-d'));
        if($existe){
            return response()->json(['error' => 'Epis j?? associados para o operador hoje!']); 
        }

        foreach ($episEtapa as $key => $e) {
            if ($e->id_epi == isset($this->request->epis[$key])) {
                $this->epiexecutor->store($executor->id, $etapa->id, $e->id_epi, 'CF');
            } else {
                $this->epiexecutor->store($executor->id, $etapa->id, $e->id_epi, 'NF');
            }
        }
        return response()->json(['success' => 'Epis associados para o operador!']);
    }
    public function RelatorioUsoEpi()
    {        
        if (empty($this->request->data_ini)) {
            $data_ini = 0;
            $data_fim = 0;            
        } else {
            $data_ini = date('Y-m-d H:i', strtotime($this->request->data_ini));
            $data_fim = date('Y-m-d H:i', strtotime($this->request->data_fim));
        }
        $data =  $this->epiexecutor->UsoEpi(
            $this->request->etapa,
            $this->request->executor,
            $this->request->epis,
            $this->request->uso,
            $this->request->localizacao,
            $data_ini,
            $data_fim,
            
        );
        return DataTables::of($data)->make(true);
    }
    public function SearchExecutor(){
        $execJunsoft = $this->executor->searchExecutorEtapaJunsoft();
        $this->executor->StoreExecutorEtapa($execJunsoft, $this->localizacao);
        return response()->json(['success' => 'Executores rede '. $this->localizacao .' sincronizados com sucesso, atualize a p??gina para visualizar novos!']);
    }
}
