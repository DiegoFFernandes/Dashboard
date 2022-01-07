<?php

namespace App\Http\Controllers\Admin\Estoque;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemLoteEntradaEstoque;
use App\Models\LoteEntradaEstoque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class ItemLoteEntradaEstoqueController extends Controller
{
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
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();
        $lote = $this->lote->findLote(Crypt::decryptString($id));
        $itemlote = $this->itemlote->list($lote->id);
        $title_page   = 'Adicionar item Lote de '.$lote->tp_lote;        
        $itemgroup = $this->itemlote->listGroup($lote->id);        
        return view('admin.estoque.add-item-lote', compact(
            'title_page',
            'user_auth',
            'uri',
            'lote',
            'itemlote',
            'itemgroup'            
        ));
    }
    public function getBuscaItem($cd_barras)
    {
        $item = $this->item->ItemFind($cd_barras);
        if ($item === 0) {
            return response()->json(['error' => 'Código produto não cadastrado ou não está usando código de barras!']);
        }
        return response()->json($item);
    }
    public function store()
    {
        $this->request['cd_usuario'] = Auth::user()->id;
        $store = $this->itemlote->store($this->request);
        if ($store == 1) {
            return response()->json(['success' => 'Item adicionado!']);
        }
        return response()->json(['errors' => 'Houve algum erro!']);
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
