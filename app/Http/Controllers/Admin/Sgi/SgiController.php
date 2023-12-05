<?php

namespace App\Http\Controllers\Admin\Sgi;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Sgi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class SgiController extends Controller
{
    public $request, $sgi, $aprovador, $publish, $user, $empresa, $recusa;
    public function __construct(
        Request $request,
        Sgi $sgi,
        Empresa $empresa
    ) {
        $this->request = $request;
        $this->sgi = $sgi;
        $this->empresa = $empresa;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        $title_page   = 'Sgi - Documentos';
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();
        $users        = User::where('id', '<>', 1)->get();
        $unidades       = $this->empresa->EmpresaFiscalAll();

        return view('admin.sgi.index', compact(
            'title_page',
            'user_auth',
            'uri',
            'users',
            'unidades'
        ));
    }
    public function store()
    {        
        $data = $this->__validate($this->request);
        $file = $this->__validateFile($this->request);

        $empresa = Empresa::select('cd_empresa_new')->where('cd_empresa_new', $data['unidade'])->count();

        if (!$empresa >= 1) {
            redirect()->route('sgi.index')->with('info', 'Empresa não existe, favor informar outra!');
        }
        $sgi = $this->sgi->storeData($data, $file);

        return redirect()->route('sgi.index')->with('status', 'Procedimento criado com sucesso!');
    }
    public function GetProcedimento()
    {
        $status = $this->request->validate([
            'status' => 'required|in:A,P'
        ]);

        $data = $this->sgi->listData($status, "all");


        return DataTables::of($data)
            ->addColumn('Actions', function ($data) {
                if ($data->status == 'Aguardando Publicar') {
                    return '
                        <button type="button" class="btn btn-success btn-sm" data-id="' . $data->id . '" id="btnPublish">Publicar</button> 
                        <button type="button" class="btn btn-warning btn-sm btn-edit" id="getEditSgi" data-id="' . $data->id . '"" data-table="table-sgi">Editar</button>
                        <a class="btn btn-info btn-sm btn-pdf" href="' . route('sgi.show-pdf', ['arquivo' => $data->path]) . '" target="_blank">PDF</a>
                        <button type="button" class="btn btn-danger btn-sm" data-id="' . $data->id . '" id="getDeleteId">Excluir</button>';
                } elseif ($data->status == 'Publico') {
                    $html = ' 
                        <a class="btn btn-danger btn-sm btn-pdf" href="' . route('sgi.show-pdf', ['arquivo' => $data->path]) . '" target="_blank" title="PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                        ';
                    if ($data->public == 'P') {
                        $html .= '<button type="button" class="btn btn-danger btn-sm" data-id="' . $data->id . '" id="btnCancelPublish" title="Despublicar"><i class="fa fa-thumbs-down" aria-hidden="true"></i></button>';;
                    } else {
                        $html .= '<button type="button" class="btn btn-success btn-sm" data-id="' . $data->id . '" id="btnPublish" title="Publicar"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></button> ';
                    }

                    return $html;
                }
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }
    public function storePublish()
    {
        $publish = Sgi::findOrFail($this->request->id);
        $publish->status = 'P';
        $publish->update();
        return response()->json(["success" => "SGI público com sucesso!"]);
    }
    public function destroyPublish()
    {
        $publish = Sgi::findOrFail($this->request->id);
        $publish->status = 'A';
        $publish->update();
        return response()->json(['success' => 'Sgi despublicado com sucesso!']);
    }
    public function destroy()
    {
        $this->sgi->find($this->request->id)->delete();
        return response()->json(['success' => 'Sgi excluido com sucesso!']);
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
    public function edit()
    {
        return Sgi::findOrFail($this->request->id);
        // $aprovador = ProcedimentoAprovador::where('id_procedimento', $this->request->id)->get();
        // foreach ($aprovador as $a) {
        //     $aprov[] = $a->id_user;
        // }
        // return $aprov;
    }
    public function update()
    {
        // return $this->request;
        $sgi = Sgi::findOrFail($this->request->id_sgi);
        $data = $this->__validate($this->request);

        $sgi->cd_empresa = $data['unidade'];
        $sgi->title = $data['title'];
        $sgi->description = $data['description'];
        $sgi->dt_validade = $data['dt_validade'];
        $sgi->id_user_create = Auth::user()->id;

        if ($this->request->hasFile('file')) {
            $file = $this->__validateFile($this->request);
            if (File::exists($sgi->path)) {
                File::delete($sgi->path);
            }
            $file = $this->request->file('file')->store('sgi');
            $sgi->path = $file;
        }
        $sgi->save();

        return redirect()->route('sgi.index')->with('message', 'Atualizado com sucesso!');
    }
    public function __validate($request)
    {
        return $request->validate(
            [
                'unidade' => 'required|integer',
                'title' => 'required|string|max:200',
                'description' => 'required|string|max:600',
                'dt_validade' => 'required|date'
            ],
            [
                'unidade.required' => 'Unidade deve ser preechida!',
                'unidade.integer' => 'Cód unidade deve numero!',
                'title.required' => 'Titulo deve ser preechida!',
                'title.string' => 'Titulo deve ser texto!',
                'description.required' => 'Descrição deve ser preechida!',
                'description.string' => 'Descrição deve ser texto!',
                'dt_validade' => 'Data de Validade deve ser Preechida',
            ]
        );
    }
    public function __validateFile($request)
    {
        return $request->validate(
            [
                'file' => 'required|mimes:pdf|max:10240'
            ],
            [
                'file.required' => 'Favor informar uma arquivo PDF!',
                'file.mimes' => 'Arquivo deve ser somente PDF, outro formato não e aceito!',
                'file.max' => 'Arquivo deve ser menor de 10MB'
            ]
        );
    }

    public function sgiPublish()
    {
        $title_page   = 'Sgis Publicos';
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();
        $filtro = $this->sgi->countSgis();

        return view('admin.sgi.sgis-publicos', compact(
            'title_page',
            'user_auth',
            'uri',
            'filtro'
        ));
    }
    public function GetSgisPublish()
    {
        $public = $this->request->validate([
            'public' => 'required|in:pub',
            'setor' => 'required'
        ]);

        $data = $this->sgi->listData($public['public'], $public['setor']);
        return DataTables::of($data)
            ->addColumn('Actions', function ($data) {
                $btn = '<a class="btn btn-danger btn-sm btn-pdf" href="' . route('procedimento.show-pdf', ['arquivo' => $data->path]) . '" target="_blank" title="PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>  
                        ';



                return $btn;
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }
}
