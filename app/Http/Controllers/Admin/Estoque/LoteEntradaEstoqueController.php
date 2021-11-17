<?php

namespace App\Http\Controllers\Admin\Estoque;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\LoteEntradaEstoque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LoteEntradaEstoqueController extends Controller
{
    public function __construct(
        Request $request,
        Empresa $empresa,
        LoteEntradaEstoque $lote,
    ) {
        $this->empresa  = $empresa;
        $this->request = $request;
        $this->lote = $lote;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        $title_page   = 'Criar Lote de Entrada';
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();

        return view('admin.estoque.index', compact(
            'title_page',
            'user_auth',
            'uri'
        ));
    }
    public function getLotes()
    {
        $lotes = $this->lote->lotesAll();
        return DataTables::of($lotes)
            ->addColumn('Actions', function ($lotes) {
                if ($lotes->status == 'F') {
                    return '<buttom class="btn btn-info btn-sm btn-edit">Ver itens</button>';
                        
                }else{
                    return '<a href="' . route('add-item-lote.index', Crypt::encryptString($lotes->id)) . '" id="add-itens" class="btn btn-default btn-sm btn-edit">Add Itens</a>
                    <button type="button" data-id="' . $lotes->id . '" class="btn btn-danger btn-sm" id="getDeleteId">Excluir</button>';
              
                }
            })
            ->rawColumns(['Actions'])
            ->setRowClass(function ($lotes) {
                return $lotes->status == 'F' ? 'alert-success' : 'alert-warning';
            })
            ->make(true);
    }
    public function store(Request $request)
    {
        $request['cd_usuario'] = Auth::user()->id;
        $request['status'] = 'A';
        $validator = $this->_validator($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $this->lote->storeData($this->request);

        return response()->json(['success' => 'Lote Criado com sucesso!']);
    }
    public function finishLote()
    {
        return $this->lote->updateData($this->request->id);
    }
    public function _validator(Request $request)
    {
        return Validator::make(
            $request->all(),
            ['ds_lote'  => 'required'],
            ['ds_lote.required' => 'Descrição deve ser preenchida']
        );
    }
}
