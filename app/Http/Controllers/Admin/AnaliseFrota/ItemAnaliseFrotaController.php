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
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;


class ItemAnaliseFrotaController extends Controller
{
    public $request, $user, $modelo, $analise, $modelopneu, $medidapneu, $posicaopneu, $motivopneu, $item, $pictures;

    public function __construct(
        Request $request,
        MarcaModeloFrota $modeloveiculo,
        AnaliseFrota $analise,
        ModeloPneu $modelopneu,
        MedidaPneu $medidapneu,
        PosicaoPneu $posicaopneu,
        MotivoPneu $motivopneu,
        ItemAnalysisFrota $item,
        PictureAnalysisFrota $pictures,
    ) {
        $this->request = $request;
        $this->modelo = $modeloveiculo;
        $this->analise = $analise;
        $this->modelopneu = $modelopneu;
        $this->medidapneu = $medidapneu;
        $this->posicaopneu = $posicaopneu;
        $this->motivopneu = $motivopneu;
        $this->item = $item;
        $this->pictures = $pictures;

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
            $error = '<ul>';
            foreach ($validator->errors()->all() as $e) {
                $error .= '<li>' . $e . '</li>';
            }
            $error .= '</ul>';
            return response()->json(['errors' => $error]);
        }
        // $validator->validated();
        $item = $this->item->storeData($validator->validated());

