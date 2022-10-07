<?php

namespace App\Http\Controllers\Admin\Client;

use App\Http\Controllers\Controller;
use App\Models\AgendaEnvio;
use App\Models\AgendaPessoa;
use App\Models\AnexoCliente;
use App\Models\Contas;
use App\Models\Empresa;
use App\Models\Pessoa;
use App\Models\Procedimento;
use App\Models\WebHook;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ClientAnexoController extends Controller
{
    public function __construct(
        Request $request,
        Empresa $empresa,
        AgendaPessoa $agenda,
        AgendaEnvio $envio,
        Pessoa $pessoa,
        WebHook $webhook,
        Contas $contas,
    ) {
        $this->empresa  = $empresa;
        $this->request = $request;
        $this->agenda = $agenda;
        $this->pessoa = $pessoa;
        $this->envio = $envio;
        $this->webhook = $webhook;
        $this->tickets = $contas;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        $title_page   = 'Parcelas pendentes';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();

        return view('admin.clientes.index', compact('title_page', 'user_auth', 'uri'));
    }
    public function getTickesPendents()
    {
        $cd_empresa = $this->user->empresa;
        $cd_pessoa = $this->user->cd_pessoa;
        $data = $this->tickets->TicketsPendentsClient($cd_pessoa, $cd_empresa);
        return DataTables::of($data)
            ->addColumn('action', function ($d) {
                if ($d->CD_FORMAPAGTO == "DD") {
                    return  '<button class="btn btn-xs btn-default disabled">Descontado</button>';
                } else {
                    return   '<button class="btn btn-xs btn-danger" id="btnDoc" data-documento="' . $d->DOCUMENTO . '">Imprimir</button>';
                }
            })
            // <button class="btn btn-xs btn-success" id="btnNF" data-documento="' . $d->DOCUMENTO . '">NFs-e</button>
            ->setRowClass(function ($d) {
                $today = Carbon::now()->format('Y-m-d');
                $vencimento = Carbon::createFromFormat('Y-m-d', $d->DT_VENCIMENTO)->format('Y-m-d');
                if ($vencimento < $today) {
                    return 'bg-yellow';
                }
            })
            ->make(true);
    }
    public function saveTickets()
    {
        $request = new Request();
        $request->cd_pessoa = $this->user->cd_pessoa;
        $request->cd_number = $this->request->doc;
        $request->nr_contexto = '33, 37, 41, 47';

        $search = $this->envio->searchSend($request);
        if (empty($search)) {
            return response()->json(["error" => 'Arquivo não encontrado, favor entrar em contato com setor de TI Ivorecap!']);
        }
        $file =  $search[0]->BI_ANEXORELAT;
        $anexo = AnexoCliente::where('nr_documento', $this->request->doc)
            ->where('nr_contexto', $search[0]->NR_CONTEXTO)
            ->where('cd_pessoa', $this->user->cd_pessoa)
            ->first();
        if (!$anexo) {
            $exploder = explode('\\', $file); // Fazer o exploder e pegar o nome do Arquivo.....
            $path = '\\\172.29.0.2/' . $exploder[2] . '/' . $exploder[3] . '/' . $exploder[4] . '/' . $exploder[5] . '';
            Storage::disk('public')->put('anexos/' . $exploder[5] . '', file_get_contents($path));

            $anexo = new AnexoCliente();
            $anexo->cd_pessoa = $this->user->cd_pessoa;
            $anexo->nr_documento = $this->request->doc;
            $anexo->nm_pessoa = $search[0]->NM_PESSOA;
            $anexo->nr_contexto = $search[0]->NR_CONTEXTO;
            $anexo->ds_contexto = $search[0]->DS_CONTEXTO;
            $anexo->path = 'anexos/' . $exploder[5] . ''; //incluir o nome via exploder acima        
            $anexo->save();
        }
        // http://portal.ivorecap.com.br/area-do-cliente/save-tickets-pdf?doc=16396
        return response()->json(["url" => env('APP_URL') . '/area-do-cliente/view-tickets-pdf/' . $this->user->cd_pessoa . '/' . $anexo->path . '']);
    }
    public function viewPdfTicket($cliente, $path, $anexo)
    {
        $path_ =  $path . '/' . $anexo;
        if ($this->user->cd_pessoa <> $cliente) {
            return abort(404);
        }
        $exists = Storage::disk('public')->exists($path_);
        if ($exists) {
            //get content of image
            $content = Storage::get($path_);
            //get mime type of image
            $mime = Storage::mimeType($path_);
            //prepare response with image content and response code
            $response = Response::make($content, 200);
            //set header
            $response->header("Content-Type", $mime);
            // return response
            return $response;
        } else {
            return abort(404);
        }
    }
}
