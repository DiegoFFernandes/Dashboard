<?php

namespace App\Http\Controllers\Admin\Producao;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Epi;
use App\Models\EpiEtapasProducao;
use App\Models\EpisEtapasExecutores;
use App\Models\EtapasProducaoPneu;
use App\Models\ExecutorEtapa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            return $next($request);
        });
    }
    public function index()
    {
        $title_page   = 'Controle de Epis';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();
        $epis = $this->epis->all();
        $executor = $this->executor->searchExecutorEtapaJunsoft();
        $etapas = $this->etapas->all();
        // $this->executor->StoreExecutorEtapa($executor);

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
        foreach ($episEtapa as $key => $e) {
            if ($e->id_epi == isset($this->request->epis[$key])) {
                $this->epiexecutor->store($executor->id, $etapa->id, $e->id_epi, 'CF');
            } else {
                $this->epiexecutor->store($executor->id, $etapa->id, $e->id_epi, 'NF');
            }
        }
        return response()->json('Epis associados para o operador!');
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
            $data_ini,
            $data_fim,
        );
        return DataTables::of($data)->make(true);
    }
}
