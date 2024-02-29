<?php

namespace App\Http\Controllers\Admin\Estoque;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemLoteEntradaEstoque;
use App\Models\LoteEntradaEstoque;
use App\Models\SubGrupo;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ItemLoteEntradaEstoqueController extends Controller
{
    public $request, $lote, $item, $itemlote, $user;
    public function __construct(
        Request $request,
        LoteEntradaEstoque $lote,
        ItemLoteEntradaEstoque $itemlote,
        Item $item,
    ) {

        $this->request = $request;
        $this->lote = $lote;
        $this->item = $item;
        $this->itemlote = $itemlote;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index($id)
    {
        $id = Crypt::decryptString($id);
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();
        $lote = $this->lote->findLote($id);
        $qtde_coleta = $this->itemlote->where('cd_lote', $id)->count();
        $title_page   = 'Adicionar item - ' . $lote->tp_lote;
        $itemgroup = $this->itemlote->listGroup($lote->id);

        return view('admin.estoque.add-item-lote', compact(
            'title_page',
            'user_auth',
            'uri',
            'lote',
            'itemgroup',
            'qtde_coleta'
        ));
    }
    public function getItensLote()
    {
        $id = $this->request->id_lote;
        $data = $this->itemlote->list($id);

        return DataTables::of($data)
            ->addColumn('actions', function ($d) {
                return '<button class="delete fa fa-trash-o" aria-hidden="true" data-id="' . $d->id . '"></button';
            })
            ->addColumn('ps', '')
            ->editColumn('ps', function ($d) {
                if ($d->peso > $d->ps_liquido) {
                    return '#90EE90';
                } else {
                    return '#f59898';
                }
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
    public function getResumeItens()
    {
        $id = $this->request->id_lote;
        $data = $this->itemlote->listGroup($id);
        return DataTables::of($data)
            ->make(true);
    }   


    public function getBuscaItem($cd_barras)
    {
        $cd_barras = Helper::RemoveSpecialChar($cd_barras);
        $item = $this->item->ItemFind($cd_barras);
        if ($item === 0) {
            return response()->json(['error' => 'Código produto não cadastrado ou não está usando código de barras!']);
        }
        return response()->json($item);
    }
    public function store()
    {
        $this->request['cd_usuario'] = Auth::user()->id;
        $item = $this->item->where('cd_item', $this->request->cd_produto)->firstOrFail();

        if ($this->request->id_marca <> 3 && $item->cd_marca == 3) {
            if (!$this->request->has('peso')) {
                return response()->json(['errors' => 'Você selecionou uma marca diferente da Bandag ao criar o lote, não pode incluir esse item!']);
            }
        }
        if ($this->request->id_subgrupo == 101 && $this->request->id_marca == 3) {
            $this->request['peso'] = str_replace(",", ".", $this->request->peso);
        } else {
            $this->request['peso'] = 1;
        }
        $store = $this->itemlote->store($this->request);
        $qtde_coleta = $this->itemlote->where('cd_lote', $this->request->cd_lote)->count();
        if ($store == 1) {
            return response()->json(['success' => 'Item adicionado!', 'qtde' => $qtde_coleta]);
        }
        return response()->json(['errors' => 'Houve algum erro, Peso ou produtos está fora dos parâmetros!!']);
    }
    public function delete()
    {
        return $this->itemlote->destroyData($this->request->id);
    }
    public function listItemLote($id)
    {
        $title_page   = 'Lote de Entrada - Finalizado';
        $lote = $this->lote->findLote(Crypt::decryptString($id));
        $itemlote = $this->itemlote->list($lote->id);
        $itemgroup = $this->itemlote->listGroup($lote->id);
        $user_auth    = $this->user;
        $exploder  = explode('/', $this->request->route()->uri());
        $uri       = ucfirst($exploder[1]);

        return view('admin.estoque.item-lote-fechado', compact(
            'title_page',
            'user_auth',
            'uri',
            'lote',
            'itemlote',
            'itemgroup'
        ));
    }
    public function _validator($request)
    {
        return Validator::make(
            $request->all(),
            ['cd_lote'  => 'required'],
            ['cd_produto'  => 'required'],
            ['peso'  => 'required'],
        );
    }
}
