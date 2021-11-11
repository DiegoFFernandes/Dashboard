<?php

namespace App\Http\Controllers\Admin\Estoque;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\LoteEntradaEstoque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $this->resposta = $request;
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
        $uri          = $this->resposta->route()->uri();

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
                return ' <a href="' . route('add-item-lote.index', $lotes->id) . '" id="add-itens" class="btn btn-default btn-sm btn-edit">Add Itens</a>
                        <button type="button" data-id="' . $lotes->id . '" class="btn btn-danger btn-sm" id="getDeleteId">Excluir</button>';
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
        $this->lote->storeData($this->resposta);

        return response()->json(['success' => 'Lote Criado com sucesso!']);
    }
    public function _validator(Request $request)
    {
        return Validator::make(
            $request->all(),
            ['ds_lote'  => 'required'],
            ['ds_lote.required' => 'Empresa deve ser preenchida']
        );
    }
}
