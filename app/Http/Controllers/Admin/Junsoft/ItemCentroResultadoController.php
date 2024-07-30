<?php

namespace App\Http\Controllers\Admin\Junsoft;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\GrupoCentroResultado;
use App\Models\ItemCentroResultado;
use App\Models\SubGrupo;
use App\Models\SubgrupoCentroResultado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class ItemCentroResultadoController extends Controller
{
    public $user, $request, $item, $empresas, $sub, $grupo;

    public function __construct(
        Request $request,
        ItemCentroResultado $item,
        SubgrupoCentroResultado $sub,
        Empresa $empresa,
        GrupoCentroResultado $grupo,
    ) {
        $this->request = $request;
        $this->item = $item;
        $this->empresas = $empresa;
        $this->sub = $sub;
        $this->grupo = $grupo;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        $title_page   = 'Item Centro de Custo';
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();
        $sub = $this->sub->listSubgrupoCentroResultado();
        $empresas =  $this->empresas->EmpresaFiscalAll();
        $grupo = GrupoCentroResultado::all();
        $ds_tipo = $this->sub->listDsTipo();

        // Faz o insert automaticamente dos ultimos 30 dias de cadastro de centro de custo do junsoft
        $this->item->ListCentroResultadoJunsoft(0);

        return view('admin.centro_resultado.index', compact(
            'title_page',
            'user_auth',
            'uri',
            'empresas',
            'sub',
            'grupo',
            'ds_tipo'
        ));
    }
    public function listItemCentroResultado()
    {
        $data = $this->item->listCentroResultado();
        return DataTables::of($data)
            ->addColumn('Actions', function ($data) {
                return '
            <a href="#" class="btn btn-success btn-xs btn-edit">Editar</a>
            
            ';
                // <button type="button" data-id="' . $data->id . '" class="btn btn-danger btn-sm" id="getDeleteId">Excluir</button>
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }
    public function EditItemCentroResultado()
    {        
        $validator = $this->__validator();
        // return $validator->validated();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $update = $this->item->updateItemCentroResultado($validator->validated());

        if ($update) {
            return response()->json(['success' => 'Dados atualizados com sucesso!']);
        }
    }
    public function __validator()
    {
        return Validator::make(
            $this->request->all(),
            [
                'cd_empresa_desp'  => 'integer|required',
                'cd_centroresultado' => 'integer|required',
                'cd_subgrupo'  => 'integer|required',
                'orcamento'  => 'regex:/^\d+(\.\d{1,2})?$/|required',
                'expurgar'  => ['required', Rule::in(['N','S'])],

            ],
            [
                'cd_empresa_desp.required' => 'Empresa deve ser preenchida',
                'cd_empresa.integer'   => 'Id empresa deve ser inteiro',
                'cd_centroresultado.required' => 'Centro de Resultado deve ser preenchido',
                'cd_centroresultado.string'  => 'id Centro de Resultado deve ser inteiro',
                'cd_subgrupo.required' => 'Subgrupo deve ser preenchida',
                'cd_subgrupo.integer'   => 'Id Subgrupo deve ser inteiro',
                'orcamento.required' => 'Orçamento deve ser preenchido',
                'orcamento.regex' => 'Orçamento deve ser um valor numérico'
            ]
        );
    }
}
