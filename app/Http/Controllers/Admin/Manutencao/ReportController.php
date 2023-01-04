<?php

namespace App\Http\Controllers\Admin\Manutencao;

use App\Models\Ticket;
use App\Models\Empresa;
use App\Models\Maquina;
use App\Models\EtapaMaquina;
use Illuminate\Http\Request;
use App\Models\PictureTicket;
use App\Models\EtapasProducaoPneu;
use App\Http\Controllers\Controller;
use App\Models\TicketAcompanhamento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
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
        $title_page   = 'Relátorio Manutenção';
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();

        return view('admin.manutencao.report', compact(
            'title_page',
            'user_auth',
            'uri'
        ));
    }
    public function tpProblem()
    {

        if ($this->request->inicio <> 0) {
            $validate = $this->request->validate([
                'status' => 'required:in:S,N',
                'inicio' => 'date_format:m/d/Y H:i',
                'fim' => 'date_format:m/d/Y H:i',
                'empresa' => 'integer'
            ]);
            $inicio = Carbon::createFromFormat('m/d/Y H:i', $validate['inicio'])->format('Y-m-d H:i');
            $fim =  Carbon::createFromFormat('m/d/Y H:i', $validate['fim'])->format('Y-m-d H:i');
            $empresa = $validate['empresa'];
        } else {
            $validate = $this->request->validate([
                'status' => 'required:in:S,N'
            ]);
            $inicio = $this->request->inicio;
            $fim = $this->request->fim;
            $empresa = $this->request->empresa;
        }

        $status = $validate['status'];


        $data = $this->tickets->ListTpProblem($status, $inicio, $fim, $empresa);

        return DataTables::of($data)->make(true);
    }
    public function timeTickets()
    {
        if ($this->request->inicio <> 0) {
            $validate = $this->request->validate([
                'inicio' => 'date_format:m/d/Y H:i',
                'fim' => 'date_format:m/d/Y H:i',
                'empresa' => 'integer'
            ]);
            $inicio = Carbon::createFromFormat('m/d/Y H:i', $validate['inicio'])->format('Y-m-d H:i');
            $fim =  Carbon::createFromFormat('m/d/Y H:i', $validate['fim'])->format('Y-m-d H:i');
            $empresa = $validate['empresa'];
        } else {
            $inicio = $this->request->inicio;
            $fim = $this->request->fim;
            $empresa = $this->request->empresa;
        }
        $data = $this->tickets->timeTickets($inicio, $fim, $empresa);

        return DataTables::of($data)->make(true);
    }
    public function averageTickets()
    {
        if ($this->request->inicio <> 0) {
            $validate = $this->request->validate([
                'inicio' => 'date_format:m/d/Y H:i',
                'fim' => 'date_format:m/d/Y H:i',
                'empresa' => 'integer'
            ]);
            $inicio = Carbon::createFromFormat('m/d/Y H:i', $validate['inicio'])->format('Y-m-d H:i');
            $fim =  Carbon::createFromFormat('m/d/Y H:i', $validate['fim'])->format('Y-m-d H:i');
            $empresa = $validate['empresa'];
        } else {
            $inicio = $this->request->inicio;
            $fim = $this->request->fim;
            $empresa = $this->request->empresa;
        }
        $data = $this->tickets->averageTickets($inicio, $fim, $empresa);
        return Datatables::of($data)->make(true);
    }
}
