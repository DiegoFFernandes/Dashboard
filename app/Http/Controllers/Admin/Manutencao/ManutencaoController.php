<?php

namespace App\Http\Controllers\Admin\Manutencao;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\EtapaMaquina;
use App\Models\EtapasProducaoPneu;
use App\Models\Maquina;
use App\Models\PictureTicket;
use App\Models\Ticket;
use App\Models\TicketAcompanhamento;
use App\Models\User;
use BaconQrCode\Encoder\QrCode;
use Exception;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;
use Yajra\DataTables\Facades\DataTables;
use solutekWpp;

class ManutencaoController extends Controller
{
    public $request, $etapas, $etapa_maquina, $maquina, $tickets, $acompanhamento, $picture, $empresa, $user;

    public function __construct(
        Request $request,
        EtapasProducaoPneu $etapa,
        EtapaMaquina $etapa_maquina,
        Ticket $ticket,
        TicketAcompanhamento $acompanhamento,
        PictureTicket $image,
        Empresa $empresa,
        Maquina $maquina,
    ) {
        $this->request = $request;
        $this->etapas = $etapa;
        $this->etapa_maquina = $etapa_maquina;
        $this->maquina = $maquina;
        $this->tickets = $ticket;
        $this->acompanhamento = $acompanhamento;
        $this->picture = $image;
        $this->empresa = $empresa;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        $title_page   = 'Abrir Chamado Manutenção';
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();
        $maquinas = $this->etapa_maquina->maquinaAll();
        $parada = Ticket::where('maq_parada', 'S')->WhereIn('status', ['R', 'A'])->count();
        $aberto = Ticket::WhereIn('status', ['R', 'A'])->count();
        $finalizado = Ticket::WhereIn('status', ['F'])->count();
        $total = Ticket::count();

        return view('admin.manutencao.index', compact(
            'title_page',
            'user_auth',
            'uri',
            'maquinas',
            'parada',
            'aberto',
            'finalizado',
            'total'

        ));
    }
    public function store()
    {
        $validate = $this->__validate();
        try {
            $etapa_maquina = EtapaMaquina::where('cd_barras', $validate['cd_maq'])->firstOrFail();
        } catch (\Exception $th) {
            return redirect()->route('manutencao.index')->with('error', 'Cód de Qrcode da Maquina não existe, verificar se digitou corretamente ou verifique TI!');
        }

        $validate['cd_empresa'] = $etapa_maquina->cd_empresa;
        $validate['status'] = 'P';
        $this->__validateFile();

        $ticket = $this->tickets->storeData($validate);
        $ticket['cd_user_resp'] = '';
        $ticket['type'] = 'C';

        $find = $this->tickets->ListTickets($user = 0, $ticket, $wpp = 'sim', $status = 0);

        //public function ListTickets($user, $input, $wpp, $status)
        $input = json_decode($find);

        solutekWpp::DataMsgWpp($input, $status = 0);

        $this->acompanhamento->storeData($ticket);

        if ($this->request->hasfile('file')) {

            foreach ($this->request->file('file') as $image) {

                $path = $image->store('image_tickets');

                $pictures = new PictureTicket();
                $pictures->cd_tickets = $ticket->id;
                $pictures->path = $path;

                $pictures->save();
            }
        }
        return redirect()->route('manutencao.index')->with('message', 'Chamado criado com sucesso!');
    }
    public function __validate()
    {
        return $this->request->validate([
            'urgencia' => 'required|string:B,M,A',
            'cd_maq' => 'required|integer',
            'mq_parada' => 'required|string:S,N',
            'tp_chamado' => 'required|string:E,M,P,O',
            'observacao' => 'required|string|max:255'
        ]);
    }
    public function __validateFile()
    {
        return $this->request->validate(
            [
                'file.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096'
            ]
        );
    }
    public function getTickets()
    {
        if ($this->user->hasRole('admin')) {
            $user = 'ver-todos-chamados';
        } else {
            $user = '';
        }

        $status = $this->request->validate(['status' => 'required:in:P,A,F']);

        $data = $this->tickets->ListTickets($user, $input = 0, $wpp = 0, $status);
        return DataTables::of($data)
            ->addColumn('actions', function ($data) {
                $button = '<button type="button" class="btn btn-primary btn-sm" data-id="' . $data->id . '" id="pictures">Fotos</button>';
                $button .= '<button type="button" class="btn btn-warning btn-sm" data-status="' . $data->status . '" data-id="' . $data->id . '" data-maquina="' . $data->maquina . '" id="ticket-andamento">Andamento</button>';
                return $button;
            })
            ->rawColumns(['actions'])
            ->setRowClass(function ($p) {
                if ($p->status == 'Finalizado') {
                    return 'bg-green';
                } elseif ($p->status == 'Reaberto') {
                    return 'bg-yellow';
                } elseif ($p->status == 'Andamento') {
                    return 'bg-aqua';
                }
            })
            ->make(true);
    }
    public function chatTickets()
    {
        $data = $this->acompanhamento->listAcompanhamentoTickets($this->request->id);
        $html = '<div class="direct-chat-messages">';

        foreach ($data as $d) {
            if ($d->type == "C") {
                $html .= '
                    <div class="direct-chat-msg">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left">' . $d->nm_criador . '</span>
                            <span class="direct-chat-timestamp pull-right">' . $d->created_at->diffForHumans() . '</span>
                        </div>
                        <div class="direct-chat-text"> 
                        ' . $d->message . '                           
                        </div>
                    </div>';
            } else {
                $html .= '
                    <div class="direct-chat-msg right">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-right">' . $d->nm_resp . '</span>
                            <span class="direct-chat-timestamp pull-left">' . $d->created_at->diffForHumans() . '</span>
                        </div>
                        <div class="direct-chat-text">
                        ' . $d->message . '
                        </div>
                    </div>
                ';
            }
        }
        $html .=  '</div>';
        return response()->json(['html' => $html]);
    }
    public function statusChamado()
    {
        $ticket = $this->tickets->findOrFail($this->request->cd_ticket);

        if ($this->request->status_ticket == "A") {
            $ticket['status'] = "A";
            $ticket->save();
        } elseif ($this->request->status_ticket == "F") {
            $ticket['status'] = "F";
            $ticket->save();
        }
        $ticket = $this->tickets->findOrFail($this->request->cd_ticket);
        $ticket['type'] = 'C';

        if ($ticket->id_user <> Auth::user()->id) {
            $ticket['type'] = 'R';
            $ticket['cd_user_resp'] = Auth::user()->id;
        }

        $ticket['observacao'] = $this->request->observacao;
        $store = $this->acompanhamento->storeData($ticket);

        $user_create = User::find($store->cd_user_create);
        $store['user_create'] = $user_create->name;
        $store['phone_create'] = $user_create->phone;
        $store['user_resolve'] = $this->user->name;
        $store['phone_resolve'] = $this->user->phone;

        if ($this->request->status_ticket == "A") {
            solutekWpp::DataMsgWpp($store, $status = 'acompanhamento'); //Dispara uma mensagem com acompanhamento.
            return redirect()->route('manutencao.index')->with('message', 'Feito Acompanhamento com sucesso!');
        } else {
            solutekWpp::DataMsgWpp($store, $status = 'finalizado'); //Dispara uma mensagem de finalização.
            return redirect()->route('manutencao.index')->with('message', 'Finalizado com sucesso!');
        }
    }
    public function reOpen()
    {
        $ticket = $this->tickets->findOrFail($this->request->id);
        $ticket['status'] = $this->request->status_ticket;
        $ticket->save();

        return response()->json(['success' => 'Chamado ' . $ticket->id . ', reaberto com sucesso!']);
    }
    public function viewPictures()
    {

        $pictures = PictureTicket::where('cd_tickets', $this->request->id)->get();
        $html = '<div class="col-sm-12" id="pictures-img">';

        foreach ($pictures as $picture) {
            $html .= '<img class="img-responsive" src="' . asset('storage/' . $picture->path) . '" alt="Photo"> <br> ';
        }
        $html .= '</div>';

        return response()->json(['html' => $html]);
    }
    public function machines()
    {

        $title_page   = 'Maquinas cadastradas';
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();
        $etapa_maquina = $this->etapa_maquina->maquinaAll();
        $maquinas = $this->maquina->all();
        $empresas = $this->empresa->EmpresaFiscal(Helper::VerifyRegion($this->user->conexao));
        $etapas = $this->etapas::select('dsetapaempresa', 'cd_etapa as id')->where('cd_etapa', '<>', 0)->get();


        return view('admin.manutencao.machines', compact(
            'title_page',
            'user_auth',
            'uri',
            'maquinas',
            'empresas',
            'etapas',
            'maquinas',
            'etapa_maquina'
        ));
    }
    public function associatePhases()
    {

        $maquina = $this->__validade($this->request);

        if ($maquina->fails()) {
            $error = '<ul>';

            foreach ($maquina->errors()->all() as $e) {
                $error .= '<li>' . $e . '</li>';
            }
            $error .= '</ul>';

            return response()->json([
                'error' => $error
            ]);
        }
        $store = $this->etapa_maquina->StoreData($maquina->validated());
        if ($store) {
            return response()->json(['success' => 'Maquina associada com sucesso, cód.: ' . $store->id]);
        }
    }
    public function __validade($request)
    {
        return Validator::make(
            $request->all(),
            [
                'empresa' => 'required|integer',
                'maquina' => 'required|integer',
                'seq_maquina' => 'required|integer',
                'etapa' => 'required|integer',
            ],
            [
                'empresa.integer' => 'Cód. Empresa de ser do tipo inteiro',
                'empresa.required' => 'Empresa e obrigatória',
                'maquina.integer' => 'Cód. Maquina de ser do tipo inteiro',
                'maquina.required' => 'Maquina é obrigatória',
                'seq_maquina.required' => 'Sequencia da maquina é obrigatória',
                'etapa.required' => 'Etapa do setor é obrigatória.',
            ]
        );
    }
    public function editPhases()
    {
        $etapa = EtapaMaquina::findOrFail($this->request->id);
        return response()->json($etapa);
    }
    public function updatePhases()
    {
        $maquina = $this->etapa_maquina::findOrFail($this->request->id);
        $validate = $this->__validade($this->request);
        $validate = $validate->validated();
        $cd_barras = $validate['empresa'] . $validate['etapa'] . $validate['seq_maquina'];

        $maquina['cd_empresa'] = $validate['empresa'];
        $maquina['cd_maquina'] = $validate['maquina'];
        $maquina['cd_etapa_producao'] = $validate['etapa'];
        $maquina['cd_seq_maq'] = $validate['seq_maquina'];
        $maquina['cd_barras'] = $cd_barras;
        $update = $maquina->save();
        if ($update) {
            return response()->json(['success' => 'Maquina editada com sucesso!']);
        } else {
            return response()->json(['error' => 'Houve algum erro ao Editar favor contatar o TI!']);
        }
    }
    public function SendWpp()
    {
        $store = $this->acompanhamento::find(45);
        // $input = json_decode($store);
        $user_create = User::find($store->cd_user_create);

        $store['user_create'] = $user_create->name;
        $store['phone_create'] = $user_create->phone;
        $store['user_resolve'] = $this->user->name;
        $store['phone_resolve'] = $this->user->phone;


        // return $store;
        return solutekWpp::DataMsgWpp($store, $status = 'finalizado');
    }
}
