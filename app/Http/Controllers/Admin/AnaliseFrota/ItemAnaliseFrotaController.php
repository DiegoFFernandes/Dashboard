<?php

namespace App\Http\Controllers\Admin\AnaliseFrota;

use App\Http\Controllers\Controller;
use App\Models\AnaliseFrota;
use App\Models\ItemAnalysisFrota;
use App\Models\MarcaModeloFrota;
use App\Models\MedidaPneu;
use App\Models\ModeloPneu;
use App\Models\MotivoPneu;
use App\Models\PictureAnalysisFrota;
use App\Models\PosicaoPneu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;
use Yajra\DataTables\Facades\DataTables;

class ItemAnaliseFrotaController extends Controller
{
    public $request, $user, $modelo, $analise, $modelopneu, $medidapneu, $posicaopneu, $motivopneu, $item;

    public function __construct(
        Request $request,
        MarcaModeloFrota $modeloveiculo,
        AnaliseFrota $analise,
        ModeloPneu $modelopneu,
        MedidaPneu $medidapneu,
        PosicaoPneu $posicaopneu,
        MotivoPneu $motivopneu,
        ItemAnalysisFrota $item
    ) {
        $this->request = $request;
        $this->modelo = $modeloveiculo;
        $this->analise = $analise;
        $this->modelopneu = $modelopneu;
        $this->medidapneu = $medidapneu;
        $this->posicaopneu = $posicaopneu;
        $this->motivopneu = $motivopneu;
        $this->item = $item;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        $title_page   = 'Item Análise de Frota';
        $user_auth    = $this->user;
        $exploder = explode('/', $this->request->route()->uri());
        $uri          = $exploder[0] . '/' . $exploder[1];
        $analise = $this->analise->findORFail($this->request->id);
        $modelopneu = $this->modelopneu->listModeloPneu();
        $medidapneu = $this->medidapneu->listMedidaPneu();
        $posicaopneu = $this->posicaopneu::all();
        $motivopneu = $this->motivopneu::all();

        return view('admin.analise_frota.add-item-analysis', compact(
            'title_page',
            'user_auth',
            'uri',
            'analise',
            'modelopneu',
            'medidapneu',
            'posicaopneu',
            'motivopneu'
        ));
    }
    public function store()
    {
        try {
            AnaliseFrota::where('id', $this->request->id)->firstOrFail();
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Não existe a analise ou Cód. Invalido']);
        }

        $validator = $this->__validate();

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $item = $this->item->storeData($validator->validated());

        if ($this->request->has('imagem')) {
            foreach ($this->request->imagem as $r) {
                $img = $r;
                $folderPath = "image_item_analysis/";

                $image_parts = explode(";base64,", $img);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];

                $image_base64 = base64_decode($image_parts[1]);
                $fileName = uniqid() . '.jpeg';
                $file = $folderPath . $fileName;

                $picture = new PictureAnalysisFrota;
                $picture->id_item_analysis = $item->id;
                $picture->path = $file;
                $picture->save();
                Storage::put($file, $image_base64);
            }
        }
        return response()->json(['success' => 'Item da análise, ' . $this->request->id . ', incluido com sucesso!']);
    }
    public function __validate()
    {
        return Validator::make(
            $this->request->all(),
            [
                'id' => 'integer|required',
                'id_medida' => 'integer|required',
                'ds_medida' => 'string|required',
                'fogo' => 'integer',
                'sulco' => 'integer|required',
                'pressao' => 'integer|required',
                'modelo' => 'integer|required',
                'ds_modelo' => 'string|max:256|required',
                'motivo' => 'integer|required',
                'posicao' => 'integer|required',
            ]
        );
    }
    public function getItemAnalise()
    {

        $data = $this->item->listAll();

        return DataTables::of($data)
            ->addColumn('action', function ($d) {
                $button = '<button class="btn btn-warning btn-xs">Editar</button> ';
                $button .= '<button class="btn btn-danger btn-xs" id="delete-item" data-id="'.$d->id.'">Excluir</button>';

                return $button;
            })
            ->addColumn('ps', '')
            ->addColumn('sc', '')
            ->editColumn('ps', function ($d) {
                if ($d->pressao > $d->ps_max || $d->pressao < $d->ps_min) {
                    return '#f59898';
                } else {
                    return '#90EE90';
                }
            })
            ->editColumn('sc', function ($d) {
                if ($d->sulco < $d->sulco_ideal) {
                    return '#f59898';
                } else {
                    return '#90EE90';
                }
            })
            ->make(true);
    }
    public function destroyItem(){
        $item = $this->item->findOrFail($this->request->id);
        $delete = $this->item->destroyData($item->id);
        if ($delete){
            return response()->json(['success' => 'Item da análise, ' . $this->request->id . ', excluida com sucesso!']);
        }else{
            return response()->json(['error' => 'Item da análise, ' . $this->request->id . ', não pode ser excluida, a imagens vinculadas a ela!']);
        }
    }
}