        if ($this->request->has('imagem')) {
            foreach ($this->request->imagem as $r) {
                $img = $r;
                $folderPath = "image_item_analysis/";

                $image_parts = explode(";base64,", $img);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];

                $image_base64 = base64_decode($image_parts[1]);
                $fileName = uniqid() . '.png';
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
                'id_medida' => ['required', 'integer', function ($attribute, $value, $fail) {
                    if ($value <= 0) {
                        $fail('Selecione uma medida.');
                    }
                }],
                'ds_medida' => 'string|required',
                'fogo' => 'integer',
                'dot' => 'string|max:10',
                'sulco' => 'integer|required',
                'pressao' => 'integer|required',
                'modelo' => 'integer|required',
                'ds_modelo' => 'string|max:256|required',
                'motivo' => 'integer|required',
                'posicao' => 'integer|required',
                'id_item' => 'integer',
                'update_image' => 'required|integer:0,1'
            ],
            [
                'ds_medida.required' => 'Medida e obrigatório.',
                'fogo.integer' => 'Fogo deve ser número.',
                'dot.integer' => 'Dot deve ser número.',
                'sulco.required' => 'Sulco e obrigatório.',
                'sulco.integer' => 'Sulco de ser número.',
                'pressao.integer' => 'Pressão deve ser número.',
                'pressao.required' => 'Pressão e obrigatório.',
                'ds_modelo.required' => 'Modelo e obrigatório',
                'motivo.required' => 'Motivo e obrigatório',
                'id_item.integer' => 'Item deve ser número',
                'posicao.required' => 'Posição e obrigatório',
                'posicao.integer' => 'Posição deve ser número'
            ]
        );
    }
    public function getItemAnalise()
    {
        // return $this->request->id;
        $data = $this->item->listAll($this->request->id);

        return DataTables::of($data)
            ->addColumn('action', function ($d) {
                $button = '<button class="btn btn-warning btn-xs" id="edit-item">Editar</button> ';
                $button .= '<button class="btn btn-danger btn-xs" id="delete-item" data-id="' . $d->id . '">Excluir</button>';
                $button .= '<button class="btn btn-primary btn-xs" id="picture-item" data-id="' . $d->id . '">Foto</button>';


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
    public function destroyItem()
    {
        $id = $this->request->id;

        $item = $this->item->findOrFail($id);
        $pictures = $this->pictures->listPictures($id);
        foreach ($pictures as $p) {
            if (Storage::exists($p->path)) {
                Storage::delete($p->path);
            }
        }
        $this->pictures->DestroyPicture($id);
        $delete = $this->item->destroyData($item->id);
        if ($delete) {
            return response()->json(['success' => 'Item da análise, ' . $id . ', excluida com sucesso!']);
        } else {
            return response()->json(['error' => 'Houve um erro ao excluir o item, ' . $id . ', contate o setor de TI!']);
        }
    }
    public function edit()
    {
        $item = ItemAnalysisFrota::findOrFail($this->request->id_item);
        $validator = $this->__validate();
        $validate = $validator->validated();

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        if ($validate['update_image'] == 1) {
            $pictures = $this->pictures->listPictures($item->id);
            foreach ($pictures as $p) {
                if (Storage::exists($p->path)) {
                    Storage::delete($p->path);
                }
            }
            $this->pictures->DestroyPicture($item->id);

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

                    $picture = new PictureAnalysisFrota();
                    $picture->id_item_analysis = $item->id;
                    $picture->path = $file;
                    $picture->save();
                    Storage::put($file, $image_base64);
                }
            }
        }

        $this->item->updateData($validator->validated());
        return response()->json(['success' => 'Item da análise, ' . $this->request->id . ', atualizado com sucesso!']);
    }
    public function getPicturesItemAnalysis()
    {
        $picture = PictureAnalysisFrota::where('id_item_analysis', $this->request->id)->get();
        $list = "";
        if (Helper::is_empty_object($picture)) {
            return response()->json(['html' => '<li>Não existe foto para esse item</li>']);
        };
        foreach ($picture as $p) {
            $list .= "<li><img class='img-responsive pad' src='" . asset('storage/' . $p->path . '') . "'</li>";
        }
        return response()->json(['html' => $list]);
    }
    public function getPrintItemAnaliseAll()
    {
        $id = decrypt($this->request->id);
        $this->analise::where('id', $id)->firstOrFail();
        $result = $this->item->listAllPrint($id);
        $itens = array();
        $dados_ps[] = array('Pneu', 'Ps', 'Ps Min', 'Ps Max');
        $dados_sulco[] = array('Pneu', 'Sulco', 'Sulco Min');
        $count = 1;
        // Verifica se há algum resultado da consulta       
        foreach ($result as $row) {
            // Adiciona o item ao array se ainda não existe
            if (!isset($itens[$row['id']])) {
                $linha_ps = array($count++, $row['pressao'], $row['ps_min'], $row['ps_max']);
                $dados_ps[] = $linha_ps;

                $linha_sulco = array($count++, $row['sulco'], $row['sulco_ideal']);
                $dados_sulco[] = $linha_sulco;


                $itens[$row['id']] = array(
                    'id' => $row['id_analise'],
                    'id_item' => $row['id'],
                    'nm_pessoa' => $row['nm_pessoa'],
                    'placa' => $row['placa'],
                    'ds_posicao' => $row['ds_posicao'],
                    'fogo' => $row['fogo'],
                    'dot' => $row['dot'],
                    'sulco' => $row['sulco'],
                    'ds_motivo' => $row['ds_motivo'],
                    'ds_modelo' => $row['ds_modelo'],
                    'ds_medida' => $row['ds_medida'],
                    'pressao' => $row['pressao'],
                    'ps_min' => $row['ps_min'],
                    'ps_max' => $row['ps_max'],
                    'responsavel' => $row['name'],
                    'sulco_ideal' => $row['sulco_ideal'],
                    'ds_causa' => $row['ds_causa'],
                    'ds_recomendacoes' => $row['ds_recomendacoes'],
                    'imagens' => array(),
                    'created_at' => Carbon::parse($row['created_at'])->format('d/m/Y')
                );
            }

            // Adiciona a imagem ao array de imagens do item correspondente
            $itens[$row['id']]['imagens'][] = $row['path'];
        }

        $uri = 'Relatório de Análise de Pneus';
        $title = 'Relatório | Análise';
        $date = Carbon::now()->format('d-m-Y h:i:s');
        $dados_ps = json_encode($dados_ps);
        $dados_sulco = json_encode($dados_sulco);

        $view = View::make('admin.analise_frota.print-analysis')->with(compact(
            'uri',
            'title',
            'date',
            'itens',
            'dados_ps',
            'dados_sulco'

        ));

       $html = $view->render();

        
        // Adiciona um tempo de espera de 1 segundo antes de renderizar o PDF
        $js = "setTimeout(function() {window.status = 'ready';, 1000);";

        $pdf = SnappyPdf::loadHTML($html,)
            // ->setOption('javascript-delay', 1000)
            // ->setOption('viewport-size', '1280x1024')
            ->setOption('page-size', 'A4')
            ->setOption('no-stop-slow-scripts', true)
            ->setOption('enable-javascript', true)
            ->setOption('javascript-delay', 1000)
            ->setOption('run-script', $js)
            ->setOption('encoding', 'UTF-8')
            ->setOption('dpi', 300)
            ->setOption('margin-top', 10)
            ->setOption('margin-right', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10);

        return $pdf->download('Relatório Análise.pdf'); //mudar para stream para visualzar direto
    }
}
