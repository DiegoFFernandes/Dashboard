<?php

namespace App\Http\Controllers\Admin\Cobranca;

use App\Charts\AgendaPessoaChart;
use App\Http\Controllers\Controller;
use App\Mail\ValidateDeleteEmailWebhook;
use App\Models\AgendaEnvio;
use App\Models\AgendaPessoa;
use App\Models\Empresa;
use App\Models\Pessoa;
use App\Models\Procedimento;
use App\Models\WebHook;
use Carbon\Carbon;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use stdClass;
use Yajra\DataTables\DataTables;

class CobrancaController extends Controller
{
    public $empresa, $request, $agenda, $envio, $pessoa, $p_dia, $atual_dia, $webhook, $user;
    public function __construct(
        Request $request,
        Empresa $empresa,
        AgendaPessoa $agenda,
        AgendaEnvio $envio,
        Pessoa $pessoa,
        WebHook $webhook,

    ) {
        $this->empresa  = $empresa;
        $this->request = $request;
        $this->agenda = $agenda;
        $this->p_dia = '1';
        $this->atual_dia = date("d");
        $this->pessoa = $pessoa;
        $this->envio = $envio;
        $this->webhook = $webhook;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        //Data inicial e Final do Mes
        $dti = Config::get('constants.options.dti');
        $dtf = Config::get('constants.options.dtf');

        $operadores = $this->agenda->Operadores();
        $meses = $this->agenda->AgendaOperador3Meses();
        $clientesNovos = $this->agenda->CadastroNovos();
        $chartClienteNovos = $this->CarregaVariavel($clientesNovos);
        $chart = $this->CarregaVariavel($meses);
        $agenda = $this->agenda->AgendaOperadorMes($operadores);
        $clientesNovosDia = $this->agenda->ClientesNovos3Mes($operadores);
        $qtdClientesNovosMes = $this->pessoa->QtdClientesNovosMes($dti, $dtf);
        $qtdClientesFormaPagamento  = $this->pessoa->QtdClientesFormaPagamento($dti, $dtf);


        $title_page   = 'Agenda';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();

        return view('admin.cobranca.index', compact(
            'title_page',
            'user_auth',
            'uri',
            'agenda',
            'operadores',
            'meses',
            'chart',
            'clientesNovos',
            'chartClienteNovos',
            'clientesNovosDia',
            'qtdClientesNovosMes',
            'qtdClientesFormaPagamento',
            'dti',
            'dtf'
        ));
    }
    public function listClientFormPgto()
    {
        $list =  $this->pessoa->listClientFormPgto($this->request['fp'], $this->request['dti'], $this->request['dtf']);
        $html = '<table class="table table-bordered" style="font-size: 12px" style="width:100%">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Cnpj/Cpf</th>  
                            <th>Usúario</th> 
                            <th>Cadastro</th>                                 
                        </tr>
                    </thead>
                    <tbody>';
        foreach ($list as $l) {
            $html .= '<tr>';
            $html .= '<td>' . $l->NM_PESSOA . '</td>';
            $html .= '<td>' . $l->NR_CNPJCPF . '</td>';
            $html .= '<td>' . $l->CD_NMUSUARIOCAD . '</td>';
            $html .= '<td>' . Carbon::createFromFormat('Y-m-d', $l->DT_CADASTRO)->format('d/m/Y') . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        return response()->json(['html' => $html]);
    }
    public function qtdClientesNovosMes()
    {
        $dt = Helper::verificaMes($this->request['mes']);
        $qtdClientesNovosMes = $this->pessoa->QtdClientesNovosMes($dt['dti'], $dt['dtf']);
        $qtdCliFp = $this->pessoa->QtdClientesFormaPagamento($dt['dti'], $dt['dtf']);

        return array("qtd" => $qtdClientesNovosMes[0]->QTD, 'dt' => $dt, 'qtdclifp' => $qtdCliFp);
    }
    public function CarregaVariavel($meses)
    {
        foreach ($meses as $m) {
            $keys[] = $m->NM_USUARIO;
            $mes1[] = $m->MES1;
            $mes2[] = $m->MES2;
            $mes3[] = $m->MES3;
        }

        return $this->GeraCharts($keys, $mes1, $mes2, $mes3);
    }
    public function GeraCharts($keys, $mes1, $mes2, $mes3)
    {
        $chart = new AgendaPessoaChart();
        $chart->labels($keys);
        $chart->displaylegend(true);
        $chart->height(180);

        $chart->dataset(Config::get('constants.meses.nMes30'), 'bar', $mes1)
            ->color("rgb(255, 99, 132)")
            ->backgroundcolor("rgb(255, 99, 132)")
            ->fill(false)
            ->linetension(0.1)
            ->dashed([5]);
        $chart->dataset(Config::get('constants.meses.nMes60'), 'bar', $mes2)
            ->options([
                'show'            => true,
                'backgroundColor' => '#D1F2EB',
                'displaylegend' => false
            ]);
        $chart->dataset(Config::get('constants.meses.nMes90'), 'bar', $mes3)->options([
            'show'            => true,
            'backgroundColor' => '#D5DBDB',
        ]);

        return $chart;
    }
    public function DetalheAgenda($cdusuario, $dt)
    {
        $title_page   = 'Detalhes Agenda';
        $user_auth    = $this->user;
        $exploder = explode('/', $this->request->route()->uri());
        $uri       = ucfirst($exploder[1]);

        $dt  = date('m-d-Y', strtotime($dt));
        $detalhes = $this->agenda->Detalhe($cdusuario, $dt);

        return view('admin.cobranca.detalhe-agenda', compact('detalhes', 'user_auth', 'uri'));
    }
    public function ClientesNovos($cdusuario, $dt)
    {
        $title_page   = 'Agenda';
        $user_auth    = $this->user;
        $exploder = explode('/', $this->request->route()->uri());
        $detalhesOperador = $this->agenda->DetalheCadastroClienteOperador($dt, $cdusuario);
        $uri       = ucfirst($exploder[1]);

        return view('admin.cobranca.detalhe-clientes-novos', compact('title_page', 'user_auth', 'uri', 'detalhesOperador'));
    }
    public function ClientesNovosMes(Request $request)
    {
        $dt = Helper::verificaMes($request->dt);
        $datai = $dt['dti'];
        $dataf = $dt['dtf'];
        $data = $this->agenda->ClientesNovosMes($datai, $dataf);

        foreach ($data as $d) {
            $labels[] = $d->NM_USUARIO;
            $mes[] = $d->MES;
        }
        $chartest = new AgendaPessoaChart();
        $chartest->labels(1, 2, 3, 4);
        $chartest->dataset('My dataset 1', 'line', [1, 2, 3, 4]);

        $html = '<table id="table-clientes-novos-mes" class="table table-striped">
                    <thead>
                    <tr>
                        <th style="width:100px">Cód. Usuario</th>
                        <th style="width:100px">Nome</th>
                        <th>Quantidade</th>
                    </tr>
                    </thead>
                <tbody>';
        foreach ($data as $d) {
            $html .= '
                <tr>
                    <td>' . $d->CD_USUARIO . '</td>
                    <td>' . $d->NM_USUARIO . '</td>
                    <td>' . $d->MES . '</td>
                </tr>';
        }
        $html .= '</tbody>';
        // return $html;
        return response()->json(['html' => $html, 'labels' => $labels, 'qtd' => $mes]);
    }
    public function AgendaData(Request $request)
    {
        $dt = Helper::verificaMes($request->dt);
        $datai = $dt['dti'];
        $dataf = $dt['dtf'];
        $data = $this->agenda->AgendaMes($datai, $dataf);
        $html = '<table id="table-agenda-mes" class="table table-striped">
                <thead>
                <tr>
                    <th style="width:100px">Cód. Usuario</th>
                    <th style="width:100px">Nome</th>
                    <th>Quantidade</th>
                </tr>
                </thead>
                <tbody>';
        foreach ($data as $d) {
            $html .= '
                <tr>
                    <td>' . $d->CD_USUARIO . '</td>
                    <td>' . $d->NM_USUARIO . '</td>
                    <td>' . $d->MES . '</td>
                </tr>';
        }
        $html .= '</tbody>';
        return $html;
    }
    public function testeChart()
    {
        $api = url(route('cobranca.chart-api'));
        $chart = new AgendaPessoaChart;
        $chart->labels(['One', 'Two', 'Three', 'Four']);
        // $chart->labels(['One', 'Two', 'Three', 'Four'])->load($api);
        $chart->dataset('My dataset', 'line', [1, 2, 3, 4]);

        return view('admin.cobranca.teste', compact('chart'));
    }
    public function chartLineAjax(Request $request)
    {
        $chart = new AgendaPessoaChart;

        $chart->dataset('New User Register Chart', 'line', [1, 2, 3, 4])
            ->options([
                'fill' => 'true',
                'borderColor' => '#51C1C0'
            ]);

        return $chart->api();
    }
    public function searchEnvio()
    {
        $title_page  = 'Agenda';
        $user_auth   = $this->user;
        $uri         = $this->request->route()->uri();
        $contexto    = $this->envio->contextoEmail();
        // return $search = $this->envio->searchSend(7369, 201);

        return view("admin.cobranca.search-follow-up", compact('uri', 'contexto'));
    }
    public function getSearchEnvio(Request $request)
    {
        $search = $this->envio->searchSend($request);
        // return $search[0]->BI_ANEXORELAT;

        $html = '<table id="table-search" class="table table-striped" style="width:100%; font-size: 12px">
                    <thead>
                        <tr>                    
                            <th>Descrição</th>                            
                            <th>Nr Agenda</th>                            
                            <th style="width: 15%">Nome</th>
                            <th>Dt Envio</th>
                            <th>Ações</th>                            
                        </tr>
                    </thead>
                    <tbody>';
        foreach ($search as $s) {
            $exploder        = explode('\\', $s->BI_ANEXORELAT);
            $anexo = is_null($s->BI_ANEXORELAT) ? '<button class="btn btn-danger btn-xs">Não Existe</button>' : '<a href="file:///\\172.29.0.2/' . $exploder[2] . '/' . $exploder[3] . '/' . $exploder[4] . '/' . $exploder[5] . '" class="btn btn-xs btn-primary">Anexo</a>';
            $email = '<button class="btn btn-default btn-xs ver-email" data-id="' . $s->NR_ENVIO . '" aria-hidden="true"> Ver E-mail </button>';
            $reenviar = '<button class="btn btn-warning btn-xs reenviar-email" data-id="' . $s->NR_ENVIO . '" aria-hidden="true"> Reenviar </button>';
            //var_dump($exploder);
            $html .= '
                    <tr>                    
                        <td>' . $s->DS_CONTEXTO . '</td>                        
                        <td>' . $s->NR_AGENDA . '</td>                        
                        <td>' . $s->CD_PESSOA . '-' . $s->NM_PESSOA . '</td>
                        <td>' . $s->DT_ENVIO . '</td>
                        <td>' . $anexo . ' ' . $email . ' ' . $reenviar . '</td>
                        
                    </tr>';
        }
        $html .= '</tbody>
                ';
        return $html;
    }
    public function getEmailEnvio($nr_envio)
    {
        $email = $this->envio->verEmail($nr_envio);
        return $email;
    }
    public function reenviaFollow()
    {        
        $reenvio = $this->envio->reenviaFollow($this->request->nr_envio, $this->request->email);
        if ($reenvio) {
            return response()->json(['success' => 'Reenviado com sucesso, pode demorar até 5 minutos para chegar ao destinatario!']);
        } else {
            return response()->json(['error' => 'Houve algum erro ao reenviar, contate setor de TI!']);
        }
    }
    public function getSubmitIagente()
    {
        $email = WebHook::all();
        foreach ($email as $e) {
            $arryEmail[] = $e->email;
        }
        $arry = implode(",", $arryEmail);
        $arryPrepar = str_replace(",", "','", $arry);
        $data = $this->webhook->emailsDifferents($arryPrepar);
        return DataTables::of($data)
            ->addColumn('action', function ($d) {
                return '<button id="validar-email" class="btn btn-success btn-xs" data-cdpessoa="' . $d->DS_EMAIL . '">Validar</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function DeleteEmailWebhookIagente()
    {
        try {
            $email = WebHook::where('email', $this->request->email)->firstOrFail();
            $email->delete();
            // Mail::send(new ValidateDeleteEmailWebhook($this->user, $this->request));
            return response()->json(['success' => 'Email validado, não irá aparecer na lista, até que haja um novo problema!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Houve algum erro ao validar o email, contacte o setor de TI']);
        }
    }
}
