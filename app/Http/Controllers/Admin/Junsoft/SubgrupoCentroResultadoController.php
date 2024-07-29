<?php

namespace App\Http\Controllers\Admin\Junsoft;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\GrupoCentroResultado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SubgrupoCentroResultado;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class SubgrupoCentroResultadoController extends Controller
{
    public $user, $request, $item, $empresas, $sub, $grupo;

    public function __construct(
        Request $request,
        SubgrupoCentroResultado $sub,
        Empresa $empresa,
        GrupoCentroResultado $grupo,
    ) {
        $this->request = $request;
        $this->empresas = $empresa;
        $this->sub = $sub;
        $this->grupo = $grupo;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function StoreSubCentroResultado()
    {
        $validate = Validator::make(
            $this->request->all(),
            [
                'ds_subgrupo' => 'required|string',
                'cd_grupo' => ['required', Rule::in([0, 1, 2, 3, 4, 5])],
                'ds_tipo' => 'required|string',
                'update' => ['required', Rule::in([0, 1])],
            ],
            [
                'ds_subgrupo.required' => 'Descrição SubGrupo deve ser informada',
                'ds_subgrupo.string' => 'Descrição deve ser dados alfabetico',
                'cd_grupo.required' => "Cód Grupo deve ser informado",
                'ds_tipo.required' => "Tipo deve ser dados alfabetico",

            ],
        );

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()->all()]);
        }
        // return $validate->validated();

        if ($this->request->update == 1) {
            $store = $this->sub->UpdateSubGrupoCentroResultado($this->request->all());
        } else {
            $store = $this->sub->StoreSubGrupoCentroResultado($validate->validated());
        }

        if ($store) {
            return response()->json(['success' => 'Dados salvos com sucesso!']);
        } else {
            return response()->json(['error' => 'Houve algum erro contacte o setor de T.I.!']);
        }
    }
    public function DeleteSubCentroResultado()
    {
        $delete = $this->sub->findOrFail($this->request->id)->delete();
        if ($delete) {
            return response()->json(['success' => 'Subgrupo deletado com sucesso!']);
        }
    }
    public function listSubgrupoCentroResultado()
    {
        $data = $this->sub->listSubgrupoCentroResultado();
        return DataTables::of($data)
            ->addColumn('Actions', function ($data) {
                return '
                 <button class="btn btn-xs btn-primary EditItemSubgrupo"
                                                            data-id=" '.$data->id.' "
                                                            data-ds_subgrupo="'.$data->subgrupo.'"
                                                            data-cd_grupo="'.$data->cd_grupo.'"
                                                            data-ds_tipo="'.$data->ds_tipo.'"
                                                            data-cd_dre="'.$data->cd_dre.'">
                                                            Editar
                                                        </button>
                <button class="btn btn-xs btn-danger DeleteItemSubgrupo"
                                                            data-id=" '.$data->id.' ">Excluir</button>
        
        ';
                // <button type="button" data-id="' . $data->id . '" class="btn btn-danger btn-sm" id="getDeleteId">Excluir</button>
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }
}
