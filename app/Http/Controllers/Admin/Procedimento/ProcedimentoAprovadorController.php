<?php

namespace App\Http\Controllers\Admin\Procedimento;

use App\Http\Controllers\Controller;
use App\Mail\ProcedimentoChatMail;
use App\Mail\ProcedimentoMail;
use App\Models\Procedimento;
use App\Models\ProcedimentoAprovador;
use App\Models\ProcedimentoRecusado;
use App\Models\Setor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables as DataTablesDataTables;
use Yajra\DataTables\Facades\DataTables;

class ProcedimentoAprovadorController extends Controller
{
    public function __construct(
        Request $request,
        Procedimento $procedimento,
        ProcedimentoAprovador $aprovador,
        ProcedimentoRecusado $recusa
    ) {
        $this->request = $request;
        $this->procedimento = $procedimento;
        $this->aprovador = $aprovador;
        $this->recusa = $recusa;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        $title_page   = 'Procedimentos';
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();
        $role = $this->user->hasRole('admin');
        $data = $this->aprovador->listData($role);

        return view('admin.qualidade.procedimento-autorizador', compact('title_page', 'user_auth', 'uri', 'data'));
    }
    public function GetProcedimentoAprovador()
    {
        $role = $this->user->hasRole('admin');
        $data = $this->aprovador->listData($role);
        return DataTables::of($data)
            ->addColumn('Actions', function ($data) {
                return '<a class="btn btn-danger btn-sm btn-pdf" href="' . route('procedimento.show-pdf', ['arquivo' => $data->path]) . '" target="_blank">Visualizar PDF</a>
                <button type="button" class="btn btn-success btn-sm" data-id="' . $data->id . '" id="getSave">Autorizar</button>
                ';
            })
            ->rawColumns(['situacao', 'Actions'])
            ->make(true);
    }
    public function GetProcedimentoReprovado()
    {
        $data = $this->recusa->listaData($this->request->id);
        return DataTables::of($data)
            ->addColumn('actions', function ($data) {
                return '<button type="button" class="btn btn-default btn-sm data-id="' . $data->id . '" id="view-motivo-procedimento"><i class="fa fa-eye" aria-hidden="true"></i></button>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
    public function chatProcedimentoReprovado()
    {
        $data = $this->recusa->listDataReproved($this->request->data);
        $html = '<div class="direct-chat-messages">';

        foreach ($data as $d) {
            if ($d->type == "A") {
                $html .= '
                    <div class="direct-chat-msg">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left">' . $d->nm_create . '</span>
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
                            <span class="direct-chat-name pull-right">' . $d->nm_approver . '</span>
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
    public function store()
    {
        // return $this->request;
        $data_aprovador = $this->__validate($this->request);
        $procedimento = Procedimento::findOrFail($data_aprovador['id']);
        $setor = Setor::findOrFail($procedimento['id_setor']);
        $user = User::findOrFail($procedimento['id_user_create']);

        if ($data_aprovador['status'] == "R") {
            $procedimento->status = $data_aprovador['status']; //recebe o status de recusado
            $procedimento->save(); //Salva na tabela procecimento o status de recusado!
            $this->aprovador->updateData($data_aprovador); //Atualiza a tabela procedimento_aprovador com o status de recusado!
            $this->recusa->storeData($procedimento, $data_aprovador); //Salva na tabela procedimento_recusados o status de recusado!
            // return new ProcedimentoMail($this->request, $setor, $user);
            Mail::send(new ProcedimentoMail($this->request, $setor, $user));            
            return response()->json(['alert' => 'Procedimento foi recusado!']);
        } else {
            // $procedimento->status = 'P';
            // $procedimento->save();
            $this->verifyIfRealeased($procedimento);
            $this->aprovador->updateData($data_aprovador);
            Mail::send(new ProcedimentoMail($this->request, $setor, $user)); 
            return response()->json(['success' => 'Procedimento aprovado com sucesso!']);
        }
    }
    public function verifyIfRealeased($procedimento)
    {
        //Consulta no banco como está o Status do procedimento por Aprovador
        $status = $this->aprovador->verifyIfReleased($procedimento->id);
        foreach ($status as $s) {
            $array[] = $s->aprovado;
        }
        if (in_array('R', $array)) {
            $procedimento['status'] = 'R';
            return $procedimento->save();
        } elseif (in_array('A', $array)) {
            $procedimento['status'] = 'P';
            return $procedimento->save();
        } elseif (!in_array('A', $array) || !in_array('R', $array)) {
            $procedimento['status'] = 'P';
            return $procedimento->save();
        }
        return false;
    }
    public function updateReplica()
    {        
        $data = $this->request->validate([
            'id' => 'required|integer',
            'user_approver' => 'required|integer',
            'user_created' => 'required|integer',
            'description' => 'required',
        ]);
        ProcedimentoRecusado::where('id_procedimento', $data['id'])->firstOrFail();

        $replica = new ProcedimentoRecusado();
        $replica->id_procedimento = $data['id'];
        $replica->id_user_create = $data['user_created'];
        $replica->id_user_approver = $data['user_approver'];
        $replica->message = $data['description'];
        $replica->type = 'C';
        $replica->save();        
       
        Mail::send(new ProcedimentoChatMail($data));
        return redirect()->route('procedimento.index')->with('status', 'Replica feita com sucesso!');
    }
    public function __validate($request)
    {
        if ($request->status == 'R') {
            return $request->validate([
                'user' => 'required|integer',
                'status' => 'required|string:R,L',
                'id' => 'required|integer',
                'description_recusa' => 'required:string'
            ]);
        }

        return $request->validate([
            'user' => 'required|integer',
            'status' => 'required|string:R,L',
            'id' => 'required|integer'
        ]);
    }
}
