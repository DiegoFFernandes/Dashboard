<?php

namespace App\Http\Controllers\Admin\Procedimento;

use App\Http\Controllers\Controller;
use App\Mail\ProcedimentoMail;
use App\Models\Procedimento;
use App\Models\ProcedimentoAprovador;
use App\Models\ProcedimentoPublish;
use App\Models\ProcedimentoRecusado;
use App\Models\Setor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Days;
use ProcedimentoHelper;
use stdClass;
use Yajra\DataTables\DataTables as DataTablesDataTables;
use Yajra\DataTables\Facades\DataTables;

class ProcedimentoController extends Controller
{
    public $request, $procedimento, $aprovador, $publish, $user, $setor;
    public function __construct(
        Request $request,
        Procedimento $procedimento,
        ProcedimentoAprovador $aprovador,
        ProcedimentoPublish $publish,
        Setor $setor
    ) {
        $this->request = $request;
        $this->procedimento = $procedimento;
        $this->aprovador = $aprovador;
        $this->publish = $publish;
        $this->setor = $setor;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        $title_page   = 'Processo de Procedimentos';
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();
        $users        = User::where('id', '<>', 1)->get();
        $setors       = $this->setor->listData();
        $this->aprovador->updateIfCreateLargerDays();
        $largeDays = $this->aprovador->groupVerifyIfReleased();

        foreach ($largeDays as $l) {
            ProcedimentoHelper::verifyIfRealeased($l);
        }

        return view('admin.qualidade.index', compact(
            'title_page',
            'user_auth',
            'uri',
            'users',
            'setors'
        ));
    }
    public function store()
    {
        $data = $this->__validate($this->request);
        $file = $this->__validateFile($this->request);
        $setor = Setor::findOrFail($data['setor']);

        $procedimento = $this->procedimento->storeData($data, $file);

        foreach ($data['users'] as $d) {
            $user = User::find($d);
            // return new ProcedimentoMail($this->request, $setor, $user);
            Mail::send(new ProcedimentoMail($this->request, $setor, $user));
            $this->aprovador->create($procedimento->id, $d);
        };
        // die();
        return redirect()->route('procedimento.index')->with('status', 'Procedimento criado com sucesso!');
    }
    public function storeNoApprover()
    {
        $procedimento = Procedimento::findOrFail($this->request->id);
        $procedimento->status = "L";
        $procedimento->save();
        return  response()->json(["success" => "Procedimento liberado sem aprovador!"]);
    }
    public function edit()
    {
        ProcedimentoAprovador::where('id_procedimento', $this->request->id)->firstOrFail();
        $aprovador = ProcedimentoAprovador::where('id_procedimento', $this->request->id)->get();
        foreach ($aprovador as $a) {
            $aprov[] = $a->id_user;
        }
        return $aprov;
    }
    public function update()
    {
        // return $this->request;
        $procedimento = Procedimento::findOrFail($this->request->id_procedimento);
        $data = $this->__validate($this->request);

        $procedimento->id_setor = $data['setor'];
        $procedimento->title = $data['title'];
        $procedimento->description = $data['description'];
        $procedimento->id_user_create = Auth::user()->id;

        if ($this->request->hasFile('file')) {
            $file = $this->__validateFile($this->request);
            if (File::exists($procedimento->path)) {
                File::delete($procedimento->path);
            }
            $file = $this->request->file('file')->store('procedimentos');
            $procedimento->path = $file;
        }
        $procedimento->save();

        $aprovador = ProcedimentoAprovador::where('id_procedimento', $this->request->id_procedimento)->get();
        foreach ($aprovador as $a) {
            $a->delete();
        }
        foreach ($this->request->users as $u) {
            $this->aprovador->create($this->request->id_procedimento, $u);
        }
        //se view da tab recusados fazer muda o status para N ( reanalise ) nas tabelas abaixo
        if ($this->request->op_table === 'table-procedimento-recusados' || $this->request->op_table === 'table-procedimento-liberados') {
            $setor = Setor::find($this->request->setor);
            foreach ($this->request->users as $d) {
                $user = User::find($d);
                // return new ProcedimentoMail($this->request, $setor, $user);
                Mail::send(new ProcedimentoMail($this->request, $setor, $user));
            };
            $this->aprovador->updateIfReproved($this->request->id_procedimento);
            $this->procedimento->updateIfReproved($this->request->id_procedimento);
        }
        return redirect()->route('procedimento.index')->with('status', 'Atualizado com sucesso!');
    }
    public function GetProcedimento()
    {
        $status = $this->request->validate([
            'status' => 'required|in:A,R,P,L,N'
        ]);
        if ($status['status'] == 'A') {
            $status = ['A', 'N'];
        }
        $data = $this->procedimento->listData($status);
        return DataTables::of($data)
            ->addColumn('Actions', function ($data) {
                if ($data->status == 'Aguardando' || $data->status == 'Reanalise') {
                    return '
                        <button type="button" class="btn btn-warning btn-sm" id="btnReleaseNotApprover" data-id="' . $data->id . '"" data-table="table-procedimento" data-toggle="tooltip" data-placement="top" title="Libera sem aprovadores">Liberar</button>
                        <button type="button" class="btn btn-success btn-sm btn-edit" id="getEditProcedimento" data-id="' . $data->id . '"" data-table="table-procedimento">Editar</button>
                        <a class="btn btn-info btn-sm btn-pdf" href="' . route('procedimento.show-pdf', ['arquivo' => $data->path]) . '" target="_blank">PDF</a>
                        <button type="button" class="btn btn-danger btn-sm" data-id="' . $data->id . '" id="getDeleteId">Excluir</button>';
                } elseif ($data->status == 'Reprovado') {
                    return '  
                    <button type="button" class="btn btn-success btn-sm btn-edit" id="getEditProcedimento" data-id="' . $data->id . '"" data-table="table-procedimento-recusados">Reanalise</button>          
                        <a class="btn btn-info btn-sm btn-pdf" href="' . route('procedimento.show-pdf', ['arquivo' => $data->path]) . '" target="_blank">PDF</a>
                        <button type="button" class="btn btn-warning btn-sm" data-id="' . $data->id . '" id="getViewReason">Motivo</button>                        
                        ';
                } elseif ($data->status == 'Liberado') {
                    $html = ' 
                        <a class="btn btn-info btn-sm btn-pdf" href="' . route('procedimento.show-pdf', ['arquivo' => $data->path]) . '" target="_blank">PDF</a>
                        ';
                    if ($data->public == 'P') {
                        $html .= '<button type="button" class="btn btn-danger btn-sm" data-id="' . $data->id . '" id="btnCancelPublish">Despublicar</button>';;
                    } else {
                        $html .= '<button type="button" class="btn btn-success btn-sm" data-id="' . $data->id . '" id="btnPublish">Publicar</button> ';
                    }
                    $html .= ' <button type="button" class="btn btn-warning btn-sm btn-edit" id="getEditProcedimento" data-id="' . $data->id . '"" data-table="table-procedimento-liberados">Reanalisar</button> 
                        ';
                    return $html;
                } elseif ($data->status == 'Em andamento') {
                    return '  
                        <button type="button" class="btn btn-warning btn-sm" data-id="' . $data->id . '"  id="btnOutstandind">Pendentes</button>            
                        <a class="btn btn-info btn-sm btn-pdf" href="' . route('procedimento.show-pdf', ['arquivo' => $data->path]) . '" target="_blank">PDF</a>                        
                        ';
                }
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }
    public function __validate($request)
    {
        return $request->validate(
            [
                'setor' => 'required|integer',
                'users' => 'required|array',
                'title' => 'required|string',
                'description' => 'required|string'
            ],
        );
    }
    public function __validateFile($request)
    {
        return $request->validate(
            [
                'file' => 'required|mimes:pdf|max:4096'
            ],
            [
                'file.required' => 'Favor informar uma arquivo PDF!',
                'file.mimes' => 'Arquivo deve ser somente PDF, outro formato não e aceito!',
                'file.max' => 'Arquivo deve ser menor de 4MB'
            ]
        );
    }
    public function showPDF()
    {
        $exists = Storage::disk('public')->exists($this->request->arquivo);
        if ($exists) {
            //get content of image
            $content = Storage::get($this->request->arquivo);
            //get mime type of image
            $mime = Storage::mimeType($this->request->arquivo);
            //prepare response with image content and response code
            $response = Response::make($content, 200);
            //set header
            $response->header("Content-Type", $mime);
            // return response
            return $response;
        } else {
            abort(404);
        }
    }
    public function destroy()
    {
        $aprovador = ProcedimentoAprovador::where('id_procedimento', $this->request->id)->get();
        if (ProcedimentoRecusado::where('id_procedimento', $this->request->id)->exists()) {
            return response()->json(['alert' => 'Procedimento com analise de recusa não pode ser excluido!']);
        }
        foreach ($aprovador as $a) {
            $a->delete();
        }
        $this->procedimento->find($this->request->id)->delete();
        return response()->json(['success' => 'Procedimento Excluido com sucesso!']);
    }
    public function destroyPublish()
    {
        $procedimento = ProcedimentoPublish::where('id_procedimento', $this->request->id)->firstOrFail();
        $procedimento->delete();
        return response()->json(['success' => 'Procedimento despublicado com sucesso!']);
    }
    public function envioEmail()
    {
        $request = new stdClass();
        $request->cd_empresa = 3;
        $request->name = "Diego Ferreira";
        $request->nr_lancamento = 28;
        $request->nr_nota = 123;
        $request->nr_cnpjcpf = '00.520.634/0003-37';
        $request->nm_pessoa = 'TRANSPORTES SELGEMAY LTDA';
        $request->motivo = 'Faturamento parcial';
        $request->observacao = 'Teste de observação';

        // return $request;

        // return new ProcedimentoMail($request, Auth::user());
        // return Mail::send(new ProcedimentoMail($request, Auth::user()));
    }
    public function procedimentoPublish()
    {
        $title_page   = 'Procedimentos Publicos';
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();

        return view('admin.qualidade.procedimento-publicos', compact(
            'title_page',
            'user_auth',
            'uri',
        ));
    }
    public function storePublish()
    {
        Procedimento::findOrFail($this->request->id);
        $publish = new ProcedimentoPublish;
        if (ProcedimentoPublish::where('id_procedimento', $this->request->id)->exists()) {
            return  response()->json(["alert" => "Esse procedimento já está público!"]);
        } else {
            $publish->id_procedimento = $this->request->id;
            $publish->status = 'P';
            $publish->save();
            return  response()->json(["success" => "Procedimento público com sucesso!"]);
        }
    }
    public function GetProcedimentoPublish()
    {
        $public = $this->request->validate([
            'public' => 'required|in:pub'
        ]);
        $data = $this->procedimento->listData($public['public']);
        return DataTables::of($data)
            ->addColumn('Actions', function ($data) {
                return '  
                        <a class="btn btn-info btn-sm btn-pdf" href="' . route('procedimento.show-pdf', ['arquivo' => $data->path]) . '" target="_blank">PDF</a>  
                                              
                        ';
                        //<button class="btn btn-warning btn-sm btn-notify" data-id="' . $data->id . '" data-toggle="modal" data-target="#modal-revisar">Revisar</button>  
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }
    public function approverOutstanding()
    {
        Procedimento::findOrFail($this->request->id);
        $data =  $this->aprovador->outStandingApprover($this->request->id);
        return DataTables::of($data)->make(true);
    }
    public function reviseProcedimento()
    {
        // return $this->request;
        $data_aprovador = $this->__validate($this->request);
        $procedimento = Procedimento::findOrFail($data_aprovador['id']);
        $setor = Setor::findOrFail($procedimento['id_setor']);
        $user = User::findOrFail($procedimento['id_user_create']);

        Mail::send(new ProcedimentoMail($this->request, $setor, $user));
        return response()->json(['success' => 'Procedimento aprovado com sucesso!']);
    }
}
