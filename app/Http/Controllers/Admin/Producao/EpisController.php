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
    public $request, $executor, $etapas, $epi_etapa, $epis, $epiexecutor, $user, $localizacao, $empresa;
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
        $empresas = $this->empresa->EmpresaAll();

        return view('admin.producao.controle-epi', compact(
            'title_page',
            'user_auth',
            'uri',
            'executor',
            'etapas',
            'epis',
            'empresas'
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
        // return $this->request->epis[0];
        // return $this->request;
        $executor = ExecutorEtapa::findOrFail($this->request->executor);
        if ($executor['cd_empresa'] == null) {
            return response()->json(['error' => 'Não existe empresa associada para executor no Junsoft, verifique TRC015!']);
        }
        $etapa = EtapasProducaoPneu::findOrFail($this->request->etapa);
        $episEtapa = $this->epi_etapa->SearchEpisEtapas($this->request->etapa);

        foreach ($episEtapa as $e) {
            $ids_epi[] = $e->id_epi;
        }
        $arrayDiferenca = array_diff($ids_epi, $this->request->epis);

        // return isset($arrayDiferenca[0]);
        $existe = $this->epiexecutor->VerifyIfExists($executor->id, $etapa->id, date('Y-m-d'));

        if ($existe) {
            return response()->json(['error' => 'Epis já associados para o operador hoje!']);
        }

        foreach ($episEtapa as $key => $e) {
            if ($e->id_epi == isset($arrayDiferenca[$key])) {
                // echo $e->ds_epi ." = ". $e->id ."<b> não conforme</b></br>";
                $this->epiexecutor->store($executor->id, $etapa->id, $e->id_epi, 'NF');
            } else {
                // echo $e->ds_epi .": ". $e->id." <b>conforme</b></br>";
                $this->epiexecutor->store($executor->id, $etapa->id, $e->id_epi, 'CF');
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
            $this->request->empresa
        );
        return DataTables::of($data)->make(true);
    }
    public function SearchExecutor($local)
    {
        $local = strtoupper($local);
        $execJunsoft = $this->executor->searchExecutorEtapaJunsoft($local);

        return $this->executor->StoreExecutorEtapa($execJunsoft, $local);

        return response()->json(['success' => 'Executores rede ' . $local . ' sincronizados com sucesso, atualize a página para visualizar novos!']);
    }
}
