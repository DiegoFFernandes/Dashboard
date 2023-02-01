<?php

namespace App\Http\Controllers\Admin\AnaliseFrota;

use App\Http\Controllers\Controller;
use App\Models\AnaliseFrota;
use App\Models\MarcaModeloFrota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AnaliseFrotaController extends Controller
{

    public $request, $user, $modelo, $analise;

    public function __construct(
        Request $request,
        MarcaModeloFrota $modeloveiculo,
        AnaliseFrota $analise,
    ) {
        $this->request = $request;
        $this->modelo = $modeloveiculo;
        $this->analise = $analise;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {

        $title_page   = 'Análise de Frota';
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();
        $frota = 1;
        $modelo = $this->modelo->MarcaModeloAll($frota);

        return view('admin.analise_frota.index', compact(
            'title_page',
            'user_auth',
            'uri',
            'modelo'
        ));
    }
    public function create()
    {
        $this->request['modelo_veiculo'] = decrypt($this->request->modelo_veiculo);
        $input = $this->__validate();
        if ($input->fails()) {
            $error = '<ul>';

            foreach ($input->errors()->all() as $e) {
                $error .= '<li>' . $e . '</li>';
            }
            $error .= '</ul>';

            return response()->json([
                'error' => $error
            ]);
        }
        try {
            $this->analise->storeData($input->validated());
            return response()->json(['success' => 'Analise incluida com sucesso!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'houve algum erro contate setor de TI!']);
        }
    }
    public function getListaData()
    {
        $data =  $this->analise->listData();
        return DataTables::of($data)
            ->addColumn('action', function ($d) {
                return
                    '<button type="button" class="btn btn-warning btn-sm" id="edit-analysis" data-id="' . $d->id . '">Editar</button>
                    <a href="' . route('item-analysis', ['id' => $d->id]) . '" class="btn btn-success btn-sm" id="item-analysis">Item</a>
                    <button type="button" data-id="' . $d->id . '" data-toggle="modal" data-target="#DeleteAnalise" class="btn btn-danger btn-sm" id="getDeleteId">Excluir</button>';
            })
            ->addColumn('cliente', function ($d) {
                return $d->cd_pessoa . '-' . $d->nm_pessoa;
            })
            ->make();
    }
    public function delete()
    {
        try {
            $data = $this->analise::findOrFail($this->request->id);
            $this->analise->DestroyData($data->id);
            return response()->json(['success' => 'Analise deletada com sucesso!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Houve algum erro favor contactar setor de TI']);
        }
    }

    public function itemAnalise()
    {
    }

    public function __validate()
    {
        return Validator::make(
            $this->request->all(),
            [
                'pessoa' => 'required|integer',
                'nm_pessoa' => 'required|string|max:255',
                'sulco' => 'required|integer',
                'placa' => 'required|string',
                'modelo_veiculo' => 'required|integer',
                'obs' => 'max:255',
                'ps_min' => 'required|integer',
                'ps_max' => 'required|integer'
            ],
            [
                'pessoa.required' => 'Cód. Pessoa é obrigatório.',
                'pessoa.integer' => 'Cód. Pessoa de ser do tipo inteiro.',
                'nm_pessoa.required' => 'Nome pessoa e obrigatório.',
                'sulco.required' => 'Sulco e obrigatório.',
                'placa.required' => 'Placa veiculo é obrigatório.',
                'modelo_veiculo.required' => 'Modelo é obrigatório.',
                'obs.max' => 'Observação tem que ter no maximo 255 caracteres.',
                'ps_min.required' => 'Pressão minima e obrigatória.',
                'ps_max.required' => 'Pressão maxima e obrigatória.',
            ]
        );
    }
}
